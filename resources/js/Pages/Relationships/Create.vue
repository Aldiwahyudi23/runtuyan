<template>
  <AppLayout>
    <Head :title="'Tambah Hubungan - ' + person.name" />
    
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Tambah Hubungan Keluarga: {{ person.name }}
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <!-- Error Message -->
            <div v-if="$page.props.errors.system" class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
              {{ $page.props.errors.system }}
            </div>

            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 gap-6">
                <!-- Jenis Hubungan -->
                <div>
                  <InputLabel for="type" value="Jenis Hubungan" />
                  <SelectInput
                    id="type"
                    v-model="form.type"
                    class="mt-1 block w-full"
                    :disabled="form.processing"
                    @change="form.related_person_id = ''"
                  >
                    <option value="">Pilih Jenis Hubungan</option>
                    <option value="parent">Orang Tua</option>
                    <option value="child">Anak</option>
                    <option 
                      value="spouse" 
                      :disabled="person.gender === 'female' && hasSpouse && !hasExSpouse"
                    >
                      Pasangan
                    </option>
                    <option value="ex_spouse">Mantan Pasangan</option>
                  </SelectInput>
                  <InputError :message="form.errors.type" class="mt-2" />
                  <p 
                    v-if="person.gender === 'female' && hasSpouse && !hasExSpouse"
                    class="text-sm text-red-500 mt-1"
                  >
                    Wanita ini sudah memiliki pasangan. Harus menambahkan sebagai mantan pasangan terlebih dahulu sebelum menambahkan pasangan baru.
                  </p>
                </div>

                <!-- Anggota Keluarga Terkait -->
                <div>
                  <InputLabel for="related_person_id" value="Anggota Keluarga" />
                  <SelectInput
                    id="related_person_id"
                    v-model="form.related_person_id"
                    class="mt-1 block w-full"
                    :disabled="form.processing || (form.type === 'child' && !canAddChild)"
                    :options="{
                      placeholder: 'Cari anggota keluarga...',
                      searchField: ['text']
                    }"
                    @change="resetParentSelection"
                  >
                    <option value="">Pilih Anggota Keluarga</option>
                    <option 
                      v-for="member in filteredAvailableMembers" 
                      :key="member.id" 
                      :value="member.id"
                    >
                      {{ member.name }} ({{ member.gender === 'male' ? 'Laki-laki' : 'Perempuan' }})
                    </option>
                  </SelectInput>
                  <InputError :message="form.errors.related_person_id" class="mt-2" />
                  <p 
                    v-if="form.type === 'child' && !canAddChild"
                    class="text-sm text-red-500 mt-1"
                  >
                    Belum bisa menambahkan data anak, harus memiliki pasangan terlebih dahulu.
                  </p>
                </div>

                <!-- Pilihan Pasangan/Orang Tua -->
                <div v-if="shouldShowParentSelection && form.related_person_id">
                  <InputLabel 
                    :for="form.type === 'child' ? 'parent_id' : 'second_parent_id'" 
                    :value="form.type === 'child' ? 'Anak dari pasangan' : 'Pasangan orang tua'"
                  />
                  <SelectInput
                    :id="form.type === 'child' ? 'parent_id' : 'second_parent_id'"
                    v-model="form[form.type === 'child' ? 'parent_id' : 'second_parent_id']"
                    class="mt-1 block w-full"
                    :disabled="form.processing || availableParents.length === 0"
                  >
                    <option value="">Pilih {{ form.type === 'child' ? 'pasangan' : 'orang tua' }} (opsional)</option>
                    <option 
                      v-for="parent in availableParents" 
                      :key="parent.id" 
                      :value="parent.id"
                    >
                      {{ parent.name }} ({{ parent.type === 'spouse' ? 'Pasangan' : parent.type === 'parent' ? 'Orang Tua' : 'Mantan Pasangan' }})
                    </option>
                  </SelectInput>
                  <p class="text-sm text-gray-500 mt-1">
                    {{ form.type === 'child' 
                      ? 'Pilih pasangan jika anak ini adalah hasil dari hubungan dengan pasangan tertentu' 
                      : 'Pilih pasangan orang tua untuk melengkapi data orang tua' }}
                  </p>
                  <p 
                    v-if="form.type === 'parent' && availableParents.length === 0"
                    class="text-sm text-red-500 mt-1"
                  >
                    Orang tua yang dipilih belum memiliki pasangan.
                  </p>
                  <InputError :message="form.errors.parent_id || form.errors.second_parent_id" class="mt-2" />
                </div>

                <!-- Tanggal Pernikahan (kondisional) -->
                <div v-if="['spouse', 'ex_spouse'].includes(form.type)">
                  <InputLabel for="marriage_date" value="Tanggal Pernikahan" />
                  <TextInput
                    id="marriage_date"
                    v-model="form.marriage_date"
                    type="date"
                    class="mt-1 block w-full"
                    required
                    :disabled="form.processing"
                  />
                  <InputError :message="form.errors.marriage_date" class="mt-2" />
                </div>

                <!-- Tombol -->
                <div class="flex justify-end space-x-3">
                  <SecondaryButton
                    type="button"
                    @click="goBack"
                    :disabled="form.processing"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                  >
                    Batal
                  </SecondaryButton>
                  <PrimaryButton
                    :disabled="form.processing || (form.type === 'child' && !canAddChild) || (form.type === 'parent' && availableParents.length === 0)"
                    :class="{ 
                      'opacity-75 cursor-not-allowed': form.processing || (form.type === 'child' && !canAddChild) || (form.type === 'parent' && availableParents.length === 0)
                    }"
                  >
                    <span v-if="form.processing">
                      <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Menyimpan...
                    </span>
                    <span v-else>
                      Tambah Hubungan
                    </span>
                  </PrimaryButton>
                </div>
              </div>
            </form>

            <!-- Daftar Hubungan yang Sudah Ada -->
            <div class="mt-8">
              <h3 class="text-lg font-medium text-gray-900">Hubungan yang Sudah Ada</h3>
              <div class="mt-4 space-y-2">
                <div 
                  v-for="rel in existingRelationships" 
                  :key="rel.id"
                  class="p-3 border rounded flex justify-between items-center hover:bg-gray-50 transition-colors"
                >
                  <div>
                    <span class="font-medium">{{ rel.related_person.name }}</span>
                    <span class="ml-2 text-sm text-gray-500">({{ formatType(rel.type) }})</span>
                    <span 
                      v-if="rel.marriage_id"
                      class="ml-2 text-sm text-gray-500"
                    >
                      Menikah pada {{ formatDate(rel.marriage?.marriage_date) }}
                    </span>
                  </div>
                  <DangerButton
                    @click="confirmDelete(rel)"
                    class="text-sm"
                    :disabled="form.processing"
                  >
                    Hapus
                  </DangerButton>
                </div>

                <p 
                  v-if="existingRelationships.length === 0"
                  class="text-gray-500 p-3 border rounded"
                >
                  Belum ada hubungan yang ditambahkan.A
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { format } from 'date-fns';
import 'tom-select/dist/css/tom-select.css';

const props = defineProps({
  person: Object,
  people: Array,
  existingRelationships: Array,
});

const form = useForm({
  person_id: props.person.id,
  related_person_id: '',
  type: '',
  marriage_date: null,
  parent_id: null,       // Untuk hubungan child
  second_parent_id: null  // Untuk hubungan parent
});

// Cek apakah sudah memiliki pasangan
const hasSpouse = computed(() => {
  return props.existingRelationships.some(rel => rel.type === 'spouse');
});

// Cek apakah sudah memiliki mantan pasangan
const hasExSpouse = computed(() => {
  return props.existingRelationships.some(rel => rel.type === 'ex_spouse');
});

// Daftar pasangan/orang tua yang ada
const availableParents = computed(() => {
  if (!form.related_person_id) return [];
  
  if (form.type === 'child') {
    return props.existingRelationships
      .filter(rel => ['spouse', 'ex_spouse'].includes(rel.type))
      .map(rel => ({
        id: rel.related_person.id,
        name: rel.related_person.name,
        type: rel.type
      }));
  } else if (form.type === 'parent') {
    // Cari pasangan dari anggota yang dipilih
    const selectedPerson = props.people.find(p => p.id == form.related_person_id);
    if (!selectedPerson) return [];
    
    return props.existingRelationships
      .filter(rel => 
        rel.id === selectedPerson.id && 
        ['spouse', 'ex_spouse'].includes(rel.type)
      )
      .map(rel => ({
        id: rel.related_person.id,
        name: rel.related_person.name,
        type: rel.type
      }));
  }
  return [];
});

// Apakah bisa menambahkan anak (harus punya pasangan)
const canAddChild = computed(() => {
  return hasSpouse.value || hasExSpouse.value;
});

// Apakah harus menampilkan pilihan pasangan/orang tua
const shouldShowParentSelection = computed(() => {
  return form.type === 'child' || form.type === 'parent';
});

// Filter anggota keluarga berdasarkan jenis kelamin jika memilih pasangan
const filteredAvailableMembers = computed(() => {
  let members = props.people.filter(member => 
    member.id !== props.person.id &&
    !props.existingRelationships.some(rel => rel.related_person_id === member.id)
  );

  // Jika memilih pasangan, filter berdasarkan gender berlawanan
  if (['spouse', 'ex_spouse'].includes(form.type)) {
    const oppositeGender = props.person.gender === 'male' ? 'female' : 'male';
    members = members.filter(member => member.gender === oppositeGender);
  }

  return members;
});

const formatType = (type) => {
  const types = {
    parent: 'Orang Tua',
    child: 'Anak',
    spouse: 'Pasangan',
    ex_spouse: 'Mantan Pasangan'
  };
  return types[type] || type;
};

const formatDate = (dateString) => {
  if (!dateString) return null;
  return format(new Date(dateString), 'dd MMMM yyyy');
};

const resetParentSelection = () => {
  form.parent_id = null;
  form.second_parent_id = null;
};

const submit = () => {
  // Siapkan data yang akan dikirim
  const formData = {
    person_id: form.person_id,
    related_person_id: form.related_person_id,
    type: form.type,
    marriage_date: form.marriage_date,
    parent_id: form.type === 'child' ? form.parent_id : null,
    second_parent_id: form.type === 'parent' ? form.second_parent_id : null
  };

  console.log('Data yang dikirim:', formData);

  form.post(route('relationships.store'), formData, {
    preserveScroll: true,
    onError: (errors) => {
      console.error('Error response:', errors);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    onSuccess: () => {
      form.reset('related_person_id', 'type', 'marriage_date', 'parent_id', 'second_parent_id');
    },
  });
};

const goBack = () => {
  router.visit(route('people.show', props.person.id));
};

const confirmDelete = (relationship) => {
  if (confirm(`Apakah Anda yakin ingin menghapus hubungan ini?`)) {
    router.delete(route('relationships.destroy', relationship.id), {
      preserveScroll: true,
    });
  }
};

// Reset related_person_id ketika type berubah
watch(() => form.type, () => {
  form.related_person_id = '';
  form.parent_id = null;
  form.second_parent_id = null;
});
</script>

<style scoped>
select:not(:disabled) {
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

select:focus:not(:disabled) {
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
}

div[class^="p-3 border rounded"] {
  transition: all 0.2s ease;
}
</style>
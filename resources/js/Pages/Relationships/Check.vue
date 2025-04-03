<template>
  <AppLayout>
    <Head title="Cek Hubungan Keluarga" />
    
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Cek Hubungan Keluarga
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Person 1 Section -->
                <div class="space-y-4">
                  <h3 class="text-lg font-medium">Orang Pertama</h3>
                  
                  <div>
                    <InputLabel for="person1_id" value="Pilih Orang Pertama" />
                    <SelectInput
                      id="person1_id"
                      v-model="form.person1_id"
                      class="w-full"
                      :options="peopleOptions"
                      placeholder="Cari nama..."
                      @change="updateParents(1)"
                    />
                    <InputError :message="errors.person1_id" />
                  </div>
                  
                  <div v-if="form.person1_id">
                    <InputLabel for="parent1_id" value="Orang Tua Orang Pertama (opsional)" />
                    <SelectInput
                      id="parent1_id"
                      v-model="form.parent1_id"
                      class="w-full"
                      :options="parents1Options"
                      placeholder="Pilih orang tua"
                    />
                  </div>
                </div>

                <!-- Person 2 Section -->
                <div class="space-y-4">
                  <h3 class="text-lg font-medium">Orang Kedua</h3>
                  
                  <div>
                    <InputLabel for="person2_id" value="Pilih Orang Kedua" />
                    <SelectInput
                      id="person2_id"
                      v-model="form.person2_id"
                      class="w-full"
                      :options="filteredPeopleOptions"
                      placeholder="Cari nama..."
                      @change="updateParents(2)"
                    />
                    <InputError :message="errors.person2_id" />
                  </div>
                  
                  <div v-if="form.person2_id">
                    <InputLabel for="parent2_id" value="Orang Tua Orang Kedua (opsional)" />
                    <SelectInput
                      id="parent2_id"
                      v-model="form.parent2_id"
                      class="w-full"
                      :options="parents2Options"
                      placeholder="Pilih orang tua"
                    />
                  </div>
                </div>
              </div>

              <div class="flex justify-end mt-6 space-x-4">
                <SecondaryButton @click="resetForm">
                  Reset
                </SecondaryButton>
                <PrimaryButton type="submit" :disabled="processing">
                  <span v-if="processing">Memproses...</span>
                  <span v-else>Cek Hubungan</span>
                </PrimaryButton>
              </div>
            </form>

            <!-- Results Section -->
            <div v-if="results" class="mt-8 space-y-6">
              <!-- Hasil dan visualisasi tetap sama -->
              ...
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import FamilyTree from '@/Components/FamilyTree.vue';

const props = defineProps({
  people: Array,
  initialData: Object,
  results: Object,
  errors: Object,
});

const processing = ref(false);

const form = useForm({
  person1_id: props.initialData.person1?.id || null,
  person2_id: props.initialData.person2?.id || null,
  parent1_id: props.initialData.parent1_id || null,
  parent2_id: props.initialData.parent2_id || null,
});

// Format options untuk select input
const peopleOptions = computed(() => {
  return props.people?.map(person => ({
    value: person.id,
    label: `${person.name} (${person.gender === 'male' ? 'L' : 'P'})`,
  })) || [];
});

// Filter person2 options untuk exclude person1
const filteredPeopleOptions = computed(() => {
  return peopleOptions.value.filter(option => option.value !== form.person1_id);
});

// Format parents options
const parents1Options = computed(() => {
  return props.initialData.parents1?.map(parent => ({
    value: parent.id,
    label: `${parent.name} (${parent.gender === 'male' ? 'L' : 'P'})`,
  })) || [];
});

const parents2Options = computed(() => {
  return props.initialData.parents2?.map(parent => ({
    value: parent.id,
    label: `${parent.name} (${parent.gender === 'male' ? 'L' : 'P'})`,
  })) || [];
});

// Update parents data ketika person dipilih
const updateParents = (personNumber) => {
  if (personNumber === 1) {
    form.parent1_id = null;
  } else {
    form.parent2_id = null;
  }
};

// Reset form
const resetForm = () => {
  form.reset();
};

// Submit form
const submit = () => {
  processing.value = true;
  form.post(route('relationship.check'), {
    preserveScroll: true,
    onFinish: () => {
      processing.value = false;
    },
  });
};

// Watch for changes in person1 to clear parent1
watch(() => form.person1_id, (newVal) => {
  if (!newVal) {
    form.parent1_id = null;
  }
});

// Watch for changes in person2 to clear parent2
watch(() => form.person2_id, (newVal) => {
  if (!newVal) {
    form.parent2_id = null;
  }
});
</script>
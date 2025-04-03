<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Form Tambah Individu -->
   <!-- Form Tambah Individu -->
  <div class="bg-white p-6 rounded-lg shadow-md mb-8 transition-all duration-300 hover:shadow-lg">
    <h2 class="text-xl font-bold mb-4">Tambah Individu Baru</h2>
    <form @submit.prevent="submitPerson">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <!-- Nama -->
          <div>
            <InputLabel for="name" value="Nama Lengkap *" />
            <TextInput
              id="name"
              v-model="personForm.name"
              type="text"
              class="mt-1 block w-full"
              required
            />
            <InputError class="mt-2" :message="personForm.errors.name" />
          </div>

          <!-- Jenis Kelamin -->
          <div>
            <InputLabel for="gender" value="Jenis Kelamin *" />
            <select
              id="gender"
              v-model="personForm.gender"
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              required
            >
              <option value="">Pilih Jenis Kelamin</option>
              <option value="male">Laki-laki</option>
              <option value="female">Perempuan</option>
            </select>
            <InputError class="mt-2" :message="personForm.errors.gender" />
          </div>

          <!-- Tanggal Lahir -->
          <div>
            <InputLabel for="birth_date" value="Tanggal Lahir" />
            <TextInput
              id="birth_date"
              v-model="personForm.birth_date"
              type="date"
              class="mt-1 block w-full"
            />
            <InputError class="mt-2" :message="personForm.errors.birth_date" />
          </div>

          <!-- Tanggal Meninggal -->
          <div>
            <InputLabel for="death_date" value="Tanggal Meninggal" />
            <TextInput
              id="death_date"
              v-model="personForm.death_date"
              type="date"
              class="mt-1 block w-full"
            />
            <InputError class="mt-2" :message="personForm.errors.death_date" />
          </div>
        </div>

      <div class="flex items-center justify-end mt-6">
        <ActionMessage :on="personForm.recentlySuccessful" class="me-3 text-green-600">
          Tersimpan.
        </ActionMessage>
        <PrimaryButton :class="{ 'opacity-25': personForm.processing }" :disabled="personForm.processing">
          Simpan
        </PrimaryButton>
      </div>
      </form>
    </div>

     <!-- Form Pernikahan -->
  <div class="bg-white p-6 rounded-lg shadow-md mb-8 transition-all duration-300 hover:shadow-lg mt-8">
      <h2 class="text-xl font-bold mb-4">Catat Pernikahan</h2>
      <form @submit.prevent="submitMarriage">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <!-- Suami -->
          <div>
            <InputLabel for="person1_id" value="Suami *" />
            <select
              id="person1_id"
              v-model="marriageForm.person1_id"
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              required
            >
              <option value="">Pilih Suami</option>
              <option v-for="person in malePersons" :value="person.id">
                {{ person.name }}
              </option>
            </select>
            <InputError class="mt-2" :message="marriageForm.errors.person1_id" />
          </div>

          <!-- Istri -->
          <div>
            <InputLabel for="person2_id" value="Istri *" />
            <select
              id="person2_id"
              v-model="marriageForm.person2_id"
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              required
            >
              <option value="">Pilih Istri</option>
             <!-- Di template, ganti femalePersons dengan availableWives -->
              <option v-for="person in availableWives" :value="person.id">
                {{ person.name }}
              </option>
            </select>
            <InputError class="mt-2" :message="marriageForm.errors.person2_id" />
          </div>

          <!-- Tanggal Pernikahan -->
          <div>
            <InputLabel for="marriage_date" value="Tanggal Pernikahan *" />
            <TextInput
              id="marriage_date"
              v-model="marriageForm.marriage_date"
              type="date"
              class="mt-1 block w-full"
              required
            />
            <InputError class="mt-2" :message="marriageForm.errors.marriage_date" />
          </div>
        </div>

        <div class="flex items-center justify-end mt-6">
          <ActionMessage :on="marriageForm.recentlySuccessful" class="me-3 text-green-600">
            Tersimpan.
          </ActionMessage>
          <PrimaryButton :class="{ 'opacity-25': marriageForm.processing }" :disabled="marriageForm.processing">
            Simpan
          </PrimaryButton>
        </div>
      </form>
    </div>

   <!-- Form Hubungan -->
  <div class="bg-white p-6 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg mt-8">
      <h2 class="text-xl font-bold mb-4">Hubungkan Orangtua dan Anak</h2>
      <form @submit.prevent="submitRelationship">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <!-- Orangtua -->
          <div>
            <InputLabel for="parent_id" value="Orangtua *" />
            <select
              id="parent_id"
              v-model="relationshipForm.parent_id"
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              required
            >
              <option value="">Pilih Orangtua</option>
              <option v-for="person in persons" :value="person.id">
                {{ person.name }}
              </option>
            </select>
            <InputError class="mt-2" :message="relationshipForm.errors.parent_id" />
          </div>

          <!-- Anak -->
          <div>
            <InputLabel for="child_id" value="Anak *" />
            <select
              id="child_id"
              v-model="relationshipForm.child_id"
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              required
            >
              <option value="">Pilih Anak</option>
              <option v-for="person in persons" :value="person.id">
                {{ person.name }}
              </option>
            </select>
            <InputError class="mt-2" :message="relationshipForm.errors.child_id" />
          </div>
        </div>

        <div class="flex items-center justify-end mt-6">
          <ActionMessage :on="relationshipForm.recentlySuccessful" class="me-3 text-green-600">
            Tersimpan.
          </ActionMessage>
          <PrimaryButton :class="{ 'opacity-25': relationshipForm.processing }" :disabled="relationshipForm.processing">
            Simpan
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ActionMessage from '@/Components/ActionMessage.vue'; // Jangan lupa import
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
  persons: Array,
  marriages: Array
});

// Filter persons by gender
const malePersons = computed(() => props.persons.filter(p => p.gender === 'male'));
const femalePersons = computed(() => props.persons.filter(p => p.gender === 'female'));

// Form untuk individu baru
const personForm = useForm({
  name: '',
  gender: '',
  birth_date: '',
  death_date: '',
  bio: ''
});

// Form untuk pernikahan
const marriageForm = useForm({
  person1_id: '',
  person2_id: '',
  marriage_date: ''
});

// Form untuk hubungan orangtua-anak
const relationshipForm = useForm({
  parent_id: '',
  child_id: ''
});

const availableWives = computed(() => {
  return femalePersons.value.filter(woman => {
    // Cek apakah wanita sudah menikah dan belum bercerai
    const isMarried = props.marriages.some(m => 
      m.relationships.some(r => 
        r.related_person_id === woman.id && 
        r.type === 'spouse' && 
        m.divorce_date === null
      )
    );
    return !isMarried;
  });
});

const submitPerson = () => {
  personForm.post(route('persons.store'), {
    preserveScroll: true,
    onSuccess: () => personForm.reset()
  });
};

const submitMarriage = () => {
  marriageForm.post(route('marriages.store'), {
    preserveScroll: true,
    onSuccess: () => marriageForm.reset()
  });
};

const submitRelationship = () => {
  relationshipForm.post(route('relationships.store'), {
    preserveScroll: true,
    onSuccess: () => relationshipForm.reset()
  });
};
</script>

<style scoped>
/* Bintang merah untuk label wajib */
.InputLabel.required::after {
  content: " *";
  color: #ef4444;
}

/* Animasi dan spacing */
.form-section {
  margin-bottom: 2rem;
  transition: all 0.3s ease;
  transform: translateY(0);
}

.form-section:hover {
  transform: translateY(-5px);
}

/* Jarak antar form */
.form-spacing {
  margin-top: 3rem;
}
</style>
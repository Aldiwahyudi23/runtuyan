<template>
  <AppLayout>
    <Head title="Edit Anggota Keluarga" />
    
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Anggota Keluarga: {{ form.name }}
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 gap-6">
                <!-- Nama Lengkap -->
                <div>
                      <div class="flex items-center">
                    <label for="name" class="block text-sm font-medium text-gray-700">
                      Nama Lengkap
                    </label>
                    <span class="text-red-600 ml-1">*</span>
                  </div>
                  <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    
                    :disabled="form.processing"
                  />
                  <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Jenis Kelamin -->
                <div>
                  <div class="flex items-center">
                    <label class="block text-sm font-medium text-gray-700">
                      Jenis Kelamin
                    </label>
                    <span class="text-red-600 ml-1">*</span>
                  </div>
                  <div class="mt-2 flex space-x-4">
                    <label class="inline-flex items-center">
                      <input
                        v-model="form.gender"
                        type="radio"
                        value="male"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                        :disabled="form.processing"
                      />
                      <span class="ml-2">Laki-laki</span>
                    </label>
                    <label class="inline-flex items-center">
                      <input
                        v-model="form.gender"
                        type="radio"
                        value="female"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                        :disabled="form.processing"
                      />
                      <span class="ml-2">Perempuan</span>
                    </label>
                  </div>
                  <InputError :message="form.errors.gender" class="mt-2" />
                </div>

                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Tanggal Lahir -->
                  <div>
                    <InputLabel for="birth_date" value="Tanggal Lahir" />
                    <TextInput
                      id="birth_date"
                      v-model="form.birth_date"
                      type="date"
                      class="mt-1 block w-full"
                      :disabled="form.processing"
                    />
                    <InputError :message="form.errors.birth_date" class="mt-2" />
                  </div>

                  <!-- Tanggal Meninggal -->
                  <div>
                    <InputLabel for="death_date" value="Tanggal Meninggal (jika ada)" />
                    <TextInput
                      id="death_date"
                      v-model="form.death_date"
                      type="date"
                      class="mt-1 block w-full"
                      :disabled="form.processing"
                    />
                    <InputError :message="form.errors.death_date" class="mt-2" />
                  </div>
                </div>

                <!-- Biografi -->
                <div>
                  <InputLabel for="bio" value="Biografi" />
                  <TextArea
                    id="bio"
                    v-model="form.bio"
                    class="mt-1 block w-full"
                    rows="3"
                    :disabled="form.processing"
                  />
                  <InputError :message="form.errors.bio" class="mt-2" />
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
                    :disabled="form.processing"
                    :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                  >
                    <span v-if="form.processing">
                      <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Menyimpan...
                    </span>
                    <span v-else>
                      Simpan Perubahan
                    </span>
                  </PrimaryButton>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, watchEffect } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';

const props = defineProps({
  person: Object,
});


// Format tanggal untuk input
const formatForInput = (date) => {
  if (!date) return null;
  const d = new Date(date);
  return d.toISOString().split('T')[0];
};

const form = useForm({
  name: props.person.name,
  gender: props.person.gender,
  birth_date: formatForInput(props.person.birth_date),
  death_date: formatForInput(props.person.death_date),
  bio: props.person.bio,
});

const submit = () => {
  form.put(route('people.update', props.person.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  });
};

const goBack = () => {
  router.visit(route('people.show', props.person.id));
};
</script>

<style scoped>
/* Efek hover untuk input */
input:not(:disabled), textarea:not(:disabled) {
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

input:focus:not(:disabled), textarea:focus:not(:disabled) {
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
}

/* Animasi untuk tombol */
button:not(:disabled) {
  transition: all 0.2s ease-in-out;
}
</style>
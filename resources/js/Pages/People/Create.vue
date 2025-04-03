<template>
  <AppLayout>
    <Head title="Tambah Anggota Keluarga" />
    
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Tambah Anggota Keluarga Baru
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
                  <input 
                    v-model="form.name" 
                    type="text" 
                    id="name" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :disabled="form.processing"
                  >
                  <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
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
                      >
                      <span class="ml-2">Laki-laki</span>
                    </label>
                    <label class="inline-flex items-center">
                      <input 
                        v-model="form.gender" 
                        type="radio" 
                        value="female" 
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                        :disabled="form.processing"
                      >
                      <span class="ml-2">Perempuan</span>
                    </label>
                  </div>
                  <p v-if="form.errors.gender" class="mt-1 text-sm text-red-600">{{ form.errors.gender }}</p>
                </div>

                <!-- Tanggal Lahir dan Tanggal Meninggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input 
                      v-model="form.birth_date" 
                      type="date" 
                      id="birth_date" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      :disabled="form.processing"
                    >
                    <p v-if="form.errors.birth_date" class="mt-1 text-sm text-red-600">{{ form.errors.birth_date }}</p>
                  </div>

                  <div>
                    <label for="death_date" class="block text-sm font-medium text-gray-700">Tanggal Meninggal (jika ada)</label>
                    <input 
                      v-model="form.death_date" 
                      type="date" 
                      id="death_date" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      :disabled="form.processing"
                    >
                    <p v-if="form.errors.death_date" class="mt-1 text-sm text-red-600">{{ form.errors.death_date }}</p>
                  </div>
                </div>

                <!-- Biografi -->
                <div>
                  <label for="bio" class="block text-sm font-medium text-gray-700">Biografi</label>
                  <textarea 
                    v-model="form.bio" 
                    id="bio" 
                    rows="3" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :disabled="form.processing"
                  ></textarea>
                  <p v-if="form.errors.bio" class="mt-1 text-sm text-red-600">{{ form.errors.bio }}</p>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3">
                  <Link 
                    :href="route('people.index')" 
                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                  >
                    Batal
                  </Link>
                  <button 
                    type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
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
                      Simpan
                    </span>
                  </button>
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
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
  name: '',
  gender: '',
  birth_date: null,
  death_date: null,
  bio: null,
});

const submit = () => {
  form.post(route('people.store'), {
    preserveState: true, // <-- Ini yang menjaga state form saat error
    onSuccess: () => {
      form.reset(); // Reset hanya jika sukses
    },
  });
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

/* Style untuk tanda bintang wajib diisi */
.text-red-600 {
  color: #dc2626;
}
</style>
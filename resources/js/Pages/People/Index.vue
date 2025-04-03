<template>
  <AppLayout>
    <Head title="Anggota Keluarga" />
    
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Data Anggota Keluarga
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-medium">Daftar Anggota Keluarga</h3>
              <Link 
                :href="route('people.create')" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
              >
                Tambah Anggota Baru
              </Link>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Lahir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="person in people" :key="person.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <Link :href="route('people.show', person.id)" class="text-blue-600 hover:text-blue-800 font-medium">
                        {{ person.name }}
                      </Link>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      {{ person.gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      {{ person.birth_date ? formatDate(person.birth_date) : 'Tidak diketahui' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      {{ calculateAge(person.birth_date, person.death_date) ?? 'Tidak diketahui' }} tahun
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span v-if="person.death_date" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        Meninggal
                      </span>
                      <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Masih Hidup
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                      <Link 
                        :href="route('people.edit', person.id)" 
                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      >
                        Edit
                      </Link>
                      <button 
                        @click="confirmDelete(person)"
                        :disabled="isDeleting === person.id"
                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-75 disabled:cursor-not-allowed"
                      >
                        <span v-if="isDeleting === person.id">Menghapus...</span>
                        <span v-else>Hapus</span>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { format } from 'date-fns';
import { calculateAge } from '@/Utils/ageCalculator';

const props = defineProps({
  people: Array,
});

const isDeleting = ref(null);

const formatDate = (dateString) => {
  if (!dateString) return null;
  return format(new Date(dateString), 'dd MMMM yyyy');
};

const confirmDelete = (person) => {
  if (confirm(`Apakah Anda yakin ingin menghapus ${person.name}?`)) {
    isDeleting.value = person.id;
    router.delete(route('people.destroy', person.id), {
      preserveScroll: true,
      onFinish: () => {
        isDeleting.value = null;
      }
    });
  }
};
</script>

<style scoped>
/* Tambahan styling untuk tabel */
table {
  min-width: 100%;
}

th {
  text-align: left;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.05em;
}

td {
  padding: 1rem 1.5rem;
  white-space: nowrap;
  vertical-align: middle;
}

tr:not(:last-child) {
  border-bottom: 1px solid #e5e7eb;
}
</style>
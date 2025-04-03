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

            <!-- Form Pencarian dan Filter -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
              <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                  <label for="globalSearch" class="block text-sm font-medium text-gray-700 mb-1">Cari Data</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <input
                      v-model="search.global"
                      type="text"
                      id="globalSearch"
                      placeholder="Cari nama, jenis kelamin, dll..."
                      class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                      @input="debouncedSearch"
                    >
                  </div>
                </div>
                <div>
                  <label for="searchGender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                  <select
                    v-model="search.gender"
                    id="searchGender"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    @change="performSearch"
                  >
                    <option value="">Semua</option>
                    <option value="male">Laki-laki</option>
                    <option value="female">Perempuan</option>
                  </select>
                </div>
                <div>
                  <label for="searchStatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                  <select
                    v-model="search.status"
                    id="searchStatus"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    @change="performSearch"
                  >
                    <option value="">Semua</option>
                    <option value="alive">Masih Hidup</option>
                    <option value="deceased">Meninggal</option>
                  </select>
                </div>
                <div class="flex items-end">
                  <button
                    @click="resetSearch"
                    class="w-full px-4 py-2 bg-gray-200 border border-transparent rounded-md font-medium text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                  >
                    Reset
                  </button>
                </div>
              </div>
            </div>

            <div class="flex justify-between items-center mb-4">
              <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-700">Tampilkan</span>
                <select 
                  v-model="perPage" 
                  @change="updatePagination"
                  class="px-5 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                >
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
              </div>
              <div v-if="props.people.data.length > 0" class="text-sm text-gray-700">
                Menampilkan {{ props.people.from }} - {{ props.people.to }} dari {{ props.people.total }} entri
              </div>
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
                  <tr v-if="props.people.data.length === 0">
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                      Tidak ada data yang ditemukan
                    </td>
                  </tr>
                  <tr v-for="person in props.people.data" :key="person.id" class="hover:bg-gray-50">
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

            <!-- Pagination -->
            <div v-if="props.people.data.length > 0" class="mt-4 flex items-center justify-between">
              <div class="text-sm text-gray-700">
                Menampilkan {{ props.people.from }} - {{ props.people.to }} dari {{ props.people.total }} entri
              </div>
              <div class="flex space-x-2">
                <button
                  @click="previousPage"
                  :disabled="!props.people.prev_page_url"
                  class="px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Sebelumnya
                </button>
                <button
                  @click="nextPage"
                  :disabled="!props.people.next_page_url"
                  class="px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Selanjutnya
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import AppLayout from '@/Layouts/AppLayout.vue';
import { format } from 'date-fns';
import { calculateAge } from '@/Utils/ageCalculator';

const props = defineProps({
  people: Object, // Sekarang people adalah objek pagination dari Laravel
  filters: Object,
});

const isDeleting = ref(null);
const perPage = ref(props.filters?.per_page || 10);
const search = ref({
  global: props.filters?.global || '',
  gender: props.filters?.gender || '',
  status: props.filters?.status || '',
});

const formatDate = (dateString) => {
  if (!dateString) return null;
  return format(new Date(dateString), 'dd MMMM yyyy');
};

const performSearch = () => {
  router.get(route('people.index'), {
    ...search.value,
    per_page: perPage.value,
  }, {
    preserveState: true,
    replace: true,
  });
};

// Debounce untuk pencarian global
const debouncedSearch = debounce(performSearch, 500);

const resetSearch = () => {
  search.value = {
    global: '',
    gender: '',
    status: '',
  };
  perPage.value = 10;
  performSearch();
};

const updatePagination = () => {
  performSearch();
};

const nextPage = () => {
  if (props.people.next_page_url) {
    router.get(props.people.next_page_url, {}, {
      preserveState: true,
      preserveScroll: true,
    });
  }
};

const previousPage = () => {
  if (props.people.prev_page_url) {
    router.get(props.people.prev_page_url, {}, {
      preserveState: true,
      preserveScroll: true,
    });
  }
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

/* Styling untuk pagination */
.pagination-info {
  font-size: 0.875rem;
  color: #4b5563;
}

.pagination-button {
  min-width: 80px;
}
</style>
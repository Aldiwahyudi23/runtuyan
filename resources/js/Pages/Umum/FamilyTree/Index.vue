<template>
  <PublicLayout>
    <Head title="Cari Keturunan" />
    
    <div class="max-w-4xl mx-auto py-8 px-4">
      <h1 class="text-3xl font-bold text-center mb-8">Cari Anggota Keluarga</h1>
      
      <div class="bg-white rounded-lg shadow p-6">
        <div class="relative mb-8">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Masukkan nama anggota keluarga..."
          />
          
          <div v-if="searchResults.length > 0" class="absolute z-10 mt-2 w-full bg-white shadow-lg rounded-lg overflow-hidden">
            <ul>
              <li
                v-for="person in searchResults"
                :key="person.id"
                class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100"
                @click="selectPerson(person)"
              >
                <div class="flex justify-between items-center">
                  <span class="font-medium">{{ person.name }}</span>
                  <span class="text-sm text-gray-500">
                    {{ person.gender === 'male' ? 'Laki-laki' : 'Perempuan' }} • 
                    {{ person.birth_date ? calculateAge(person.birth_date, person.death_date) : 'Usia tidak diketahui' }}
                  </span>
                </div>
              </li>
            </ul>
          </div>
        </div>
        
        <div v-if="selectedPerson" class="mt-6 p-4 bg-blue-50 rounded-lg">
          <h2 class="text-xl font-semibold mb-2">{{ selectedPerson.name }}</h2>
          <p class="text-gray-600 mb-4">
            {{ selectedPerson.gender === 'male' ? 'Laki-laki' : 'Perempuan' }} • 
            {{ selectedPerson.birth_date ? calculateAge(selectedPerson.birth_date, selectedPerson.death_date) : 'Usia tidak diketahui' }}
          </p>
          <Link 
            :href="route('public.family-tree.show', selectedPerson.id)"
            class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
          >
            Lihat Keturunan
          </Link>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../PublicLayout.vue';
import axios from 'axios';
import { debounce } from 'lodash';

const searchQuery = ref('');
const searchResults = ref([]);
const selectedPerson = ref(null);

const handleSearch = debounce(() => {
  if (searchQuery.value.length < 2) {
    searchResults.value = [];
    return;
  }

  // Gunakan axios langsung ke endpoint API
  axios.get('/api/family-tree/search', {
    params: {
      query: searchQuery.value
    }
  })
  .then(response => {
    searchResults.value = response.data;
  })
  .catch(error => {
    console.error('Search error:', error);
    searchResults.value = [];
  });
}, 300);

const selectPerson = (person) => {
  selectedPerson.value = person;
  searchResults.value = [];
};

const calculateAge = (birthDate, deathDate) => {
  if (!birthDate) return null;
  const birthYear = new Date(birthDate).getFullYear();
  const endYear = deathDate ? new Date(deathDate).getFullYear() : new Date().getFullYear();
  const age = endYear - birthYear;
  return age + ' tahun' + (deathDate ? ' (Alm)' : '');
};
</script>
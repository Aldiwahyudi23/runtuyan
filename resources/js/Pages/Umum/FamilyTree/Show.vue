<template>
  <PublicLayout>
    <Head :title="person.name + ' - Keluarga'" />
    
    <div class="max-w-7xl mx-auto py-4 px-4">
      <div class="flex justify-between items-center mb-4">
        <Link 
          :href="route('public.family-tree.index')"
          class="text-blue-600 hover:text-blue-800 flex items-center"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
          </svg>
          Kembali ke Pencarian
        </Link>
      </div>
      
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
              <h2 class="text-xl font-semibold mb-4">Informasi Pribadi</h2>
              <div class="space-y-2">
                <p><span class="font-medium">Nama:</span> {{ person.name }}</p>
                <p><span class="font-medium">Jenis Kelamin:</span> {{ person.gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                <p><span class="font-medium">Tanggal Lahir:</span> {{ formatDate(person.birth_date) || '-' }}</p>
                <p><span class="font-medium">Usia:</span> {{ calculateAge(person.birth_date, person.death_date) || '-' }}</p>
                <p v-if="person.death_date"><span class="font-medium">Tanggal Meninggal:</span> {{ formatDate(person.death_date) }}</p>
                <p>
                  <span class="font-medium">Status:</span>
                  <span :class="person.death_date ? 'text-red-600' : 'text-green-600'">
                    {{ person.death_date ? ' Meninggal' : ' Masih Hidup' }}
                  </span>
                </p>
              </div>
            </div>
            
            <div v-if="person.bio">
              <h2 class="text-xl font-semibold mb-4">Biografi</h2>
              <p class="whitespace-pre-line">{{ person.bio }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-4 prounded-lg shadow overflow-hidden bg-gray-50">
        <h2 class="text-xl font-semibold mb-4">Keluarga</h2>
        <div class="overflow-x-auto">
          <div class="min-w-max p-4 bg-gray-200 rounded-lg border border-gray-200">
            <FamilyTreeUmum :person="person" />
          </div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../PublicLayout.vue';
import FamilyTreeUmum from '@/Components/FamilyTreeUmum.vue';

const props = defineProps({
  person: Object
});

const formatDate = (dateString) => {
  if (!dateString) return null;
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};

const calculateAge = (birthDate, deathDate) => {
  if (!birthDate) return null;
  const birth = new Date(birthDate);
  const end = deathDate ? new Date(deathDate) : new Date();
  let age = end.getFullYear() - birth.getFullYear();
  const monthDiff = end.getMonth() - birth.getMonth();
  
  if (monthDiff < 0 || (monthDiff === 0 && end.getDate() < birth.getDate())) {
    age--;
  }
  
  return age + ' tahun' + (deathDate ? ' (Alm)' : '');
};
</script>
<template>
  <AppLayout>
    <Head :title="'Detail ' + person.name" />
    
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ person.name }}
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-start mb-6">
              <div>
                <h3 class="text-lg font-medium">Informasi Pribadi</h3>
                <p class="text-sm text-gray-500">Detail anggota keluarga</p>
              </div>
              <div class="flex space-x-2">
                <Link 
                  :href="route('people.edit', person.id)" 
                  class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                >
                  Edit
                </Link>
                <Link 
                  :href="route('relationships.create', { person_id: person.id })" 
                  class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                  Tambah Relasi
                </Link>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div>
                <div class="mb-4">
                  <h4 class="font-medium text-gray-900">Informasi Dasar</h4>
                  <div class="mt-2 space-y-2">
                    <p><span class="font-medium">Jenis Kelamin:</span> {{ person.gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                    <p><span class="font-medium">Tanggal Lahir:</span> {{ formatDate(person.birth_date) || 'Tidak diketahui' }}</p>
                    <p><span class="font-medium">Usia:</span> 
                      {{ ageText(person.birth_date, person.death_date) }}
                    </p>
                    <p v-if="person.death_date">
                      <span class="font-medium">Tanggal Meninggal:</span> {{ formatDate(person.death_date) }}
                    </p>
                    <p>
                      <span class="font-medium">Status:</span> 
                      <span :class="person.death_date ? 'text-red-600' : 'text-green-600'">
                        {{ person.death_date ? 'Meninggal' : 'Masih Hidup' }}
                      </span>
                    </p>
                  </div>
                </div>

                <div>
                  <h4 class="font-medium text-gray-900">Biografi</h4>
                  <p class="mt-2 text-gray-700">{{ person.bio || 'Tidak ada biografi tersedia.' }}</p>
                </div>
              </div>

              <div>
                <div class="mb-6">
                  <h4 class="font-medium text-gray-900">Hubungan Keluarga</h4>
                  <div class="mt-4 space-y-4">
                    <div v-if="person.parents.length > 0">
                      <h5 class="font-medium">Orang Tua</h5>
                      <ul class="mt-2 space-y-1">
                        <li v-for="parent in person.parents" :key="parent.id" class="flex items-center">
                          <Link :href="route('people.show', parent.id)" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-600 mr-2"></span>
                            {{ parent.name }}
                          </Link>
                        </li>
                      </ul>
                    </div>

                    <div v-if="person.spouses.length > 0">
                      <h5 class="font-medium">Pasangan</h5>
                      <ul class="mt-2 space-y-1">
                        <li v-for="spouse in person.spouses" :key="spouse.id" class="flex items-center">
                          <Link :href="route('people.show', spouse.id)" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-600 mr-2"></span>
                            {{ spouse.name }}
                          </Link>
                          <span v-if="spouse.pivot.marriage_id" class="text-sm text-gray-500 ml-2">
                            (Menikah)
                          </span>
                        </li>
                      </ul>
                    </div>

                    <div v-if="person.children.length > 0">
                      <h5 class="font-medium">Anak-anak</h5>
                      <ul class="mt-2 space-y-1">
                        <li v-for="child in person.children" :key="child.id" class="flex items-center">
                          <Link :href="route('people.show', child.id)" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-600 mr-2"></span>
                            {{ child.name }}
                          </Link>
                        </li>
                      </ul>
                    </div>

                    <div v-if="person.parents.length === 0 && person.spouses.length === 0 && person.children.length === 0">
                      <p class="text-gray-500">Belum ada hubungan keluarga yang ditambahkan.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<div class="mt-8">
  <h4 class="font-medium text-gray-900">Pohon Keluarga</h4>
  <!-- Perubahan utama di container ini -->
  <div class="mt-4 p-4 border rounded bg-gray-50 overflow-x-auto">
    <div class="min-w-max">
      <FamilyTree :person="person" />
    </div>
  </div>
</div>

          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FamilyTree from '@/Components/FamilyTree.vue';
import { format } from 'date-fns';
import { calculateAge } from '@/Utils/ageCalculator';

// ... kode lainnya ...



const props = defineProps({
  person: Object,
  familyTree: Object,
});

const ageText = (birthDate, deathDate) => {
  const age = calculateAge(birthDate, deathDate);
  if (age === null) return 'Tidak diketahui';
  return `${age} tahun` + (deathDate ? ' (meninggal)' : '');
};

const formatDate = (dateString) => {
  if (!dateString) return null;
  return format(new Date(dateString), 'dd MMMM yyyy');
};
</script>

<style scoped>
/* Styling untuk daftar hubungan keluarga */
ul li {
  padding: 0.25rem 0;
}

/* Efek hover untuk link */
a:hover {
  text-decoration: underline;
}

/* Border dan background untuk pohon keluarga */
.bg-gray-50 {
  background-color: #f9fafb;
}

.rounded {
  border-radius: 0.375rem;
}

/* Spacing dan layout */
.grid {
  gap: 2rem;
}

@media (min-width: 768px) {
  .grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

.family-tree {
  min-width: 100%;
  display: inline-block;
}

.tree-container {
  min-width: max-content;
}

/* Di file CSS global atau style tag */
.scroll-container {
  -webkit-overflow-scrolling: touch; /* Untuk smooth scroll di iOS */
  scrollbar-width: none; /* Sembunyikan scrollbar di Firefox */
}
.scroll-container::-webkit-scrollbar {
  display: none; /* Sembunyikan scrollbar di Chrome/Safari */
}
</style>
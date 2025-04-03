<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
  persons: Array,
});

const formatDate = (date) => {
  if (!date) return "-";
  return new Date(date).toLocaleDateString("id-ID");
};
</script>

<template>
  <Head title="Data Keluarga" />
  <div class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-center mb-4">Data Keluarga</h1>
    <div class="overflow-x-auto">
      <table class="w-full border-collapse border border-gray-200">
        <thead class="bg-gray-100">
          <tr class="text-left">
            <th class="p-2 border">Nama</th>
            <th class="p-2 border">Jenis Kelamin</th>
            <th class="p-2 border">Tanggal Lahir</th>
            <th class="p-2 border">Hubungan</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="person in persons" :key="person.id" class="border">
            <td class="p-2 border">{{ person.name }}</td>
            <td class="p-2 border">{{ person.gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td class="p-2 border">{{ formatDate(person.birth_date) }}</td>
            <td class="p-2 border">
              <ul>
                <li v-for="rel in person.relationships" :key="rel.id">
                  {{ rel.type }} dengan {{ rel.related_person?.name || "Tidak Diketahui" }}
                </li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

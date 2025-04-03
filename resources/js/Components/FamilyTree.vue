<template>
  <div class="family-tree">
    <div v-if="person" class="tree-container">
      <!-- Orang Tua -->
      <div v-if="person.parents && person.parents.length > 0" class="parents-level">
        <div class="parents-wrapper">
          <div class="parents-container">
            <div v-for="parent in person.parents" :key="parent.id" class="parent-node">
              <FamilyTreeNode :person="parent" />
            </div>
          </div>
        </div>
        <div class="connector-line vertical"></div>
      </div>

      <!-- Baris Tengah (Orang Utama + Pasangan) -->
      <div class="main-row">
        <!-- Orang Utama -->
        <div class="main-person">
          <FamilyTreeNode :person="person" :isMain="true" />
        </div>

        <!-- Pasangan -->
        <div v-if="person.spouses && person.spouses.length > 0" class="spouses-level">
          <div class="spouses-wrapper">
            <div class="spouses-container">
              <div v-for="spouse in person.spouses" :key="spouse.id" class="spouse-node">
                <div class="connector-line horizontal"></div>
                <FamilyTreeNode :person="spouse" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Anak-anak -->
      <div v-if="person.children && person.children.length > 0" class="children-level">
        <div class="connector-line vertical"></div>
        <div class="children-wrapper">
          <div class="children-container">
            <div v-for="child in person.children" :key="child.id" class="child-node">
              <FamilyTreeNode :person="child" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import FamilyTreeNode from '@/Components/FamilyTreeNode.vue';

const props = defineProps({
  person: Object,
});
</script>

<style scoped>
.family-tree {
  @apply w-full;
}

.tree-container {
  @apply flex flex-col items-center;
}

/* Wrapper untuk scroll horizontal */
.parents-wrapper,
.spouses-wrapper,
.children-wrapper {
  @apply w-full max-w-full overflow-x-auto py-2;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none; /* Untuk Firefox */
}

/* Hide scrollbar */
.parents-wrapper::-webkit-scrollbar,
.spouses-wrapper::-webkit-scrollbar,
.children-wrapper::-webkit-scrollbar {
  display: none;
}

/* Container untuk item horizontal */
.parents-container,
.spouses-container,
.children-container {
  @apply flex justify-center space-x-4 px-4;
  display: inline-flex;
  white-space: nowrap;
}

/* Baris utama (orang utama + pasangan) */
.main-row {
  @apply flex flex-col md:flex-row items-center justify-center;
}

.parents-level {
  @apply mb-4 flex flex-col md:flex-row items-center justify-center;
}

.main-person {
  @apply my-4 md:mx-4;
}

.spouses-level {
  @apply mt-4 md:mt-0 w-full;
}

.children-level {
  @apply mt-4 flex flex-col items-center w-full;
}

/* Garis penghubung */
.connector-line {
  @apply bg-gray-400 mx-auto;
}

.connector-line.vertical {
  @apply w-0.5 h-6;
}

.connector-line.horizontal {
  @apply w-6 h-0.5 my-2;
}

.parent-node,
.spouse-node,
.child-node {
  @apply flex flex-col items-center;
  flex-shrink: 0;
}

/* Responsive design */
@media (max-width: 768px) {
  .main-row {
    @apply flex-col;
  }
  
  .parents-container,
  .spouses-container,
  .children-container {
    @apply space-x-4 px-2;
  }
  
  .connector-line.vertical {
    @apply w-0.5 h-6;
  }
  
  .connector-line.horizontal {
    @apply w-6 h-0.5;
  }
}

/* Animasi untuk hover */
.member-card:hover {
  @apply shadow-md transform transition-all duration-200;
}
</style>
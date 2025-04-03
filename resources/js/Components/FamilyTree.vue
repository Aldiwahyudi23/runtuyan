<template>
  <div class="family-tree">
    <div v-if="person" class="tree-container">
      <!-- Orang Tua -->
      <div v-if="person.parents && person.parents.length > 0" class="parents-level">
        <div class="parents-container">
          <div v-for="parent in person.parents" :key="parent.id" class="parent-node">
            <FamilyTreeNode :person="parent" />
          </div>
        </div>
        <div class="connector-line"></div>
      </div>

      <!-- Orang Utama -->
      <div class="main-person">
        <FamilyTreeNode :person="person" :isMain="true" />
      </div>

      <!-- Pasangan -->
      <div v-if="person.spouses && person.spouses.length > 0" class="spouses-level">
        <div class="spouses-container">
          <div v-for="spouse in person.spouses" :key="spouse.id" class="spouse-node">
            <div class="connector-line"></div>
            <FamilyTreeNode :person="spouse" />
          </div>
        </div>
      </div>

      <!-- Anak-anak -->
      <div v-if="person.children && person.children.length > 0" class="children-level">
        <div class="connector-line"></div>
        <div class="children-container">
          <div v-for="child in person.children" :key="child.id" class="child-node">
            <FamilyTreeNode :person="child" />
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
  @apply overflow-auto;
}

.tree-container {
  @apply flex flex-col items-center;
}

.parents-level {
  @apply mb-4 flex flex-col items-center;
}

.parents-container {
  @apply flex space-x-8;
}

.main-person {
  @apply my-4;
}

.spouses-level {
  @apply mt-4;
}

.spouses-container {
  @apply flex space-x-8;
}

.children-level {
  @apply mt-4 flex flex-col items-center;
}

.children-container {
  @apply flex space-x-8;
}

.connector-line {
  @apply w-0.5 h-6 bg-gray-400 mx-auto;
}

.parent-node, .spouse-node, .child-node {
  @apply flex flex-col items-center;
}

/* Animasi untuk hover */
.member-card:hover {
  @apply shadow-md transform transition-all duration-200;
}

/* Responsive design */
@media (max-width: 640px) {
  .parents-container,
  .spouses-container,
  .children-container {
    @apply flex-col space-x-0 space-y-4;
  }
  
  .connector-line {
    @apply w-6 h-0.5;
  }
}
</style>
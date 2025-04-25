<template>
  <div class="family-tree">
    <div v-if="person" class="tree-container" ref="treeContainer">
      <!-- Parents & Grandparents Level -->
      <div v-if="person.parents && person.parents.length > 0" class="parents-grandparents-level">
        <div class="parents-grandparents-wrapper">
          <div class="parents-grandparents-container">
            <!-- Father and his parents -->
            <div v-if="father" class="father-line">
              <div class="father-parents">
                <div v-for="grandparent in fathersParents" :key="`father-parent-${grandparent.id}`" 
                     class="grandparent-node">
                  <div class="relationship-label">
                    {{ getGrandparentRelationshipLabel(grandparent, 'father') }}
                  </div>
                  <FamilyTreeNodeUmum :person="grandparent" />
                </div>
              </div>
              <div class="vertical-connectors">
                <div class="connector-line vertical"></div>
                <div class="connector-line vertical"></div>
              </div>
              <div class="parent-node father-node">
                <div class="relationship-label">
                  Ayah
                </div>
                <FamilyTreeNodeUmum :person="father" />
              </div>
            </div>

            <!-- Mother and her parents -->
            <div v-if="mother" class="mother-line">
              <div class="mother-parents">
                <div v-for="grandparent in mothersParents" :key="`mother-parent-${grandparent.id}`" 
                     class="grandparent-node">
                  <div class="relationship-label">
                    {{ getGrandparentRelationshipLabel(grandparent, 'mother') }}
                  </div>
                  <FamilyTreeNodeUmum :person="grandparent" />
                </div>
              </div>
              <div class="vertical-connectors">
                <div class="connector-line vertical"></div>
                <div class="connector-line vertical"></div>
              </div>
              <div class="parent-node mother-node">
                <div class="relationship-label">
                  Ibu
                </div>
                <FamilyTreeNodeUmum :person="mother" />
              </div>
            </div>
          </div>
        </div>
        <div class="connector-line vertical long"></div>
      </div>

      <!-- Main Row (Main Person + Spouses) -->
      <div class="main-row">
        <!-- Main Person with Highlight -->
        <div class="main-person" ref="mainPerson">
          <div class="relationship-label main-label">
            ORANG UTAMA
          </div>
          <div class="main-person-highlight">
            <FamilyTreeNodeUmum :person="person" :isMain="true" />
          </div>
          <div class="pulse-animation"></div>
        </div>

        <!-- Spouses -->
        <div v-if="person.spouses && person.spouses.length > 0" class="spouses-level">
          <div class="spouses-wrapper">
            <div class="spouses-container">
              <div v-for="spouse in uniqueSpouses" :key="spouse.id" class="spouse-node">
                <div class="relationship-label">
                  {{ getMainSpouseRelationshipLabel(spouse) }}
                </div>
                <div class="connector-line horizontal"></div>
                <FamilyTreeNodeUmum :person="spouse" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Children Level -->
      <div v-if="person.children && person.children.length > 0" class="children-level">
        <div class="connector-line vertical"></div>
        <div class="children-wrapper">
          <div class="children-container">
            <div v-for="child in uniqueChildren" :key="child.id" class="child-node">
              <div class="relationship-label">
                {{ getChildRelationshipLabel(child) }}
              </div>
              <div class="child-with-spouse">
                <FamilyTreeNodeUmum :person="child" />
                <!-- Spouses of children -->
                <div v-if="child.spouses && child.spouses.length > 0" class="spouses-of-child">
                  <div v-for="spouse in getNonChildSpouses(child)" :key="`child-spouse-${spouse.id}`" class="spouse-of-child-node">
                    <div class="relationship-label">
                      Menantu {{ spouse.gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                    </div>
                    <div class="connector-line horizontal"></div>
                    <FamilyTreeNodeUmum :person="spouse" />
                  </div>
                </div>
              </div>
              <!-- Grandchildren -->
              <div v-if="child.children && child.children.length > 0" class="grandchildren-level">
                <div class="connector-line vertical"></div>
                <div class="grandchildren-wrapper">
                  <div class="grandchildren-container">
                    <div v-for="grandchild in uniqueGrandchildren(child)" :key="grandchild.id" class="grandchild-node">
                      <div class="relationship-label">
                        Cucu {{ grandchild.gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                      </div>
                      <FamilyTreeNodeUmum :person="grandchild" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick, computed } from 'vue';
import FamilyTreeNodeUmum from './FamilyTreeNodeUmum.vue';

const props = defineProps({
  person: Object,
});

const treeContainer = ref(null);
const mainPerson = ref(null);

// Auto-center on load
onMounted(() => {
  nextTick(() => {
    setTimeout(() => {
      if (mainPerson.value && treeContainer.value) {
        const container = treeContainer.value;
        const personElement = mainPerson.value;
        
        const containerWidth = container.offsetWidth;
        const containerScrollWidth = container.scrollWidth;
        const personLeft = personElement.offsetLeft;
        const personWidth = personElement.offsetWidth;
        
        const scrollPosition = personLeft - (containerWidth / 2) + (personWidth / 2);
        const maxScroll = containerScrollWidth - containerWidth;
        container.scrollTo({
          left: Math.max(0, Math.min(scrollPosition, maxScroll)),
          behavior: 'smooth'
        });
      }
    }, 500);
  });
});

// Computed properties
const father = computed(() => {
  return props.person.parents?.find(p => p.gender === 'male');
});

const mother = computed(() => {
  return props.person.parents?.find(p => p.gender === 'female');
});

const fathersParents = computed(() => {
  return father.value?.parents || [];
});

const mothersParents = computed(() => {
  return mother.value?.parents || [];
});

const uniqueSpouses = computed(() => {
  if (!props.person.spouses) return [];
  return props.person.spouses.filter((spouse, index, self) =>
    index === self.findIndex(s => s.id === spouse.id)
  );
});

const uniqueChildren = computed(() => {
  if (!props.person.children) return [];
  return props.person.children.filter((child, index, self) =>
    index === self.findIndex(c => c.id === child.id)
  );
});

const uniqueGrandchildren = (child) => {
  if (!child.children) return [];
  return child.children.filter((grandchild, index, self) =>
    index === self.findIndex(g => g.id === grandchild.id)
  );
};

const getNonChildSpouses = (child) => {
  if (!child.spouses) return [];
  return child.spouses.filter(spouse => 
    !props.person.children?.some(c => c.id === spouse.id)
  );
};

const getGrandparentRelationshipLabel = (grandparent, parentType) => {
  return grandparent.gender === 'male' ? 'Kakek' : 'Nenek';
};

const getMainSpouseRelationshipLabel = (spouse) => {
  return props.person.gender === 'male' ? 
    (spouse.gender === 'male' ? 'Pasangan' : 'Istri') :
    (spouse.gender === 'female' ? 'Pasangan' : 'Suami');
};

const getChildRelationshipLabel = (child) => {
  return child.gender === 'male' ? 'Anak Laki-laki' : 'Anak Perempuan';
};
</script>

<style scoped>
.family-tree {
  @apply w-full;
}

.tree-container {
  @apply flex flex-col items-center overflow-x-auto;
  scroll-behavior: smooth;
  width: 100%;
  max-width: 100vw;
  padding-bottom: 20px;
}

/* Parents & Grandparents Level */
.parents-grandparents-level {
  @apply flex flex-col items-center w-full mb-2;
}

.parents-grandparents-wrapper {
  @apply w-full max-w-full overflow-x-auto py-2;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
}

.parents-grandparents-container {
  @apply flex justify-center space-x-8 px-4;
  display: inline-flex;
  white-space: nowrap;
}

.father-line, .mother-line {
  @apply flex flex-col items-center;
}

.father-parents, .mother-parents {
  @apply flex space-x-4 mb-2;
}

.vertical-connectors {
  @apply flex justify-center space-x-4;
  height: 20px;
}

.parent-node {
  @apply flex flex-col items-center;
  flex-shrink: 0;
  min-width: 120px;
  max-width: 200px;
}

.father-node .relationship-label {
  @apply bg-blue-100 text-blue-800;
}

.mother-node .relationship-label {
  @apply bg-pink-100 text-pink-800;
}

.grandparent-node {
  @apply flex flex-col items-center;
  flex-shrink: 0;
  min-width: 100px;
  max-width: 160px;
}

.father-parents .grandparent-node .relationship-label {
  @apply bg-blue-50 text-blue-700;
}

.mother-parents .grandparent-node .relationship-label {
  @apply bg-pink-50 text-pink-700;
}

.connector-line {
  @apply bg-gray-400 mx-auto;
}

.connector-line.vertical {
  @apply w-0.5 h-6;
}

.connector-line.vertical.long {
  @apply h-12;
}

.connector-line.horizontal {
  @apply w-6 h-0.5 my-2;
}

/* Main Person Highlight */
.main-row {
  @apply flex flex-col md:flex-row items-center justify-center my-4;
  scroll-margin: 50% 0;
}

.main-person {
  @apply md:mx-4 relative;
}

.main-person-highlight {
  @apply relative z-10;
  animation: float 3s ease-in-out infinite;
}

.main-label {
  @apply text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-blue-500 px-3 py-1 rounded-full mb-2;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.pulse-animation {
  @apply absolute inset-0 rounded-full bg-blue-400 opacity-0;
  z-index: 1;
  animation: pulse 2s infinite;
}

@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-5px); }
  100% { transform: translateY(0px); }
}

@keyframes pulse {
  0% {
    transform: scale(0.95);
    opacity: 0.5;
  }
  70% {
    transform: scale(1.05);
    opacity: 0.2;
  }
  100% {
    transform: scale(0.95);
    opacity: 0;
  }
}

/* Spouses Level */
.spouses-level {
  @apply md:mt-0 w-full;
}

.spouses-wrapper {
  @apply w-full max-w-full overflow-x-auto py-2;
  scrollbar-width: none;
}

.spouses-container {
  @apply flex justify-center space-x-4 px-4;
  display: inline-flex;
  white-space: nowrap;
}

.spouse-node {
  @apply flex flex-col items-center;
  flex-shrink: 0;
  min-width: 120px;
  max-width: 200px;
}

.spouse-node .relationship-label {
  @apply bg-green-50 text-green-700;
}

/* Children Level */
.children-level {
  @apply flex flex-col items-center w-full mt-4;
}

.children-wrapper {
  @apply w-full max-w-full overflow-x-auto py-2;
  scrollbar-width: none;
}

.children-container {
  @apply flex justify-center space-x-4 px-4;
  display: inline-flex;
  white-space: nowrap;
}

.child-node {
  @apply flex flex-col items-center;
  flex-shrink: 0;
  min-width: 120px;
  max-width: 200px;
}

.child-node .relationship-label {
  @apply bg-green-100 text-green-800;
}

.child-with-spouse {
  @apply flex flex-col items-center;
}

.spouses-of-child {
  @apply flex flex-col items-center;
}

.spouse-of-child-node {
  @apply flex flex-col items-center mt-2;
}

.spouse-of-child-node .relationship-label {
  @apply bg-orange-100 text-orange-800;
}

/* Grandchildren Level */
.grandchildren-level {
  @apply flex flex-col items-center w-full mt-2;
}

.grandchildren-wrapper {
  @apply w-full max-w-full overflow-x-auto py-2;
  scrollbar-width: none;
}

.grandchildren-container {
  @apply flex justify-center space-x-4 px-4;
  display: inline-flex;
  white-space: nowrap;
}

.grandchild-node {
  @apply flex flex-col items-center;
  flex-shrink: 0;
  min-width: 100px;
  max-width: 160px;
}

.grandchild-node .relationship-label {
  @apply bg-purple-100 text-purple-800;
}

/* Hide scrollbars */
.parents-grandparents-wrapper::-webkit-scrollbar,
.spouses-wrapper::-webkit-scrollbar,
.children-wrapper::-webkit-scrollbar,
.grandchildren-wrapper::-webkit-scrollbar,
.tree-container::-webkit-scrollbar {
  display: none;
}

/* Responsive design */
@media (max-width: 768px) {
  .parents-grandparents-container {
    @apply space-x-4 px-2;
  }
  
  .father-parents, .mother-parents {
    @apply space-x-2;
  }
  
  .grandparent-node {
    min-width: 80px;
    max-width: 120px;
  }
  
  .main-row {
    @apply flex-col;
  }
  
  .spouses-container,
  .children-container,
  .grandchildren-container {
    @apply space-x-4 px-2;
  }
  
  .connector-line.vertical {
    @apply w-0.5 h-6;
  }
  
  .connector-line.horizontal {
    @apply w-6 h-0.5;
  }
  
  .parent-node,
  .spouse-node,
  .child-node {
    min-width: 100px;
    max-width: 160px;
  }
  
  .grandchild-node {
    min-width: 80px;
    max-width: 120px;
  }
  
  .spouse-of-child-node {
    @apply mt-1;
  }

  .relationship-label {
    @apply text-[10px];
    padding: 1px 4px;
  }
  
  .main-label {
    @apply text-xs px-2 py-1;
  }
  
  .connector-line.vertical.long {
    @apply h-8;
  }
}
</style>
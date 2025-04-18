<template>
  <div class="family-tree">
    <div v-if="person" class="tree-container" ref="treeContainer">
      <!-- Parents Level -->
      <div v-if="person.parents && person.parents.length > 0" class="parents-level">
        <div class="parents-wrapper">
          <div class="parents-container">
            <div v-for="parent in uniqueParents" :key="parent.id" class="parent-node">
              <div class="relationship-label">
                {{ getParentRelationshipLabel(parent) }}
              </div>
              <FamilyTreeNodeUmum :person="parent" />
              <!-- Spouses of parents -->
              <div v-if="parent.spouses && parent.spouses.length > 0" class="spouses-of-parent">
                <div v-for="spouse in getNonParentSpouses(parent)" :key="`parent-spouse-${spouse.id}`" class="spouse-of-parent-node">
                  <div class="relationship-label">
                    {{ getSpouseRelationshipLabel(parent, spouse) }}
                  </div>
                  <div class="connector-line horizontal"></div>
                  <FamilyTreeNodeUmum :person="spouse" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="connector-line vertical"></div>
      </div>

      <!-- Main Row (Main Person + Spouses) -->
      <div class="main-row">
        <!-- Main Person -->
        <div class="main-person" ref="mainPerson">
          <div class="relationship-label main-label">
            {{ person.gender === 'male' ? 'Orang Utama' : 'Orang Utama' }}
          </div>
          <FamilyTreeNodeUmum :person="person" :isMain="true" />
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
                      <!-- Spouses of grandchildren -->
                      <div v-if="grandchild.spouses && grandchild.spouses.length > 0" class="spouses-of-grandchild">
                        <div v-for="spouse in getNonGrandchildSpouses(grandchild, child)" :key="`grandchild-spouse-${spouse.id}`" class="spouse-of-grandchild-node">
                          <div class="relationship-label">
                            Pasangan Cucu
                          </div>
                          <div class="connector-line horizontal"></div>
                          <FamilyTreeNodeUmum :person="spouse" />
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
        
        // Calculate scroll position
        const containerWidth = container.offsetWidth;
        const containerScrollWidth = container.scrollWidth;
        const personLeft = personElement.offsetLeft;
        const personWidth = personElement.offsetWidth;
        
        // Calculate center position
        const scrollPosition = personLeft - (containerWidth / 2) + (personWidth / 2);
        
        // Apply scroll with boundary checks
        const maxScroll = containerScrollWidth - containerWidth;
        container.scrollTo({
          left: Math.max(0, Math.min(scrollPosition, maxScroll)),
          behavior: 'smooth'
        });
      }
    }, 500); // Additional delay to ensure all elements are rendered
  });
});

// Existing computed properties and helper functions remain the same
const uniqueParents = computed(() => {
  if (!props.person.parents) return [];
  return props.person.parents.filter((parent, index, self) =>
    index === self.findIndex(p => p.id === parent.id)
  );
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

const getNonParentSpouses = (parent) => {
  if (!parent.spouses) return [];
  return parent.spouses.filter(spouse => 
    !props.person.parents?.some(p => p.id === spouse.id)
  );
};

const getNonChildSpouses = (child) => {
  if (!child.spouses) return [];
  return child.spouses.filter(spouse => 
    !props.person.children?.some(c => c.id === spouse.id)
  );
};

const getNonGrandchildSpouses = (grandchild, parentChild) => {
  if (!grandchild.spouses) return [];
  return grandchild.spouses.filter(spouse => 
    !parentChild.children?.some(gc => gc.id === spouse.id)
  );
};

const getParentRelationshipLabel = (parent) => {
  return parent.gender === 'male' ? 'Ayah' : 'Ibu';
};

const getSpouseRelationshipLabel = (person, spouse) => {
  if (person.gender === 'male') {
    return spouse.gender === 'male' ? 'Pasangan (Laki-laki)' : 'Istri';
  } else {
    return spouse.gender === 'female' ? 'Pasangan (Perempuan)' : 'Suami';
  }
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

.parents-wrapper,
.spouses-wrapper,
.children-wrapper,
.grandchildren-wrapper {
  @apply w-full max-w-full overflow-x-auto py-2;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  scroll-behavior: smooth;
}

/* Hide scrollbars */
.parents-wrapper::-webkit-scrollbar,
.spouses-wrapper::-webkit-scrollbar,
.children-wrapper::-webkit-scrollbar,
.grandchildren-wrapper::-webkit-scrollbar,
.tree-container::-webkit-scrollbar {
  display: none;
}

/* Container untuk item horizontal */
.parents-container,
.spouses-container,
.children-container,
.grandchildren-container {
  @apply flex justify-center space-x-4 px-4;
  display: inline-flex;
  white-space: nowrap;
}

/* Baris utama (orang utama + pasangan) */
.main-row {
  @apply flex flex-col md:flex-row items-center justify-center;
  scroll-margin: 50% 0;
}

.parents-level {
  @apply flex flex-col md:flex-row items-center justify-center;
}

.main-person {
  @apply md:mx-4;
}

.spouses-level {
  @apply md:mt-0 w-full;
}

.children-level,
.grandchildren-level {
  @apply flex flex-col items-center w-full;
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
.child-node,
.grandchild-node {
  @apply flex flex-col items-center;
  flex-shrink: 0;
  min-width: 120px;
  max-width: 200px;
}

/* Spouse containers */
.spouses-of-parent,
.spouses-of-child,
.spouses-of-grandchild {
  @apply flex flex-col items-center;
}

.spouse-of-parent-node,
.spouse-of-child-node,
.spouse-of-grandchild-node {
  @apply flex flex-col items-center mt-2;
}

.child-with-spouse {
  @apply flex flex-col items-center;
}

.relationship-label {
  @apply text-xs font-medium text-gray-600 mb-1 text-center;
  padding: 2px 6px;
  background-color: rgba(255, 255, 255, 0.8);
  border-radius: 4px;
  display: inline-block;
}

.main-label {
  @apply text-sm font-semibold text-blue-600;
}

.parent-node .relationship-label,
.spouse-node .relationship-label {
  @apply bg-blue-50 text-blue-700;
}

.child-node .relationship-label {
  @apply bg-green-50 text-green-700;
}

.grandchild-node .relationship-label {
  @apply bg-purple-50 text-purple-700;
}

.spouse-of-child-node .relationship-label,
.spouse-of-grandchild-node .relationship-label {
  @apply bg-orange-50 text-orange-700;
}

/* Responsive design */
@media (max-width: 768px) {
  .main-row {
    @apply flex-col;
  }
  
  .parents-container,
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
  .child-node,
  .grandchild-node {
    min-width: 100px;
    max-width: 160px;
  }
  
  .spouse-of-parent-node,
  .spouse-of-child-node,
  .spouse-of-grandchild-node {
    @apply mt-1;
  }

  .relationship-label {
    @apply text-[10px];
    padding: 1px 4px;
  }
  
  .main-label {
    @apply text-xs;
  }
}
</style>
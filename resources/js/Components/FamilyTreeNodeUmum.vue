<template>
  <div 
    class="family-member" 
    :class="{ 'main-member': isMain, 'selected': isSelected }"
    @click="selectMember"
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
  >
    <Link 
      :href="route('public.family-tree.show', person.id)"
      class="member-card"
    >
      <div class="member-avatar" :class="avatarClass">
        {{ initials }}
      </div>
      <div class="member-info-container">
        <div class="member-info-content">
          <div class="member-name">{{ person.name }}</div>
          <div class="member-details">
            {{ genderText }} <br> {{ ageText }}
          </div>
        </div>
      </div>
    </Link>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { differenceInYears, parseISO } from 'date-fns';

const props = defineProps({
  person: Object,
  isMain: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['member-selected']);

const isSelected = ref(false);
const isHovered = ref(false);

const selectMember = () => {
  isSelected.value = !isSelected.value;
  emit('member-selected', { person: props.person, selected: isSelected.value });
};

// Hitung inisial dari nama
const initials = computed(() => {
  const names = props.person.name.split(' ');
  return names.map(name => name[0]).join('').toUpperCase();
});

// Teks jenis kelamin dalam Bahasa Indonesia
const genderText = computed(() => {
  return props.person.gender === 'male' ? 'Laki-laki' : 'Perempuan';
});

// Hitung usia dari tanggal lahir
const ageText = computed(() => {
  if (!props.person.birth_date) return '-';
  
  const birthDate = parseISO(props.person.birth_date);
  const endDate = props.person.death_date ? parseISO(props.person.death_date) : new Date();
  const age = differenceInYears(endDate, birthDate);
  
  return props.person.death_date 
    ? `${age} tahun (Alm)`
    : `${age} tahun`;
});

// Kelas untuk avatar berdasarkan jenis kelamin
const avatarClass = computed(() => {
  return {
    'bg-blue-100 text-blue-800': props.person.gender === 'male',
    'bg-pink-100 text-pink-800': props.person.gender === 'female',
    'border-2 border-yellow-400': props.isMain,
    'ring-2 ring-blue-500': isSelected.value,
    'scale-110': isHovered.value && !isSelected.value,
  };
});
</script>

<style scoped>
.family-member {
  @apply flex flex-col items-center;
  min-width: 120px;
  max-width: 200px;
  transition: all 0.3s ease;
}

.main-member {
  @apply transform scale-110;
  min-width: 140px;
  max-width: 220px;
}

.member-card {
  @apply flex flex-col items-center p-3 rounded-lg shadow-sm bg-white hover:shadow-md transition-all duration-300 w-full;
  min-height: 120px;
  overflow: hidden;
  transform-origin: center;
}

/* Animation effects */
.family-member:hover .member-card {
  @apply shadow-lg;
  transform: translateY(-2px);
}

.family-member.selected .member-card {
  @apply shadow-lg ring-2 ring-blue-500;
  transform: translateY(-4px);
}

/* Avatar */
.member-avatar {
  @apply flex items-center justify-center font-medium text-lg mb-2 rounded-full flex-shrink-0 transition-all duration-300;
  height: 3rem; /* 48px */
  width: 3rem; /* 48px */
}

/* Info Container */
.member-info-container {
  @apply w-full overflow-x-auto;
  scrollbar-width: thin; /* For Firefox */
  scrollbar-color: #cbd5e0 #f7fafc; /* For Firefox */
  -webkit-overflow-scrolling: touch; /* For smooth scrolling on iOS */
}

/* Hide scrollbar for Chrome, Safari and Opera */
.member-info-container::-webkit-scrollbar {
  height: 3px; /* Thin scrollbar */
}

.member-info-container::-webkit-scrollbar-track {
  @apply bg-gray-100;
}

.member-info-container::-webkit-scrollbar-thumb {
  @apply bg-gray-400 rounded-full;
}

/* Info Content */
.member-info-content {
  @apply text-center;
  min-width: 100%; /* Ensure content takes full width */
  white-space: nowrap; /* Prevent text wrapping */
}

.member-name {
  @apply font-medium text-sm;
  max-width: 100%;
  display: inline-block;
  overflow: hidden;
  text-overflow: ellipsis;
  transition: all 0.3s ease;
}

.family-member:hover .member-name {
  @apply text-blue-600;
}

.member-details {
  @apply text-xs text-gray-500;
  white-space: nowrap;
  transition: all 0.3s ease;
}

.family-member:hover .member-details {
  @apply text-gray-700;
}

/* Pulse animation for selected state */
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.03); }
  100% { transform: scale(1); }
}

.family-member.selected {
  animation: pulse 1.5s infinite;
  z-index: 10;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .family-member {
    min-width: 100px;
    max-width: 160px;
  }
  
  .main-member {
    min-width: 120px;
    max-width: 180px;
  }

  .member-card {
    @apply p-2;
    min-height: 110px;
  }

  .member-avatar {
    height: 2.5rem; /* 40px */
    width: 2.5rem; /* 40px */
    @apply text-base;
  }

  .member-name {
    @apply text-xs;
  }

  .member-details {
    @apply text-[10px];
  }
  
  .family-member:hover .member-card {
    transform: translateY(-1px);
  }
  
  .family-member.selected .member-card {
    transform: translateY(-2px);
  }
}
</style>
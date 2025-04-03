<template>
  <div class="family-member" :class="{ 'main-member': isMain }">
    <Link 
      :href="route('people.show', person.id)"
      class="member-card"
    >
      <div class="member-avatar" :class="avatarClass">
        {{ initials }}
      </div>
      <div class="member-info">
        <div class="member-name">{{ person.name }}</div>
        <div class="member-details">
          {{ ageText }} • {{ genderText }}
        </div>
      </div>
    </Link>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { differenceInYears, parseISO } from 'date-fns';

const props = defineProps({
  person: Object,
  isMain: {
    type: Boolean,
    default: false,
  },
});

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
  if (!props.person.birth_date) return '';
  
  const birthDate = parseISO(props.person.birth_date);
  const endDate = props.person.death_date ? parseISO(props.person.death_date) : new Date();
  const age = differenceInYears(endDate, birthDate);
  
  return props.person.death_date 
    ? `${age} tahun (meninggal)`
    : `${age} tahun`;
});

// Kelas untuk avatar berdasarkan jenis kelamin
const avatarClass = computed(() => {
  return {
    'bg-blue-100 text-blue-800': props.person.gender === 'male',
    'bg-pink-100 text-pink-800': props.person.gender === 'female',
    'border-2 border-yellow-400': props.isMain,
  };
});
</script>

<style scoped>
.family-member {
  @apply flex flex-col items-center;
}

.main-member {
  @apply transform scale-110;
}

.member-card {
  @apply flex flex-col items-center p-3 rounded-lg shadow-sm bg-white hover:shadow-md transition-shadow;
  width: 7rem; /* Ukuran default */
}

/* Avatar */
.member-avatar {
  @apply flex items-center justify-center font-medium text-lg mb-2 rounded-full;
  height: 3rem; /* 48px */
  width: 3rem; /* 48px */
}

/* Info */
.member-info {
  @apply text-center;
}

.member-name {
  @apply font-medium text-sm truncate w-full;
}

.member-details {
  @apply text-xs text-gray-500;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .member-card {
    width: 5rem; /* Diperkecil untuk mobile */
    @apply p-2 text-xs;
  }

  .member-avatar {
    height: 2.5rem; /* 40px */
    width: 2.5rem; /* 40px */
  }

  .member-name {
    @apply text-xs;
  }

  .member-details {
    @apply text-[10px];
  }
}
</style>
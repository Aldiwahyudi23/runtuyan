<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900">
          <Link :href="route('public.family-tree.index')">
            Sistem Runtuyan-Keturunan
          </Link>
        </h1>
      </div>
    </header>

    <!-- Page Content -->
    <main>
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8">
      <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
        &copy; {{ new Date().getFullYear() }} Kel Ma HAYA.
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
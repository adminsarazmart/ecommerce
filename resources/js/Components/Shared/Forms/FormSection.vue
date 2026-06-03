<template>
  <div class="glass-card p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ title }}</h3>
    <p v-if="description" class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ description }}</p>
    <div class="space-y-5"><slot /></div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  description: String,
})
</script>

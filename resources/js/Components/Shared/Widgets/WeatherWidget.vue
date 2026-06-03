<template>
  <div class="glass-card p-4">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ location }}</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ temperature }}°</p>
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ condition }}</p>
      </div>
      <SunIcon class="h-10 w-10 text-amber-400" />
    </div>
  </div>
</template>

<script setup>
import { SunIcon } from '@heroicons/vue/24/outline'

defineProps({
  location: { type: String, default: 'New York' },
  temperature: { type: Number, default: 72 },
  condition: { type: String, default: 'Sunny' },
})
</script>

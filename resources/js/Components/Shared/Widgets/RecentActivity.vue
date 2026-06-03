<template>
  <div class="glass-card p-5">
    <SectionHeader title="Recent Activity" size="md" />
    <div class="space-y-4 mt-4">
      <div v-for="(activity, i) in activities" :key="i" class="flex items-start gap-3">
        <div class="p-2 rounded-xl" :class="activity.color">
          <component :is="activity.icon" class="h-4 w-4" />
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm text-gray-900 dark:text-white">{{ activity.text }}</p>
          <p class="text-xs text-gray-500 dark:text-gray-400">{{ activity.time }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import SectionHeader from '../UI/SectionHeader.vue'

defineProps({
  activities: { type: Array, default: () => [] },
})
</script>

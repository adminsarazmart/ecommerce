<template>
  <div :class="skeletonClasses" :style="{ width: width, height: height }">
    <div class="shimmer-bg h-full w-full rounded-inherit" />
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  width: { type: String, default: '100%' },
  height: { type: String, default: '20px' },
  rounded: { type: String, default: 'lg' },
  variant: { type: String, default: 'text' },
})

const variants = { text: 'h-4', circle: 'rounded-full', rect: '' }

const skeletonClasses = computed(() => [
  'relative overflow-hidden',
  `rounded-${props.rounded}`,
  variants[props.variant] || '',
  'bg-gray-100 dark:bg-gray-800',
])
</script>

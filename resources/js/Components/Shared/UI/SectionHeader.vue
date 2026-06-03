<template>
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 :class="['font-heading font-semibold text-gray-900 dark:text-white', sizeClass]">{{ title }}</h2>
      <p v-if="description" class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ description }}</p>
    </div>
    <div v-if="$slots.actions" class="flex items-center gap-2">
      <slot name="actions" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: String,
  description: String,
  size: { type: String, default: 'xl' },
})

const sizes = { sm: 'text-lg', md: 'text-xl', lg: 'text-2xl', xl: 'text-3xl' }
const sizeClass = computed(() => sizes[props.size] || sizes.xl)
</script>

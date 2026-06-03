<template>
  <div :class="cardClasses">
    <div v-if="$slots.header" class="px-6 py-4 border-b border-gray-100 dark:border-gray-800"><slot name="header" /></div>
    <div :class="['px-6 py-5', $slots.header ? '' : '']"><slot /></div>
    <div v-if="$slots.footer" class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 rounded-b-2xl"><slot name="footer" /></div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'glass' },
  hover: Boolean,
  padding: { type: String, default: '' },
})

const variants = {
  glass: 'glass-card',
  solid: 'bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm',
  outline: 'border-2 border-gray-200 dark:border-gray-700 rounded-2xl',
  elevated: 'bg-white dark:bg-gray-900 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-black/20',
}

const cardClasses = computed(() => [
  variants[props.variant] || variants.glass,
  props.hover ? 'hover:shadow-glass-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer' : '',
  props.padding || '',
])
</script>

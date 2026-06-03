<template>
  <span :class="['font-semibold', sizeClass, colorClass]">
    <span v-if="original" class="line-through text-gray-400 dark:text-gray-500 font-normal mr-2">{{ formatPrice(original) }}</span>
    {{ formatPrice(value) }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  value: { type: Number, required: true },
  original: Number,
  currency: { type: String, default: 'USD' },
  size: { type: String, default: 'base' },
  color: { type: String, default: 'default' },
})

const sizes = { xs: 'text-xs', sm: 'text-sm', base: 'text-base', lg: 'text-lg', xl: 'text-xl', '2xl': 'text-2xl', '3xl': 'text-3xl' }
const colors = { default: 'text-gray-900 dark:text-white', brand: 'text-brand-600 dark:text-brand-400', danger: 'text-red-600 dark:text-red-400', success: 'text-emerald-600 dark:text-emerald-400' }

const sizeClass = computed(() => sizes[props.size] || sizes.base)
const colorClass = computed(() => colors[props.color] || colors.default)

const formatPrice = (amount) => {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: props.currency }).format(amount || 0)
}
</script>

<template>
  <span :class="badgeClasses"><slot /></span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'neutral' },
  size: { type: String, default: 'sm' },
  dot: Boolean,
})

const variants = { success: 'badge-success', warning: 'badge-warning', danger: 'badge-danger', info: 'badge-info', neutral: 'badge-neutral' }
const sizes = { sm: 'px-2 py-0.5 text-xs', md: 'px-3 py-1 text-sm', lg: 'px-4 py-1.5 text-sm' }

const badgeClasses = computed(() => [
  variants[props.variant] || variants.neutral,
  sizes[props.size] || sizes.sm,
  'inline-flex items-center font-medium rounded-full',
  props.dot ? 'gap-1.5' : '',
])
</script>

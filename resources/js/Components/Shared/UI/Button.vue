<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="buttonClasses"
    @click="$emit('click', $event)"
    v-bind="$attrs"
  >
    <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
    </svg>
    <component :is="icon" v-if="icon && !loading" class="h-5 w-5" />
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: { type: String, default: 'button' },
  variant: { type: String, default: 'primary' },
  size: { type: String, default: 'md' },
  disabled: Boolean,
  loading: Boolean,
  icon: [Object, Function],
  block: Boolean,
  rounded: { type: String, default: 'xl' },
})

defineEmits(['click'])

const classes = {
  primary: 'btn-primary',
  secondary: 'btn-secondary',
  danger: 'btn-danger',
  ghost: 'btn-ghost',
  outline: 'border-2 border-brand-500 text-brand-600 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/10',
  link: 'text-brand-600 dark:text-brand-400 hover:underline px-0 py-0 shadow-none bg-transparent',
}

const sizes = {
  xs: 'px-2.5 py-1 text-xs',
  sm: 'px-3 py-1.5 text-sm',
  md: 'px-6 py-2.5 text-sm',
  lg: 'px-8 py-3.5 text-base',
  xl: 'px-10 py-4 text-lg',
}

const buttonClasses = computed(() => [
  classes[props.variant] || classes.primary,
  sizes[props.size] || sizes.md,
  props.block ? 'w-full' : '',
  `rounded-${props.rounded}`,
  'inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95',
])
</script>

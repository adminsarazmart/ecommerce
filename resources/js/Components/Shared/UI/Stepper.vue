<template>
  <div class="flex items-center justify-center">
    <div v-for="(step, i) in steps" :key="i" class="flex items-center">
      <div :class="['flex items-center gap-2', i < modelValue ? 'text-brand-600 dark:text-brand-400' : i === modelValue ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500']">
        <div :class="['w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300', i < modelValue ? 'bg-brand-500 text-white' : i === modelValue ? 'glass border-2 border-brand-500' : 'bg-gray-100 dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700']">
          <CheckIcon v-if="i < modelValue" class="h-4 w-4" />
          <span v-else>{{ i + 1 }}</span>
        </div>
        <span class="text-sm font-medium hidden sm:inline">{{ step.label }}</span>
      </div>
      <div v-if="i < steps.length - 1" :class="['w-12 sm:w-20 h-0.5 mx-2 rounded transition-colors duration-300', i < modelValue ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-700']" />
    </div>
  </div>
</template>

<script setup>
import { CheckIcon } from '@heroicons/vue/20/solid'

defineProps({
  modelValue: { type: Number, default: 0 },
  steps: { type: Array, required: true },
})
</script>

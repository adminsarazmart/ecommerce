<template>
  <Switch :modelValue="modelValue" @update:modelValue="$emit('update:modelValue', $event)" :class="['relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900', modelValue ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-700']">
    <span :class="['inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform duration-200', modelValue ? 'translate-x-6' : 'translate-x-1']" />
  </Switch>
</template>

<script setup>
import { Switch } from '@headlessui/vue'
defineProps({ modelValue: Boolean })
defineEmits(['update:modelValue'])
</script>

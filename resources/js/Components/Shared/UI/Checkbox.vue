<template>
  <label class="flex items-center gap-2 cursor-pointer group">
    <input :checked="modelValue" type="checkbox" class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500 dark:bg-gray-800" @change="$emit('update:modelValue', $event.target.checked)" />
    <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white"><slot /></span>
  </label>
</template>

<script setup>
defineProps({ modelValue: { type: Boolean, default: false } })
defineEmits(['update:modelValue'])
</script>

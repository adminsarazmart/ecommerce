<template>
  <fieldset>
    <legend v-if="label" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ label }}</legend>
    <div class="flex gap-4" :class="{ 'flex-col': vertical }">
      <label v-for="option in options" :key="option.value" class="flex items-center gap-2 cursor-pointer group">
        <input :value="option.value" :checked="modelValue === option.value" type="radio" class="h-4 w-4 text-brand-500 border-gray-300 dark:border-gray-600 focus:ring-brand-500 dark:bg-gray-800" @change="$emit('update:modelValue', option.value)" />
        <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">{{ option.label }}</span>
      </label>
    </div>
  </fieldset>
</template>

<script setup>
defineProps({ modelValue: [String, Number], options: Array, label: String, vertical: Boolean })
defineEmits(['update:modelValue'])
</script>

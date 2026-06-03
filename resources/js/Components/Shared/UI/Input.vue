<template>
  <div class="relative">
    <input
      :type="type"
      :value="modelValue"
      :placeholder="placeholder || ' '"
      :disabled="disabled"
      :class="inputClasses"
      @input="$emit('update:modelValue', $event.target.value)"
      @blur="$emit('blur', $event)"
      v-bind="$attrs"
    />
    <label v-if="label" :for="$attrs.id" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-brand-600 dark:peer-focus:text-brand-400 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-1 rtl:peer-focus:translate-x-1/4 cursor-text">
      {{ label }}
    </label>
    <div v-if="error" class="mt-1 text-xs text-red-500">{{ error }}</div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: [String, Number],
  type: { type: String, default: 'text' },
  label: String,
  placeholder: String,
  error: String,
  disabled: Boolean,
})

defineEmits(['update:modelValue', 'blur'])

const inputClasses = computed(() => [
  'glass-input peer w-full',
  props.error ? 'border-red-500 focus:ring-red-500' : '',
  props.label ? 'pt-5 pb-2' : '',
  props.disabled ? 'opacity-50 cursor-not-allowed' : '',
])
</script>

<template>
  <div class="relative">
    <div class="relative">
      <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
      <input :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" type="text" :placeholder="placeholder" class="glass-input w-full pl-10 pr-4" />
      <button v-if="modelValue" @click="$emit('update:modelValue', '')" class="absolute right-3 top-1/2 -translate-y-1/2 p-0.5 rounded hover:bg-gray-100 dark:hover:bg-gray-800">
        <XMarkIcon class="h-4 w-4 text-gray-400" />
      </button>
    </div>
  </div>
</template>

<script setup>
import { MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline'

defineProps({
  modelValue: String,
  placeholder: { type: String, default: 'Search...' },
})
defineEmits(['update:modelValue'])
</script>

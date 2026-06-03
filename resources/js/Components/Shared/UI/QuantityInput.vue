<template>
  <div class="flex items-center glass rounded-xl">
    <button :disabled="modelValue <= 1" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-l-xl transition-colors disabled:opacity-30" @click="$emit('update:modelValue', modelValue - 1)">
      <MinusIcon class="h-4 w-4 text-gray-600 dark:text-gray-400" />
    </button>
    <input :value="modelValue" @input="handleInput" type="number" min="1" class="w-14 text-center bg-transparent border-x border-gray-200 dark:border-gray-700 py-2 text-sm font-medium text-gray-900 dark:text-white focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
    <button :disabled="modelValue >= max" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-r-xl transition-colors disabled:opacity-30" @click="$emit('update:modelValue', modelValue + 1)">
      <PlusIcon class="h-4 w-4 text-gray-600 dark:text-gray-400" />
    </button>
  </div>
</template>

<script setup>
import { MinusIcon, PlusIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
  modelValue: { type: Number, default: 1 },
  max: { type: Number, default: 99 },
})

const emit = defineEmits(['update:modelValue'])

const handleInput = (e) => {
  const val = parseInt(e.target.value) || 1
  emit('update:modelValue', Math.max(1, Math.min(val, props.max)))
}
</script>

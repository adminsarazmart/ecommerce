<template>
  <div class="flex items-center gap-0.5" :class="{ 'cursor-pointer': interactive }">
    <button v-for="i in max" :key="i" :type="interactive ? 'button' : undefined" :disabled="!interactive" @click="interactive && $emit('update:modelValue', i)" class="transition-transform duration-150" :class="{ 'hover:scale-110': interactive }">
      <StarIcon v-if="i <= modelValue" class="h-5 w-5 text-amber-400 fill-current" />
      <StarIcon v-else class="h-5 w-5 text-gray-300 dark:text-gray-600" />
    </button>
    <span v-if="showValue" class="ml-1.5 text-sm font-medium text-gray-600 dark:text-gray-400">{{ modelValue }}/{{ max }}</span>
    <span v-if="reviewCount" class="ml-1.5 text-xs text-gray-400">({{ reviewCount }})</span>
  </div>
</template>

<script setup>
import { StarIcon } from '@heroicons/vue/20/solid'

defineProps({
  modelValue: { type: Number, default: 0 },
  max: { type: Number, default: 5 },
  interactive: Boolean,
  showValue: Boolean,
  reviewCount: Number,
})

defineEmits(['update:modelValue'])
</script>

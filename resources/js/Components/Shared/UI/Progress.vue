<template>
  <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden" :style="{ height: height + 'px' }">
    <div class="h-full rounded-full transition-all duration-500 ease-out" :class="colorClass" :style="{ width: Math.min(modelValue, 100) + '%' }">
      <span v-if="showLabel" class="absolute inset-0 flex items-center justify-center text-xs font-medium text-white">{{ Math.round(modelValue) }}%</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: { type: Number, default: 0 },
  height: { type: Number, default: 8 },
  color: { type: String, default: 'brand' },
  showLabel: Boolean,
})

const colors = {
  brand: 'bg-gradient-to-r from-brand-500 to-brand-400',
  success: 'bg-gradient-to-r from-emerald-500 to-emerald-400',
  warning: 'bg-gradient-to-r from-amber-500 to-amber-400',
  danger: 'bg-gradient-to-r from-red-500 to-red-400',
}

const colorClass = computed(() => colors[props.color] || colors.brand)
</script>

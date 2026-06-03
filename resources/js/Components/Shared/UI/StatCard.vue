<template>
  <div class="glass-card p-5">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ label }}</p>
        <p :class="['mt-1 font-bold tracking-tight', sizeClass]">{{ formattedValue }}</p>
        <p v-if="change" :class="['mt-1 flex items-center gap-0.5 text-xs font-medium', change >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400']">
          <ArrowTrendingUpIcon v-if="change >= 0" class="h-3 w-3" />
          <ArrowTrendingDownIcon v-else class="h-3 w-3" />
          {{ Math.abs(change) }}%
        </p>
      </div>
      <div class="p-3 rounded-xl" :class="iconBg">
        <component :is="icon" class="h-6 w-6" :class="iconColor" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { ArrowTrendingUpIcon, ArrowTrendingDownIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  label: String,
  value: [String, Number],
  change: Number,
  icon: [Object, Function],
  iconBg: { type: String, default: 'bg-brand-50 dark:bg-brand-500/10' },
  iconColor: { type: String, default: 'text-brand-600 dark:text-brand-400' },
  size: { type: String, default: '2xl' },
  prefix: String,
  suffix: String,
})

const sizes = { sm: 'text-lg', base: 'text-xl', lg: 'text-2xl', xl: 'text-3xl', '2xl': 'text-4xl' }
const sizeClass = computed(() => sizes[props.size] || sizes['2xl'])

const formattedValue = computed(() => {
  let v = props.value
  if (props.prefix) v = props.prefix + v
  if (props.suffix) v = v + props.suffix
  return v
})
</script>

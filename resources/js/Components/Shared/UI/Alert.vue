<template>
  <div :class="['flex items-start gap-3 p-4 rounded-xl border', alertClass]" role="alert">
    <component :is="iconMap[variant]" class="h-5 w-5 flex-shrink-0 mt-0.5" />
    <div class="flex-1">
      <p v-if="title" class="text-sm font-semibold">{{ title }}</p>
      <p class="text-sm opacity-90"><slot /></p>
    </div>
    <button v-if="dismissible" @click="$emit('dismiss')" class="flex-shrink-0 p-0.5 rounded-lg hover:bg-black/10 dark:hover:bg-white/10 transition-colors">
      <XMarkIcon class="h-4 w-4" />
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { CheckCircleIcon, XCircleIcon, ExclamationTriangleIcon, InformationCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  variant: { type: String, default: 'info' },
  title: String,
  dismissible: Boolean,
})

defineEmits(['dismiss'])

const iconMap = { success: CheckCircleIcon, error: XCircleIcon, warning: ExclamationTriangleIcon, info: InformationCircleIcon }

const alertClass = computed(() => ({
  success: 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  error: 'bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-300 border-red-200 dark:border-red-800',
  warning: 'bg-amber-50 dark:bg-amber-900/20 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  info: 'bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-800',
}[props.variant]))
</script>

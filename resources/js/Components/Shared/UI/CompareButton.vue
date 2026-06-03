<template>
  <button :class="['p-2 rounded-xl transition-all duration-200', active ? 'text-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'text-gray-400 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10']" @click="$emit('toggle')">
    <ArrowsRightLeftIcon :class="['h-5 w-5 transition-transform duration-200', active ? 'scale-110' : '']" />
  </button>
</template>

<script setup>
import { ArrowsRightLeftIcon } from '@heroicons/vue/24/outline'

defineProps({ active: Boolean })
defineEmits(['toggle'])
</script>

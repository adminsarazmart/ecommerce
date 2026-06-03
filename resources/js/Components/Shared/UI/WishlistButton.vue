<template>
  <button :class="['p-2 rounded-xl transition-all duration-200', active ? 'text-red-500 bg-red-50 dark:bg-red-900/20' : 'text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20']" @click="$emit('toggle')">
    <HeartIcon :class="['h-5 w-5 transition-transform duration-200', active ? 'fill-current scale-110' : '']" />
  </button>
</template>

<script setup>
import { HeartIcon } from '@heroicons/vue/24/outline'

defineProps({ active: Boolean })
defineEmits(['toggle'])
</script>

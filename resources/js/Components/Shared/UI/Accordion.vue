<template>
  <div :class="['border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden', { 'shadow-sm': open }]">
    <button class="w-full flex items-center justify-between px-5 py-4 text-left font-medium text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" @click="open = !open">
      <span>{{ title }}</span>
      <ChevronDownIcon :class="['h-5 w-5 text-gray-400 transition-transform duration-300', open ? 'rotate-180' : '']" />
    </button>
    <Transition name="accordion">
      <div v-if="open" class="px-5 pb-4 text-sm text-gray-600 dark:text-gray-400"><slot /></div>
    </Transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

defineProps({ title: String })
const open = ref(false)
</script>

<style scoped>
.accordion-enter-active { transition: all 0.3s ease-out; max-height: 500px; }
.accordion-leave-active { transition: all 0.2s ease-in; max-height: 0; }
.accordion-enter-from, .accordion-leave-to { opacity: 0; max-height: 0; overflow: hidden; }
.accordion-enter-to, .accordion-leave-from { opacity: 1; overflow: hidden; }
</style>

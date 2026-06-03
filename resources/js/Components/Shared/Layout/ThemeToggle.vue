<template>
  <button :class="['p-2 rounded-xl transition-all duration-200', variant === 'glass' ? 'glass hover:bg-white/30 dark:hover:bg-white/10' : 'hover:bg-gray-100 dark:hover:bg-gray-800']" @click="toggleTheme" :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
    <SunIcon v-if="isDark" class="h-5 w-5 text-amber-400 transition-transform duration-300 hover:rotate-45" />
    <MoonIcon v-else class="h-5 w-5 text-gray-600 dark:text-gray-400 transition-transform duration-300 hover:-rotate-12" />
  </button>
</template>

<script setup>
import { computed } from 'vue'
import { SunIcon, MoonIcon } from '@heroicons/vue/24/outline'
import { useAppStore } from '@/Stores/app'

const props = defineProps({ variant: { type: String, default: 'glass' } })

const app = useAppStore()
const isDark = computed(() => app.isDark)
const toggleTheme = () => app.toggleTheme()
</script>

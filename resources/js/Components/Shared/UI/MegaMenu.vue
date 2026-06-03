<template>
  <Transition name="mega-menu">
    <div v-if="open" class="absolute top-full left-0 right-0 z-50 mt-0" @mouseenter="$emit('mouseenter')" @mouseleave="$emit('mouseleave')">
      <div class="glass rounded-2xl shadow-2xl p-6 max-h-[70vh] overflow-y-auto">
        <div class="grid grid-cols-4 gap-6">
          <div v-for="column in columns" :key="column.title" class="space-y-3">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">{{ column.title }}</h3>
            <ul class="space-y-1.5">
              <li v-for="link in column.links" :key="link.label">
                <Link :href="link.url" class="text-sm text-gray-600 dark:text-gray-400 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">{{ link.label }}</Link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  open: Boolean,
  columns: { type: Array, required: true },
})

defineEmits(['mouseenter', 'mouseleave'])
</script>

<style scoped>
.mega-menu-enter-active { transition: all 0.2s ease-out; }
.mega-menu-leave-active { transition: all 0.15s ease-in; }
.mega-menu-enter-from, .mega-menu-leave-to { opacity: 0; transform: translateY(-8px); }
</style>

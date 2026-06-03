<template>
  <Dropdown label="" button-class="p-2 rounded-xl glass hover:bg-white/30 dark:hover:bg-white/10 relative">
    <template #button>
      <BellIcon class="h-5 w-5 text-gray-600 dark:text-gray-400" />
      <span v-if="count > 0" class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ count }}</span>
    </template>
    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
      <span class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</span>
      <button v-if="count" class="text-xs text-brand-600 dark:text-brand-400">Mark all read</button>
    </div>
    <div class="max-h-64 overflow-y-auto">
      <button v-for="(notif, i) in notifications" :key="i" :class="['w-full px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors flex items-start gap-3', !notif.read ? 'bg-brand-50/50 dark:bg-brand-500/5' : '']">
        <div :class="['p-1.5 rounded-lg', notif.color]"><component :is="notif.icon" class="h-3 w-3" /></div>
        <div class="flex-1 min-w-0">
          <p class="text-sm text-gray-900 dark:text-white truncate">{{ notif.message }}</p>
          <p class="text-xs text-gray-400">{{ notif.time }}</p>
        </div>
      </button>
    </div>
    <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-800 text-center">
      <Link href="/admin/notifications" class="text-xs text-brand-600 dark:text-brand-400 hover:underline">View all</Link>
    </div>
  </Dropdown>
</template>

<script setup>
import { BellIcon } from '@heroicons/vue/24/outline'
import { Link } from '@inertiajs/vue3'
import Dropdown from '../UI/Dropdown.vue'

defineProps({
  notifications: { type: Array, default: () => [] },
  count: { type: Number, default: 0 },
})
</script>

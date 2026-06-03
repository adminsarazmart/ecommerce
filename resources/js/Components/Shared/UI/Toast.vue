<template>
  <div class="fixed top-4 right-4 z-[100] flex flex-col gap-2 max-w-sm w-full pointer-events-none">
    <TransitionGroup name="toast">
      <div v-for="notification in notifications" :key="notification.id" :class="['pointer-events-auto flex items-start gap-3 p-4 rounded-xl shadow-lg backdrop-blur-xl border transition-all duration-500', toastClass(notification.type)]">
        <component :is="iconMap[notification.type]" class="h-5 w-5 flex-shrink-0 mt-0.5" />
        <div class="flex-1 min-w-0">
          <p v-if="notification.title" class="text-sm font-semibold">{{ notification.title }}</p>
          <p class="text-sm opacity-90">{{ notification.message }}</p>
        </div>
        <button @click="store.remove(notification.id)" class="flex-shrink-0 p-0.5 rounded-lg hover:bg-black/10 dark:hover:bg-white/10 transition-colors">
          <XMarkIcon class="h-4 w-4" />
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useNotificationStore } from '@/Stores/notification'
import { CheckCircleIcon, XCircleIcon, ExclamationTriangleIcon, InformationCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const store = useNotificationStore()
const notifications = computed(() => store.notifications)

const iconMap = { success: CheckCircleIcon, error: XCircleIcon, warning: ExclamationTriangleIcon, info: InformationCircleIcon }

const toastClass = (type) => ({
  success: 'bg-emerald-500/90 text-white border-emerald-400',
  error: 'bg-red-500/90 text-white border-red-400',
  warning: 'bg-amber-500/90 text-white border-amber-400',
  info: 'bg-blue-500/90 text-white border-blue-400',
}[type] || 'bg-gray-800/90 text-white border-gray-700')
</script>

<style scoped>
.toast-enter-active { animation: slideInRight 0.3s ease-out; }
.toast-leave-active { animation: slideInRight 0.3s ease-in reverse; }
@keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>

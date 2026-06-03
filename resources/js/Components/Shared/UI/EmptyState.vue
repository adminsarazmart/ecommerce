<template>
  <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
    <div class="glass w-20 h-20 rounded-full flex items-center justify-center mb-4">
      <component :is="icon" class="h-10 w-10 text-gray-400 dark:text-gray-500" />
    </div>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ title }}</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mb-6">{{ description }}</p>
    <Button v-if="actionText" :variant="actionVariant" @click="$emit('action')">
      {{ actionText }}
    </Button>
  </div>
</template>

<script setup>
import { InboxArrowDownIcon } from '@heroicons/vue/24/outline'
import Button from './Button.vue'

defineProps({
  title: { type: String, default: 'Nothing here yet' },
  description: { type: String, default: 'Get started by adding your first item.' },
  icon: { type: [Object, Function], default: InboxArrowDownIcon },
  actionText: String,
  actionVariant: { type: String, default: 'primary' },
})

defineEmits(['action'])
</script>

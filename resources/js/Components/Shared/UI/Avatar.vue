<template>
  <div :class="avatarClasses">
    <img v-if="src && !error" :src="src" :alt="alt" @error="error = true" class="h-full w-full object-cover" />
    <span v-else class="font-medium text-white">{{ initials }}</span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  src: String,
  alt: String,
  name: String,
  size: { type: String, default: 'md' },
  status: { type: String, default: '' },
})

const error = ref(false)

const sizes = { xs: 'h-6 w-6 text-xs', sm: 'h-8 w-8 text-sm', md: 'h-10 w-10 text-base', lg: 'h-12 w-12 text-lg', xl: 'h-16 w-16 text-xl', '2xl': 'h-20 w-20 text-2xl' }

const initials = computed(() => {
  if (!props.name) return '?'
  return props.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const statusColors = { online: 'bg-green-500', offline: 'bg-gray-400', busy: 'bg-red-500', away: 'bg-yellow-500' }

const avatarClasses = computed(() => [
  'relative inline-flex items-center justify-center rounded-full overflow-hidden bg-gradient-to-br from-brand-500 to-purple-600 flex-shrink-0',
  sizes[props.size] || sizes.md,
])
</script>

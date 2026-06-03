<template>
  <Transition name="announcement">
    <div v-if="visible" class="relative bg-gradient-to-r from-brand-600 via-purple-600 to-brand-600 text-white text-center text-sm py-2.5 px-4 overflow-hidden">
      <div class="flex items-center justify-center gap-2">
        <SparklesIcon class="h-4 w-4 flex-shrink-0" />
        <span class="font-medium">{{ text }}</span>
        <Link v-if="link" :href="link.url" class="underline font-semibold hover:no-underline whitespace-nowrap">{{ link.label }}</Link>
      </div>
      <button @click="visible = false" class="absolute right-3 top-1/2 -translate-y-1/2 p-0.5 rounded hover:bg-white/20 transition-colors">
        <XMarkIcon class="h-4 w-4" />
      </button>
    </div>
  </Transition>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { SparklesIcon, XMarkIcon } from '@heroicons/vue/24/outline'

defineProps({
  text: { type: String, default: 'Free shipping on orders over $100!' },
  link: { type: Object, default: null },
})

const visible = ref(true)
</script>

<style scoped>
.announcement-enter-active { animation: slideDown 0.4s ease-out; }
.announcement-leave-active { animation: slideDown 0.3s ease-in reverse; }
</style>

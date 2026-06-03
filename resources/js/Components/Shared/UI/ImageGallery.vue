<template>
  <div class="flex gap-3">
    <div class="flex flex-col gap-2 flex-shrink-0">
      <button v-for="(img, i) in images" :key="i" :class="['w-16 h-16 rounded-xl overflow-hidden border-2 transition-all duration-200', activeIndex === i ? 'border-brand-500 ring-2 ring-brand-500/30' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600']" @click="activeIndex = i">
        <img :src="img.thumbnail || img.url" :alt="img.alt || ''" class="h-full w-full object-cover" />
      </button>
    </div>
    <div class="flex-1 relative overflow-hidden rounded-2xl glass" @mousemove="handleMouseMove" @mouseleave="showZoom = false">
      <img :src="images[activeIndex]?.url" :alt="images[activeIndex]?.alt || ''" class="w-full h-full object-cover transition-transform duration-500" :class="{ 'cursor-crosshair': zoomable }" :style="zoomStyle" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  images: { type: Array, default: () => [] },
  zoomable: { type: Boolean, default: true },
})

const activeIndex = ref(0)
const showZoom = ref(false)
const zoomPos = ref({ x: 50, y: 50 })

const zoomStyle = computed(() => {
  if (!props.zoomable || !showZoom.value) return {}
  return { transform: 'scale(2)', transformOrigin: `${zoomPos.value.x}% ${zoomPos.value.y}%` }
})

const handleMouseMove = (e) => {
  if (!props.zoomable) return
  const rect = e.currentTarget.getBoundingClientRect()
  zoomPos.value = { x: ((e.clientX - rect.left) / rect.width) * 100, y: ((e.clientY - rect.top) / rect.height) * 100 }
  showZoom.value = true
}
</script>

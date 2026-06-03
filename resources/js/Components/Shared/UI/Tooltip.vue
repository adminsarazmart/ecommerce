<template>
  <div class="relative inline-flex" @mouseenter="show = true" @mouseleave="show = false">
    <slot />
    <Transition name="tooltip">
      <div v-if="show" :class="['absolute z-50 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 dark:bg-gray-700 rounded-lg shadow-lg whitespace-nowrap pointer-events-none', positionClass]">
        {{ text }}
        <div :class="['absolute w-2 h-2 bg-gray-900 dark:bg-gray-700 rotate-45', arrowClass]"></div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({ text: String, position: { type: String, default: 'top' } })
const show = ref(false)

const positions = {
  top: 'bottom-full left-1/2 -translate-x-1/2 mb-2',
  bottom: 'top-full left-1/2 -translate-x-1/2 mt-2',
  left: 'right-full top-1/2 -translate-y-1/2 mr-2',
  right: 'left-full top-1/2 -translate-y-1/2 ml-2',
}

const arrows = {
  top: 'top-full left-1/2 -translate-x-1/2 -mt-1',
  bottom: 'bottom-full left-1/2 -translate-x-1/2 -mb-1',
  left: 'left-full top-1/2 -translate-y-1/2 -ml-1',
  right: 'right-full top-1/2 -translate-y-1/2 -mr-1',
}

const positionClass = computed(() => positions[props.position])
const arrowClass = computed(() => arrows[props.position])
</script>

<style scoped>
.tooltip-enter-active { transition: all 0.2s ease-out; }
.tooltip-leave-active { transition: all 0.15s ease-in; }
.tooltip-enter-from, .tooltip-leave-to { opacity: 0; transform: translateY(4px); }
</style>

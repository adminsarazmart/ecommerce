<template>
  <div class="relative">
    <button class="glass-input w-full flex items-center gap-3" @click="open = !open">
      <component :is="selectedIcon" class="h-5 w-5 text-gray-600 dark:text-gray-400" />
      <span class="text-sm">{{ selectedLabel }}</span>
    </button>
    <Transition name="fade">
      <div v-if="open" class="absolute z-10 mt-1 glass rounded-xl p-3 w-72 max-h-60 overflow-y-auto grid grid-cols-6 gap-1">
        <button v-for="icon in icons" :key="icon.name" :class="['p-2 rounded-lg hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors', modelValue === icon.name ? 'bg-brand-100 dark:bg-brand-500/20 ring-2 ring-brand-500' : '']" @click="select(icon.name)">
          <component :is="icon.component" class="h-5 w-5 text-gray-600 dark:text-gray-400" />
        </button>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import * as HI from '@heroicons/vue/24/outline'

const props = defineProps({ modelValue: String })
const emit = defineEmits(['update:modelValue'])
const open = ref(false)

const icons = Object.entries(HI).filter(([name]) => name.endsWith('Icon')).map(([name, component]) => ({ name, component }))

const selectedIcon = computed(() => icons.find(i => i.name === props.modelValue)?.component || icons[0]?.component)
const selectedLabel = computed(() => props.modelValue || 'Select icon')

const select = (name) => { emit('update:modelValue', name); open.value = false }
</script>

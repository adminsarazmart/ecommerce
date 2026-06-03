<template>
  <div class="space-y-3">
    <div v-for="group in groups" :key="group.name" class="variant-group">
      <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ group.label }}: <span class="text-brand-600 dark:text-brand-400">{{ selectedLabel(group) }}</span>
      </p>
      <div class="flex flex-wrap gap-2">
        <button v-for="option in group.options" :key="option.value" @click="select(group.name, option)" :class="['relative px-4 py-2 text-sm rounded-xl border-2 transition-all duration-200', isSelected(group.name, option) ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-300' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-400']">
          <span v-if="option.color" class="inline-block w-4 h-4 rounded-full mr-2 align-middle" :style="{ backgroundColor: option.color }" />
          {{ option.label }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: Object,
  groups: { type: Array, required: true },
})

const emit = defineEmits(['update:modelValue'])

const selectedLabel = (group) => {
  const sel = props.modelValue?.[group.name]
  const opt = group.options.find(o => o.value === sel)
  return opt ? (opt.color ? '' : opt.label) : 'Select'
}

const isSelected = (groupName, option) => props.modelValue?.[groupName] === option.value

const select = (groupName, option) => {
  emit('update:modelValue', { ...props.modelValue, [groupName]: option.value })
}
</script>

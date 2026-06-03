<template>
  <div>
    <div class="border-b border-gray-200 dark:border-gray-800">
      <nav class="flex gap-1 -mb-px overflow-x-auto" role="tablist">
        <button v-for="tab in tabs" :key="tab.value" :class="['relative px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors duration-200', modelValue === tab.value ? 'text-brand-600 dark:text-brand-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300']" @click="$emit('update:modelValue', tab.value)">
          {{ tab.label }}
          <span v-if="modelValue === tab.value" class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand-500 rounded-full" />
        </button>
      </nav>
    </div>
    <div class="mt-4"><slot /></div>
  </div>
</template>

<script setup>
defineProps({
  modelValue: [String, Number],
  tabs: { type: Array, required: true },
})
defineEmits(['update:modelValue'])
</script>

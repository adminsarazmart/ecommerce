<template>
  <div class="flex items-center gap-3">
    <div class="relative">
      <input :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" type="color" class="absolute inset-0 opacity-0 cursor-pointer h-10 w-10" />
      <div class="h-10 w-10 rounded-xl border-2 border-gray-200 dark:border-gray-700 shadow-sm cursor-pointer" :style="{ backgroundColor: modelValue }" />
    </div>
    <input :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" type="text" class="glass-input w-32 text-sm font-mono" placeholder="#000000" />
  </div>
</template>

<script setup>
defineProps({ modelValue: { type: String, default: '#000000' } })
defineEmits(['update:modelValue'])
</script>

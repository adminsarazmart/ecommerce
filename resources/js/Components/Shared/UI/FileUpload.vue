<template>
  <div>
    <div @drop.prevent="handleDrop" @dragover.prevent="dragover = true" @dragleave="dragover = false" :class="['relative border-2 border-dashed rounded-2xl p-8 text-center transition-all duration-300 cursor-pointer', dragover ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'border-gray-300 dark:border-gray-600 hover:border-brand-400 dark:hover:border-brand-500']" @click="inputRef?.click()">
      <input ref="inputRef" type="file" :multiple="multiple" :accept="accept" class="hidden" @change="handleFiles" />
      <div v-if="!modelValue || !modelValue.length">
        <CloudArrowUpIcon class="mx-auto h-12 w-12 text-gray-400 mb-4" />
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Drop files here or click to upload</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ accept ? `Accepted: ${accept}` : 'All files supported' }}</p>
      </div>
      <div v-else class="flex flex-wrap gap-2">
        <div v-for="(file, i) in files" :key="i" class="relative glass rounded-xl p-3 flex items-center gap-2">
          <DocumentIcon class="h-8 w-8 text-gray-400" />
          <div class="text-left">
            <p class="text-sm font-medium truncate max-w-[150px]">{{ file.name }}</p>
            <p class="text-xs text-gray-400">{{ (file.size / 1024).toFixed(1) }} KB</p>
          </div>
          <button @click.stop="removeFile(i)" class="p-1 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg text-red-500">
            <XMarkIcon class="h-4 w-4" />
          </button>
        </div>
      </div>
    </div>
    <p v-if="error" class="mt-2 text-xs text-red-500">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { CloudArrowUpIcon, DocumentIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  modelValue: Array,
  multiple: Boolean,
  accept: String,
  error: String,
})

const emit = defineEmits(['update:modelValue'])
const dragover = ref(false)
const inputRef = ref(null)

const files = computed(() => props.modelValue || [])

const handleFiles = (e) => {
  const selected = Array.from(e.target.files || e.dataTransfer?.files || [])
  if (selected.length) {
    if (props.multiple) emit('update:modelValue', [...files.value, ...selected])
    else emit('update:modelValue', [selected[0]])
  }
  dragover.value = false
}

const handleDrop = (e) => handleFiles(e)

const removeFile = (index) => {
  const updated = [...files.value]
  updated.splice(index, 1)
  emit('update:modelValue', updated)
}
</script>

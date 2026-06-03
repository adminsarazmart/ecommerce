<template>
  <TransitionRoot appear :show="modelValue" as="template">
    <Dialog as="div" class="relative z-50" @close="close">
      <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
            <DialogPanel :class="['glass-card w-full transform overflow-hidden text-left align-middle shadow-xl transition-all', maxWidthClass]">
              <div v-if="showClose" class="absolute top-4 right-4">
                <button @click="close" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                  <XMarkIcon class="h-5 w-5 text-gray-500" />
                </button>
              </div>
              <div v-if="title" class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <DialogTitle as="h3" class="text-lg font-semibold text-gray-900 dark:text-white">{{ title }}</DialogTitle>
                <p v-if="description" class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ description }}</p>
              </div>
              <div class="px-6 py-5"><slot /></div>
              <div v-if="$slots.footer" class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 rounded-b-2xl">
                <slot name="footer" />
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { computed } from 'vue'
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  modelValue: Boolean,
  title: String,
  description: String,
  size: { type: String, default: 'md' },
  showClose: { type: Boolean, default: true },
})

const emit = defineEmits(['update:modelValue'])

const sizes = { sm: 'max-w-md', md: 'max-w-lg', lg: 'max-w-2xl', xl: 'max-w-4xl', full: 'max-w-6xl' }
const maxWidthClass = computed(() => sizes[props.size] || sizes.md)

const close = () => emit('update:modelValue', false)
</script>

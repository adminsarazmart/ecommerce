<template>
  <TransitionRoot appear :show="open" as="template">
    <Dialog as="div" class="relative z-50" @close="$emit('close')">
      <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-hidden">
        <div class="flex min-h-full items-start justify-end">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="translate-x-full" enter-to="translate-x-0" leave="duration-200 ease-in" leave-from="translate-x-0" leave-to="translate-x-full">
            <DialogPanel class="relative w-full max-w-sm bg-white dark:bg-gray-900 h-full overflow-y-auto shadow-2xl">
              <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <span class="text-lg font-heading font-bold gradient-text">Menu</span>
                <button @click="$emit('close')" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                  <XMarkIcon class="h-5 w-5" />
                </button>
              </div>
              <div class="p-4 space-y-1">
                <slot />
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { TransitionRoot, TransitionChild, Dialog, DialogPanel } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

defineProps({ open: Boolean })
defineEmits(['close'])
</script>

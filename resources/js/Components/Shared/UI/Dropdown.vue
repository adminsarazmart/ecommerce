<template>
  <Menu as="div" class="relative inline-block text-left">
    <div>
      <MenuButton v-slot="{ open }" :class="buttonClass" :disabled="disabled">
        <slot name="button">
          <span>{{ label }}</span>
          <ChevronDownIcon class="h-4 w-4 ml-1" :class="{ 'rotate-180': open }" />
        </slot>
      </MenuButton>
    </div>
    <transition enter="transition ease-out duration-200" enter-from="opacity-0 translate-y-1" enter-to="opacity-100 translate-y-0" leave="transition ease-in duration-150" leave-from="opacity-100 translate-y-0" leave-to="opacity-0 translate-y-1">
      <MenuItems class="absolute right-0 z-40 mt-2 min-w-[200px] origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black/5 focus:outline-none py-1">
        <slot />
      </MenuItems>
    </transition>
  </Menu>
</template>

<script setup>
import { Menu, MenuButton, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

defineProps({
  label: String,
  buttonClass: { type: String, default: 'inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors' },
  disabled: Boolean,
})
</script>

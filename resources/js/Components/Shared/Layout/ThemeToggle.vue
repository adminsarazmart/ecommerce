<template>
  <button class="p-2 rounded-xl hover:bg-gray-100 transition-all duration-200" @click="$emit('click')">
    <BellIcon class="h-5 w-5 text-gray-600" />
  </button>
</template>

<script setup>
import { BellIcon } from '@heroicons/vue/24/outline'
defineEmits(['click'])
</script>

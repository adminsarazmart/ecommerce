<template>
  <div class="flex items-center justify-end gap-3 mt-6">
    <Button v-if="cancelText" variant="ghost" @click="$emit('cancel')">{{ cancelText }}</Button>
    <Button :variant="submitVariant" :loading="loading" @click="$emit('submit')">
      <slot>{{ submitText }}</slot>
    </Button>
  </div>
</template>

<script setup>
import Button from '../UI/Button.vue'

defineProps({
  submitText: { type: String, default: 'Save' },
  cancelText: { type: String, default: 'Cancel' },
  submitVariant: { type: String, default: 'primary' },
  loading: Boolean,
})

defineEmits(['submit', 'cancel'])
</script>

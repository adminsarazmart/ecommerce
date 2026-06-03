<template>
  <Modal :modelValue="modelValue" :title="title" size="sm" :showClose="false" @update:modelValue="$emit('update:modelValue', $event)">
    <p class="text-sm text-gray-600 dark:text-gray-400">{{ message }}</p>
    <template #footer>
      <div class="flex justify-end gap-3">
        <Button variant="ghost" @click="$emit('update:modelValue', false)">{{ cancelText }}</Button>
        <Button :variant="confirmVariant" @click="$emit('confirm')">{{ confirmText }}</Button>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import Modal from './Modal.vue'
import Button from './Button.vue'

defineProps({
  modelValue: Boolean,
  title: { type: String, default: 'Confirm Action' },
  message: { type: String, default: 'Are you sure you want to proceed?' },
  confirmText: { type: String, default: 'Confirm' },
  cancelText: { type: String, default: 'Cancel' },
  confirmVariant: { type: String, default: 'primary' },
})

defineEmits(['update:modelValue', 'confirm'])
</script>

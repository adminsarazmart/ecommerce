<template>
  <CreateProduct :product="product" />
</template>

<script setup>
const props = defineProps({ product: { type: Object, required: true } })
</script>

<template>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <StatCard v-for="stat in stats" :key="stat.label" v-bind="stat" />
  </div>
</template>

<script setup>
import StatCard from '../UI/StatCard.vue'

defineProps({
  stats: { type: Array, required: true },
})
</script>

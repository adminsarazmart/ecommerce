<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Warehouses</h1>
          <p class="text-sm text-gray-500 mt-1">Manage your warehouse locations</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />Add Warehouse</Button>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="wh in warehouses" :key="wh.id" class="glass-card p-5">
        <div class="flex items-start justify-between">
          <div>
            <h3 class="font-semibold text-gray-900 dark:text-white">{{ wh.name }}</h3>
            <p class="text-sm text-gray-500">{{ wh.location }}</p>
          </div>
          <Badge :variant="wh.status === 'active' ? 'success' : 'neutral'">{{ wh.status }}</Badge>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
          <div><span class="text-gray-500">Capacity:</span> {{ wh.capacity }}%</div>
          <div><span class="text-gray-500">Products:</span> {{ wh.products }}</div>
        </div>
        <div class="mt-3"><Progress :modelValue="wh.capacity" :color="wh.capacity > 80 ? 'warning' : 'brand'" :height="6" /></div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { PlusIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'
import Progress from '@/Components/Shared/UI/Progress.vue'

const warehouses = ref([
  { id: 1, name: 'Main Warehouse', location: 'New York, NY', capacity: 65, products: 1250, status: 'active' },
  { id: 2, name: 'East Distribution', location: 'Atlanta, GA', capacity: 82, products: 890, status: 'active' },
  { id: 3, name: 'West Fulfillment', location: 'Los Angeles, CA', capacity: 45, products: 620, status: 'active' },
])
</script>

<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Warehouse Transfers</h1>
          <p class="text-sm text-gray-500 mt-1">Manage stock transfers between warehouses</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />New Transfer</Button>
      </div>
    </template>

    <DataTable :columns="columns" :data="transfers" searchable>
      <template #cell-status="{ row }">
        <Badge :variant="row.status === 'completed' ? 'success' : row.status === 'pending' ? 'warning' : 'info'">{{ row.status }}</Badge>
      </template>
    </DataTable>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { PlusIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const columns = [
  { key: 'id', label: 'ID', sortable: true },
  { key: 'from', label: 'From' },
  { key: 'to', label: 'To' },
  { key: 'products', label: 'Products' },
  { key: 'qty', label: 'Quantity' },
  { key: 'status', label: 'Status', sortable: true },
  { key: 'date', label: 'Date', sortable: true },
]

const transfers = ref([
  { id: 'TR-001', from: 'Main Warehouse', to: 'East Distribution', products: 3, qty: 150, status: 'completed', date: '2024-03-15' },
  { id: 'TR-002', from: 'East Distribution', to: 'West Fulfillment', products: 1, qty: 50, status: 'in_transit', date: '2024-03-14' },
  { id: 'TR-003', from: 'Main Warehouse', to: 'West Fulfillment', products: 5, qty: 200, status: 'pending', date: '2024-03-13' },
])
</script>

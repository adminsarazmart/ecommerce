<template>
  <VendorLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Orders</h1>
        <p class="text-sm text-gray-500 mt-1">View and manage your orders</p>
      </div>
    </template>

    <DataTable :columns="columns" :data="orders" searchable>
      <template #cell-total="{ row }">{{ formatCurrency(row.total) }}</template>
      <template #cell-status="{ row }"><Badge :variant="row.status === 'completed' ? 'success' : row.status === 'processing' ? 'warning' : 'info'">{{ row.status }}</Badge></template>
    </DataTable>
  </VendorLayout>
</template>

<script setup>
import { ref } from 'vue'
import VendorLayout from '@/Layouts/VendorLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const columns = [
  { key: 'id', label: 'Order', sortable: true },
  { key: 'customer', label: 'Customer' },
  { key: 'items', label: 'Items' },
  { key: 'total', label: 'Total', sortable: true },
  { key: 'status', label: 'Status' },
  { key: 'date', label: 'Date', sortable: true },
]

const orders = ref([
  { id: '#2847', customer: 'John Smith', items: 3, total: 245.99, status: 'completed', date: '2024-03-15' },
  { id: '#2846', customer: 'Sarah Johnson', items: 1, total: 189.00, status: 'processing', date: '2024-03-15' },
  { id: '#2845', customer: 'Mike Chen', items: 2, total: 520.50, status: 'pending', date: '2024-03-14' },
])
</script>

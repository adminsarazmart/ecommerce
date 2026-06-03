<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">POS Sessions</h1>
          <p class="text-sm text-gray-500 mt-1">Manage point of sale sessions</p>
        </div>
      </div>
    </template>

    <DataTable :columns="columns" :data="sessions" searchable>
      <template #cell-status="{ row }"><Badge :variant="row.status === 'open' ? 'success' : 'neutral'">{{ row.status }}</Badge></template>
      <template #cell-opened="{ row }">{{ row.opened }}</template>
      <template #cell-closed="{ row }">{{ row.closed || '—' }}</template>
      <template #cell-total="{ row }">{{ formatCurrency(row.total) }}</template>
    </DataTable>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const columns = [
  { key: 'id', label: 'Session', sortable: true },
  { key: 'register', label: 'Register' },
  { key: 'opened', label: 'Opened', sortable: true },
  { key: 'closed', label: 'Closed' },
  { key: 'total', label: 'Total', sortable: true },
  { key: 'status', label: 'Status' },
]

const sessions = ref([
  { id: 1, register: 'Main Register', opened: '2024-03-15 09:00', closed: '2024-03-15 17:30', total: 2845.50, status: 'closed' },
  { id: 2, register: 'Main Register', opened: '2024-03-16 09:00', closed: null, total: 1250.00, status: 'open' },
])
</script>

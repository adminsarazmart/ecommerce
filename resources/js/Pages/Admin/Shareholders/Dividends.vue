<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Dividend Distribution</h1>
          <p class="text-sm text-gray-500 mt-1">Manage dividend payouts</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />Distribute Dividends</Button>
      </div>
    </template>

    <DataTable :columns="columns" :data="dividends" searchable>
      <template #cell-amount="{ row }">{{ formatCurrency(row.amount) }}</template>
      <template #cell-total="{ row }">{{ formatCurrency(row.total) }}</template>
      <template #cell-status="{ row }"><Badge :variant="row.status === 'paid' ? 'success' : 'warning'">{{ row.status }}</Badge></template>
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

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const columns = [
  { key: 'date', label: 'Date', sortable: true },
  { key: 'shareholder', label: 'Shareholder' },
  { key: 'shares', label: 'Shares' },
  { key: 'amount', label: 'Per Share', sortable: true },
  { key: 'total', label: 'Total', sortable: true },
  { key: 'status', label: 'Status' },
]

const dividends = ref([
  { id: 1, date: '2024-03-15', shareholder: 'John Doe', shares: 250000, amount: 0.50, total: 125000, status: 'paid' },
  { id: 2, date: '2024-03-15', shareholder: 'Jane Smith', shares: 150000, amount: 0.50, total: 75000, status: 'paid' },
  { id: 3, date: '2024-03-15', shareholder: 'Acme Ventures', shares: 200000, amount: 0.50, total: 100000, status: 'pending' },
])
</script>

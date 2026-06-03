<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Shareholder Ledger</h1>
        <p class="text-sm text-gray-500 mt-1">Transaction history for all shareholders</p>
      </div>
    </template>

    <DataTable :columns="columns" :data="ledger" searchable>
      <template #cell-amount="{ row }">{{ formatCurrency(row.amount) }}</template>
      <template #cell-type="{ row }"><Badge :variant="row.type === 'credit' ? 'success' : 'danger'">{{ row.type }}</Badge></template>
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
  { key: 'date', label: 'Date', sortable: true },
  { key: 'shareholder', label: 'Shareholder' },
  { key: 'description', label: 'Description' },
  { key: 'type', label: 'Type' },
  { key: 'shares', label: 'Shares' },
  { key: 'amount', label: 'Amount', sortable: true },
]

const ledger = ref([
  { id: 1, date: '2024-03-15', shareholder: 'John Doe', description: 'Share Purchase', type: 'credit', shares: 10000, amount: 500000 },
  { id: 2, date: '2024-03-10', shareholder: 'Jane Smith', description: 'Dividend Payment', type: 'debit', shares: 0, amount: 75000 },
])
</script>

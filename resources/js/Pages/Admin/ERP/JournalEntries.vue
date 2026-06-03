<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Journal Entries</h1>
          <p class="text-sm text-gray-500 mt-1">Record and view accounting journal entries</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />New Entry</Button>
      </div>
    </template>

    <DataTable :columns="columns" :data="entries" searchable>
      <template #cell-debit="{ row }">{{ formatCurrency(row.debit) }}</template>
      <template #cell-credit="{ row }">{{ formatCurrency(row.credit) }}</template>
      <template #cell-status="{ row }"><Badge :variant="row.status === 'posted' ? 'success' : 'warning'">{{ row.status }}</Badge></template>
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
  { key: 'description', label: 'Description' },
  { key: 'account', label: 'Account' },
  { key: 'debit', label: 'Debit', sortable: true },
  { key: 'credit', label: 'Credit', sortable: true },
  { key: 'status', label: 'Status' },
]

const entries = ref([
  { id: 1, date: '2024-03-15', description: 'Sales Revenue - March', account: 'Revenue', debit: 0, credit: 125000, status: 'posted' },
  { id: 2, date: '2024-03-15', description: 'Inventory Purchase', account: 'Inventory', debit: 45000, credit: 0, status: 'posted' },
  { id: 3, date: '2024-03-14', description: 'Payroll Expenses', account: 'Salaries', debit: 85000, credit: 0, status: 'draft' },
])
</script>

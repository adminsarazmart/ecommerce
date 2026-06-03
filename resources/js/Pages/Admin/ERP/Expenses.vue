<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Expenses</h1>
          <p class="text-sm text-gray-500 mt-1">Track and manage business expenses</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />Add Expense</Button>
      </div>
    </template>

    <DataTable :columns="columns" :data="expenses" searchable>
      <template #cell-amount="{ row }">{{ formatCurrency(row.amount) }}</template>
      <template #cell-status="{ row }"><Badge :variant="row.status === 'approved' ? 'success' : row.status === 'pending' ? 'warning' : 'neutral'">{{ row.status }}</Badge></template>
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
  { key: 'category', label: 'Category', sortable: true },
  { key: 'description', label: 'Description' },
  { key: 'amount', label: 'Amount', sortable: true },
  { key: 'paid_by', label: 'Paid By' },
  { key: 'status', label: 'Status' },
]

const expenses = ref([
  { id: 1, date: '2024-03-15', category: 'Office Supplies', description: 'Printer paper and ink', amount: 450.00, paid_by: 'Admin', status: 'approved' },
  { id: 2, date: '2024-03-14', category: 'Travel', description: 'Flight tickets - Conference', amount: 1250.00, paid_by: 'John', status: 'pending' },
  { id: 3, date: '2024-03-13', category: 'Utilities', description: 'Electricity bill', amount: 890.00, paid_by: 'Admin', status: 'approved' },
])
</script>

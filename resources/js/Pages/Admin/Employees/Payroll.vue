<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Payroll</h1>
          <p class="text-sm text-gray-500 mt-1">Manage employee payroll</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />Run Payroll</Button>
      </div>
    </template>

    <DataTable :columns="columns" :data="payroll" searchable>
      <template #cell-avatar="{ row }"><Avatar :name="row.name" size="xs" /></template>
      <template #cell-salary="{ row }">{{ formatCurrency(row.salary) }}</template>
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
import Avatar from '@/Components/Shared/UI/Avatar.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const columns = [
  { key: 'avatar', label: '' },
  { key: 'name', label: 'Employee', sortable: true },
  { key: 'department', label: 'Department' },
  { key: 'salary', label: 'Salary', sortable: true },
  { key: 'period', label: 'Period' },
  { key: 'status', label: 'Status' },
]

const payroll = ref([
  { id: 1, name: 'Alice Johnson', department: 'Engineering', salary: 8500, period: 'March 2024', status: 'paid' },
  { id: 2, name: 'Bob Smith', department: 'Marketing', salary: 7200, period: 'March 2024', status: 'paid' },
  { id: 3, name: 'Carol Davis', department: 'HR', salary: 6800, period: 'March 2024', status: 'pending' },
])
</script>

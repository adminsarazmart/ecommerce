<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Attendance</h1>
        <p class="text-sm text-gray-500 mt-1">Track employee attendance</p>
      </div>
    </template>

    <DataTable :columns="columns" :data="attendance" searchable>
      <template #cell-status="{ row }">
        <Badge :variant="row.status === 'present' ? 'success' : row.status === 'late' ? 'warning' : 'danger'">{{ row.status }}</Badge>
      </template>
    </DataTable>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const columns = [
  { key: 'name', label: 'Employee', sortable: true },
  { key: 'date', label: 'Date', sortable: true },
  { key: 'check_in', label: 'Check In' },
  { key: 'check_out', label: 'Check Out' },
  { key: 'status', label: 'Status' },
]

const attendance = ref([
  { id: 1, name: 'Alice Johnson', date: '2024-03-15', check_in: '08:55', check_out: '17:05', status: 'present' },
  { id: 2, name: 'Bob Smith', date: '2024-03-15', check_in: '09:15', check_out: '17:30', status: 'late' },
  { id: 3, name: 'Carol Davis', date: '2024-03-15', check_in: '—', check_out: '—', status: 'absent' },
])
</script>

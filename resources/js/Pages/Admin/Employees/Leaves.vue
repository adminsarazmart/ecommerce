<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Leave Management</h1>
          <p class="text-sm text-gray-500 mt-1">Manage employee leave requests</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />New Leave Request</Button>
      </div>
    </template>

    <DataTable :columns="columns" :data="leaves" searchable>
      <template #cell-status="{ row }">
        <Badge :variant="row.status === 'approved' ? 'success' : row.status === 'pending' ? 'warning' : 'danger'">{{ row.status }}</Badge>
      </template>
      <template #actions="{ row }">
        <div v-if="row.status === 'pending'" class="flex gap-1">
          <Button size="xs" variant="ghost">Approve</Button>
          <Button size="xs" variant="ghost">Reject</Button>
        </div>
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
  { key: 'name', label: 'Employee', sortable: true },
  { key: 'type', label: 'Leave Type' },
  { key: 'from', label: 'From', sortable: true },
  { key: 'to', label: 'To' },
  { key: 'days', label: 'Days' },
  { key: 'status', label: 'Status' },
]

const leaves = ref([
  { id: 1, name: 'Alice Johnson', type: 'Vacation', from: '2024-04-01', to: '2024-04-05', days: 5, status: 'approved' },
  { id: 2, name: 'Bob Smith', type: 'Sick Leave', from: '2024-03-20', to: '2024-03-21', days: 2, status: 'pending' },
  { id: 3, name: 'Carol Davis', type: 'Personal', from: '2024-03-25', to: '2024-03-25', days: 1, status: 'pending' },
])
</script>

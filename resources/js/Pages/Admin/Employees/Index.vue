<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Employees</h1>
          <p class="text-sm text-gray-500 mt-1">Manage your workforce</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />Add Employee</Button>
      </div>
    </template>

    <DataTable :columns="columns" :data="employees" searchable>
      <template #cell-avatar="{ row }"><Avatar :name="row.name" size="sm" :src="row.avatar" /></template>
      <template #cell-status="{ row }"><Badge :variant="row.status === 'active' ? 'success' : 'neutral'">{{ row.status }}</Badge></template>
      <template #actions="{ row }">
        <Link :href="route('admin.employees.show', row.id)" class="btn-ghost btn-sm">View</Link>
      </template>
    </DataTable>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { PlusIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'
import Avatar from '@/Components/Shared/UI/Avatar.vue'

const columns = [
  { key: 'avatar', label: '' },
  { key: 'name', label: 'Name', sortable: true },
  { key: 'email', label: 'Email' },
  { key: 'department', label: 'Department', sortable: true },
  { key: 'role', label: 'Role' },
  { key: 'status', label: 'Status' },
]

const employees = ref([
  { id: 1, name: 'Alice Johnson', email: 'alice@company.com', avatar: '', department: 'Engineering', role: 'Senior Developer', status: 'active' },
  { id: 2, name: 'Bob Smith', email: 'bob@company.com', avatar: '', department: 'Marketing', role: 'Marketing Lead', status: 'active' },
  { id: 3, name: 'Carol Davis', email: 'carol@company.com', avatar: '', department: 'HR', role: 'HR Manager', status: 'active' },
  { id: 4, name: 'David Wilson', email: 'david@company.com', avatar: '', department: 'Sales', role: 'Sales Rep', status: 'inactive' },
])
</script>

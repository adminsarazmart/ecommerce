<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Suppliers</h1>
          <p class="text-sm text-gray-500 mt-1">Manage your suppliers</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />Add Supplier</Button>
      </div>
    </template>

    <DataTable :columns="columns" :data="suppliers" searchable>
      <template #cell-status="{ row }"><Badge :variant="row.status === 'active' ? 'success' : 'neutral'">{{ row.status }}</Badge></template>
      <template #actions="{ row }">
        <button class="btn-ghost btn-sm">Edit</button>
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
  { key: 'name', label: 'Supplier', sortable: true },
  { key: 'contact', label: 'Contact' },
  { key: 'email', label: 'Email' },
  { key: 'phone', label: 'Phone' },
  { key: 'products', label: 'Products' },
  { key: 'status', label: 'Status' },
]

const suppliers = ref([
  { id: 1, name: 'TechSupply Co.', contact: 'John Smith', email: 'john@techsupply.com', phone: '+1 555-0100', products: 45, status: 'active' },
  { id: 2, name: 'Global Parts Inc.', contact: 'Sarah Lee', email: 'sarah@globalparts.com', phone: '+1 555-0200', products: 28, status: 'active' },
])
</script>

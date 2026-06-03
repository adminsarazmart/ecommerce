<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Purchase Orders</h1>
          <p class="text-sm text-gray-500 mt-1">Manage purchase orders to suppliers</p>
        </div>
        <Link :href="route('admin.inventory.purchase-orders.create')">
          <Button size="sm"><PlusIcon class="h-4 w-4" />New PO</Button>
        </Link>
      </div>
    </template>

    <DataTable :columns="columns" :data="pos" searchable>
      <template #cell-status="{ row }"><Badge :variant="row.status === 'received' ? 'success' : row.status === 'pending' ? 'warning' : 'info'">{{ row.status }}</Badge></template>
      <template #cell-total="{ row }">{{ formatCurrency(row.total) }}</template>
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

const columns = [
  { key: 'po', label: 'PO #', sortable: true },
  { key: 'supplier', label: 'Supplier', sortable: true },
  { key: 'items', label: 'Items' },
  { key: 'total', label: 'Total', sortable: true },
  { key: 'status', label: 'Status' },
  { key: 'date', label: 'Date', sortable: true },
]

const pos = ref([
  { id: 1, po: 'PO-2024-001', supplier: 'TechSupply Co.', items: 5, total: 12500.00, status: 'received', date: '2024-03-10' },
  { id: 2, po: 'PO-2024-002', supplier: 'Global Parts Inc.', items: 3, total: 8900.00, status: 'pending', date: '2024-03-12' },
  { id: 3, po: 'PO-2024-003', supplier: 'Direct Source Ltd.', items: 8, total: 22300.00, status: 'ordered', date: '2024-03-14' },
])

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)
</script>

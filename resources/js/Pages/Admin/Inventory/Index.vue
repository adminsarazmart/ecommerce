<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Inventory Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor stock levels across all warehouses</p>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <StatCard label="Total Products" :value="stats.totalProducts" icon="CubeIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
      <StatCard label="In Stock" :value="stats.inStock" icon="CheckCircleIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
      <StatCard label="Low Stock" :value="stats.lowStock" :change="-5" icon="ExclamationTriangleIcon" iconBg="bg-amber-50" iconColor="text-amber-600" />
      <StatCard label="Out of Stock" :value="stats.outOfStock" :change="2" icon="XCircleIcon" iconBg="bg-red-50" iconColor="text-red-600" />
    </div>

    <DataTable :columns="columns" :data="inventory" searchable>
      <template #cell-image="{ row }"><img :src="row.image || '/placeholder.jpg'" class="w-10 h-10 rounded object-cover" /></template>
      <template #cell-stock="{ row }">
        <div class="flex items-center gap-2">
          <div :class="['w-2 h-2 rounded-full', row.stock > 10 ? 'bg-green-500' : row.stock > 0 ? 'bg-amber-500' : 'bg-red-500']" />
          <span class="text-sm font-medium">{{ row.stock }}</span>
        </div>
      </template>
      <template #cell-warehouse="{ row }">
        <Badge variant="neutral">{{ row.warehouse }}</Badge>
      </template>
    </DataTable>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const stats = { totalProducts: 3450, inStock: 2800, lowStock: 450, outOfStock: 200 }

const columns = [
  { key: 'image', label: '' },
  { key: 'name', label: 'Product', sortable: true },
  { key: 'sku', label: 'SKU' },
  { key: 'stock', label: 'Stock', sortable: true },
  { key: 'warehouse', label: 'Warehouse' },
]

const inventory = ref([
  { id: 1, name: 'Wireless Headphones', sku: 'WH-1000', stock: 45, warehouse: 'Main Warehouse', image: '' },
  { id: 2, name: 'USB Cable', sku: 'USB-6FT', stock: 3, warehouse: 'East Warehouse', image: '' },
  { id: 3, name: 'Phone Case', sku: 'PC-IP15', stock: 0, warehouse: 'Main Warehouse', image: '' },
])
</script>

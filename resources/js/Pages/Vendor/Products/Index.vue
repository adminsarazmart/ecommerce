<template>
  <VendorLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">My Products</h1>
          <p class="text-sm text-gray-500 mt-1">Manage your product catalog</p>
        </div>
        <Link :href="route('vendor.products.create')">
          <Button size="sm"><PlusIcon class="h-4 w-4" />Add Product</Button>
        </Link>
      </div>
    </template>

    <DataTable :columns="columns" :data="products" searchable>
      <template #cell-image="{ row }"><img :src="row.image || '/placeholder.jpg'" class="w-10 h-10 rounded object-cover" /></template>
      <template #cell-price="{ row }">{{ formatCurrency(row.price) }}</template>
      <template #cell-status="{ row }"><Switch :modelValue="row.status === 'active'" @update:modelValue="toggleStatus(row)" /></template>
    </DataTable>
  </VendorLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { PlusIcon } from '@heroicons/vue/24/outline'
import VendorLayout from '@/Layouts/VendorLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Switch from '@/Components/Shared/UI/Switch.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const columns = [
  { key: 'image', label: '' },
  { key: 'name', label: 'Product', sortable: true },
  { key: 'sku', label: 'SKU' },
  { key: 'price', label: 'Price', sortable: true },
  { key: 'stock', label: 'Stock', sortable: true },
  { key: 'status', label: 'Status' },
]

const products = ref([
  { id: 1, name: 'Wireless Headphones', sku: 'WH-1000', price: 149.99, stock: 45, status: 'active', image: '' },
  { id: 2, name: 'Bluetooth Speaker', sku: 'BS-200', price: 79.99, stock: 12, status: 'active', image: '' },
  { id: 3, name: 'USB-C Cable', sku: 'UC-6FT', price: 14.99, stock: 0, status: 'inactive', image: '' },
])

const toggleStatus = (product) => { product.status = product.status === 'active' ? 'inactive' : 'active' }
</script>

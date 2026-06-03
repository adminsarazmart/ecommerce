<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Products</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your product catalog</p>
        </div>
        <div class="flex items-center gap-2">
          <Button variant="secondary" size="sm">
            <ArrowDownTrayIcon class="h-4 w-4" />Export
          </Button>
          <Link :href="route('admin.products.create')">
            <Button size="sm"><PlusIcon class="h-4 w-4" />Add Product</Button>
          </Link>
        </div>
      </div>
    </template>

    <DataTable
      :columns="columns"
      :data="products"
      searchable
      selectable
      showBulkActions
      @bulk-delete="handleBulkDelete"
    >
      <template #cell-image="{ row }">
        <img :src="row.image || '/placeholder.jpg'" class="w-10 h-10 rounded-lg object-cover" />
      </template>
      <template #cell-name="{ row }">
        <div>
          <p class="text-sm font-medium text-gray-900 dark:text-white">{{ row.name }}</p>
          <p class="text-xs text-gray-500">{{ row.sku }}</p>
        </div>
      </template>
      <template #cell-price="{ row }">
        <Price :value="row.price" :original="row.original_price" size="sm" />
      </template>
      <template #cell-stock="{ row }">
        <div class="flex items-center gap-2">
          <div :class="['w-2 h-2 rounded-full', row.stock > 10 ? 'bg-green-500' : row.stock > 0 ? 'bg-amber-500' : 'bg-red-500']" />
          <span class="text-sm" :class="row.stock > 10 ? 'text-green-600 dark:text-green-400' : row.stock > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400'">{{ row.stock }}</span>
        </div>
      </template>
      <template #cell-status="{ row }">
        <Switch :modelValue="row.status === 'active'" @update:modelValue="toggleStatus(row)" />
      </template>
      <template #cell-category="{ row }">
        <Badge variant="neutral">{{ row.category }}</Badge>
      </template>
      <template #cell-vendor="{ row }">
        <span class="text-sm text-gray-600 dark:text-gray-400">{{ row.vendor }}</span>
      </template>
      <template #actions="{ row }">
        <div class="flex items-center gap-1 justify-end">
          <button @click="editProduct(row)" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <PencilIcon class="h-4 w-4 text-gray-500" />
          </button>
          <button @click="confirmDelete(row)" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
            <TrashIcon class="h-4 w-4 text-red-500" />
          </button>
        </div>
      </template>
    </DataTable>

    <Modal v-model="editModalOpen" title="Quick Edit Product" size="lg" @close="editModalOpen = false">
      <div class="space-y-4">
        <Input v-model="editForm.name" label="Product Name" />
        <Input v-model="editForm.price" label="Price" type="number" />
        <Input v-model="editForm.stock" label="Stock" type="number" />
        <div class="flex justify-end gap-2">
          <Button variant="ghost" @click="editModalOpen = false">Cancel</Button>
          <Button @click="saveEdit">Save Changes</Button>
        </div>
      </div>
    </Modal>

    <Dialog v-model="deleteDialogOpen" title="Delete Product" message="Are you sure you want to delete this product? This action cannot be undone." confirmVariant="danger" @confirm="deleteProduct" />
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { PlusIcon, PencilIcon, TrashIcon, ArrowDownTrayIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'
import Switch from '@/Components/Shared/UI/Switch.vue'
import Price from '@/Components/Shared/UI/Price.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Modal from '@/Components/Shared/UI/Modal.vue'
import Dialog from '@/Components/Shared/UI/Dialog.vue'

const columns = [
  { key: 'image', label: '' },
  { key: 'name', label: 'Product', sortable: true },
  { key: 'price', label: 'Price', sortable: true },
  { key: 'stock', label: 'Stock', sortable: true },
  { key: 'category', label: 'Category', sortable: true },
  { key: 'vendor', label: 'Vendor', sortable: true },
  { key: 'status', label: 'Status' },
]

const products = ref([
  { id: 1, name: 'Wireless Bluetooth Headphones', sku: 'WH-1000', price: 249.99, original_price: 299.99, stock: 45, category: 'Electronics', vendor: 'TechPro', status: 'active', image: '' },
  { id: 2, name: 'Organic Cotton T-Shirt', sku: 'OCT-001', price: 39.99, stock: 120, category: 'Fashion', vendor: 'FashionHub', status: 'active', image: '' },
  { id: 3, name: 'Smart Home Speaker', sku: 'SHS-200', price: 129.99, original_price: 159.99, stock: 8, category: 'Electronics', vendor: 'TechPro', status: 'active', image: '' },
  { id: 4, name: 'Stainless Steel Water Bottle', sku: 'SSB-300', price: 24.99, stock: 0, category: 'Home & Garden', vendor: 'HomeGoods', status: 'inactive', image: '' },
  { id: 5, name: 'Yoga Mat Premium', sku: 'YMP-400', price: 59.99, stock: 32, category: 'Sports', vendor: 'SportsDirect', status: 'active', image: '' },
])

const editModalOpen = ref(false)
const deleteDialogOpen = ref(false)
const editForm = ref({})
const selectedProduct = ref(null)

const toggleStatus = (product) => {
  product.status = product.status === 'active' ? 'inactive' : 'active'
}

const editProduct = (product) => {
  selectedProduct.value = product
  editForm.value = { name: product.name, price: product.price, stock: product.stock }
  editModalOpen.value = true
}

const saveEdit = () => {
  if (selectedProduct.value) {
    Object.assign(selectedProduct.value, editForm.value)
  }
  editModalOpen.value = false
}

const confirmDelete = (product) => {
  selectedProduct.value = product
  deleteDialogOpen.value = true
}

const deleteProduct = () => {
  products.value = products.value.filter(p => p.id !== selectedProduct.value?.id)
  deleteDialogOpen.value = false
}

const handleBulkDelete = (ids) => {
  products.value = products.value.filter(p => !ids.includes(p.id))
}
</script>

<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Vendors</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your marketplace vendors</p>
        </div>
        <Link :href="route('admin.vendors.create')">
          <Button size="sm"><PlusIcon class="h-4 w-4" />Add Vendor</Button>
        </Link>
      </div>
    </template>

    <DataTable :columns="columns" :data="vendors" searchable :sortKey="sortKey" :sortDir="sortDir" @sort="handleSort">
      <template #cell-logo="{ row }">
        <Avatar :src="row.logo" :name="row.name" size="sm" />
      </template>
      <template #cell-status="{ row }">
        <Badge :variant="getStatusVariant(row.status)">{{ row.status }}</Badge>
      </template>
      <template #cell-kyc="{ row }">
        <Badge :variant="row.kyc_verified ? 'success' : 'warning'">{{ row.kyc_verified ? 'Verified' : 'Pending' }}</Badge>
      </template>
      <template #cell-revenue="{ row }">{{ formatCurrency(row.revenue) }}</template>
      <template #actions="{ row }">
        <div class="flex items-center gap-1 justify-end">
          <Link :href="route('admin.vendors.show', row.id)" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
            <EyeIcon class="h-4 w-4 text-gray-500" />
          </Link>
          <button class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
            <PencilIcon class="h-4 w-4 text-gray-500" />
          </button>
        </div>
      </template>
    </DataTable>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { PlusIcon, EyeIcon, PencilIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'
import Avatar from '@/Components/Shared/UI/Avatar.vue'

const sortKey = ref('name')
const sortDir = ref('asc')

const columns = [
  { key: 'logo', label: '' },
  { key: 'name', label: 'Vendor', sortable: true },
  { key: 'email', label: 'Email', sortable: true },
  { key: 'products_count', label: 'Products', sortable: true },
  { key: 'revenue', label: 'Revenue', sortable: true },
  { key: 'status', label: 'Status', sortable: true },
  { key: 'kyc', label: 'KYC' },
]

const vendors = ref([
  { id: 1, name: 'TechPro Electronics', email: 'contact@techpro.com', logo: '', products_count: 245, revenue: 485000, status: 'Active', kyc_verified: true },
  { id: 2, name: 'FashionHub Apparel', email: 'info@fashionhub.com', logo: '', products_count: 189, revenue: 325000, status: 'Active', kyc_verified: true },
  { id: 3, name: 'HomeGoods Decor', email: 'hello@homegoods.com', logo: '', products_count: 156, revenue: 198000, status: 'Active', kyc_verified: false },
  { id: 4, name: 'SportsDirect', email: 'sales@sportsdirect.com', logo: '', products_count: 98, revenue: 125000, status: 'Suspended', kyc_verified: true },
])

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)
const getStatusVariant = (s) => ({ Active: 'success', Suspended: 'danger', Pending: 'warning' }[s] || 'neutral')
const handleSort = ({ key, dir }) => { sortKey.value = key; sortDir.value = dir }
</script>

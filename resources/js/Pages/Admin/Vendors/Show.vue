<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <Breadcrumb :crumbs="[{ label: 'Vendors', url: route('admin.vendors.index') }, { label: vendor.name }]" />
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mt-2">{{ vendor.name }}</h1>
        </div>
        <div class="flex items-center gap-2">
          <Badge :variant="vendor.status === 'Active' ? 'success' : 'danger'" size="md">{{ vendor.status }}</Badge>
          <Button variant="secondary" size="sm"><PencilIcon class="h-4 w-4" />Edit</Button>
        </div>
      </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <div class="lg:col-span-1 space-y-6">
        <div class="glass-card p-5 text-center">
          <Avatar :name="vendor.name" size="2xl" class="mx-auto" />
          <h3 class="mt-3 font-semibold text-gray-900 dark:text-white">{{ vendor.name }}</h3>
          <p class="text-sm text-gray-500">{{ vendor.email }}</p>
          <div class="mt-3 flex justify-center gap-2">
            <Badge :variant="vendor.kyc_verified ? 'success' : 'warning'">{{ vendor.kyc_verified ? 'KYC Verified' : 'KYC Pending' }}</Badge>
          </div>
        </div>
        <div class="glass-card p-5">
          <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Contact Info</h4>
          <div class="space-y-2 text-sm">
            <p><span class="text-gray-500">Phone:</span> {{ vendor.phone }}</p>
            <p><span class="text-gray-500">Address:</span> {{ vendor.address }}</p>
            <p><span class="text-gray-500">Since:</span> {{ vendor.joined_date }}</p>
          </div>
        </div>
      </div>

      <div class="lg:col-span-3 space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <StatCard label="Total Products" :value="vendor.products_count" icon="CubeIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
          <StatCard label="Total Orders" :value="vendor.orders_count" icon="ShoppingCartIcon" iconBg="bg-purple-50" iconColor="text-purple-600" />
          <StatCard label="Revenue" :value="formatCurrency(vendor.revenue)" icon="CurrencyDollarIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
          <StatCard label="Commission" :value="formatCurrency(vendor.commission)" icon="BanknotesIcon" iconBg="bg-amber-50" iconColor="text-amber-600" />
        </div>

        <div class="glass-card p-5">
          <SectionHeader title="Recent Orders" size="md">
            <template #actions><Link href="#" class="text-sm text-brand-600">View All</Link></template>
          </SectionHeader>
          <DataTable :columns="orderColumns" :data="vendor.recentOrders" />
        </div>

        <div class="glass-card p-5">
          <SectionHeader title="Recent Products" size="md" />
          <DataTable :columns="productColumns" :data="vendor.recentProducts" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { PencilIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Avatar from '@/Components/Shared/UI/Avatar.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'

const vendor = {
  id: 1, name: 'TechPro Electronics', email: 'contact@techpro.com',
  phone: '+1 (555) 123-4567', address: '123 Tech St, San Francisco, CA',
  joined_date: 'Jan 2024', status: 'Active', kyc_verified: true,
  products_count: 245, orders_count: 1280, revenue: 485000, commission: 48500,
  recentOrders: [
    { id: 1, order: '#2847', customer: 'John Smith', total: 245.99, status: 'Completed', date: 'Today' },
    { id: 2, order: '#2846', customer: 'Sarah J.', total: 189.00, status: 'Processing', date: 'Yesterday' },
  ],
  recentProducts: [
    { id: 1, name: 'Wireless Headphones', price: 249.99, stock: 45, status: 'Active' },
    { id: 2, name: 'Smart Speaker', price: 129.99, stock: 8, status: 'Active' },
  ],
}

const orderColumns = [
  { key: 'order', label: 'Order', sortable: true },
  { key: 'customer', label: 'Customer' },
  { key: 'total', label: 'Total', sortable: true },
  { key: 'status', label: 'Status' },
  { key: 'date', label: 'Date', sortable: true },
]

const productColumns = [
  { key: 'name', label: 'Product', sortable: true },
  { key: 'price', label: 'Price', sortable: true },
  { key: 'stock', label: 'Stock', sortable: true },
  { key: 'status', label: 'Status' },
]

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)
</script>

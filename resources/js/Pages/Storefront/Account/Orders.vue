<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">My Orders</h1>
      <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="space-y-1">
          <Link v-for="link in navLinks" :key="link.label" :href="link.url" class="sidebar-item sidebar-item-inactive">
            <component :is="link.icon" class="h-5 w-5" />{{ link.label }}
          </Link>
        </aside>
        <div class="lg:col-span-3">
          <DataTable :columns="columns" :data="orders" searchable>
            <template #cell-total="{ row }">{{ formatCurrency(row.total) }}</template>
            <template #cell-status="{ row }"><Badge :variant="row.status.color">{{ row.status.label }}</Badge></template>
            <template #actions="{ row }">
              <Link :href="`/account/orders/${row.id}`" class="btn-ghost btn-sm">View</Link>
            </template>
          </DataTable>
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ShoppingCartIcon, UserIcon, HeartIcon, MapPinIcon, StarIcon, GiftTopIcon, WalletIcon, ArrowDownTrayIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const navLinks = [
  { label: 'Dashboard', url: '/account', icon: UserIcon },
  { label: 'Orders', url: '/account/orders', icon: ShoppingCartIcon },
  { label: 'Profile', url: '/account/profile', icon: UserIcon },
  { label: 'Addresses', url: '/account/addresses', icon: MapPinIcon },
  { label: 'Wishlist', url: '/account/wishlist', icon: HeartIcon },
  { label: 'Reviews', url: '/account/reviews', icon: StarIcon },
  { label: 'Loyalty', url: '/account/loyalty', icon: GiftTopIcon },
  { label: 'Wallet', url: '/account/wallet', icon: WalletIcon },
]

const columns = [
  { key: 'id', label: 'Order', sortable: true },
  { key: 'date', label: 'Date', sortable: true },
  { key: 'items', label: 'Items' },
  { key: 'total', label: 'Total', sortable: true },
  { key: 'status', label: 'Status' },
]

const orders = ref([
  { id: 2847, date: '2024-03-15', items: 3, total: 245.99, status: { label: 'Delivered', color: 'success' } },
  { id: 2846, date: '2024-03-10', items: 1, total: 189.00, status: { label: 'Processing', color: 'warning' } },
  { id: 2845, date: '2024-03-05', items: 2, total: 520.50, status: { label: 'Delivered', color: 'success' } },
])
</script>

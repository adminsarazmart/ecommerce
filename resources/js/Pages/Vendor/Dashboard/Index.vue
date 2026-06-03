<template>
  <VendorLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Vendor Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Welcome back! Here's your store performance.</p>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <StatCard label="Today's Sales" :value="formatCurrency(1250)" :change="12" icon="CurrencyDollarIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
      <StatCard label="Total Orders" :value="48" :change="8" icon="ShoppingCartIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
      <StatCard label="Products" :value="156" icon="CubeIcon" iconBg="bg-purple-50" iconColor="text-purple-600" />
      <StatCard label="Wallet Balance" :value="formatCurrency(12500)" icon="WalletIcon" iconBg="bg-amber-50" iconColor="text-amber-600" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 glass-card p-5">
        <SectionHeader title="Sales Overview" size="md" />
        <div class="h-72"><Chart type="line" :data="salesData" /></div>
      </div>
      <div class="space-y-6">
        <div class="glass-card p-5">
          <SectionHeader title="Recent Orders" size="sm" />
          <div class="mt-3 space-y-2">
            <div v-for="order in recentOrders" :key="order.id" class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
              <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">#{{ order.id }}</p>
                <p class="text-xs text-gray-500">{{ order.customer }}</p>
              </div>
              <Badge :variant="order.status.color">{{ order.status.label }}</Badge>
            </div>
          </div>
        </div>
        <div class="glass-card p-5">
          <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Quick Actions</h4>
          <div class="space-y-2">
            <Link :href="route('vendor.products.create')" class="btn-primary w-full text-center text-sm block">Add Product</Link>
            <Link :href="route('vendor.orders.index')" class="btn-secondary w-full text-center text-sm block">View Orders</Link>
          </div>
        </div>
      </div>
    </div>
  </VendorLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import VendorLayout from '@/Layouts/VendorLayout.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Chart from '@/Components/Shared/UI/Chart.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const salesData = {
  labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
  datasets: [{ label: 'Sales', data: [450, 520, 380, 620, 580, 720, 680], borderColor: '#4c6ef5', tension: 0.4 }],
}

const recentOrders = ref([
  { id: 2847, customer: 'John Smith', status: { label: 'Completed', color: 'success' } },
  { id: 2846, customer: 'Sarah Johnson', status: { label: 'Processing', color: 'warning' } },
  { id: 2845, customer: 'Mike Chen', status: { label: 'Pending', color: 'info' } },
])
</script>

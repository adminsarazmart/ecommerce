<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Reports Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Comprehensive business reports</p>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <Link v-for="report in reports" :key="report.title" :href="report.url" class="glass-card p-6 hover:-translate-y-1 transition-all duration-300 group">
        <div class="p-3 rounded-xl w-fit mb-4" :class="report.color">
          <component :is="report.icon" class="h-6 w-6" />
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-brand-600 transition-colors">{{ report.title }}</h3>
        <p class="text-sm text-gray-500 mt-1">{{ report.description }}</p>
      </Link>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ChartBarIcon, CurrencyDollarIcon, ShoppingCartIcon, UserGroupIcon, CubeIcon, ArrowTrendingUpIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const reports = [
  { title: 'Sales Report', description: 'Comprehensive sales analysis with trends', url: route('admin.reports.sales'), icon: ChartBarIcon, color: 'bg-blue-50 text-blue-600' },
  { title: 'Profit Report', description: 'Revenue, costs, and profit margins', url: route('admin.reports.profit'), icon: CurrencyDollarIcon, color: 'bg-emerald-50 text-emerald-600' },
  { title: 'Order Report', description: 'Order volume, fulfillment, and status', url: '#', icon: ShoppingCartIcon, color: 'bg-purple-50 text-purple-600' },
  { title: 'Customer Report', description: 'Customer acquisition and retention', url: '#', icon: UserGroupIcon, color: 'bg-amber-50 text-amber-600' },
  { title: 'Inventory Report', description: 'Stock levels and product performance', url: '#', icon: CubeIcon, color: 'bg-rose-50 text-rose-600' },
  { title: 'Analytics', description: 'Advanced analytics and insights', url: route('admin.analytics'), icon: ArrowTrendingUpIcon, color: 'bg-cyan-50 text-cyan-600' },
]
</script>

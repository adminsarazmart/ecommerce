<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Analytics</h1>
        <p class="text-sm text-gray-500 mt-1">Enterprise analytics dashboard</p>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <StatCard label="Page Views" :value="formattedNumber(analytics.pageViews)" :change="15.2" icon="EyeIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
      <StatCard label="Unique Visitors" :value="formattedNumber(analytics.visitors)" :change="12.8" icon="UserGroupIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
      <StatCard label="Bounce Rate" :value="analytics.bounceRate + '%'" :change="-3.5" icon="ArrowTrendingDownIcon" iconBg="bg-amber-50" iconColor="text-amber-600" />
      <StatCard label="Avg Session" :value="analytics.avgSession" icon="ClockIcon" iconBg="bg-purple-50" iconColor="text-purple-600" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <div class="glass-card p-5">
        <SectionHeader title="Traffic Overview" size="md" />
        <div class="h-72"><Chart type="line" :data="trafficData" /></div>
      </div>
      <div class="glass-card p-5">
        <SectionHeader title="Traffic Sources" size="md" />
        <div class="h-72"><Chart type="doughnut" :data="sourceData" :options="{ plugins: { legend: { position: 'bottom' } } }" /></div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="glass-card p-5">
        <SectionHeader title="Top Pages" size="md" />
        <div class="mt-4 space-y-3">
          <div v-for="page in topPages" :key="page.url" class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">{{ page.title }}</p>
              <p class="text-xs text-gray-500">{{ page.url }}</p>
            </div>
            <div class="text-right">
              <p class="text-sm font-medium">{{ formattedNumber(page.views) }}</p>
              <p class="text-xs text-gray-500">views</p>
            </div>
          </div>
        </div>
      </div>
      <div class="glass-card p-5">
        <SectionHeader title="Device Breakdown" size="md" />
        <div class="h-64 mt-4"><Chart type="doughnut" :data="deviceData" :options="{ plugins: { legend: { position: 'bottom' } } }" /></div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Chart from '@/Components/Shared/UI/Chart.vue'

const analytics = {
  pageViews: 2450000, visitors: 890000, bounceRate: 32.5, avgSession: '4m 32s',
}

const formattedNumber = (num) => new Intl.NumberFormat('en-US').format(num)

const trafficData = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  datasets: [
    { label: 'Visitors', data: [45000, 52000, 48000, 58000, 62000, 68000, 72000, 78000, 85000, 92000, 98000, 105000], borderColor: '#4c6ef5', fill: true, backgroundColor: 'rgba(76, 110, 245, 0.1)', tension: 0.4 },
  ],
}

const sourceData = {
  labels: ['Organic Search', 'Direct', 'Social Media', 'Referral', 'Email'],
  datasets: [{ data: [45, 25, 15, 10, 5], backgroundColor: ['#4c6ef5', '#0ca678', '#f59f00', '#7950f2', '#ff6b6b'] }],
}

const deviceData = {
  labels: ['Desktop', 'Mobile', 'Tablet'],
  datasets: [{ data: [55, 35, 10], backgroundColor: ['#4c6ef5', '#0ca678', '#7950f2'] }],
}

const topPages = ref([
  { title: 'Homepage', url: '/', views: 520000 },
  { title: 'Shop', url: '/shop', views: 380000 },
  { title: 'Product Detail', url: '/products/*', views: 245000 },
  { title: 'Cart', url: '/cart', views: 125000 },
  { title: 'Checkout', url: '/checkout', views: 89000 },
])
</script>

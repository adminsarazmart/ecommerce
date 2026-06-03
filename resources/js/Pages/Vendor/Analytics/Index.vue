<template>
  <VendorLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Analytics</h1>
        <p class="text-sm text-gray-500 mt-1">Advanced analytics for your store</p>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <StatCard label="Product Views" :value="formattedNumber(analytics.views)" :change="18.5" icon="EyeIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
      <StatCard label="Conversion Rate" :value="analytics.conversion + '%'" :change="2.3" icon="ArrowTrendingUpIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
      <StatCard label="Avg Rating" :value="analytics.rating + '/5'" icon="StarIcon" iconBg="bg-amber-50" iconColor="text-amber-600" />
      <StatCard label="Return Customers" :value="analytics.returnRate + '%'" :change="5.1" icon="UserGroupIcon" iconBg="bg-purple-50" iconColor="text-purple-600" />
    </div>

    <div class="glass-card p-5">
      <SectionHeader title="Product Performance" size="md" />
      <div class="h-72"><Chart type="line" :data="performanceData" /></div>
    </div>
  </VendorLayout>
</template>

<script setup>
import { ref } from 'vue'
import VendorLayout from '@/Layouts/VendorLayout.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Chart from '@/Components/Shared/UI/Chart.vue'

const formattedNumber = (num) => new Intl.NumberFormat('en-US').format(num)

const analytics = { views: 125000, conversion: 3.2, rating: 4.5, returnRate: 28 }

const performanceData = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
  datasets: [
    { label: 'Views', data: [8500, 9200, 8800, 10500, 11500, 12800], borderColor: '#4c6ef5', tension: 0.4 },
    { label: 'Sales', data: [280, 320, 290, 350, 380, 420], borderColor: '#0ca678', tension: 0.4, yAxisID: 'y1' },
  ],
}
</script>

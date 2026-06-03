<template>
  <VendorLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Reports</h1>
        <p class="text-sm text-gray-500 mt-1">Your store performance reports</p>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="glass-card p-6">
        <SectionHeader title="Sales Summary" size="sm" />
        <div class="mt-4 space-y-3 text-sm">
          <div class="flex justify-between"><span class="text-gray-500">Today</span><span class="font-medium">{{ formatCurrency(1250) }}</span></div>
          <div class="flex justify-between"><span class="text-gray-500">This Week</span><span class="font-medium">{{ formatCurrency(8500) }}</span></div>
          <div class="flex justify-between"><span class="text-gray-500">This Month</span><span class="font-medium">{{ formatCurrency(35000) }}</span></div>
          <div class="flex justify-between font-semibold pt-2 border-t border-gray-200 dark:border-gray-700"><span>Total</span><span>{{ formatCurrency(485000) }}</span></div>
        </div>
      </div>
      <div class="glass-card p-5">
        <SectionHeader title="Orders" size="sm" />
        <div class="mt-4 space-y-3 text-sm">
          <div class="flex justify-between"><span class="text-gray-500">Pending</span><span>12</span></div>
          <div class="flex justify-between"><span class="text-gray-500">Processing</span><span>8</span></div>
          <div class="flex justify-between"><span class="text-gray-500">Completed</span><span>245</span></div>
          <div class="flex justify-between"><span class="text-gray-500">Cancelled</span><span>5</span></div>
        </div>
      </div>
      <div class="glass-card p-5">
        <SectionHeader title="Top Products" size="sm" />
        <div class="mt-4 space-y-2 text-sm">
          <div v-for="p in topProducts" :key="p.name" class="flex justify-between">
            <span class="text-gray-500 truncate">{{ p.name }}</span>
            <span class="font-medium">{{ p.sold }} sold</span>
          </div>
        </div>
      </div>
    </div>

    <div class="glass-card p-5 mt-6">
      <SectionHeader title="Sales Chart" size="md" />
      <div class="h-72"><Chart type="line" :data="salesData" /></div>
    </div>
  </VendorLayout>
</template>

<script setup>
import { ref } from 'vue'
import VendorLayout from '@/Layouts/VendorLayout.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Chart from '@/Components/Shared/UI/Chart.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const salesData = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
  datasets: [{ label: 'Sales', data: [28000, 32000, 29000, 35000, 38000, 42000], borderColor: '#4c6ef5', tension: 0.4 }],
}

const topProducts = ref([
  { name: 'Wireless Headphones', sold: 245 },
  { name: 'Bluetooth Speaker', sold: 189 },
  { name: 'Phone Case', sold: 156 },
])
</script>

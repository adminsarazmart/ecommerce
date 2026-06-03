<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Sales Report</h1>
        <p class="text-sm text-gray-500 mt-1">Detailed sales analysis</p>
      </div>
    </template>

    <div class="glass-card p-4 mb-6 flex items-center gap-3 flex-wrap">
      <Input v-model="filters.from" type="date" label="From" />
      <Input v-model="filters.to" type="date" label="To" />
      <Select v-model="filters.period" :options="periods" placeholder="Period" />
      <Select v-model="filters.vendor" :options="[]" placeholder="All Vendors" />
      <Button @click="generateReport">Generate Report</Button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <StatCard label="Total Sales" :value="formatCurrency(18000000)" :change="12.5" icon="CurrencyDollarIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
      <StatCard label="Orders" :value="12580" :change="8.3" icon="ShoppingCartIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
      <StatCard label="Avg Order Value" :value="formatCurrency(1430)" :change="3.8" icon="ChartBarIcon" iconBg="bg-purple-50" iconColor="text-purple-600" />
      <StatCard label="Conversion Rate" :value="'3.2%'" :change="-0.5" icon="ArrowTrendingUpIcon" iconBg="bg-amber-50" iconColor="text-amber-600" />
    </div>

    <div class="glass-card p-5">
      <SectionHeader title="Sales Trend" size="md" />
      <div class="h-72"><Chart type="line" :data="salesData" /></div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { reactive, ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Select from '@/Components/Shared/UI/Select.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Chart from '@/Components/Shared/UI/Chart.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const filters = reactive({ from: '', to: '', period: 'monthly', vendor: '' })
const periods = [{ value: 'daily', label: 'Daily' }, { value: 'weekly', label: 'Weekly' }, { value: 'monthly', label: 'Monthly' }]

const salesData = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  datasets: [{ label: 'Sales', data: [1200000, 1350000, 1280000, 1500000, 1650000, 1800000, 1720000, 1900000, 2100000, 2250000, 2400000, 2600000], borderColor: '#4c6ef5', tension: 0.4 }],
}

const generateReport = () => {}
</script>

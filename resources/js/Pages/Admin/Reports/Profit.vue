<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Profit Report</h1>
        <p class="text-sm text-gray-500 mt-1">Revenue, costs, and profit analysis</p>
      </div>
    </template>

    <div class="glass-card p-4 mb-6 flex items-center gap-3 flex-wrap">
      <Input v-model="filters.from" type="date" label="From" />
      <Input v-model="filters.to" type="date" label="To" />
      <Button @click="generateReport">Generate Report</Button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <StatCard label="Gross Revenue" :value="formatCurrency(18000000)" :change="12.5" icon="CurrencyDollarIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
      <StatCard label="Cost of Goods" :value="formatCurrency(8500000)" :change="5.2" icon="BanknotesIcon" iconBg="bg-red-50" iconColor="text-red-600" />
      <StatCard label="Net Profit" :value="formatCurrency(5200000)" :change="18.3" icon="ChartBarIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="glass-card p-5">
        <SectionHeader title="Profit Trend" size="md" />
        <div class="h-72"><Chart type="line" :data="profitData" /></div>
      </div>
      <div class="glass-card p-5">
        <SectionHeader title="Cost Breakdown" size="md" />
        <div class="h-72"><Chart type="doughnut" :data="costData" :options="{ plugins: { legend: { position: 'bottom' } } }" /></div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Chart from '@/Components/Shared/UI/Chart.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const filters = reactive({ from: '', to: '' })

const profitData = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
  datasets: [
    { label: 'Revenue', data: [1200000, 1350000, 1280000, 1500000, 1650000, 1800000], borderColor: '#4c6ef5', tension: 0.4 },
    { label: 'Profit', data: [350000, 420000, 380000, 450000, 520000, 580000], borderColor: '#0ca678', tension: 0.4, fill: true, backgroundColor: 'rgba(12, 166, 120, 0.1)' },
  ],
}

const costData = {
  labels: ['COGS', 'Salaries', 'Marketing', 'Operations', 'Rent', 'Other'],
  datasets: [{ data: [45, 25, 12, 8, 6, 4], backgroundColor: ['#4c6ef5', '#7950f2', '#0ca678', '#f59f00', '#ff6b6b', '#94a3b8'] }],
}

const generateReport = () => {}
</script>

<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">ERP Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Enterprise resource planning overview</p>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <StatCard label="Total Revenue" :value="formatCurrency(erp.revenue)" :change="12.5" icon="CurrencyDollarIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
      <StatCard label="Total Expenses" :value="formatCurrency(erp.expenses)" :change="5.2" icon="BanknotesIcon" iconBg="bg-red-50" iconColor="text-red-600" />
      <StatCard label="Net Profit" :value="formatCurrency(erp.profit)" :change="18.3" icon="ChartBarIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
      <StatCard label="Cash Flow" :value="formatCurrency(erp.cashflow)" :change="-2.1" icon="ArrowTrendingUpIcon" iconBg="bg-purple-50" iconColor="text-purple-600" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="glass-card p-5">
        <SectionHeader title="Revenue vs Expenses" size="md" />
        <div class="h-72"><Chart type="bar" :data="revExpData" /></div>
      </div>
      <div class="glass-card p-5">
        <SectionHeader title="Cash Flow" size="md" />
        <div class="h-72"><Chart type="line" :data="cashflowData" /></div>
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

const erp = { revenue: 12500000, expenses: 8500000, profit: 4000000, cashflow: 2500000 }

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const revExpData = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
  datasets: [
    { label: 'Revenue', data: [850000, 920000, 880000, 1020000, 1150000, 1280000], backgroundColor: '#4c6ef5', borderRadius: 6 },
    { label: 'Expenses', data: [620000, 680000, 650000, 720000, 780000, 850000], backgroundColor: '#ff6b6b', borderRadius: 6 },
  ],
}

const cashflowData = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
  datasets: [{ label: 'Cash Flow', data: [230000, 240000, 230000, 300000, 370000, 430000], borderColor: '#0ca678', fill: true, backgroundColor: 'rgba(12, 166, 120, 0.1)', tension: 0.4 }],
}
</script>

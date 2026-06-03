<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Shareholders</h1>
        <p class="text-sm text-gray-500 mt-1">Manage shareholders and equity</p>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <StatCard label="Total Shareholders" :value="stats.total" icon="UserGroupIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
      <StatCard label="Total Shares" :value="stats.shares" icon="CurrencyDollarIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
      <StatCard label="Equity Value" :value="formatCurrency(stats.equity)" :change="8.5" icon="ArrowTrendingUpIcon" iconBg="bg-purple-50" iconColor="text-purple-600" />
      <StatCard label="Dividends Paid" :value="formatCurrency(stats.dividends)" icon="BanknotesIcon" iconBg="bg-amber-50" iconColor="text-amber-600" />
    </div>

    <DataTable :columns="columns" :data="shareholders" searchable>
      <template #cell-shares="{ row }">{{ row.shares.toLocaleString() }}</template>
      <template #cell-equity="{ row }">{{ formatCurrency(row.equity) }}</template>
    </DataTable>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const stats = { total: 124, shares: 1000000, equity: 50000000, dividends: 2500000 }

const columns = [
  { key: 'name', label: 'Name', sortable: true },
  { key: 'shares', label: 'Shares', sortable: true },
  { key: 'percentage', label: 'Percentage', sortable: true },
  { key: 'equity', label: 'Equity Value', sortable: true },
  { key: 'type', label: 'Type' },
]

const shareholders = ref([
  { id: 1, name: 'John Doe', shares: 250000, percentage: '25%', equity: 12500000, type: 'Founder' },
  { id: 2, name: 'Jane Smith', shares: 150000, percentage: '15%', equity: 7500000, type: 'Investor' },
  { id: 3, name: 'Acme Ventures', shares: 200000, percentage: '20%', equity: 10000000, type: 'Institutional' },
])
</script>

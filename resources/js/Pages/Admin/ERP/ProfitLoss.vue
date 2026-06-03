<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Profit & Loss Statement</h1>
        <p class="text-sm text-gray-500 mt-1">View your profit and loss</p>
      </div>
    </template>

    <div class="max-w-3xl mx-auto space-y-1">
      <div class="glass-card p-6">
        <h3 class="text-lg font-semibold text-emerald-600 mb-4">Revenue</h3>
        <div v-for="item in pnl.revenue" :key="item.label" class="flex justify-between py-2 text-sm border-b border-gray-100 dark:border-gray-800 last:border-0">
          <span class="text-gray-600 dark:text-gray-400">{{ item.label }}</span>
          <span class="font-medium">{{ formatCurrency(item.amount) }}</span>
        </div>
        <div class="flex justify-between py-3 mt-2 font-semibold text-emerald-600 border-t-2 border-emerald-500">
          <span>Total Revenue</span>
          <span>{{ formatCurrency(totalRevenue) }}</span>
        </div>
      </div>

      <div class="glass-card p-6">
        <h3 class="text-lg font-semibold text-red-600 mb-4">Expenses</h3>
        <div v-for="item in pnl.expenses" :key="item.label" class="flex justify-between py-2 text-sm border-b border-gray-100 dark:border-gray-800 last:border-0">
          <span class="text-gray-600 dark:text-gray-400">{{ item.label }}</span>
          <span class="font-medium">{{ formatCurrency(item.amount) }}</span>
        </div>
        <div class="flex justify-between py-3 mt-2 font-semibold text-red-600 border-t-2 border-red-500">
          <span>Total Expenses</span>
          <span>{{ formatCurrency(totalExpenses) }}</span>
        </div>
      </div>

      <div class="glass-card p-6">
        <div class="flex justify-between text-xl font-bold" :class="netProfit >= 0 ? 'text-emerald-600' : 'text-red-600'">
          <span>Net Profit</span>
          <span>{{ formatCurrency(netProfit) }}</span>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const pnl = {
  revenue: [
    { label: 'Product Sales', amount: 12500000 },
    { label: 'Service Revenue', amount: 850000 },
    { label: 'Commission Income', amount: 1875000 },
  ],
  expenses: [
    { label: 'Cost of Goods Sold', amount: 6500000 },
    { label: 'Salaries & Wages', amount: 3200000 },
    { label: 'Marketing & Advertising', amount: 850000 },
    { label: 'Rent & Utilities', amount: 420000 },
    { label: 'Other Operating Expenses', amount: 350000 },
  ],
}

const totalRevenue = computed(() => pnl.revenue.reduce((s, i) => s + i.amount, 0))
const totalExpenses = computed(() => pnl.expenses.reduce((s, i) => s + i.amount, 0))
const netProfit = computed(() => totalRevenue.value - totalExpenses.value)
</script>

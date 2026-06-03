<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Balance Sheet</h1>
        <p class="text-sm text-gray-500 mt-1">View your balance sheet</p>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <div class="glass-card p-6">
          <h3 class="text-lg font-semibold text-blue-600 mb-4">Assets</h3>
          <div v-for="item in bs.assets" :key="item.label" class="flex justify-between py-2 text-sm border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-600 dark:text-gray-400">{{ item.label }}</span>
            <span class="font-medium">{{ formatCurrency(item.amount) }}</span>
          </div>
          <div class="flex justify-between py-3 mt-2 font-semibold text-blue-600 border-t-2 border-blue-500">
            <span>Total Assets</span><span>{{ formatCurrency(totalAssets) }}</span>
          </div>
        </div>
      </div>

      <div class="space-y-6">
        <div class="glass-card p-6">
          <h3 class="text-lg font-semibold text-red-600 mb-4">Liabilities</h3>
          <div v-for="item in bs.liabilities" :key="item.label" class="flex justify-between py-2 text-sm border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-600 dark:text-gray-400">{{ item.label }}</span>
            <span class="font-medium">{{ formatCurrency(item.amount) }}</span>
          </div>
          <div class="flex justify-between py-3 mt-2 font-semibold text-red-600 border-t-2 border-red-500">
            <span>Total Liabilities</span><span>{{ formatCurrency(totalLiabilities) }}</span>
          </div>
        </div>

        <div class="glass-card p-6">
          <h3 class="text-lg font-semibold text-emerald-600 mb-4">Equity</h3>
          <div v-for="item in bs.equity" :key="item.label" class="flex justify-between py-2 text-sm border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-600 dark:text-gray-400">{{ item.label }}</span>
            <span class="font-medium">{{ formatCurrency(item.amount) }}</span>
          </div>
          <div class="flex justify-between py-3 mt-2 font-semibold text-emerald-600 border-t-2 border-emerald-500">
            <span>Total Equity</span><span>{{ formatCurrency(totalEquity) }}</span>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const bs = {
  assets: [
    { label: 'Cash & Cash Equivalents', amount: 2500000 },
    { label: 'Accounts Receivable', amount: 1800000 },
    { label: 'Inventory', amount: 3200000 },
    { label: 'Property & Equipment', amount: 4500000 },
  ],
  liabilities: [
    { label: 'Accounts Payable', amount: 1200000 },
    { label: 'Short-term Debt', amount: 2000000 },
    { label: 'Long-term Debt', amount: 3500000 },
  ],
  equity: [
    { label: 'Share Capital', amount: 4000000 },
    { label: 'Retained Earnings', amount: 1300000 },
  ],
}

const totalAssets = computed(() => bs.assets.reduce((s, i) => s + i.amount, 0))
const totalLiabilities = computed(() => bs.liabilities.reduce((s, i) => s + i.amount, 0))
const totalEquity = computed(() => bs.equity.reduce((s, i) => s + i.amount, 0))
</script>

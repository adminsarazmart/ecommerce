<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Chart of Accounts</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your accounting chart of accounts</p>
      </div>
    </template>

    <div class="space-y-2">
      <div v-for="account in accounts" :key="account.code" class="glass-card p-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <span class="text-sm font-mono text-gray-400">{{ account.code }}</span>
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">{{ account.name }}</p>
              <p class="text-xs text-gray-500">{{ account.type }}</p>
            </div>
          </div>
          <span class="text-sm font-medium">{{ formatCurrency(account.balance) }}</span>
        </div>
        <div v-if="account.children" class="mt-3 ml-8 space-y-2">
          <div v-for="child in account.children" :key="child.code" class="flex items-center justify-between py-1">
            <div class="flex items-center gap-2">
              <span class="text-xs font-mono text-gray-400">{{ child.code }}</span>
              <span class="text-sm text-gray-600 dark:text-gray-400">{{ child.name }}</span>
            </div>
            <span class="text-sm">{{ formatCurrency(child.balance) }}</span>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const accounts = [
  { code: '1000', name: 'Assets', type: 'Asset', balance: 8500000, children: [
    { code: '1100', name: 'Cash & Cash Equivalents', balance: 2500000 },
    { code: '1200', name: 'Accounts Receivable', balance: 1800000 },
    { code: '1300', name: 'Inventory', balance: 3200000 },
  ]},
  { code: '2000', name: 'Liabilities', type: 'Liability', balance: 3200000, children: [
    { code: '2100', name: 'Accounts Payable', balance: 1200000 },
    { code: '2200', name: 'Short-term Debt', balance: 2000000 },
  ]},
  { code: '3000', name: 'Equity', type: 'Equity', balance: 5300000, children: [
    { code: '3100', name: 'Share Capital', balance: 4000000 },
    { code: '3200', name: 'Retained Earnings', balance: 1300000 },
  ]},
]
</script>

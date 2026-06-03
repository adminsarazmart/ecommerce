<template>
  <VendorLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Wallet & Withdrawals</h1>
          <p class="text-sm text-gray-500 mt-1">Manage your earnings and withdrawals</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />Withdraw Funds</Button>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <StatCard label="Available Balance" :value="formatCurrency(12500)" icon="WalletIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
      <StatCard label="Pending Clearance" :value="formatCurrency(4500)" icon="ClockIcon" iconBg="bg-amber-50" iconColor="text-amber-600" />
      <StatCard label="Total Withdrawn" :value="formatCurrency(85000)" icon="BanknotesIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
    </div>

    <DataTable :columns="columns" :data="transactions" searchable>
      <template #cell-amount="{ row }">{{ formatCurrency(row.amount) }}</template>
      <template #cell-status="{ row }"><Badge :variant="row.status === 'completed' ? 'success' : row.status === 'pending' ? 'warning' : 'danger'">{{ row.status }}</Badge></template>
    </DataTable>
  </VendorLayout>
</template>

<script setup>
import { ref } from 'vue'
import { PlusIcon } from '@heroicons/vue/24/outline'
import VendorLayout from '@/Layouts/VendorLayout.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const columns = [
  { key: 'date', label: 'Date', sortable: true },
  { key: 'description', label: 'Description' },
  { key: 'amount', label: 'Amount', sortable: true },
  { key: 'type', label: 'Type' },
  { key: 'status', label: 'Status' },
]

const transactions = ref([
  { id: 1, date: '2024-03-15', description: 'Order #2847', amount: 245.99, type: 'Credit', status: 'completed' },
  { id: 2, date: '2024-03-14', description: 'Withdrawal - Bank', amount: 5000.00, type: 'Debit', status: 'completed' },
  { id: 3, date: '2024-03-13', description: 'Order #2845', amount: 520.50, type: 'Credit', status: 'pending' },
])
</script>

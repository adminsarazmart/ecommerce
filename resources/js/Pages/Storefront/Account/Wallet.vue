<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">My Wallet</h1>
      <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="space-y-1">
          <Link v-for="link in navLinks" :key="link.label" :href="link.url" class="sidebar-item sidebar-item-inactive"><component :is="link.icon" class="h-5 w-5" />{{ link.label }}</Link>
        </aside>
        <div class="lg:col-span-3 space-y-6">
          <div class="glass-card p-8">
            <p class="text-sm text-gray-500">Wallet Balance</p>
            <p class="text-5xl font-bold text-gray-900 dark:text-white mt-2">{{ formatCurrency(balance) }}</p>
            <div class="flex gap-3 mt-6">
              <Button @click="showAddFunds = true">Add Funds</Button>
              <Button variant="secondary">Withdraw</Button>
            </div>
          </div>
          <div class="glass-card p-5">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Transaction History</h3>
            <DataTable :columns="columns" :data="transactions">
              <template #cell-amount="{ row }">{{ formatCurrency(row.amount) }}</template>
              <template #cell-type="{ row }"><Badge :variant="row.type === 'credit' ? 'success' : 'danger'">{{ row.type }}</Badge></template>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
    <Modal v-model="showAddFunds" title="Add Funds" size="sm">
      <Input v-model="fundAmount" label="Amount" type="number" />
      <template #footer><Button @click="showAddFunds = false">Add Funds</Button></template>
    </Modal>
  </StorefrontLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { UserIcon, ShoppingCartIcon, HeartIcon, MapPinIcon, StarIcon, GiftTopIcon, WalletIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Modal from '@/Components/Shared/UI/Modal.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const balance = 12500
const showAddFunds = ref(false)
const fundAmount = ref(100)

const navLinks = [
  { label: 'Dashboard', url: '/account', icon: UserIcon },
  { label: 'Orders', url: '/account/orders', icon: ShoppingCartIcon },
  { label: 'Wishlist', url: '/account/wishlist', icon: HeartIcon },
  { label: 'Wallet', url: '/account/wallet', icon: WalletIcon },
]

const columns = [
  { key: 'date', label: 'Date', sortable: true },
  { key: 'description', label: 'Description' },
  { key: 'amount', label: 'Amount', sortable: true },
  { key: 'type', label: 'Type' },
]

const transactions = ref([
  { id: 1, date: '2024-03-15', description: 'Refund Order #2846', amount: 189.00, type: 'credit' },
  { id: 2, date: '2024-03-10', description: 'Added funds', amount: 200.00, type: 'credit' },
  { id: 3, date: '2024-03-05', description: 'Withdrawal to bank', amount: 500.00, type: 'debit' },
])
</script>

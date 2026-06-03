<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Orders</h1>
          <p class="text-sm text-gray-500 mt-1">View and manage all orders</p>
        </div>
      </div>
    </template>

    <div class="glass-card p-4 mb-6">
      <div class="flex items-center gap-3 flex-wrap">
        <Input v-model="filters.search" placeholder="Search orders..." class="w-64" />
        <Select v-model="filters.status" :options="statusOptions" placeholder="All Statuses" />
        <Select v-model="filters.payment" :options="paymentOptions" placeholder="All Payments" />
        <Input v-model="filters.date_from" type="date" class="w-40" />
        <Input v-model="filters.date_to" type="date" class="w-40" />
        <Button variant="ghost" size="sm" @click="clearFilters">Clear</Button>
      </div>
    </div>

    <DataTable :columns="columns" :data="orders" searchable>
      <template #cell-order="{ row }">
        <Link :href="route('admin.orders.show', row.id)" class="text-brand-600 dark:text-brand-400 font-medium">#{{ row.id }}</Link>
      </template>
      <template #cell-status="{ row }">
        <Badge :variant="row.status.color">{{ row.status.label }}</Badge>
      </template>
      <template #cell-payment="{ row }">
        <Badge :variant="row.payment.color">{{ row.payment.label }}</Badge>
      </template>
      <template #cell-total="{ row }">{{ formatCurrency(row.total) }}</template>
      <template #actions="{ row }">
        <Link :href="route('admin.orders.show', row.id)" class="btn-ghost btn-sm">View</Link>
      </template>
    </DataTable>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Select from '@/Components/Shared/UI/Select.vue'
import Button from '@/Components/Shared/UI/Button.vue'

const filters = reactive({ search: '', status: '', payment: '', date_from: '', date_to: '' })

const statusOptions = [{ value: 'pending', label: 'Pending' }, { value: 'processing', label: 'Processing' }, { value: 'completed', label: 'Completed' }, { value: 'cancelled', label: 'Cancelled' }]
const paymentOptions = [{ value: 'paid', label: 'Paid' }, { value: 'unpaid', label: 'Unpaid' }, { value: 'refunded', label: 'Refunded' }]

const columns = [
  { key: 'order', label: 'Order', sortable: true },
  { key: 'customer', label: 'Customer', sortable: true },
  { key: 'items', label: 'Items' },
  { key: 'total', label: 'Total', sortable: true },
  { key: 'status', label: 'Status' },
  { key: 'payment', label: 'Payment' },
  { key: 'date', label: 'Date', sortable: true },
]

const orders = ref([
  { id: 2847, customer: 'John Smith', items: 3, total: 245.99, status: { label: 'Completed', color: 'success' }, payment: { label: 'Paid', color: 'success' }, date: '2024-03-15' },
  { id: 2846, customer: 'Sarah Johnson', items: 1, total: 189.00, status: { label: 'Processing', color: 'warning' }, payment: { label: 'Paid', color: 'success' }, date: '2024-03-15' },
  { id: 2845, customer: 'Mike Chen', items: 2, total: 520.50, status: { label: 'Pending', color: 'info' }, payment: { label: 'Unpaid', color: 'warning' }, date: '2024-03-14' },
  { id: 2844, customer: 'Emily Davis', items: 5, total: 89.99, status: { label: 'Completed', color: 'success' }, payment: { label: 'Paid', color: 'success' }, date: '2024-03-14' },
  { id: 2843, customer: 'Alex Wilson', items: 1, total: 150.00, status: { label: 'Cancelled', color: 'danger' }, payment: { label: 'Refunded', color: 'danger' }, date: '2024-03-13' },
])

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)
const clearFilters = () => { Object.assign(filters, { search: '', status: '', payment: '', date_from: '', date_to: '' }) }
</script>

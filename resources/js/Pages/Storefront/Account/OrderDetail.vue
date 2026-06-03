<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :crumbs="[{ label: 'My Account', url: '/account' }, { label: 'Orders', url: '/account/orders' }, { label: `#${order.id}` }]" />
      <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mt-4 mb-8">Order #{{ order.id }}</h1>

      <div class="glass-card p-6 mb-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Placed on {{ order.date }}</p>
            <Badge :variant="order.status.color" size="md" class="mt-1">{{ order.status.label }}</Badge>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(order.total) }}</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="glass-card p-5">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Shipping Address</h3>
          <p class="text-sm text-gray-600 dark:text-gray-400">{{ order.shipping_address.street }}<br />{{ order.shipping_address.city }}, {{ order.shipping_address.state }} {{ order.shipping_address.zip }}</p>
        </div>
        <div class="glass-card p-5">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Order Timeline</h3>
          <div class="space-y-3">
            <div v-for="(event, i) in order.timeline" :key="i" class="flex items-center gap-3 text-sm">
              <div :class="['w-2 h-2 rounded-full', event.active ? 'bg-brand-500' : 'bg-gray-300']" />
              <span :class="event.active ? 'text-gray-900 dark:text-white' : 'text-gray-400'">{{ event.action }}</span>
              <span class="text-xs text-gray-400 ml-auto">{{ event.time }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="glass-card p-5">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Order Items</h3>
        <div class="space-y-3">
          <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0">
            <img :src="item.image || '/placeholder.jpg'" class="w-16 h-16 rounded-xl object-cover" />
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900 dark:text-white">{{ item.name }}</p>
              <p class="text-xs text-gray-500">Qty: {{ item.quantity }} × {{ formatCurrency(item.price) }}</p>
            </div>
            <span class="text-sm font-medium">{{ formatCurrency(item.price * item.quantity) }}</span>
          </div>
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { ref } from 'vue'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const order = {
  id: 2847, date: 'March 15, 2024', total: 245.99,
  status: { label: 'Delivered', color: 'success' },
  shipping_address: { street: '123 Main St', city: 'New York', state: 'NY', zip: '10001' },
  timeline: [
    { action: 'Order placed', time: 'Mar 15, 2:30 PM', active: true },
    { action: 'Payment confirmed', time: 'Mar 15, 2:31 PM', active: true },
    { action: 'Shipped', time: 'Mar 16, 10:00 AM', active: true },
    { action: 'Delivered', time: 'Mar 18, 3:45 PM', active: true },
  ],
  items: [
    { id: 1, name: 'Wireless Headphones', image: '', quantity: 1, price: 149.99 },
    { id: 2, name: 'USB Cable', image: '', quantity: 2, price: 19.99 },
    { id: 3, name: 'Phone Case', image: '', quantity: 1, price: 29.99 },
  ],
}
</script>

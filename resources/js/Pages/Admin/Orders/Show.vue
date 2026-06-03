<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <Breadcrumb :crumbs="[{ label: 'Orders', url: route('admin.orders.index') }, { label: `#${order.id}` }]" />
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mt-2">Order #{{ order.id }}</h1>
        </div>
        <div class="flex items-center gap-2">
          <Badge :variant="order.status.color" size="md">{{ order.status.label }}</Badge>
          <Button variant="secondary" size="sm"><PrinterIcon class="h-4 w-4" />Print</Button>
        </div>
      </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 space-y-6">
        <div class="glass-card p-5">
          <SectionHeader title="Order Timeline" size="md" />
          <div class="relative mt-4">
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-800" />
            <div v-for="(event, i) in timeline" :key="i" class="relative pl-10 pb-6 last:pb-0">
              <div :class="['absolute left-2.5 w-3 h-3 rounded-full border-2 mt-1.5', event.active ? 'bg-brand-500 border-brand-500' : 'bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-600']" />
              <p class="text-sm font-medium text-gray-900 dark:text-white">{{ event.action }}</p>
              <p class="text-xs text-gray-500">{{ event.time }}</p>
            </div>
          </div>
        </div>

        <div class="glass-card p-5">
          <SectionHeader title="Order Items" size="md" />
          <div class="mt-4 space-y-3">
            <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50">
              <img :src="item.image || '/placeholder.jpg'" class="w-16 h-16 rounded-xl object-cover" />
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ item.name }}</p>
                <p v-if="item.variant" class="text-xs text-gray-500">{{ item.variant }}</p>
                <p class="text-sm text-gray-500">Qty: {{ item.quantity }}</p>
              </div>
              <Price :value="item.price" size="sm" />
            </div>
          </div>
        </div>
      </div>

      <div class="space-y-6">
        <div class="glass-card p-5">
          <SectionHeader title="Order Summary" size="md" />
          <div class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>{{ formatCurrency(order.subtotal) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span>{{ formatCurrency(order.shipping) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Tax</span><span>{{ formatCurrency(order.tax) }}</span></div>
            <div v-if="order.discount" class="flex justify-between text-green-600"><span>Discount</span><span>-{{ formatCurrency(order.discount) }}</span></div>
            <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between font-semibold text-gray-900 dark:text-white">
              <span>Total</span><span>{{ formatCurrency(order.total) }}</span>
            </div>
          </div>
        </div>

        <div class="glass-card p-5">
          <SectionHeader title="Customer" size="sm" />
          <div class="mt-3 space-y-2 text-sm">
            <p class="font-medium text-gray-900 dark:text-white">{{ order.customer.name }}</p>
            <p class="text-gray-500">{{ order.customer.email }}</p>
            <p class="text-gray-500">{{ order.customer.phone }}</p>
          </div>
        </div>

        <div class="glass-card p-5">
          <SectionHeader title="Shipping Address" size="sm" />
          <div class="mt-3 text-sm text-gray-500">
            <p>{{ order.shipping_address.street }}</p>
            <p>{{ order.shipping_address.city }}, {{ order.shipping_address.state }} {{ order.shipping_address.zip }}</p>
          </div>
        </div>

        <div class="glass-card p-5 space-y-2">
          <Button block variant="secondary" @click="updateStatus('processing')">Mark Processing</Button>
          <Button block variant="secondary" @click="updateStatus('completed')">Mark Completed</Button>
          <Button block variant="danger" @click="updateStatus('cancelled')">Cancel Order</Button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { PrinterIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Price from '@/Components/Shared/UI/Price.vue'

const order = {
  id: 2847,
  status: { label: 'Pending', color: 'info' },
  subtotal: 220.00, shipping: 15.00, tax: 22.00, discount: 11.01, total: 245.99,
  customer: { name: 'John Smith', email: 'john@example.com', phone: '+1 555-1234' },
  shipping_address: { street: '123 Main St', city: 'New York', state: 'NY', zip: '10001' },
  items: [
    { id: 1, name: 'Wireless Bluetooth Headphones', image: '', variant: 'Black', quantity: 1, price: 149.99 },
    { id: 2, name: 'USB-C Charging Cable', image: '', variant: '6ft', quantity: 2, price: 19.99 },
    { id: 3, name: 'Phone Case', image: '', variant: 'iPhone 15 Pro', quantity: 1, price: 29.99 },
  ],
}

const timeline = [
  { action: 'Order placed', time: 'Today, 2:30 PM', active: true },
  { action: 'Payment confirmed', time: 'Today, 2:31 PM', active: true },
  { action: 'Order processing', time: 'In progress', active: true },
  { action: 'Shipped', time: 'Pending', active: false },
  { action: 'Delivered', time: 'Pending', active: false },
]

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)
const updateStatus = (status) => {}
</script>

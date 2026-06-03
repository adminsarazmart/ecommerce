<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Address Book</h1>
        <Button size="sm"><PlusIcon class="h-4 w-4" />Add Address</Button>
      </div>
      <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="space-y-1">
          <Link v-for="link in navLinks" :key="link.label" :href="link.url" class="sidebar-item sidebar-item-inactive"><component :is="link.icon" class="h-5 w-5" />{{ link.label }}</Link>
        </aside>
        <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="addr in addresses" :key="addr.id" class="glass-card p-4 relative">
            <Badge v-if="addr.default" variant="success" class="absolute top-3 right-3">Default</Badge>
            <p class="font-semibold text-gray-900 dark:text-white">{{ addr.label }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ addr.street }}, {{ addr.city }}, {{ addr.state }} {{ addr.zip }}</p>
            <div class="flex gap-2 mt-3">
              <Button variant="ghost" size="xs">Edit</Button>
              <Button variant="ghost" size="xs" class="text-red-500">Delete</Button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { UserIcon, ShoppingCartIcon, HeartIcon, MapPinIcon, StarIcon } from '@heroicons/vue/24/outline'
import { PlusIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const navLinks = [
  { label: 'Dashboard', url: '/account', icon: UserIcon },
  { label: 'Orders', url: '/account/orders', icon: ShoppingCartIcon },
  { label: 'Profile', url: '/account/profile', icon: UserIcon },
  { label: 'Addresses', url: '/account/addresses', icon: MapPinIcon },
  { label: 'Wishlist', url: '/account/wishlist', icon: HeartIcon },
]

const addresses = ref([
  { id: 1, label: 'Home', street: '123 Main St', city: 'New York', state: 'NY', zip: '10001', default: true },
  { id: 2, label: 'Work', street: '456 Office Blvd', city: 'New York', state: 'NY', zip: '10002', default: false },
])
</script>

<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Loyalty Points</h1>
      <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="space-y-1">
          <Link v-for="link in navLinks" :key="link.label" :href="link.url" class="sidebar-item sidebar-item-inactive"><component :is="link.icon" class="h-5 w-5" />{{ link.label }}</Link>
        </aside>
        <div class="lg:col-span-3 space-y-6">
          <div class="glass-card p-8 text-center">
            <GiftTopIcon class="h-12 w-12 mx-auto text-amber-500 mb-3" />
            <p class="text-5xl font-bold text-gray-900 dark:text-white">{{ points }}</p>
            <p class="text-gray-500 mt-1">Available Points</p>
            <p class="text-sm text-gray-500 mt-2">≈ {{ formatCurrency(points * 0.01) }} value</p>
          </div>
          <div class="glass-card p-5">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Points History</h3>
            <div class="space-y-2 text-sm">
              <div v-for="t in transactions" :key="t.id" class="flex justify-between py-2 border-b border-gray-100 last:border-0">
                <div>
                  <p class="text-gray-900 dark:text-white">{{ t.description }}</p>
                  <p class="text-xs text-gray-500">{{ t.date }}</p>
                </div>
                <span :class="t.type === 'earned' ? 'text-emerald-600' : 'text-red-600'">{{ t.type === 'earned' ? '+' : '-' }}{{ t.points }}</span>
              </div>
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
import { GiftTopIcon, UserIcon, ShoppingCartIcon, HeartIcon, MapPinIcon, StarIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const navLinks = [
  { label: 'Dashboard', url: '/account', icon: UserIcon },
  { label: 'Orders', url: '/account/orders', icon: ShoppingCartIcon },
  { label: 'Wishlist', url: '/account/wishlist', icon: HeartIcon },
  { label: 'Loyalty', url: '/account/loyalty', icon: GiftTopIcon },
]

const points = 2450

const transactions = ref([
  { id: 1, description: 'Order #2847', points: 250, type: 'earned', date: '2 days ago' },
  { id: 2, description: 'Points redeemed', points: 500, type: 'redeemed', date: '1 week ago' },
  { id: 3, description: 'Order #2845', points: 520, type: 'earned', date: '2 weeks ago' },
])
</script>

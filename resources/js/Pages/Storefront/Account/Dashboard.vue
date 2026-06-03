<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">My Account</h1>
      <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="space-y-1">
          <Link v-for="link in navLinks" :key="link.label" :href="link.url" :class="['sidebar-item', route().current(link.route) ? 'sidebar-item-active' : 'sidebar-item-inactive']">
            <component :is="link.icon" class="h-5 w-5" />
            {{ link.label }}
          </Link>
        </aside>
        <div class="lg:col-span-3 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <StatCard label="Total Orders" :value="userStats.orders" icon="ShoppingCartIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
            <StatCard label="Wishlist" :value="userStats.wishlist" icon="HeartIcon" iconBg="bg-red-50" iconColor="text-red-600" />
            <StatCard label="Loyalty Points" :value="userStats.loyalty" icon="StarIcon" iconBg="bg-amber-50" iconColor="text-amber-600" />
          </div>

          <div class="glass-card p-5">
            <SectionHeader title="Recent Orders" size="md">
              <template #actions><Link href="/account/orders" class="text-sm text-brand-600">View All</Link></template>
            </SectionHeader>
            <div class="mt-4 space-y-3">
              <div v-for="order in recentOrders" :key="order.id" class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white">#{{ order.id }}</p>
                  <p class="text-xs text-gray-500">{{ order.date }}</p>
                </div>
                <Badge :variant="order.status.color">{{ order.status.label }}</Badge>
                <span class="text-sm font-medium">{{ formatCurrency(order.total) }}</span>
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
import { ShoppingCartIcon, HeartIcon, StarIcon, UserIcon, MapPinIcon, ClockIcon, WalletIcon, GiftTopIcon, ArrowDownTrayIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const navLinks = [
  { label: 'Dashboard', url: '/account', route: 'account.dashboard', icon: UserIcon },
  { label: 'Orders', url: '/account/orders', route: 'account.orders', icon: ShoppingCartIcon },
  { label: 'Profile', url: '/account/profile', route: 'account.profile', icon: UserIcon },
  { label: 'Addresses', url: '/account/addresses', route: 'account.addresses', icon: MapPinIcon },
  { label: 'Wishlist', url: '/account/wishlist', route: 'account.wishlist', icon: HeartIcon },
  { label: 'Reviews', url: '/account/reviews', route: 'account.reviews', icon: StarIcon },
  { label: 'Loyalty', url: '/account/loyalty', route: 'account.loyalty', icon: GiftTopIcon },
  { label: 'Wallet', url: '/account/wallet', route: 'account.wallet', icon: WalletIcon },
  { label: 'Downloadable', url: '/account/downloadable', route: 'account.downloadable', icon: ArrowDownTrayIcon },
]

const userStats = { orders: 12, wishlist: 8, loyalty: 2450 }

const recentOrders = ref([
  { id: 2847, date: '2024-03-15', total: 245.99, status: { label: 'Delivered', color: 'success' } },
  { id: 2846, date: '2024-03-10', total: 189.00, status: { label: 'Processing', color: 'warning' } },
  { id: 2845, date: '2024-03-05', total: 520.50, status: { label: 'Delivered', color: 'success' } },
])
</script>

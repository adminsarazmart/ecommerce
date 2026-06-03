<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    <Toast />

    <aside :class="['fixed top-0 left-0 z-40 h-screen glass border-r border-glass-border dark:border-glass-border-dark transition-all duration-300 flex flex-col', collapsed ? 'w-[72px]' : 'w-64']">
      <div class="flex items-center h-16 px-4 border-b border-gray-100 dark:border-gray-800 flex-shrink-0">
        <div v-if="!collapsed" class="flex items-center gap-2">
          <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">V</div>
          <span class="font-heading font-bold text-lg text-gray-900 dark:text-white">Vendor</span>
        </div>
        <button @click="collapsed = !collapsed" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors mx-auto" :class="{ 'rotate-180': collapsed }">
          <Bars3Icon class="h-5 w-5 text-gray-500" />
        </button>
      </div>

      <div v-if="stores.length > 1 && !collapsed" class="px-3 py-3 border-b border-gray-100 dark:border-gray-800">
        <Select v-model="selectedStore" :options="stores" placeholder="Switch Store" />
      </div>

      <nav class="flex-1 overflow-y-auto p-3 space-y-1">
        <Link v-for="item in navigation" :key="item.label" :href="item.url" :class="['sidebar-item', route().current(item.route) ? 'sidebar-item-active' : 'sidebar-item-inactive']" :title="item.label">
          <component :is="item.icon" class="h-5 w-5 flex-shrink-0" />
          <span v-if="!collapsed">{{ item.label }}</span>
        </Link>
      </nav>

      <div class="p-3 border-t border-gray-100 dark:border-gray-800">
        <div class="flex items-center gap-3 px-3 py-2">
          <Avatar :name="user?.name" size="sm" />
          <div v-if="!collapsed" class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ store?.name || user?.name }}</p>
            <p class="text-xs text-gray-500">Vendor</p>
          </div>
        </div>
        <Link v-if="!collapsed" href="/" class="mt-2 flex items-center gap-2 px-3 py-2 text-xs text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
          <ArrowLeftIcon class="h-3 w-3" />
          Back to Storefront
        </Link>
      </div>
    </aside>

    <div :class="['transition-all duration-300', collapsed ? 'ml-[72px]' : 'ml-64']">
      <header class="sticky top-0 z-30 glass border-b border-glass-border dark:border-glass-border-dark">
        <div class="flex items-center justify-between h-16 px-6">
          <div class="flex items-center gap-4">
            <Breadcrumb v-if="breadcrumbs.length" :crumbs="breadcrumbs" />
          </div>
          <div class="flex items-center gap-3">
            <QuickStats :stats="dashboardStats" />
            <ThemeToggle />
            <Dropdown>
              <template #button>
                <Avatar :name="user?.name" size="sm" />
              </template>
              <MenuItem v-slot="{ active }">
                <Link :href="route('vendor.profile')" :class="['block px-4 py-2 text-sm', active ? 'bg-gray-50 dark:bg-gray-800' : '']">Profile</Link>
              </MenuItem>
              <div class="border-t border-gray-100 dark:border-gray-800" />
              <MenuItem v-slot="{ active }">
                <Link :href="route('logout')" method="post" as="button" :class="['block w-full text-left px-4 py-2 text-sm text-red-600', active ? 'bg-red-50 dark:bg-red-900/20' : '']">Logout</Link>
              </MenuItem>
            </Dropdown>
          </div>
        </div>
      </header>

      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { MenuItem } from '@headlessui/vue'
import {
  Bars3Icon, ArrowLeftIcon, Squares2X2Icon, CubeIcon,
  ShoppingCartIcon, ChartBarIcon, WalletIcon, ArrowTrendingUpIcon,
} from '@heroicons/vue/24/outline'
import { useAuth } from '@/Composables/useAuth'
import Toast from '@/Components/Shared/UI/Toast.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import Avatar from '@/Components/Shared/UI/Avatar.vue'
import Select from '@/Components/Shared/UI/Select.vue'
import Dropdown from '@/Components/Shared/UI/Dropdown.vue'
import ThemeToggle from '@/Components/Shared/Layout/ThemeToggle.vue'
import QuickStats from '@/Components/Shared/Widgets/QuickStats.vue'

const { user } = useAuth()
const page = usePage()

const collapsed = ref(false)
const selectedStore = ref(null)

const stores = computed(() => page.props?.stores || [])
const store = computed(() => page.props?.store || null)
const breadcrumbs = computed(() => page.props?.breadcrumbs || [])

const dashboardStats = computed(() => page.props?.dashboardStats || [
  { label: 'Today Sales', value: '$1,245', icon: WalletIcon, iconBg: 'bg-emerald-50 dark:bg-emerald-900/20', iconColor: 'text-emerald-600 dark:text-emerald-400', change: 12 },
  { label: 'Orders', value: '48', icon: ShoppingCartIcon, iconBg: 'bg-blue-50 dark:bg-blue-900/20', iconColor: 'text-blue-600 dark:text-blue-400', change: 8 },
])

const navigation = [
  { label: 'Dashboard', url: route('vendor.dashboard'), route: 'vendor.dashboard', icon: Squares2X2Icon },
  { label: 'Products', url: route('vendor.products.index'), route: 'vendor.products.index', icon: CubeIcon },
  { label: 'Orders', url: route('vendor.orders.index'), route: 'vendor.orders.index', icon: ShoppingCartIcon },
  { label: 'Wallet', url: route('vendor.wallet'), route: 'vendor.wallet', icon: WalletIcon },
  { label: 'Reports', url: route('vendor.reports'), route: 'vendor.reports', icon: ChartBarIcon },
  { label: 'Analytics', url: route('vendor.analytics'), route: 'vendor.analytics', icon: ArrowTrendingUpIcon },
]
</script>

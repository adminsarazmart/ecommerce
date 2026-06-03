<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    <div v-if="loading" class="fixed inset-0 z-[100] bg-white/80 dark:bg-gray-950/80 backdrop-blur-sm flex items-center justify-center">
      <div class="flex flex-col items-center gap-3">
        <div class="h-10 w-10 border-4 border-brand-500 border-t-transparent rounded-full animate-spin" />
        <p class="text-sm text-gray-500">Loading...</p>
      </div>
    </div>

    <Toast />

    <aside :class="['fixed top-0 left-0 z-40 h-screen glass border-r border-glass-border dark:border-glass-border-dark transition-all duration-300 flex flex-col', sidebarCollapsed ? 'w-[72px]' : 'w-64']">
      <div class="flex items-center h-16 px-4 border-b border-gray-100 dark:border-gray-800 flex-shrink-0">
        <div v-if="!sidebarCollapsed" class="flex items-center gap-2">
          <div class="w-8 h-8 bg-gradient-to-br from-brand-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">M</div>
          <span class="font-heading font-bold text-lg text-gray-900 dark:text-white">Marketplace</span>
        </div>
        <button @click="toggleCollapse" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors mx-auto" :class="{ 'rotate-180': sidebarCollapsed }">
          <Bars3Icon class="h-5 w-5 text-gray-500" />
        </button>
      </div>

      <nav class="flex-1 overflow-y-auto p-3 space-y-1">
        <div v-for="module in navigation" :key="module.title">
          <p v-if="!sidebarCollapsed" class="px-3 py-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ module.title }}</p>
          <template v-for="item in module.items" :key="item.label">
            <div v-if="item.children" class="space-y-0.5">
              <button @click="toggleSubmenu(item)" :class="['sidebar-item w-full', isActive(item) ? 'sidebar-item-active' : 'sidebar-item-inactive']" :title="item.label">
                <component :is="item.icon" class="h-5 w-5 flex-shrink-0" />
                <span v-if="!sidebarCollapsed" class="flex-1 text-left">{{ item.label }}</span>
                <ChevronDownIcon v-if="!sidebarCollapsed" :class="['h-4 w-4 transition-transform', item.open ? 'rotate-180' : '']" />
              </button>
              <Transition name="submenu">
                <div v-if="item.open && !sidebarCollapsed" class="ml-2 space-y-0.5">
                  <Link v-for="child in item.children" :key="child.label" :href="child.url" :class="['sidebar-item pl-10', route().current(child.route) ? 'sidebar-item-active' : 'sidebar-item-inactive']">
                    {{ child.label }}
                  </Link>
                </div>
              </Transition>
            </div>
            <Link v-else :href="item.url" :class="['sidebar-item', isActive(item) ? 'sidebar-item-active' : 'sidebar-item-inactive']" :title="item.label">
              <component :is="item.icon" class="h-5 w-5 flex-shrink-0" />
              <span v-if="!sidebarCollapsed">{{ item.label }}</span>
            </Link>
          </template>
        </div>
      </nav>

      <div class="p-3 border-t border-gray-100 dark:border-gray-800">
        <div class="flex items-center gap-3 px-3 py-2">
          <Avatar :name="user?.name" size="sm" />
          <div v-if="!sidebarCollapsed" class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ user?.name }}</p>
            <p class="text-xs text-gray-500 truncate">{{ user?.email }}</p>
          </div>
        </div>
      </div>
    </aside>

    <div :class="['transition-all duration-300', sidebarCollapsed ? 'ml-[72px]' : 'ml-64']">
      <header class="sticky top-0 z-30 glass border-b border-glass-border dark:border-glass-border-dark">
        <div class="flex items-center justify-between h-16 px-6">
          <div class="flex items-center gap-4">
            <Breadcrumb v-if="breadcrumbs.length" :crumbs="breadcrumbs" />
          </div>
          <div class="flex items-center gap-2">
            <button @click="searchOpen = !searchOpen" class="p-2 rounded-xl glass hover:bg-white/30 dark:hover:bg-white/10 transition-colors">
              <MagnifyingGlassIcon class="h-5 w-5 text-gray-600 dark:text-gray-400" />
            </button>
            <NotificationsDropdown :notifications="notifications" :count="notifications.filter(n => !n.read).length" />
            <ThemeToggle />
            <div class="w-px h-6 bg-gray-200 dark:bg-gray-700" />
            <Dropdown>
              <template #button>
                <Avatar :name="user?.name" size="sm" />
              </template>
              <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800">
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ user?.name }}</p>
                <p class="text-xs text-gray-500">{{ user?.email }}</p>
              </div>
              <MenuItem v-slot="{ active }">
                <Link :href="route('admin.profile')" :class="['block px-4 py-2 text-sm', active ? 'bg-gray-50 dark:bg-gray-800' : '']">Profile</Link>
              </MenuItem>
              <MenuItem v-slot="{ active }">
                <Link :href="route('admin.settings')" :class="['block px-4 py-2 text-sm', active ? 'bg-gray-50 dark:bg-gray-800' : '']">Settings</Link>
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
        <div v-if="$slots.header" class="mb-6"><slot name="header" /></div>
        <slot />
      </main>
    </div>

    <SearchOverlay :open="searchOpen" @close="searchOpen = false" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { MenuItem } from '@headlessui/vue'
import {
  Bars3Icon, MagnifyingGlassIcon, ChevronDownIcon,
  Squares2X2Icon, ShoppingBagIcon, CubeIcon, UserGroupIcon,
  ShoppingCartIcon, DocumentTextIcon, ArchiveBoxIcon,
  CreditCardIcon, CalculatorIcon, ChartBarIcon, Cog6ToothIcon,
  BuildingStorefrontIcon, CurrencyDollarIcon, GiftTopIcon,
  UserIcon, UsersIcon, ArrowTrendingUpIcon, GlobeAltIcon,
} from '@heroicons/vue/24/outline'
import { useAppStore } from '@/Stores/app'
import { useAuth } from '@/Composables/useAuth'
import Toast from '@/Components/Shared/UI/Toast.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import Avatar from '@/Components/Shared/UI/Avatar.vue'
import Dropdown from '@/Components/Shared/UI/Dropdown.vue'
import ThemeToggle from '@/Components/Shared/Layout/ThemeToggle.vue'
import NotificationsDropdown from '@/Components/Shared/Widgets/NotificationsDropdown.vue'
import SearchOverlay from '@/Components/Shared/Layout/SearchOverlay.vue'

const app = useAppStore()
const { user } = useAuth()
const page = usePage()

const sidebarCollapsed = ref(false)
const loading = ref(false)
const searchOpen = ref(false)

const toggleCollapse = () => { sidebarCollapsed.value = !sidebarCollapsed.value }

const breadcrumbs = computed(() => page.props?.breadcrumbs || [])

const notifications = ref([
  { message: 'New order received', time: '2 min ago', color: 'bg-brand-50', icon: ShoppingBagIcon, read: false },
  { message: 'Product stock low', time: '15 min ago', color: 'bg-amber-50', icon: CubeIcon, read: false },
  { message: 'New vendor registered', time: '1 hour ago', color: 'bg-emerald-50', icon: UserGroupIcon, read: true },
])

const isActive = (item) => {
  const current = route().current()
  if (item.route === current) return true
  if (item.children) return item.children.some(c => route().current(c.route))
  return false
}

const toggleSubmenu = (item) => { item.open = !item.open }

const navigation = [
  {
    title: 'Main',
    items: [
      { label: 'Dashboard', url: route('admin.dashboard'), route: 'admin.dashboard', icon: Squares2X2Icon },
      { label: 'Marketplace', url: route('admin.marketplace'), route: 'admin.marketplace', icon: GlobeAltIcon },
    ],
  },
  {
    title: 'Commerce',
    items: [
      {
        label: 'Products', icon: CubeIcon, open: false, route: 'admin.products',
        children: [
          { label: 'All Products', url: route('admin.products.index'), route: 'admin.products.index' },
          { label: 'Add Product', url: route('admin.products.create'), route: 'admin.products.create' },
          { label: 'Categories', url: route('admin.categories'), route: 'admin.categories' },
          { label: 'Attributes', url: route('admin.attributes'), route: 'admin.attributes' },
        ],
      },
      { label: 'Vendors', url: route('admin.vendors.index'), route: 'admin.vendors.index', icon: BuildingStorefrontIcon },
      {
        label: 'Orders', icon: ShoppingCartIcon, open: false, route: 'admin.orders',
        children: [
          { label: 'All Orders', url: route('admin.orders.index'), route: 'admin.orders.index' },
          { label: 'Order Fulfillment', url: route('admin.orders.fulfillment'), route: 'admin.orders.fulfillment' },
        ],
      },
    ],
  },
  {
    title: 'Content',
    items: [
      {
        label: 'CMS', icon: DocumentTextIcon, open: false, route: 'admin.cms',
        children: [
          { label: 'Pages', url: route('admin.cms.pages'), route: 'admin.cms.pages' },
          { label: 'Menus', url: route('admin.cms.menus'), route: 'admin.cms.menus' },
          { label: 'Banners', url: route('admin.cms.banners'), route: 'admin.cms.banners' },
          { label: 'Sliders', url: route('admin.cms.sliders'), route: 'admin.cms.sliders' },
          { label: 'Header Builder', url: route('admin.builder.header'), route: 'admin.builder.header' },
          { label: 'Footer Builder', url: route('admin.builder.footer'), route: 'admin.builder.footer' },
          { label: 'Homepage Builder', url: route('admin.builder.homepage'), route: 'admin.builder.homepage' },
        ],
      },
    ],
  },
  {
    title: 'Operations',
    items: [
      {
        label: 'Inventory', icon: ArchiveBoxIcon, open: false, route: 'admin.inventory',
        children: [
          { label: 'Dashboard', url: route('admin.inventory.index'), route: 'admin.inventory.index' },
          { label: 'Warehouses', url: route('admin.inventory.warehouses'), route: 'admin.inventory.warehouses' },
          { label: 'Transfers', url: route('admin.inventory.transfers'), route: 'admin.inventory.transfers' },
          { label: 'Purchase Orders', url: route('admin.inventory.purchase-orders'), route: 'admin.inventory.purchase-orders' },
          { label: 'Suppliers', url: route('admin.inventory.suppliers'), route: 'admin.inventory.suppliers' },
        ],
      },
      {
        label: 'POS', icon: CreditCardIcon, open: false, route: 'admin.pos',
        children: [
          { label: 'Register', url: route('admin.pos.register'), route: 'admin.pos.register' },
          { label: 'Sessions', url: route('admin.pos.sessions'), route: 'admin.pos.sessions' },
        ],
      },
    ],
  },
  {
    title: 'Enterprise',
    items: [
      {
        label: 'ERP', icon: CalculatorIcon, open: false, route: 'admin.erp',
        children: [
          { label: 'Dashboard', url: route('admin.erp.dashboard'), route: 'admin.erp.dashboard' },
          { label: 'Chart of Accounts', url: route('admin.erp.accounts'), route: 'admin.erp.accounts' },
          { label: 'Journal Entries', url: route('admin.erp.journal'), route: 'admin.erp.journal' },
          { label: 'Profit & Loss', url: route('admin.erp.profit-loss'), route: 'admin.erp.profit-loss' },
          { label: 'Balance Sheet', url: route('admin.erp.balance-sheet'), route: 'admin.erp.balance-sheet' },
          { label: 'Expenses', url: route('admin.erp.expenses'), route: 'admin.erp.expenses' },
        ],
      },
      {
        label: 'Employees', icon: UsersIcon, open: false, route: 'admin.employees',
        children: [
          { label: 'All Employees', url: route('admin.employees.index'), route: 'admin.employees.index' },
          { label: 'Payroll', url: route('admin.employees.payroll'), route: 'admin.employees.payroll' },
          { label: 'Attendance', url: route('admin.employees.attendance'), route: 'admin.employees.attendance' },
          { label: 'Leaves', url: route('admin.employees.leaves'), route: 'admin.employees.leaves' },
        ],
      },
      {
        label: 'Shareholders', icon: CurrencyDollarIcon, open: false, route: 'admin.shareholders',
        children: [
          { label: 'Overview', url: route('admin.shareholders.index'), route: 'admin.shareholders.index' },
          { label: 'Ledger', url: route('admin.shareholders.ledger'), route: 'admin.shareholders.ledger' },
          { label: 'Dividends', url: route('admin.shareholders.dividends'), route: 'admin.shareholders.dividends' },
        ],
      },
      {
        label: 'Resellers', icon: UserGroupIcon, open: false, route: 'admin.resellers',
        children: [
          { label: 'All Resellers', url: route('admin.resellers.index'), route: 'admin.resellers.index' },
          { label: 'Commissions', url: route('admin.resellers.commissions'), route: 'admin.resellers.commissions' },
        ],
      },
    ],
  },
  {
    title: 'Analytics',
    items: [
      {
        label: 'Reports', icon: ChartBarIcon, open: false, route: 'admin.reports',
        children: [
          { label: 'Dashboard', url: route('admin.reports.index'), route: 'admin.reports.index' },
          { label: 'Sales Report', url: route('admin.reports.sales'), route: 'admin.reports.sales' },
          { label: 'Profit Report', url: route('admin.reports.profit'), route: 'admin.reports.profit' },
        ],
      },
      { label: 'Analytics', url: route('admin.analytics'), route: 'admin.analytics', icon: ArrowTrendingUpIcon },
    ],
  },
  {
    title: 'System',
    items: [
      { label: 'Settings', url: route('admin.settings'), route: 'admin.settings', icon: Cog6ToothIcon },
    ],
  },
]
</script>

<style scoped>
.submenu-enter-active { transition: all 0.2s ease-out; max-height: 200px; }
.submenu-leave-active { transition: all 0.15s ease-in; max-height: 0; }
.submenu-enter-from, .submenu-leave-to { opacity: 0; max-height: 0; overflow: hidden; }
.submenu-enter-to, .submenu-leave-from { opacity: 1; overflow: hidden; }
</style>

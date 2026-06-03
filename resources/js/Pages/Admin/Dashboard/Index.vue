<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Dashboard</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Welcome back, {{ user?.name }}. Here's your store overview.</p>
        </div>
        <div class="flex items-center gap-2">
          <Button variant="secondary" size="sm" @click="refreshData">
            <ArrowPathIcon class="h-4 w-4" />
            Refresh
          </Button>
          <Button size="sm">
            <PlusIcon class="h-4 w-4" />
            Add Product
          </Button>
        </div>
      </div>
    </template>

    <div class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard label="Total Revenue" :value="formatCurrency(stats.revenue)" :change="stats.revenueChange" icon="CurrencyDollarIcon" iconBg="bg-emerald-50 dark:bg-emerald-900/20" iconColor="text-emerald-600 dark:text-emerald-400" />
        <StatCard label="Total Orders" :value="stats.orders" :change="stats.orderChange" icon="ShoppingCartIcon" iconBg="bg-blue-50 dark:bg-blue-900/20" iconColor="text-blue-600 dark:text-blue-400" />
        <StatCard label="Total Customers" :value="stats.customers" :change="stats.customerChange" icon="UserGroupIcon" iconBg="bg-purple-50 dark:bg-purple-900/20" iconColor="text-purple-600 dark:text-purple-400" />
        <StatCard label="Total Products" :value="stats.products" :change="stats.productChange" icon="CubeIcon" iconBg="bg-amber-50 dark:bg-amber-900/20" iconColor="text-amber-600 dark:text-amber-400" />
        <StatCard label="Total Vendors" :value="stats.vendors" :change="stats.vendorChange" icon="BuildingStorefrontIcon" iconBg="bg-rose-50 dark:bg-rose-900/20" iconColor="text-rose-600 dark:text-rose-400" />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass-card p-6">
          <SectionHeader title="Revenue Overview" size="md" description="Monthly revenue for the current year">
            <template #actions>
              <div class="flex gap-1">
                <button v-for="p in periods" :key="p" :class="['px-3 py-1.5 text-xs rounded-lg font-medium transition-colors', selectedPeriod === p ? 'bg-brand-500 text-white' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800']" @click="selectedPeriod = p">{{ p }}</button>
              </div>
            </template>
          </SectionHeader>
          <div class="h-72">
            <Chart type="line" :data="revenueChartData" :options="chartOptions" />
          </div>
        </div>
        <div class="space-y-6">
          <div class="glass-card p-5">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Real-time Visitors</h3>
            <div class="flex items-center gap-3">
              <div class="relative">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center">
                  <span class="text-2xl font-bold text-white">{{ visitorCount }}</span>
                </div>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full animate-pulse" />
              </div>
              <div>
                <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">Active Now</p>
                <p class="text-xs text-gray-500">+{{ visitorChange }} this hour</p>
              </div>
            </div>
          </div>
          <WeatherWidget />
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass-card p-5">
          <SectionHeader title="Recent Orders" size="md">
            <template #actions>
              <Link :href="route('admin.orders.index')" class="text-sm text-brand-600 dark:text-brand-400 hover:underline">View All</Link>
            </template>
          </SectionHeader>
          <div class="overflow-x-auto mt-4">
            <table class="w-full">
              <thead>
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-800">
                  <th class="pb-3">Order</th>
                  <th class="pb-3">Customer</th>
                  <th class="pb-3">Status</th>
                  <th class="pb-3">Total</th>
                  <th class="pb-3">Date</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                  <td class="py-3 text-sm font-medium text-brand-600 dark:text-brand-400">#{{ order.id }}</td>
                  <td class="py-3 text-sm text-gray-700 dark:text-gray-300">{{ order.customer }}</td>
                  <td class="py-3"><Badge :variant="order.status.color">{{ order.status.label }}</Badge></td>
                  <td class="py-3 text-sm font-medium">{{ formatCurrency(order.total) }}</td>
                  <td class="py-3 text-sm text-gray-500">{{ order.date }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="glass-card p-5">
          <SectionHeader title="Top Products" size="md" />
          <div class="mt-4 space-y-4">
            <div v-for="product in topProducts" :key="product.id" class="flex items-center gap-3">
              <img :src="product.image || '/placeholder.jpg'" class="w-10 h-10 rounded-lg object-cover flex-shrink-0" />
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ product.name }}</p>
                <p class="text-xs text-gray-500">{{ product.sold }} sold</p>
              </div>
              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ formatCurrency(product.revenue) }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card p-5">
          <SectionHeader title="Vendor Performance" size="md" />
          <div class="h-64 mt-4">
            <Chart type="bar" :data="vendorChartData" :options="chartOptions" />
          </div>
        </div>
        <div class="glass-card p-5">
          <SectionHeader title="Quick Actions" size="md" />
          <div class="grid grid-cols-2 gap-3 mt-4">
            <button v-for="action in quickActions" :key="action.label" @click="action.action" class="glass p-4 rounded-xl text-left hover:shadow-glass-lg transition-all duration-300 hover:-translate-y-0.5">
              <component :is="action.icon" class="h-6 w-6 text-brand-500 mb-2" />
              <p class="text-sm font-medium text-gray-900 dark:text-white">{{ action.label }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">{{ action.description }}</p>
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowPathIcon, PlusIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Chart from '@/Components/Shared/UI/Chart.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import WeatherWidget from '@/Components/Shared/Widgets/WeatherWidget.vue'
import { useAuth } from '@/Composables/useAuth'

const { user } = useAuth()

const selectedPeriod = ref('Monthly')
const periods = ['Daily', 'Weekly', 'Monthly']

const stats = ref({
  revenue: 284500, revenueChange: 12.5, orders: 12580, orderChange: 8.3,
  customers: 45230, customerChange: 15.2, products: 3450, productChange: -2.1,
  vendors: 890, vendorChange: 22.4,
})

const visitorCount = ref(142)
const visitorChange = 18

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const recentOrders = ref([
  { id: 2847, customer: 'John Smith', status: { label: 'Completed', color: 'success' }, total: 245.99, date: '2 min ago' },
  { id: 2846, customer: 'Sarah Johnson', status: { label: 'Processing', color: 'warning' }, total: 189.00, date: '15 min ago' },
  { id: 2845, customer: 'Mike Chen', status: { label: 'Pending', color: 'info' }, total: 520.50, date: '1 hour ago' },
  { id: 2844, customer: 'Emily Davis', status: { label: 'Completed', color: 'success' }, total: 89.99, date: '2 hours ago' },
  { id: 2843, customer: 'Alex Wilson', status: { label: 'Cancelled', color: 'danger' }, total: 150.00, date: '3 hours ago' },
])

const topProducts = ref([
  { id: 1, name: 'Wireless Headphones', image: '', sold: 1250, revenue: 87500 },
  { id: 2, name: 'Smart Watch Pro', image: '', sold: 980, revenue: 156800 },
  { id: 3, name: 'Designer Backpack', image: '', sold: 756, revenue: 45360 },
  { id: 4, name: 'Organic Coffee Set', image: '', sold: 645, revenue: 19350 },
  { id: 5, name: 'LED Desk Lamp', image: '', sold: 534, revenue: 16020 },
])

const revenueChartData = computed(() => ({
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  datasets: [
    {
      label: 'This Year',
      data: [18500, 22300, 19800, 25600, 28400, 31200, 29800, 33500, 36700, 38900, 41200, 44500],
      borderColor: '#4c6ef5',
      backgroundColor: 'rgba(76, 110, 245, 0.1)',
      fill: true,
      tension: 0.4,
    },
    {
      label: 'Last Year',
      data: [14200, 16800, 15200, 18900, 21200, 22800, 21500, 24500, 26800, 28200, 30500, 32800],
      borderColor: '#94a3b8',
      backgroundColor: 'rgba(148, 163, 184, 0.05)',
      borderDash: [5, 5],
      fill: false,
      tension: 0.4,
    },
  ],
}))

const vendorChartData = computed(() => ({
  labels: ['TechPro', 'FashionHub', 'HomeGoods', 'SportsDirect', 'BookWorld'],
  datasets: [
    {
      label: 'Revenue',
      data: [85000, 62000, 45000, 38000, 22000],
      backgroundColor: ['#4c6ef5', '#7950f2', '#0ca678', '#f59f00', '#ff6b6b'],
      borderRadius: 6,
    },
  ],
}))

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: true, position: 'bottom' } },
  scales: { y: { beginAtZero: true } },
}

const quickActions = [
  { label: 'New Product', description: 'Add a product to your catalog', icon: 'CubeIcon', action: () => {} },
  { label: 'Create Order', description: 'Place a manual order', icon: 'ShoppingCartIcon', action: () => {} },
  { label: 'Add Vendor', description: 'Register a new vendor', icon: 'BuildingStorefrontIcon', action: () => {} },
  { label: 'View Reports', description: 'Check sales analytics', icon: 'ChartBarIcon', action: () => {} },
]

const refreshData = () => {
  visitorCount.value = Math.floor(Math.random() * 200) + 50
}

let interval
onMounted(() => { interval = setInterval(() => { visitorCount.value += Math.floor(Math.random() * 10) - 3 }, 5000) })
onUnmounted(() => clearInterval(interval))
</script>

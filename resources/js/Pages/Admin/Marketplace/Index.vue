<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Marketplace Overview</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Key metrics and performance indicators</p>
      </div>
    </template>

    <div class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard label="Active Vendors" :value="marketplace.activeVendors" :change="12" icon="BuildingStorefrontIcon" iconBg="bg-emerald-50 dark:bg-emerald-900/20" iconColor="text-emerald-600 dark:text-emerald-400" />
        <StatCard label="Listed Products" :value="marketplace.listedProducts" :change="8" icon="CubeIcon" iconBg="bg-blue-50 dark:bg-blue-900/20" iconColor="text-blue-600 dark:text-blue-400" />
        <StatCard label="Monthly GMV" :value="formatCurrency(marketplace.gmv)" :change="15.3" icon="CurrencyDollarIcon" iconBg="bg-purple-50 dark:bg-purple-900/20" iconColor="text-purple-600 dark:text-purple-400" />
        <StatCard label="Commission Earned" :value="formatCurrency(marketplace.commission)" :change="22.7" icon="BanknotesIcon" iconBg="bg-amber-50 dark:bg-amber-900/20" iconColor="text-amber-600 dark:text-amber-400" />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card p-5">
          <SectionHeader title="Marketplace Growth" size="md" />
          <div class="h-72"><Chart type="line" :data="growthData" /></div>
        </div>
        <div class="glass-card p-5">
          <SectionHeader title="Category Distribution" size="md" />
          <div class="h-72"><Chart type="doughnut" :data="categoryData" :options="{ plugins: { legend: { position: 'bottom' } } }" /></div>
        </div>
      </div>

      <DataTable :columns="vendorColumns" :data="topVendors" searchable>
        <template #cell-revenue="{ row }">{{ formatCurrency(row.revenue) }}</template>
        <template #cell-status="{ row }">
          <Badge :variant="row.status === 'Active' ? 'success' : 'warning'">{{ row.status }}</Badge>
        </template>
      </DataTable>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Chart from '@/Components/Shared/UI/Chart.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const marketplace = { activeVendors: 890, listedProducts: 34500, gmv: 12500000, commission: 1875000 }

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const growthData = {
  labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
  datasets: [{ label: 'GMV', data: [850000, 920000, 880000, 1020000, 1150000, 1280000, 1220000, 1350000, 1420000, 1580000, 1650000, 1780000], borderColor: '#4c6ef5', tension: 0.4 }],
}

const categoryData = {
  labels: ['Electronics','Fashion','Home & Garden','Sports','Books','Other'],
  datasets: [{ data: [35, 25, 18, 12, 7, 3], backgroundColor: ['#4c6ef5','#7950f2','#0ca678','#f59f00','#ff6b6b','#94a3b8'] }],
}

const vendorColumns = [
  { key: 'name', label: 'Vendor', sortable: true },
  { key: 'products', label: 'Products', sortable: true },
  { key: 'revenue', label: 'Revenue', sortable: true },
  { key: 'status', label: 'Status', sortable: true },
]

const topVendors = [
  { id: 1, name: 'TechPro Electronics', products: 245, revenue: 485000, status: 'Active' },
  { id: 2, name: 'FashionHub Apparel', products: 189, revenue: 325000, status: 'Active' },
  { id: 3, name: 'HomeGoods Decor', products: 156, revenue: 198000, status: 'Active' },
  { id: 4, name: 'SportsDirect', products: 98, revenue: 125000, status: 'Active' },
  { id: 5, name: 'BookWorld', products: 450, revenue: 89000, status: 'Inactive' },
]
</script>

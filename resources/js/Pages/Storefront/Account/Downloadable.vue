<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Downloadable Products</h1>
      <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="space-y-1">
          <Link v-for="link in navLinks" :key="link.label" :href="link.url" class="sidebar-item sidebar-item-inactive"><component :is="link.icon" class="h-5 w-5" />{{ link.label }}</Link>
        </aside>
        <div class="lg:col-span-3">
          <DataTable :columns="columns" :data="downloads">
            <template #cell-size="{ row }">{{ (row.size / 1024 / 1024).toFixed(1) }} MB</template>
            <template #actions="{ row }">
              <Button size="xs" @click="downloadFile(row)">Download</Button>
            </template>
          </DataTable>
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowDownTrayIcon, UserIcon, ShoppingCartIcon, HeartIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Button from '@/Components/Shared/UI/Button.vue'

const navLinks = [
  { label: 'Dashboard', url: '/account', icon: UserIcon },
  { label: 'Orders', url: '/account/orders', icon: ShoppingCartIcon },
  { label: 'Downloadable', url: '/account/downloadable', icon: ArrowDownTrayIcon },
]

const columns = [
  { key: 'name', label: 'Product', sortable: true },
  { key: 'file', label: 'File' },
  { key: 'size', label: 'Size' },
  { key: 'downloads', label: 'Downloads' },
  { key: 'expires', label: 'Expires', sortable: true },
]

const downloads = ref([
  { id: 1, name: 'Premium E-book Guide', file: 'premium-guide.pdf', size: 2450000, downloads: 3, expires: '2024-06-15' },
  { id: 2, name: 'Design Templates Pack', file: 'templates.zip', size: 15000000, downloads: 1, expires: '2024-05-20' },
])

const downloadFile = (item) => {}
</script>

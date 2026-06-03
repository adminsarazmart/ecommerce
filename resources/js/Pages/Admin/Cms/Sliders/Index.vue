<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Sliders</h1>
          <p class="text-sm text-gray-500 mt-1">Manage hero sliders and carousels</p>
        </div>
        <Link :href="route('admin.cms.sliders.create')">
          <Button size="sm"><PlusIcon class="h-4 w-4" />New Slider</Button>
        </Link>
      </div>
    </template>

    <DataTable :columns="columns" :data="sliders" searchable>
      <template #cell-status="{ row }"><Badge :variant="row.status === 'active' ? 'success' : 'neutral'">{{ row.status }}</Badge></template>
      <template #actions="{ row }">
        <Link :href="route('admin.cms.sliders.edit', row.id)" class="btn-ghost btn-sm">Edit</Link>
      </template>
    </DataTable>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { PlusIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const columns = [
  { key: 'name', label: 'Slider Name', sortable: true },
  { key: 'location', label: 'Location', sortable: true },
  { key: 'slides', label: 'Slides' },
  { key: 'status', label: 'Status' },
]

const sliders = ref([
  { id: 1, name: 'Homepage Hero', location: 'Homepage', slides: 3, status: 'active' },
  { id: 2, name: 'Category Banner', location: 'Shop Page', slides: 2, status: 'active' },
])
</script>

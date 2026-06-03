<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Pages</h1>
          <p class="text-sm text-gray-500 mt-1">Manage your static pages</p>
        </div>
        <Link :href="route('admin.cms.pages.create')">
          <Button size="sm"><PlusIcon class="h-4 w-4" />New Page</Button>
        </Link>
      </div>
    </template>

    <DataTable :columns="columns" :data="pages" searchable>
      <template #cell-status="{ row }">
        <Switch :modelValue="row.status === 'published'" @update:modelValue="toggleStatus(row)" />
      </template>
      <template #actions="{ row }">
        <div class="flex gap-1">
          <button class="p-1.5 rounded-lg hover:bg-gray-100"><PencilIcon class="h-4 w-4" /></button>
          <button class="p-1.5 rounded-lg hover:bg-red-50"><TrashIcon class="h-4 w-4 text-red-500" /></button>
        </div>
      </template>
    </DataTable>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { PlusIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/Shared/UI/DataTable.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Switch from '@/Components/Shared/UI/Switch.vue'

const columns = [
  { key: 'title', label: 'Title', sortable: true },
  { key: 'slug', label: 'Slug', sortable: true },
  { key: 'author', label: 'Author' },
  { key: 'updated_at', label: 'Last Updated', sortable: true },
  { key: 'status', label: 'Status' },
]

const pages = ref([
  { id: 1, title: 'About Us', slug: 'about-us', author: 'Admin', updated_at: '2 days ago', status: 'published' },
  { id: 2, title: 'Terms & Conditions', slug: 'terms', author: 'Admin', updated_at: '1 week ago', status: 'published' },
  { id: 3, title: 'Privacy Policy', slug: 'privacy', author: 'Admin', updated_at: '2 weeks ago', status: 'published' },
  { id: 4, title: 'FAQ', slug: 'faq', author: 'Admin', updated_at: '1 month ago', status: 'draft' },
])

const toggleStatus = (page) => { page.status = page.status === 'published' ? 'draft' : 'published' }
</script>

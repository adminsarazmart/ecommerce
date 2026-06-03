<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Banners</h1>
          <p class="text-sm text-gray-500 mt-1">Manage promotional banners</p>
        </div>
        <Button size="sm"><PlusIcon class="h-4 w-4" />Add Banner</Button>
      </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="banner in banners" :key="banner.id" class="glass-card overflow-hidden group">
        <div class="aspect-[2/1] bg-gradient-to-br from-brand-500 to-purple-600 relative overflow-hidden">
          <img :src="banner.image" :alt="banner.title" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent" />
          <div class="absolute bottom-3 left-3 right-3">
            <h3 class="text-white font-semibold text-sm">{{ banner.title }}</h3>
          </div>
          <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <button class="p-1.5 bg-white/90 rounded-lg"><PencilIcon class="h-4 w-4 text-gray-700" /></button>
            <button class="p-1.5 bg-white/90 rounded-lg"><TrashIcon class="h-4 w-4 text-red-500" /></button>
          </div>
        </div>
        <div class="p-3 flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ banner.title }}</p>
            <p class="text-xs text-gray-500">{{ banner.position }}</p>
          </div>
          <Switch :modelValue="banner.active" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { PlusIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Switch from '@/Components/Shared/UI/Switch.vue'

const banners = ref([
  { id: 1, title: 'Spring Sale', image: '', position: 'Homepage Hero', active: true },
  { id: 2, title: 'New Arrivals', image: '', position: 'Category Page', active: true },
  { id: 3, title: 'Free Shipping', image: '', position: 'Sidebar', active: false },
])
</script>

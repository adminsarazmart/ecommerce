<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Menu Builder</h1>
        <p class="text-sm text-gray-500 mt-1">Drag & drop to build your navigation menus</p>
      </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 glass-card p-5 min-h-[400px]">
        <SectionHeader title="Menu Items" size="md">
          <template #actions>
            <Button size="sm" @click="addMenuItem">+ Add Item</Button>
          </template>
        </SectionHeader>
        <div class="mt-4 space-y-2">
          <div v-for="(item, i) in menuItems" :key="i" class="flex items-center gap-3 p-3 glass rounded-xl cursor-move" draggable="true" @dragstart="dragStart(i)" @dragover.prevent @drop="dropItem(i)">
            <Bars3Icon class="h-5 w-5 text-gray-400 cursor-grab" />
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900 dark:text-white">{{ item.label }}</p>
              <p class="text-xs text-gray-500">{{ item.url }}</p>
            </div>
            <Badge>{{ item.type }}</Badge>
            <button @click="editItem(i)" class="p-1 hover:bg-gray-100 rounded"><PencilIcon class="h-4 w-4" /></button>
            <button @click="menuItems.splice(i, 1)" class="p-1 hover:bg-red-50 rounded"><TrashIcon class="h-4 w-4 text-red-500" /></button>
          </div>
        </div>
      </div>

      <div class="space-y-6">
        <div class="glass-card p-5">
          <SectionHeader title="Menu Settings" size="sm" />
          <div class="mt-3 space-y-3">
            <Input v-model="menuName" label="Menu Name" />
            <Select v-model="menuLocation" :options="locations" placeholder="Display Location" />
          </div>
        </div>
        <div class="glass-card p-5">
          <h4 class="text-sm font-semibold mb-3">Add Links</h4>
          <div class="space-y-2">
            <button v-for="link in availableLinks" :key="link.label" class="w-full text-left p-3 glass rounded-xl hover:bg-white/30 text-sm transition-colors" @click="addCustomLink(link)">
              + {{ link.label }}
            </button>
          </div>
        </div>
        <Button block @click="saveMenu">Save Menu</Button>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Bars3Icon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Select from '@/Components/Shared/UI/Select.vue'
import Badge from '@/Components/Shared/UI/Badge.vue'

const menuName = ref('Main Menu')
const menuLocation = ref('header')

const locations = [
  { value: 'header', label: 'Header' },
  { value: 'footer', label: 'Footer' },
  { value: 'sidebar', label: 'Sidebar' },
]

const menuItems = ref([
  { label: 'Home', url: '/', type: 'Custom Link' },
  { label: 'Shop', url: '/shop', type: 'Custom Link' },
  { label: 'About', url: '/about', type: 'Page' },
])

const availableLinks = [
  { label: 'Home Page', url: '/' },
  { label: 'Shop', url: '/shop' },
  { label: 'Categories', url: '/categories' },
  { label: 'About Us', url: '/about' },
  { label: 'Contact', url: '/contact' },
]

let dragIndex = null
const dragStart = (i) => { dragIndex = i }
const dropItem = (i) => {
  if (dragIndex !== null) {
    const item = menuItems.value.splice(dragIndex, 1)[0]
    menuItems.value.splice(i, 0, item)
    dragIndex = null
  }
}

const addMenuItem = () => { menuItems.value.push({ label: 'New Link', url: '/', type: 'Custom' }) }
const addCustomLink = (link) => { menuItems.value.push({ label: link.label, url: link.url, type: 'Custom Link' }) }
const editItem = (i) => { const label = prompt('Label:', menuItems.value[i].label); if (label) menuItems.value[i].label = label }
const saveMenu = () => {}
</script>

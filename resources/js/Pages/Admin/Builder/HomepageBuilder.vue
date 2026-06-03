<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Homepage Builder</h1>
          <p class="text-sm text-gray-500 mt-1">Visually build your homepage sections</p>
        </div>
        <div class="flex gap-2">
          <Button variant="ghost" size="sm" @click="previewMode = !previewMode">{{ previewMode ? 'Edit' : 'Preview' }}</Button>
          <Button size="sm" @click="saveHomepage">Save</Button>
        </div>
      </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <div v-if="!previewMode" class="lg:col-span-1 glass-card p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Sections</h3>
        <div class="space-y-2">
          <div v-for="section in availableSections" :key="section.type" class="glass p-3 rounded-xl cursor-grab text-sm flex items-center gap-2" draggable="true" @dragstart="dragSection(section)">
            <component :is="section.icon" class="h-4 w-4 text-brand-500" />
            {{ section.label }}
          </div>
        </div>
      </div>

      <div :class="previewMode ? 'lg:col-span-4' : 'lg:col-span-3'">
        <div class="space-y-4" @dragover.prevent @drop="addSection">
          <div v-for="(section, i) in sections" :key="i" class="glass-card relative group" :class="{ 'border-2 border-dashed border-brand-500': !previewMode }">
            <div v-if="!previewMode" class="absolute -top-3 right-3 flex gap-1 z-10">
              <button @click="moveSection(i, -1)" :disabled="i === 0" class="p-1.5 bg-brand-500 text-white rounded-lg text-xs disabled:opacity-50">▲</button>
              <button @click="moveSection(i, 1)" :disabled="i === sections.length - 1" class="p-1.5 bg-brand-500 text-white rounded-lg text-xs disabled:opacity-50">▼</button>
              <button @click="editSection(section)" class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow"><PencilIcon class="h-3 w-3" /></button>
              <button @click="sections.splice(i, 1)" class="p-1.5 bg-red-500 text-white rounded-lg"><TrashIcon class="h-3 w-3" /></button>
            </div>
            <div class="p-8 text-center">
              <component :is="section.icon" class="h-8 w-8 mx-auto text-gray-400 mb-2" />
              <p class="text-sm font-medium text-gray-900 dark:text-white">{{ section.label }}</p>
              <p class="text-xs text-gray-500">Click to edit this section</p>
            </div>
          </div>
          <div v-if="sections.length === 0" class="glass-card p-12 text-center text-sm text-gray-400 border-2 border-dashed">
            Drop sections here to build your homepage
          </div>
        </div>
      </div>
    </div>

    <Modal v-model="editModal" title="Edit Section" size="lg">
      <div class="space-y-3" v-if="editingSection">
        <Input v-model="editingSection.title" label="Title" />
        <Input v-model="editingSection.subtitle" label="Subtitle" />
      </div>
      <template #footer>
        <Button @click="editModal = false">Done</Button>
      </template>
    </Modal>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { PhotoIcon, CubeIcon, BanknotesIcon, UserGroupIcon, ChatBubbleLeftRightIcon, NewspaperIcon, RectangleGroupIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Modal from '@/Components/Shared/UI/Modal.vue'
import Input from '@/Components/Shared/UI/Input.vue'

const previewMode = ref(false)
const sections = ref([
  { type: 'hero', label: 'Hero Banner', icon: PhotoIcon, title: 'Hero', subtitle: '' },
  { type: 'featured', label: 'Featured Products', icon: CubeIcon, title: 'Featured', subtitle: '' },
])

const availableSections = [
  { type: 'hero', label: 'Hero Banner', icon: PhotoIcon },
  { type: 'featured', label: 'Featured Products', icon: CubeIcon },
  { type: 'categories', label: 'Categories Grid', icon: RectangleGroupIcon },
  { type: 'deals', label: 'Deals & Offers', icon: BanknotesIcon },
  { type: 'brands', label: 'Brand Carousel', icon: UserGroupIcon },
  { type: 'testimonials', label: 'Testimonials', icon: ChatBubbleLeftRightIcon },
  { type: 'blog', label: 'Blog Posts', icon: NewspaperIcon },
]

const editModal = ref(false)
const editingSection = ref(null)

let draggedSec = null
const dragSection = (s) => { draggedSec = s }
const addSection = () => { if (draggedSec) { sections.value.push({ ...draggedSec, title: '', subtitle: '' }); draggedSec = null } }

const moveSection = (i, dir) => {
  const item = sections.value.splice(i, 1)[0]
  sections.value.splice(i + dir, 0, item)
}

const editSection = (section) => { editingSection.value = section; editModal.value = true }
const saveHomepage = () => {}
</script>

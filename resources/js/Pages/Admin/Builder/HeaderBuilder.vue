<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Header Builder</h1>
          <p class="text-sm text-gray-500 mt-1">Drag & drop to customize your header layout</p>
        </div>
        <div class="flex gap-2">
          <Button variant="ghost" size="sm" @click="saveTemplate">Save as Template</Button>
          <Button size="sm" @click="saveHeader">Save Header</Button>
        </div>
      </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <div class="lg:col-span-1 glass-card p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Components</h3>
        <div class="space-y-2">
          <div v-for="comp in availableComponents" :key="comp.type" class="glass p-3 rounded-xl cursor-grab text-sm flex items-center gap-2 hover:bg-white/30 transition-colors" draggable="true" @dragstart="dragComponent(comp)">
            <component :is="comp.icon" class="h-4 w-4 text-brand-500" />
            {{ comp.label }}
          </div>
        </div>
      </div>

      <div class="lg:col-span-3">
        <div class="glass-card p-4 mb-4">
          <p class="text-xs text-gray-500 mb-3 uppercase tracking-wider font-medium">Header Preview</p>
          <div class="glass rounded-xl p-4">
            <div class="flex items-center justify-between gap-2">
              <template v-for="(section, i) in headerSections" :key="i">
                <div class="relative group flex items-center gap-1 p-2 glass rounded-lg text-xs cursor-pointer hover:bg-white/30" @dragstart="dragSection(i)" draggable="true">
                  <component :is="section.icon" class="h-4 w-4" />
                  <span>{{ section.label }}</span>
                  <button @click="openSectionSettings(section)" class="ml-1 p-0.5 rounded hover:bg-gray-200 opacity-0 group-hover:opacity-100 transition-opacity">
                    <Cog6ToothIcon class="h-3 w-3" />
                  </button>
                  <button @click="headerSections.splice(i, 1)" class="p-0.5 rounded hover:bg-red-100 opacity-0 group-hover:opacity-100 transition-opacity">
                    <XMarkIcon class="h-3 w-3 text-red-500" />
                  </button>
                </div>
                <div v-if="i < headerSections.length - 1" class="flex-1" />
              </template>
              <div v-if="headerSections.length === 0" class="w-full text-center py-8 text-sm text-gray-400">Drop components here</div>
            </div>
          </div>
        </div>

        <div class="glass-card p-4" @dragover.prevent @drop="addToHeader">
          <p class="text-xs text-gray-500 mb-3 uppercase tracking-wider font-medium">Drop Zone</p>
          <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center text-sm text-gray-400">
            Drag components here to add them to the header
          </div>
        </div>
      </div>
    </div>

    <Modal v-model="settingsModal" title="Section Settings" size="sm">
      <div class="space-y-3" v-if="activeSettings">
        <Input v-model="activeSettings.settings.label" label="Label" />
        <Switch v-model="activeSettings.settings.visible" /> Visible
      </div>
    </Modal>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Cog6ToothIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { MagnifyingGlassIcon, ShoppingBagIcon, HeartIcon, LanguageIcon, SunIcon, Bars3Icon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Modal from '@/Components/Shared/UI/Modal.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Switch from '@/Components/Shared/UI/Switch.vue'

const availableComponents = [
  { type: 'logo', label: 'Logo', icon: Bars3Icon, settings: { label: 'Logo', visible: true } },
  { type: 'search', label: 'Search Bar', icon: MagnifyingGlassIcon, settings: { label: 'Search', visible: true } },
  { type: 'cart', label: 'Cart Icon', icon: ShoppingBagIcon, settings: { label: 'Cart', visible: true } },
  { type: 'wishlist', label: 'Wishlist', icon: HeartIcon, settings: { label: 'Wishlist', visible: true } },
  { type: 'language', label: 'Language Switcher', icon: LanguageIcon, settings: { label: 'Language', visible: true } },
  { type: 'darkmode', label: 'Dark Mode Toggle', icon: SunIcon, settings: { label: 'Dark Mode', visible: true } },
]

const headerSections = ref([
  { type: 'logo', label: 'Logo', icon: Bars3Icon, settings: { label: 'Logo', visible: true } },
  { type: 'search', label: 'Search', icon: MagnifyingGlassIcon, settings: { label: 'Search', visible: true } },
  { type: 'cart', label: 'Cart', icon: ShoppingBagIcon, settings: { label: 'Cart', visible: true } },
])

const settingsModal = ref(false)
const activeSettings = ref(null)

let draggedComp = null
const dragComponent = (comp) => { draggedComp = comp }
const dragSection = (i) => {}
const addToHeader = () => { if (draggedComp) { headerSections.value.push({ ...draggedComp }); draggedComp = null } }

const openSectionSettings = (section) => { activeSettings.value = section; settingsModal.value = true }
const saveHeader = () => {}
const saveTemplate = () => {}
</script>

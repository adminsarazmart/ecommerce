<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Footer Builder</h1>
          <p class="text-sm text-gray-500 mt-1">Design your footer layout with drag & drop</p>
        </div>
        <Button @click="saveFooter">Save Footer</Button>
      </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <div class="lg:col-span-1 glass-card p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Widgets</h3>
        <div class="space-y-2">
          <div v-for="widget in availableWidgets" :key="widget.type" class="glass p-3 rounded-xl cursor-grab text-sm flex items-center gap-2" draggable="true" @dragstart="dragWidget(widget)">
            <component :is="widget.icon" class="h-4 w-4 text-brand-500" />
            {{ widget.label }}
          </div>
        </div>
      </div>

      <div class="lg:col-span-3">
        <div class="flex items-center justify-between mb-4">
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Number of Columns:</span>
          <div class="flex gap-1">
            <button v-for="n in 4" :key="n" :class="['px-3 py-1.5 rounded-lg text-sm', columnCount === n ? 'bg-brand-500 text-white' : 'glass']" @click="columnCount = n">{{ n }}</button>
          </div>
        </div>

        <div class="grid gap-4" :class="`grid-cols-${columnCount}`">
          <div v-for="col in columnCount" :key="col" class="glass-card p-4 min-h-[200px]" @dragover.prevent @drop="addWidget(col - 1)">
            <p class="text-xs text-gray-400 mb-3">Column {{ col }}</p>
            <div v-for="(widget, wi) in columns[col - 1]" :key="wi" class="glass p-3 rounded-xl mb-2 text-sm flex items-center justify-between group">
              <span>{{ widget.label }}</span>
              <button @click="columns[col - 1].splice(wi, 1)" class="text-red-400 hover:text-red-500 opacity-0 group-hover:opacity-100"><XMarkIcon class="h-3 w-3" /></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { XMarkIcon, Bars3Icon, CreditCardIcon, UserGroupIcon, CodeBracketIcon, EnvelopeIcon, PhoneIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Button from '@/Components/Shared/UI/Button.vue'

const columnCount = ref(4)
const columns = ref([[], [], [], []])

const availableWidgets = [
  { type: 'menu', label: 'Menu', icon: Bars3Icon },
  { type: 'payment', label: 'Payment Icons', icon: CreditCardIcon },
  { type: 'social', label: 'Social Icons', icon: UserGroupIcon },
  { type: 'html', label: 'Custom HTML', icon: CodeBracketIcon },
  { type: 'newsletter', label: 'Newsletter', icon: EnvelopeIcon },
  { type: 'contact', label: 'Contact Info', icon: PhoneIcon },
]

let draggedWidget = null
const dragWidget = (w) => { draggedWidget = w }
const addWidget = (colIndex) => { if (draggedWidget) { columns.value[colIndex].push({ ...draggedWidget }); draggedWidget = null } }
const saveFooter = () => {}
</script>

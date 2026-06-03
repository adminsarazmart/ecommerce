<template>
  <div class="glass rounded-2xl overflow-hidden">
    <div v-if="$slots.header || searchable || showBulkActions" class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between gap-4 flex-wrap">
      <div v-if="$slots.header" class="flex-1"><slot name="header" /></div>
      <Search v-if="searchable" v-model="searchQuery" :placeholder="searchPlaceholder" class="max-w-xs" />
      <div v-if="showBulkActions && selected.length" class="flex items-center gap-2">
        <span class="text-sm text-gray-500">{{ selected.length }} selected</span>
        <Button variant="danger" size="sm" @click="$emit('bulk-delete', selected)">Delete</Button>
        <Button variant="ghost" size="sm" @click="selected = []">Clear</Button>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-100 dark:border-gray-800">
            <th v-if="selectable" class="px-4 py-3 text-left">
              <Checkbox :modelValue="allSelected" @update:modelValue="toggleAll" />
            </th>
            <th v-for="col in columns" :key="col.key" :class="['px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider', col.sortable ? 'cursor-pointer hover:text-gray-700 dark:hover:text-gray-200' : '']" @click="col.sortable && toggleSort(col.key)">
              <div class="flex items-center gap-1">
                {{ col.label }}
                <span v-if="col.sortable && sortKey === col.key" class="text-brand-500">
                  <ChevronUpIcon v-if="sortDir === 'asc'" class="h-3 w-3" />
                  <ChevronDownIcon v-else class="h-3 w-3" />
                </span>
              </div>
            </th>
            <th v-if="$slots.actions" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
          <tr v-for="(row, i) in filteredData" :key="row.id || i" :class="['transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-800/30', selected.includes(row.id) ? 'bg-brand-50/50 dark:bg-brand-500/5' : '']">
            <td v-if="selectable" class="px-4 py-3">
              <Checkbox :modelValue="selected.includes(row.id)" @update:modelValue="toggleRow(row.id)" />
            </td>
            <td v-for="col in columns" :key="col.key" class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
              <slot :name="`cell-${col.key}`" :row="row" :value="row[col.key]">
                {{ row[col.key] }}
              </slot>
            </td>
            <td v-if="$slots.actions" class="px-4 py-3 text-right">
              <slot name="actions" :row="row" />
            </td>
          </tr>
          <tr v-if="filteredData.length === 0">
            <td :colspan="columns.length + (selectable ? 1 : 0) + ($slots.actions ? 1 : 0)" class="px-4 py-12 text-center text-gray-400">No data found</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="totalPages > 1" class="p-4 border-t border-gray-100 dark:border-gray-800">
      <Pagination :currentPage="currentPage" :totalPages="totalPages" :total="total" :perPage="perPage" @page-change="(p) => $emit('page-change', p)" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { ChevronUpIcon, ChevronDownIcon } from '@heroicons/vue/20/solid'
import Search from './Search.vue'
import Checkbox from './Checkbox.vue'
import Pagination from './Pagination.vue'

const props = defineProps({
  columns: { type: Array, required: true },
  data: { type: Array, required: true },
  selectable: Boolean,
  searchable: Boolean,
  searchPlaceholder: { type: String, default: 'Search...' },
  sortKey: String,
  sortDir: { type: String, default: 'asc' },
  currentPage: { type: Number, default: 1 },
  totalPages: { type: Number, default: 1 },
  total: { type: Number, default: 0 },
  perPage: { type: Number, default: 15 },
  showBulkActions: Boolean,
})

const emit = defineEmits(['sort', 'page-change', 'bulk-delete', 'update:sortKey', 'update:sortDir'])

const searchQuery = ref('')
const selected = ref([])

const filteredData = computed(() => {
  if (!searchQuery.value) return props.data
  return props.data.filter(row =>
    Object.values(row).some(val =>
      String(val).toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  )
})

const allSelected = computed(() => filteredData.value.length > 0 && selected.value.length === filteredData.value.length)

const toggleAll = () => {
  if (allSelected.value) selected.value = []
  else selected.value = filteredData.value.map(r => r.id)
}

const toggleRow = (id) => {
  const i = selected.value.indexOf(id)
  if (i > -1) selected.value.splice(i, 1)
  else selected.value.push(id)
}

const toggleSort = (key) => {
  const dir = props.sortKey === key && props.sortDir === 'asc' ? 'desc' : 'asc'
  emit('update:sortKey', key)
  emit('update:sortDir', dir)
  emit('sort', { key, dir })
}
</script>

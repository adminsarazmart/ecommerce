<template>
  <nav v-if="totalPages > 1" class="flex items-center justify-between gap-4 mt-6">
    <p class="text-sm text-gray-500 dark:text-gray-400">
      Showing <span class="font-medium">{{ from }}</span> to <span class="font-medium">{{ to }}</span> of <span class="font-medium">{{ total }}</span>
    </p>
    <div class="flex items-center gap-1">
      <button :disabled="currentPage <= 1" class="btn-ghost btn-sm rounded-lg" @click="goTo(currentPage - 1)">
        <ChevronLeftIcon class="h-4 w-4" />
      </button>
      <template v-for="page in visiblePages" :key="page">
        <span v-if="page === '...'" class="px-2 text-gray-400">...</span>
        <button v-else :class="['btn-sm rounded-lg min-w-[36px]', page === currentPage ? 'btn-primary' : 'btn-ghost']" @click="goTo(page)">
          {{ page }}
        </button>
      </template>
      <button :disabled="currentPage >= totalPages" class="btn-ghost btn-sm rounded-lg" @click="goTo(currentPage + 1)">
        <ChevronRightIcon class="h-4 w-4" />
      </button>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
  currentPage: { type: Number, default: 1 },
  totalPages: { type: Number, default: 1 },
  total: { type: Number, default: 0 },
  perPage: { type: Number, default: 15 },
})

const emit = defineEmits(['page-change'])

const from = computed(() => Math.min((props.currentPage - 1) * props.perPage + 1, props.total))
const to = computed(() => Math.min(props.currentPage * props.perPage, props.total))

const visiblePages = computed(() => {
  const pages = []
  const total = props.totalPages
  const current = props.currentPage
  if (total <= 7) { for (let i = 1; i <= total; i++) pages.push(i); return pages }
  pages.push(1)
  if (current > 3) pages.push('...')
  const start = Math.max(2, current - 1)
  const end = Math.min(total - 1, current + 1)
  for (let i = start; i <= end; i++) pages.push(i)
  if (current < total - 2) pages.push('...')
  pages.push(total)
  return pages
})

const goTo = (page) => { if (page >= 1 && page <= props.totalPages) emit('page-change', page) }
</script>

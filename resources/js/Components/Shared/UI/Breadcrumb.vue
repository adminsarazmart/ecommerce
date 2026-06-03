<template>
  <nav class="flex" aria-label="Breadcrumb">
    <ol class="flex items-center gap-1.5 text-sm">
      <li v-for="(crumb, i) in crumbs" :key="i" class="flex items-center gap-1.5">
        <ChevronRightIcon v-if="i > 0" class="h-3.5 w-3.5 text-gray-400 flex-shrink-0" />
        <component :is="crumb.icon" v-if="crumb.icon && i === 0" class="h-4 w-4 text-gray-400 flex-shrink-0" />
        <Link v-if="crumb.url && i < crumbs.length - 1" :href="crumb.url" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
          {{ crumb.label }}
        </Link>
        <span v-else :class="[i === crumbs.length - 1 ? 'text-gray-900 dark:text-white font-medium' : 'text-gray-500 dark:text-gray-400']">
          {{ crumb.label }}
        </span>
      </li>
    </ol>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ChevronRightIcon } from '@heroicons/vue/20/solid'

defineProps({ crumbs: { type: Array, required: true } })
</script>

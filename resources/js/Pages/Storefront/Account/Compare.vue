<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Compare Products</h1>
        <Button variant="ghost" size="sm" @click="clearCompare">Clear All</Button>
      </div>
      <div v-if="items.length === 0" class="text-center py-20">
        <ArrowsRightLeftIcon class="mx-auto h-12 w-12 text-gray-300 mb-4" />
        <p class="text-gray-500">No products to compare</p>
      </div>
      <div v-else class="mt-8 overflow-x-auto">
        <table class="w-full min-w-[600px]">
          <thead>
            <tr>
              <th class="w-48 text-left text-sm font-medium text-gray-500">Attributes</th>
              <th v-for="item in items" :key="item.id" class="text-center p-4">
                <button @click="removeItem(item.id)" class="float-right p-1 text-red-400 hover:text-red-500"><XMarkIcon class="h-4 w-4" /></button>
                <img :src="item.image || '/placeholder.jpg'" class="w-32 h-32 object-cover rounded-xl mx-auto mb-2" />
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ item.name }}</p>
                <Price :value="item.price" size="lg" class="block mt-1" />
                <Button size="sm" class="mt-2" @click="addToCart(item)">Add to Cart</Button>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="attr in attributes" :key="attr" class="border-t border-gray-200 dark:border-gray-800">
              <td class="py-3 text-sm font-medium text-gray-500 capitalize">{{ attr }}</td>
              <td v-for="item in items" :key="item.id" class="text-center py-3 text-sm text-gray-700 dark:text-gray-300">{{ item[attr] || '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { computed } from 'vue'
import { XMarkIcon, ArrowsRightLeftIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Price from '@/Components/Shared/UI/Price.vue'
import { useCompare } from '@/Composables/useCompare'

const { items, removeItem, clearCompare } = useCompare()
const attributes = ['brand', 'color', 'size', 'material', 'weight', 'rating']
const addToCart = () => {}
</script>

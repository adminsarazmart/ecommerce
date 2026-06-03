<template>
  <div class="glass-card group overflow-hidden" @mouseenter="isHovered = true" @mouseleave="isHovered = false">
    <div class="relative aspect-square overflow-hidden">
      <img :src="product.image || '/placeholder.jpg'" :alt="product.name" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" />
      <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
        <button class="p-2.5 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:scale-110 transition-transform" @click="$emit('quick-view', product)">
          <EyeIcon class="h-5 w-5 text-gray-700 dark:text-gray-300" />
        </button>
        <button class="p-2.5 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:scale-110 transition-transform" @click="$emit('add-to-cart', product)">
          <ShoppingBagIcon class="h-5 w-5 text-gray-700 dark:text-gray-300" />
        </button>
      </div>
      <div v-if="product.discount" class="absolute top-3 left-3">
        <Badge variant="danger">-{{ product.discount }}%</Badge>
      </div>
      <button class="absolute top-3 right-3 p-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-full hover:scale-110 transition-transform" @click="$emit('toggle-wishlist', product)">
        <HeartIcon v-if="inWishlist" class="h-4 w-4 text-red-500 fill-current" />
        <HeartIcon v-else class="h-4 w-4 text-gray-600 dark:text-gray-400" />
      </button>
    </div>
    <div class="p-4">
      <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ product.brand || product.category }}</p>
      <Link :href="`/products/${product.slug}`" class="text-sm font-semibold text-gray-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 line-clamp-2 transition-colors">{{ product.name }}</Link>
      <div class="flex items-center gap-1 mt-1">
        <Rating :modelValue="product.rating || 0" :reviewCount="product.reviews_count" />
      </div>
      <div class="mt-2 flex items-center justify-between">
        <Price :value="product.price" :original="product.original_price" size="lg" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { EyeIcon, ShoppingBagIcon, HeartIcon } from '@heroicons/vue/24/outline'
import Badge from './Badge.vue'
import Price from './Price.vue'
import Rating from './Rating.vue'

defineProps({
  product: { type: Object, required: true },
  inWishlist: { type: Boolean, default: false },
})

defineEmits(['quick-view', 'add-to-cart', 'toggle-wishlist'])

const isHovered = ref(false)
</script>

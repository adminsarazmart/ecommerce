<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">My Reviews</h1>
      <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="space-y-1">
          <Link v-for="link in navLinks" :key="link.label" :href="link.url" class="sidebar-item sidebar-item-inactive"><component :is="link.icon" class="h-5 w-5" />{{ link.label }}</Link>
        </aside>
        <div class="lg:col-span-3 space-y-4">
          <div v-for="review in reviews" :key="review.id" class="glass-card p-4">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ review.product }}</p>
                <Rating :modelValue="review.rating" />
              </div>
              <span class="text-xs text-gray-400">{{ review.date }}</span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ review.text }}</p>
          </div>
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { UserIcon, ShoppingCartIcon, HeartIcon, MapPinIcon, StarIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import Rating from '@/Components/Shared/UI/Rating.vue'

const navLinks = [
  { label: 'Dashboard', url: '/account', icon: UserIcon },
  { label: 'Orders', url: '/account/orders', icon: ShoppingCartIcon },
  { label: 'Profile', url: '/account/profile', icon: UserIcon },
  { label: 'Addresses', url: '/account/addresses', icon: MapPinIcon },
  { label: 'Wishlist', url: '/account/wishlist', icon: HeartIcon },
  { label: 'Reviews', url: '/account/reviews', icon: StarIcon },
]

const reviews = ref([
  { id: 1, product: 'Wireless Headphones Pro', rating: 5, text: 'Amazing sound quality and comfort!', date: '2 weeks ago' },
  { id: 2, product: 'Phone Case', rating: 4, text: 'Good quality, fits perfectly.', date: '1 month ago' },
])
</script>

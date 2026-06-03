<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">My Wishlist</h1>
      <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="space-y-1">
          <Link v-for="link in navLinks" :key="link.label" :href="link.url" class="sidebar-item sidebar-item-inactive"><component :is="link.icon" class="h-5 w-5" />{{ link.label }}</Link>
        </aside>
        <div class="lg:col-span-3">
          <div v-if="items.length === 0" class="text-center py-12">
            <HeartIcon class="mx-auto h-12 w-12 text-gray-300 mb-4" />
            <p class="text-gray-500">Your wishlist is empty</p>
          </div>
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <ProductCard v-for="product in items" :key="product.id" :product="product" :inWishlist="true" @toggle-wishlist="toggleItem" @add-to-cart="addToCart" />
          </div>
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { HeartIcon, UserIcon, ShoppingCartIcon, MapPinIcon, StarIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import ProductCard from '@/Components/Shared/UI/ProductCard.vue'
import { useWishlist } from '@/Composables/useWishlist'

const { items, toggleItem } = useWishlist()

const navLinks = [
  { label: 'Dashboard', url: '/account', icon: UserIcon },
  { label: 'Orders', url: '/account/orders', icon: ShoppingCartIcon },
  { label: 'Profile', url: '/account/profile', icon: UserIcon },
  { label: 'Addresses', url: '/account/addresses', icon: MapPinIcon },
  { label: 'Wishlist', url: '/account/wishlist', icon: HeartIcon },
]

const addToCart = () => {}
</script>

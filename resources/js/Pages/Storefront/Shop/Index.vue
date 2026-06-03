<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :crumbs="[{ label: 'Home', url: '/' }, { label: 'Shop' }]" />
      <div class="flex items-center justify-between mt-4 mb-6">
        <div>
          <h1 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white">Shop</h1>
          <p class="text-sm text-gray-500 mt-1">{{ filteredProducts.length }} products found</p>
        </div>
        <div class="flex items-center gap-3">
          <div class="hidden sm:flex glass rounded-xl p-1">
            <button :class="['p-2 rounded-lg transition-colors', viewMode === 'grid' ? 'bg-white dark:bg-gray-800 shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-800']" @click="viewMode = 'grid'">
              <Squares2X2Icon class="h-4 w-4" />
            </button>
            <button :class="['p-2 rounded-lg transition-colors', viewMode === 'list' ? 'bg-white dark:bg-gray-800 shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-800']" @click="viewMode = 'list'">
              <ListBulletIcon class="h-4 w-4" />
            </button>
          </div>
          <Select v-model="sortBy" :options="sortOptions" placeholder="Sort by" class="w-44" />
        </div>
      </div>

      <div class="flex gap-8">
        <aside class="hidden lg:block w-64 flex-shrink-0 space-y-6">
          <div class="glass-card p-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Categories</h3>
            <div class="space-y-1">
              <button v-for="cat in categories" :key="cat.value" :class="['w-full text-left px-3 py-2 rounded-lg text-sm transition-colors', activeCategory === cat.value ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800']" @click="activeCategory = cat.value">
                {{ cat.label }}
              </button>
            </div>
          </div>

          <div class="glass-card p-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Price Range</h3>
            <div class="space-y-2">
              <div v-for="range in priceRanges" :key="range.value" :class="['w-full text-left px-3 py-2 rounded-lg text-sm transition-colors cursor-pointer', activePrice === range.value ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800']" @click="activePrice = range.value">
                {{ range.label }}
              </div>
            </div>
          </div>

          <div class="glass-card p-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Rating</h3>
            <div class="space-y-2">
              <button v-for="r in [4, 3, 2, 1]" :key="r" :class="['w-full text-left px-3 py-2 rounded-lg text-sm flex items-center gap-1 transition-colors', activeRating === r ? 'bg-brand-50 dark:bg-brand-500/10' : 'hover:bg-gray-50 dark:hover:bg-gray-800']" @click="activeRating = r">
                <StarIcon v-for="i in r" :key="i" class="h-4 w-4 text-amber-400 fill-current" />
                <StarIcon v-for="i in (5 - r)" :key="'e' + i" class="h-4 w-4 text-gray-300" />
                <span class="text-gray-500 ml-1">& up</span>
              </button>
            </div>
          </div>
        </aside>

        <div class="flex-1">
          <div v-if="activeFilters.length" class="flex items-center gap-2 mb-4 flex-wrap">
            <span class="text-xs text-gray-500">Active filters:</span>
            <button v-for="filter in activeFilters" :key="filter" class="inline-flex items-center gap-1 px-3 py-1 glass rounded-full text-xs" @click="removeFilter(filter)">
              {{ filter }}
              <XMarkIcon class="h-3 w-3" />
            </button>
            <button class="text-xs text-brand-600 hover:underline" @click="clearFilters">Clear all</button>
          </div>

          <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" @quick-view="openQuickView" @add-to-cart="addToCart" @toggle-wishlist="toggleWishlist" />
          </div>

          <div v-else class="space-y-4">
            <div v-for="product in filteredProducts" :key="product.id" class="glass-card p-4 flex gap-4">
              <img :src="product.image || '/placeholder.jpg'" class="w-32 h-32 rounded-xl object-cover flex-shrink-0" />
              <div class="flex-1">
                <Link :href="`/products/${product.slug}`" class="text-lg font-semibold text-gray-900 dark:text-white hover:text-brand-600">{{ product.name }}</Link>
                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ product.short_description }}</p>
                <div class="flex items-center gap-2 mt-2">
                  <Rating :modelValue="product.rating" :reviewCount="product.reviews_count" />
                </div>
                <div class="flex items-center justify-between mt-3">
                  <Price :value="product.price" :original="product.original_price" size="lg" />
                  <Button size="sm" @click="addToCart(product)">Add to Cart</Button>
                </div>
              </div>
            </div>
          </div>

          <Pagination :currentPage="1" :totalPages="5" :total="125" :perPage="24" class="mt-8" />
        </div>
      </div>
    </div>

    <Modal v-model="quickViewOpen" :title="quickViewProduct?.name" size="lg">
      <div class="grid grid-cols-2 gap-6" v-if="quickViewProduct">
        <img :src="quickViewProduct.image" class="rounded-xl w-full" />
        <div>
          <Rating :modelValue="quickViewProduct.rating" />
          <Price :value="quickViewProduct.price" :original="quickViewProduct.original_price" size="2xl" class="mt-3 block" />
          <p class="text-sm text-gray-500 mt-3">{{ quickViewProduct.short_description }}</p>
          <QuantityInput v-model="quickViewQty" class="mt-4" />
          <Button block class="mt-4" @click="addToCart(quickViewProduct)">Add to Cart</Button>
        </div>
      </div>
    </Modal>
  </StorefrontLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Squares2X2Icon, ListBulletIcon, XMarkIcon, StarIcon } from '@heroicons/vue/20/solid'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import Select from '@/Components/Shared/UI/Select.vue'
import ProductCard from '@/Components/Shared/UI/ProductCard.vue'
import Price from '@/Components/Shared/UI/Price.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Rating from '@/Components/Shared/UI/Rating.vue'
import QuantityInput from '@/Components/Shared/UI/QuantityInput.vue'
import Modal from '@/Components/Shared/UI/Modal.vue'
import Pagination from '@/Components/Shared/UI/Pagination.vue'

const viewMode = ref('grid')
const sortBy = ref('newest')
const activeCategory = ref(null)
const activePrice = ref(null)
const activeRating = ref(null)

const quickViewOpen = ref(false)
const quickViewProduct = ref(null)
const quickViewQty = ref(1)

const sortOptions = [
  { value: 'newest', label: 'Newest' },
  { value: 'price-asc', label: 'Price: Low to High' },
  { value: 'price-desc', label: 'Price: High to Low' },
  { value: 'rating', label: 'Top Rated' },
]

const categories = [
  { value: 'electronics', label: 'Electronics' },
  { value: 'fashion', label: 'Fashion' },
  { value: 'home-garden', label: 'Home & Garden' },
  { value: 'sports', label: 'Sports' },
  { value: 'beauty', label: 'Beauty' },
]

const priceRanges = [
  { value: '0-50', label: 'Under $50' },
  { value: '50-100', label: '$50 - $100' },
  { value: '100-500', label: '$100 - $500' },
  { value: '500+', label: '$500+' },
]

const products = ref([
  { id: 1, name: 'Wireless Headphones Pro', slug: 'wireless-headphones-pro', price: 349.99, original_price: 449.99, image: '', short_description: 'Premium noise-cancelling wireless headphones.', rating: 4.8, reviews_count: 245 },
  { id: 2, name: 'Italian Leather Bag', slug: 'leather-bag', price: 899.99, image: '', short_description: 'Handcrafted Italian leather.', rating: 4.9, reviews_count: 128 },
  { id: 3, name: 'Smart Watch Ultra', slug: 'smart-watch', price: 599.99, image: '', short_description: 'Advanced fitness tracking.', rating: 4.7, reviews_count: 312 },
  { id: 4, name: 'Designer Sunglasses', slug: 'sunglasses', price: 299.99, image: '', short_description: 'UV protection with style.', rating: 4.5, reviews_count: 89 },
  { id: 5, name: 'Organic Skincare Set', slug: 'skincare-set', price: 149.99, image: '', short_description: 'Natural organic ingredients.', rating: 4.6, reviews_count: 178 },
  { id: 6, name: 'Premium Yoga Mat', slug: 'yoga-mat', price: 89.99, image: '', short_description: 'Extra thick eco-friendly mat.', rating: 4.4, reviews_count: 56 },
])

const filteredProducts = computed(() => products.value)

const activeFilters = computed(() => {
  const filters = []
  if (activeCategory.value) filters.push(activeCategory.value)
  if (activePrice.value) filters.push(activePrice.value)
  return filters
})

const removeFilter = (filter) => {}
const clearFilters = () => { activeCategory.value = null; activePrice.value = null; activeRating.value = null }
const openQuickView = (product) => { quickViewProduct.value = product; quickViewOpen.value = true }
const addToCart = (product) => { quickViewOpen.value = false }
const toggleWishlist = () => {}
</script>

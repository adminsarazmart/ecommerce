<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :crumbs="[{ label: 'Home', url: '/' }, { label: 'Electronics', url: '/shop?category=electronics' }, { label: product.name }]" />

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mt-6">
        <ImageGallery :images="product.images" />

        <div>
          <p class="text-sm text-brand-600 dark:text-brand-400 font-medium tracking-wider">{{ product.brand }}</p>
          <h1 class="text-2xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mt-1">{{ product.name }}</h1>

          <div class="flex items-center gap-3 mt-3">
            <Rating :modelValue="product.rating" :reviewCount="product.reviews_count" />
          </div>

          <div class="mt-4">
            <Price :value="selectedPrice" :original="product.original_price" size="3xl" />
            <p v-if="product.discount" class="text-sm text-emerald-600 dark:text-emerald-400 mt-1">You save {{ formatCurrency(product.original_price - product.price) }}</p>
          </div>

          <p class="text-sm text-gray-600 dark:text-gray-400 mt-4 leading-relaxed">{{ product.description }}</p>

          <div class="mt-6 space-y-4">
            <div v-for="group in product.variant_groups" :key="group.name">
              <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ group.label }}: <span class="text-brand-600">{{ selectedVariants[group.name] }}</span></p>
              <div class="flex flex-wrap gap-2">
                <button v-for="opt in group.options" :key="opt.value" @click="selectVariant(group.name, opt)" :class="['px-4 py-2 text-sm rounded-xl border-2 transition-all', selectedVariants[group.name] === opt.label ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10 text-brand-700' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 text-gray-600 dark:text-gray-400']">
                  <span v-if="opt.color" class="inline-block w-4 h-4 rounded-full mr-2 align-middle" :style="{ background: opt.color }" />
                  {{ opt.label }}
                </button>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-4 mt-6">
            <QuantityInput v-model="quantity" />
            <div class="flex items-center gap-1 text-sm">
              <div :class="['w-2 h-2 rounded-full', product.stock > 10 ? 'bg-green-500' : product.stock > 0 ? 'bg-amber-500' : 'bg-red-500']" />
              <span :class="product.stock > 10 ? 'text-green-600' : product.stock > 0 ? 'text-amber-600' : 'text-red-600'">
                {{ product.stock > 10 ? 'In Stock' : product.stock > 0 ? 'Low Stock' : 'Out of Stock' }}
              </span>
            </div>
          </div>

          <div class="flex items-center gap-3 mt-6">
            <Button size="lg" class="flex-1" :disabled="product.stock === 0" @click="addToCart">
              <ShoppingBagIcon class="h-5 w-5" />
              {{ product.stock === 0 ? 'Out of Stock' : 'Add to Cart' }}
            </Button>
            <WishlistButton :active="inWishlist" @toggle="toggleWishlist" />
            <CompareButton :active="inCompare" @toggle="toggleCompare" />
          </div>

          <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <SocialShare :url="currentUrl" :title="product.name" />
          </div>
        </div>
      </div>

      <div class="mt-12">
        <Tabs v-model="activeTab" :tabs="productTabs">
          <div v-if="activeTab === 'description'" class="prose dark:prose-invert max-w-none">
            <p>{{ product.long_description }}</p>
          </div>
          <div v-else-if="activeTab === 'reviews'" class="space-y-6">
            <div v-for="review in product.reviews" :key="review.id" class="glass-card p-4">
              <div class="flex items-start gap-3">
                <Avatar :name="review.author" size="sm" />
                <div>
                  <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ review.author }}</span>
                    <Rating :modelValue="review.rating" />
                  </div>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ review.text }}</p>
                </div>
              </div>
            </div>
          </div>
          <div v-else-if="activeTab === 'shipping'" class="text-sm text-gray-600 dark:text-gray-400">
            <p>Free shipping on orders over $100. Estimated delivery: 3-5 business days.</p>
          </div>
        </Tabs>
      </div>

      <section class="mt-16">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-heading font-bold text-gray-900 dark:text-white">Related Products</h2>
          <Link href="/shop" class="text-sm text-brand-600 hover:underline">View All</Link>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <ProductCard v-for="p in relatedProducts" :key="p.id" :product="p" @add-to-cart="addToCart" />
        </div>
      </section>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { ShoppingBagIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import ImageGallery from '@/Components/Shared/UI/ImageGallery.vue'
import Rating from '@/Components/Shared/UI/Rating.vue'
import Price from '@/Components/Shared/UI/Price.vue'
import QuantityInput from '@/Components/Shared/UI/QuantityInput.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import WishlistButton from '@/Components/Shared/UI/WishlistButton.vue'
import CompareButton from '@/Components/Shared/UI/CompareButton.vue'
import SocialShare from '@/Components/Shared/UI/SocialShare.vue'
import Tabs from '@/Components/Shared/UI/Tabs.vue'
import Avatar from '@/Components/Shared/UI/Avatar.vue'
import ProductCard from '@/Components/Shared/UI/ProductCard.vue'
import { useWishlist } from '@/Composables/useWishlist'
import { useCompare } from '@/Composables/useCompare'

const { isInWishlist, toggleItem: toggleWishlistItem } = useWishlist()
const { isInCompare, toggleItem: toggleCompareItem } = useCompare()

const quantity = ref(1)
const activeTab = ref('description')
const selectedVariants = ref({})

const currentUrl = computed(() => window.location.href)

const product = {
  id: 1, name: 'Premium Wireless Headphones Pro', slug: 'wireless-headphones-pro',
  brand: 'TechPro', price: 349.99, original_price: 449.99, discount: 22,
  rating: 4.8, reviews_count: 245, stock: 45,
  description: 'Experience premium sound quality with our flagship wireless headphones featuring active noise cancellation, 40-hour battery life, and ultra-comfortable memory foam ear cushions.',
  long_description: 'Immerse yourself in studio-quality sound...',
  images: [{ url: '/placeholder.jpg', thumbnail: '/placeholder.jpg', alt: 'Headphones' }],
  variant_groups: [
    { name: 'color', label: 'Color', options: [{ label: 'Midnight Black', value: 'black', color: '#000' }, { label: 'Silver', value: 'silver', color: '#c0c0c0' }] },
    { name: 'size', label: 'Size', options: [{ label: 'Standard', value: 'std' }, { label: 'Large', value: 'lrg' }] },
  ],
  reviews: [
    { id: 1, author: 'John D.', rating: 5, text: 'Best headphones I have ever owned. The sound quality is incredible.' },
    { id: 2, author: 'Sarah M.', rating: 4, text: 'Great battery life and comfortable for long sessions.' },
  ],
}

const selectedPrice = computed(() => product.price)

const inWishlist = computed(() => isInWishlist(product.id))
const inCompare = computed(() => isInCompare(product.id))

const selectVariant = (groupName, option) => { selectedVariants.value[groupName] = option.label }

const toggleWishlist = () => toggleWishlistItem(product)
const toggleCompare = () => toggleCompareItem(product)

const addToCart = () => {}

const productTabs = [
  { label: 'Description', value: 'description' },
  { label: 'Reviews (24)', value: 'reviews' },
  { label: 'Shipping', value: 'shipping' },
]

const relatedProducts = ref([
  { id: 2, name: 'Wireless Earbuds Pro', slug: 'earbuds-pro', price: 249.99, image: '', rating: 4.6, reviews_count: 189 },
  { id: 3, name: 'Bluetooth Speaker', slug: 'bluetooth-speaker', price: 179.99, image: '', rating: 4.5, reviews_count: 156 },
  { id: 4, name: 'USB-C Hub Premium', slug: 'usb-hub', price: 89.99, image: '', rating: 4.4, reviews_count: 98 },
])

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)
</script>

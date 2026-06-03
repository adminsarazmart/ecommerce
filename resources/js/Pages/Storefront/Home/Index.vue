<template>
  <StorefrontLayout>
    <section ref="heroRef" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-900 via-brand-950 to-gray-900">
      <div class="absolute inset-0">
        <div class="absolute top-20 left-20 w-72 h-72 bg-brand-500/20 rounded-full blur-3xl animate-float" />
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-float" style="animation-delay: 1s" />
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-brand-500/10 rounded-full blur-3xl" />
      </div>
      <div class="relative z-10 text-center px-4 max-w-5xl mx-auto">
        <p class="text-brand-400 font-medium text-sm tracking-widest uppercase mb-4 animate-fade-in-down">Premium Marketplace</p>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-heading font-bold text-white mb-6 leading-tight animate-fade-in-up">
          Discover <span class="gradient-text">Luxury</span><br />Beyond Compare
        </h1>
        <p class="text-lg md:text-xl text-gray-400 mb-10 max-w-2xl mx-auto animate-fade-in-up animate-delay-200">
          Explore curated collections from the world's finest vendors. Elevate your lifestyle with premium products.
        </p>
        <div class="flex items-center justify-center gap-4 animate-fade-in-up animate-delay-300">
          <Link href="/shop" class="btn-primary btn-xl text-lg group">
            Shop Now
            <ArrowRightIcon class="h-5 w-5 group-hover:translate-x-1 transition-transform" />
          </Link>
          <Link href="/collections" class="btn-secondary btn-xl text-lg">
            Explore Collections
          </Link>
        </div>
      </div>
      <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce-gentle">
        <ChevronDownIcon class="h-8 w-8 text-white/50" />
      </div>
    </section>

    <section class="py-20 px-4 bg-white dark:bg-gray-950">
      <div class="max-w-screen-2xl mx-auto">
        <div class="text-center mb-12">
          <p class="text-brand-600 dark:text-brand-400 text-sm font-semibold tracking-widest uppercase">Categories</p>
          <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mt-2">Shop by Category</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
          <Link v-for="cat in categories" :key="cat.name" :href="cat.url" class="glass-card group p-6 text-center hover:-translate-y-2">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
              <component :is="cat.icon" class="h-8 w-8 text-white" />
            </div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ cat.name }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ cat.count }} products</p>
          </Link>
        </div>
      </div>
    </section>

    <section ref="featuredRef" class="py-20 px-4 bg-gray-50 dark:bg-gray-900/50">
      <div class="max-w-screen-2xl mx-auto">
        <div class="flex items-center justify-between mb-10">
          <div>
            <p class="text-brand-600 dark:text-brand-400 text-sm font-semibold tracking-widest uppercase">Featured</p>
            <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mt-2">Trending Products</h2>
          </div>
          <Link href="/shop" class="text-sm text-brand-600 dark:text-brand-400 hover:underline font-medium">View All →</Link>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" @quick-view="openQuickView" @add-to-cart="addToCart" @toggle-wishlist="toggleWishlist" />
        </div>
      </div>
    </section>

    <section class="py-20 px-4 bg-gradient-to-br from-brand-600 to-purple-700 text-white">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl md:text-5xl font-heading font-bold mb-6">Up to 70% Off</h2>
        <p class="text-xl text-white/80 mb-8">Flash sale ends in</p>
        <div class="flex items-center justify-center gap-6 mb-10">
          <div v-for="unit in ['Hours', 'Minutes', 'Seconds']" :key="unit" class="text-center">
            <div class="text-4xl md:text-6xl font-bold font-mono">{{ countdown[unit.toLowerCase()] }}</div>
            <p class="text-sm text-white/60 mt-1">{{ unit }}</p>
          </div>
        </div>
        <Link href="/shop?deals" class="btn-xl bg-white text-brand-700 hover:bg-gray-100 shadow-2xl">Shop Deals</Link>
      </div>
    </section>

    <section class="py-20 px-4 bg-white dark:bg-gray-950">
      <div class="max-w-screen-2xl mx-auto">
        <div class="text-center mb-12">
          <p class="text-brand-600 dark:text-brand-400 text-sm font-semibold tracking-widest uppercase">Testimonials</p>
          <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mt-2">What Our Customers Say</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-for="testimonial in testimonials" :key="testimonial.name" class="glass-card p-6">
            <div class="flex items-center gap-1 mb-3">
              <StarIcon v-for="i in 5" :key="i" class="h-4 w-4 text-amber-400 fill-current" />
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">"{{ testimonial.text }}"</p>
            <div class="flex items-center gap-3">
              <Avatar :name="testimonial.name" size="sm" />
              <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ testimonial.name }}</p>
                <p class="text-xs text-gray-500">{{ testimonial.role }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 px-4 bg-gray-50 dark:bg-gray-900/50">
      <div class="max-w-screen-2xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 items-center opacity-50">
          <img v-for="brand in brands" :key="brand" :src="brand" :alt="brand" class="h-12 object-contain grayscale hover:grayscale-0 transition-all" />
        </div>
      </div>
    </section>

    <section class="py-20 px-4 bg-white dark:bg-gray-950">
      <div class="max-w-screen-2xl mx-auto">
        <div class="glass-card p-12 md:p-16 text-center max-w-3xl mx-auto">
          <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mb-2">Stay in the Loop</h2>
          <p class="text-gray-500 dark:text-gray-400 mb-8">Subscribe to get exclusive offers and early access to new products.</p>
          <div class="flex gap-3 max-w-md mx-auto">
            <input v-model="email" type="email" placeholder="Enter your email" class="glass-input flex-1" />
            <Button @click="subscribe">Subscribe</Button>
          </div>
        </div>
      </div>
    </section>

    <Modal v-model="quickViewOpen" :title="quickViewProduct?.name" size="lg">
      <div class="grid grid-cols-2 gap-6" v-if="quickViewProduct">
        <img :src="quickViewProduct.image || '/placeholder.jpg'" class="rounded-xl w-full" />
        <div>
          <Price :value="quickViewProduct.price" :original="quickViewProduct.original_price" size="2xl" />
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">{{ quickViewProduct.description }}</p>
          <div class="mt-6 space-y-3">
            <QuantityInput v-model="quickViewQty" />
            <Button block @click="addToCart(quickViewProduct)">Add to Cart</Button>
          </div>
        </div>
      </div>
    </Modal>
  </StorefrontLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowRightIcon, ChevronDownIcon, StarIcon } from '@heroicons/vue/20/solid'
import { ComputerDesktopIcon, DevicePhoneMobileIcon, HomeModernIcon, ShoppingBagIcon, SparklesIcon, TrophyIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import ProductCard from '@/Components/Shared/UI/ProductCard.vue'
import Price from '@/Components/Shared/UI/Price.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Avatar from '@/Components/Shared/UI/Avatar.vue'
import QuantityInput from '@/Components/Shared/UI/QuantityInput.vue'
import Modal from '@/Components/Shared/UI/Modal.vue'
import { useCart } from '@/Composables/useCart'
import { useWishlist } from '@/Composables/useWishlist'

const { addItem } = useCart()
const { toggleItem } = useWishlist()

const email = ref('')
const quickViewOpen = ref(false)
const quickViewProduct = ref(null)
const quickViewQty = ref(1)

const countdown = ref({ hours: 12, minutes: 48, seconds: 32 })

const categories = [
  { name: 'Electronics', count: 1250, url: '/shop?category=electronics', icon: ComputerDesktopIcon },
  { name: 'Fashion', count: 890, url: '/shop?category=fashion', icon: ShoppingBagIcon },
  { name: 'Home & Garden', count: 650, url: '/shop?category=home-garden', icon: HomeModernIcon },
  { name: 'Mobile', count: 420, url: '/shop?category=mobile', icon: DevicePhoneMobileIcon },
  { name: 'Premium', count: 180, url: '/shop?category=premium', icon: SparklesIcon },
  { name: 'Sports', count: 320, url: '/shop?category=sports', icon: TrophyIcon },
]

const featuredProducts = ref([
  { id: 1, name: 'Wireless Noise-Cancelling Headphones', slug: 'wireless-headphones', price: 349.99, original_price: 449.99, image: '', rating: 4.8, reviews_count: 245, brand: 'TechPro', discount: 22 },
  { id: 2, name: 'Italian Leather Briefcase', slug: 'leather-briefcase', price: 899.99, image: '', rating: 4.9, reviews_count: 128, brand: 'LuxCraft' },
  { id: 3, name: 'Smart Watch Ultra', slug: 'smart-watch-ultra', price: 599.99, original_price: 749.99, image: '', rating: 4.7, reviews_count: 312, brand: 'TechPro', discount: 20 },
  { id: 4, name: 'Organic Cotton Bed Set', slug: 'cotton-bed-set', price: 249.99, image: '', rating: 4.6, reviews_count: 89, brand: 'HomeGoods' },
])

const testimonials = [
  { name: 'Sarah Johnson', role: 'Verified Buyer', text: 'Absolutely stunning quality. The products exceeded my expectations and arrived in beautiful packaging.', rating: 5 },
  { name: 'Michael Chen', role: 'Premium Member', text: 'The marketplace offers an incredible selection of luxury items. Fast shipping and excellent customer service.', rating: 5 },
  { name: 'Emily Davis', role: 'Frequent Shopper', text: 'I love the variety of vendors. The platform makes it easy to discover new brands and products.', rating: 5 },
]

const brands = ['/brand1.svg', '/brand2.svg', '/brand3.svg', '/brand4.svg', '/brand5.svg', '/brand6.svg']

let timer
onMounted(() => {
  timer = setInterval(() => {
    if (countdown.value.seconds > 0) countdown.value.seconds--
    else if (countdown.value.minutes > 0) { countdown.value.minutes--; countdown.value.seconds = 59 }
    else if (countdown.value.hours > 0) { countdown.value.hours--; countdown.value.minutes = 59; countdown.value.seconds = 59 }
  }, 1000)
})
onUnmounted(() => clearInterval(timer))

const openQuickView = (product) => { quickViewProduct.value = product; quickViewOpen.value = true }
const addToCart = (product) => { addItem(product, quickViewOpen.value ? quickViewQty.value : 1); quickViewOpen.value = false }
const toggleWishlist = (product) => { toggleItem(product) }
const subscribe = () => { email.value = '' }
</script>

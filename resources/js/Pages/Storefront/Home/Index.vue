<template>
  <StorefrontLayout>
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 via-white to-indigo-50/30">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-32 left-16 w-96 h-96 bg-indigo-200/20 rounded-full blur-3xl" />
        <div class="absolute bottom-32 right-16 w-[500px] h-[500px] bg-purple-200/20 rounded-full blur-3xl" />
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-br from-indigo-100/10 to-transparent rounded-full blur-3xl" />
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiM5OTkiIGZpbGwtb3BhY2l0eT0iMC4wNCI+PGc+PHBhdGggZD0iTTM2IDM0djItSDI0di0yaDEyek0zNiAyNHYySDI0di0yaDEyeiIvPjwvZz48L2c+PC9nPjwvc3ZnPg==')] opacity-50" />
      </div>

      <div class="relative z-10 text-center px-4 max-w-5xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-50 border border-indigo-100 rounded-full text-xs font-semibold text-indigo-600 uppercase tracking-widest mb-8">
          <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse" />
          Premium Marketplace
        </div>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-heading font-bold text-gray-900 mb-6 leading-[1.1] tracking-tight">
          Discover <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">Luxury</span><br />
          <span class="text-gray-700">Beyond Compare</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-500 mb-10 max-w-2xl mx-auto leading-relaxed">
          Explore curated collections from the world's finest vendors. 
          Elevate your lifestyle with premium products crafted for distinction.
        </p>
        <div class="flex items-center justify-center gap-4 flex-wrap">
          <Link href="/shop" class="inline-flex items-center gap-2.5 px-8 py-3.5 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition-all shadow-lg shadow-gray-200 hover:shadow-xl hover:-translate-y-0.5">
            Shop Now
            <ArrowRightIcon class="h-4 w-4" />
          </Link>
          <Link href="/collections" class="inline-flex items-center gap-2 px-8 py-3.5 border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:border-gray-300 hover:bg-gray-50 transition-all">
            Explore Collections
          </Link>
        </div>
      </div>

      <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
        <ChevronDownIcon class="h-6 w-6 text-gray-300" />
      </div>
    </section>

    <section class="py-24 px-4 bg-white">
      <div class="max-w-screen-2xl mx-auto">
        <div class="text-center mb-16">
          <p class="text-indigo-600 text-sm font-semibold tracking-[0.2em] uppercase mb-3">Categories</p>
          <h2 class="text-4xl md:text-5xl font-heading font-bold text-gray-900">Shop by Category</h2>
          <p class="text-gray-500 mt-3 max-w-xl mx-auto">Explore our meticulously curated categories, each a gateway to exceptional quality.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
          <Link v-for="cat in categories" :key="cat.name" :href="cat.url" class="group relative p-8 bg-gray-50 rounded-2xl text-center hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 hover:-translate-y-1 transition-all duration-300">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-indigo-200 transition-all">
              <component :is="cat.icon" class="h-8 w-8 text-white" />
            </div>
            <h3 class="text-sm font-semibold text-gray-900">{{ cat.name }}</h3>
            <p class="text-xs text-gray-400 mt-1.5">{{ cat.count }} products</p>
          </Link>
        </div>
      </div>
    </section>

    <section ref="featuredRef" class="py-24 px-4 bg-gray-50/50">
      <div class="max-w-screen-2xl mx-auto">
        <div class="flex items-end justify-between mb-12">
          <div>
            <p class="text-indigo-600 text-sm font-semibold tracking-[0.2em] uppercase mb-3">Featured</p>
            <h2 class="text-4xl md:text-5xl font-heading font-bold text-gray-900">Trending Products</h2>
            <p class="text-gray-500 mt-3">Handpicked excellence, updated weekly.</p>
          </div>
          <Link href="/shop" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
            View All <ArrowRightIcon class="h-3.5 w-3.5" />
          </Link>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" @quick-view="openQuickView" @add-to-cart="addToCart" @toggle-wishlist="toggleWishlist" />
        </div>
        <div class="mt-8 text-center sm:hidden">
          <Link href="/shop" class="inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600">View All Products <ArrowRightIcon class="h-3.5 w-3.5" /></Link>
        </div>
      </div>
    </section>

    <section class="py-24 px-4 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white relative overflow-hidden">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-10 right-10 w-64 h-64 bg-white/5 rounded-full blur-3xl" />
        <div class="absolute bottom-10 left-10 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl" />
      </div>
      <div class="max-w-4xl mx-auto text-center relative z-10">
        <p class="text-indigo-200 text-sm font-semibold tracking-[0.2em] uppercase mb-4">Limited Time</p>
        <h2 class="text-4xl md:text-6xl font-heading font-bold mb-4">Up to 70% Off</h2>
        <p class="text-xl text-indigo-200/80 mb-10">Flash sale ends in</p>
        <div class="flex items-center justify-center gap-6 mb-10">
          <div v-for="unit in ['Hours', 'Minutes', 'Seconds']" :key="unit" class="text-center">
            <div class="text-4xl md:text-6xl font-bold font-mono tabular-nums">{{ countdown[unit.toLowerCase()] }}</div>
            <p class="text-sm text-indigo-200/60 mt-1.5 uppercase tracking-wider">{{ unit }}</p>
          </div>
        </div>
        <Link href="/shop?deals" class="inline-flex items-center gap-2 px-10 py-4 bg-white text-indigo-700 rounded-xl text-sm font-bold hover:bg-gray-50 hover:-translate-y-0.5 transition-all shadow-2xl">
          Shop Deals <ArrowRightIcon class="h-4 w-4" />
        </Link>
      </div>
    </section>

    <section class="py-24 px-4 bg-white">
      <div class="max-w-screen-2xl mx-auto">
        <div class="text-center mb-16">
          <p class="text-indigo-600 text-sm font-semibold tracking-[0.2em] uppercase mb-3">Testimonials</p>
          <h2 class="text-4xl md:text-5xl font-heading font-bold text-gray-900">What Our Customers Say</h2>
          <p class="text-gray-500 mt-3 max-w-xl mx-auto">Trusted by thousands of discerning customers worldwide.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-for="testimonial in testimonials" :key="testimonial.name" class="p-8 bg-gray-50 rounded-2xl border border-gray-100 hover:border-gray-200 hover:shadow-lg transition-all">
            <div class="flex items-center gap-1 mb-4">
              <StarIcon v-for="i in 5" :key="i" class="h-4 w-4 text-amber-400 fill-current" />
            </div>
            <p class="text-sm text-gray-600 mb-5 leading-relaxed">"{{ testimonial.text }}"</p>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">{{ testimonial.name.charAt(0) }}</div>
              <div>
                <p class="text-sm font-semibold text-gray-900">{{ testimonial.name }}</p>
                <p class="text-xs text-gray-400">{{ testimonial.role }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 px-4 bg-gray-50/50">
      <div class="max-w-screen-2xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 items-center opacity-40 grayscale">
          <img v-for="brand in brands" :key="brand" :src="brand" :alt="brand" class="h-10 object-contain hover:grayscale-0 hover:opacity-100 transition-all" />
        </div>
      </div>
    </section>

    <section class="py-24 px-4 bg-white">
      <div class="max-w-screen-2xl mx-auto">
        <div class="p-12 md:p-16 bg-gradient-to-br from-gray-50 to-indigo-50/30 rounded-3xl text-center max-w-3xl mx-auto border border-gray-100">
          <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 mb-3">Stay in the Loop</h2>
          <p class="text-gray-500 mb-8 max-w-md mx-auto">Subscribe to receive exclusive offers, early access to new products, and curated inspiration.</p>
          <div class="flex gap-3 max-w-md mx-auto">
            <input v-model="email" type="email" placeholder="Enter your email" class="flex-1 px-5 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-300 transition-all" />
            <Button @click="subscribe" class="flex-shrink-0">Subscribe</Button>
          </div>
        </div>
      </div>
    </section>

    <Modal v-model="quickViewOpen" :title="quickViewProduct?.name" size="lg">
      <div class="grid grid-cols-2 gap-6" v-if="quickViewProduct">
        <img :src="quickViewProduct.image || '/placeholder.jpg'" class="rounded-xl w-full" />
        <div>
          <Price :value="quickViewProduct.price" :original="quickViewProduct.original_price" size="2xl" />
          <p class="text-sm text-gray-500 mt-3 leading-relaxed">{{ quickViewProduct.description }}</p>
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
  { name: 'Sarah Johnson', role: 'Verified Buyer', text: 'Absolutely stunning quality. The products exceeded my expectations and arrived in beautiful packaging.' },
  { name: 'Michael Chen', role: 'Premium Member', text: 'The marketplace offers an incredible selection of luxury items. Fast shipping and excellent customer service.' },
  { name: 'Emily Davis', role: 'Frequent Shopper', text: 'I love the variety of vendors. The platform makes it easy to discover new brands and products.' },
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

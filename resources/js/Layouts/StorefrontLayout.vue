<template>
  <div class="min-h-screen bg-white">
    <Toast />
    <CookieConsent />

    <header ref="headerRef" class="sticky top-0 z-40 bg-white/95 backdrop-blur-xl border-b border-gray-100 transition-all duration-300" :class="{ 'shadow-sm': scrolled }">
      <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
          <div class="flex items-center gap-6 lg:gap-10">
            <button class="lg:hidden p-2 -ml-2 rounded-xl hover:bg-gray-50" @click="mobileOpen = true">
              <Bars3Icon class="h-6 w-6 text-gray-700" />
            </button>
            <Link href="/" class="flex items-center gap-2.5 group">
              <div class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-sm tracking-tight group-hover:shadow-lg group-hover:shadow-indigo-200 transition-shadow">M</div>
              <span class="font-heading font-bold text-xl text-gray-900 hidden sm:inline">Nexus<span class="text-indigo-600">Mart</span></span>
            </Link>
            <nav class="hidden lg:flex items-center gap-1">
              <div v-for="item in headerMenu" :key="item.label" class="relative" @mouseenter="item.children && (activeMega = item.label)" @mouseleave="activeMega = null">
                <Link :href="item.url" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all">
                  {{ item.label }}
                </Link>
                <MegaMenu v-if="item.children" :open="activeMega === item.label" :columns="item.children" @mouseenter="activeMega = item.label" @mouseleave="activeMega = null" />
              </div>
            </nav>
          </div>

          <div class="flex items-center gap-2">
            <button @click="searchOpen = true" class="hidden sm:flex items-center gap-2.5 bg-gray-50 border border-gray-200 px-4 py-2 rounded-xl text-sm text-gray-400 hover:text-gray-600 hover:border-gray-300 transition-all">
              <MagnifyingGlassIcon class="h-4 w-4" />
              <span>Search products...</span>
              <kbd class="hidden lg:inline px-1.5 py-0.5 text-xs bg-white border border-gray-200 rounded-md">/</kbd>
            </button>
            <button class="sm:hidden p-2 rounded-xl hover:bg-gray-50" @click="searchOpen = true">
              <MagnifyingGlassIcon class="h-5 w-5 text-gray-600" />
            </button>

            <div class="hidden sm:flex items-center gap-1">
              <Dropdown label="" button-class="p-2 rounded-xl hover:bg-gray-50">
                <template #button>
                  <LanguageIcon class="h-5 w-5 text-gray-600" />
                </template>
                <MenuItem v-for="lang in languages" :key="lang.code" v-slot="{ active }">
                  <button :class="['block w-full text-left px-4 py-2 text-sm', active ? 'bg-gray-50' : '']">{{ lang.label }}</button>
                </MenuItem>
              </Dropdown>
              <Dropdown label="" button-class="p-2 rounded-xl hover:bg-gray-50">
                <template #button>
                  <span class="text-sm font-medium text-gray-700 px-1">{{ currency }}</span>
                </template>
                <MenuItem v-for="cur in currencies" :key="cur" v-slot="{ active }">
                  <button :class="['block w-full text-left px-4 py-2 text-sm', active ? 'bg-gray-50' : '']">{{ cur }}</button>
                </MenuItem>
              </Dropdown>
            </div>

            <div class="flex items-center gap-1">
              <Link href="/compare" class="relative p-2 rounded-xl hover:bg-gray-50">
                <ArrowsRightLeftIcon class="h-5 w-5 text-gray-600" />
                <span v-if="compareCount" class="absolute -top-0.5 -right-0.5 h-4 w-4 bg-indigo-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ compareCount }}</span>
              </Link>
              <Link href="/wishlist" class="relative p-2 rounded-xl hover:bg-gray-50">
                <HeartIcon class="h-5 w-5 text-gray-600" />
                <span v-if="wishlistCount" class="absolute -top-0.5 -right-0.5 h-4 w-4 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ wishlistCount }}</span>
              </Link>
              <div class="relative">
                <button @click="cartOpen = !cartOpen" class="relative p-2 rounded-xl hover:bg-gray-50">
                  <ShoppingBagIcon class="h-5 w-5 text-gray-600" />
                  <span v-if="cartTotal" class="absolute -top-0.5 -right-0.5 h-4 w-4 bg-indigo-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ cartTotal }}</span>
                </button>
                <MiniCart :open="cartOpen" @close="cartOpen = false" />
              </div>
              <Link :href="user ? '/account' : '/login'" class="p-2 rounded-xl hover:bg-gray-50">
                <UserIcon class="h-5 w-5 text-gray-600" />
              </Link>
            </div>
          </div>
        </div>
      </div>
    </header>

    <main>
      <slot />
    </main>

    <footer class="bg-gray-900">
      <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-16">
          <div class="space-y-5">
            <Link href="/" class="flex items-center gap-2.5">
              <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-sm">M</div>
              <span class="font-heading font-bold text-xl text-white">Nexus<span class="text-indigo-400">Mart</span></span>
            </Link>
            <p class="text-sm text-gray-400 leading-relaxed">Curating the world's finest products for those who appreciate exceptional quality and timeless design.</p>
            <div class="flex gap-3">
              <a v-for="social in socialLinks" :key="social.label" :href="social.url" class="w-9 h-9 bg-gray-800 rounded-xl flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition-all" :title="social.label">
                <component :is="social.icon" class="h-4 w-4" />
              </a>
            </div>
          </div>
          <div v-for="col in footer?.columns || footerColumns" :key="col.title" class="space-y-4">
            <h4 class="font-heading font-semibold text-white text-sm uppercase tracking-widest">{{ col.title }}</h4>
            <ul v-if="col.links" class="space-y-3">
              <li v-for="link in col.links" :key="link.label">
                <Link :href="link.url" class="text-sm text-gray-400 hover:text-white transition-colors">{{ link.label }}</Link>
              </li>
            </ul>
            <p v-if="col.text" class="text-sm text-gray-400 leading-relaxed">{{ col.text }}</p>
          </div>
        </div>
        <div class="mt-12 pt-8 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
          <p>&copy; {{ new Date().getFullYear() }} NexusMart. All rights reserved.</p>
          <div class="flex gap-6">
            <Link href="/privacy" class="hover:text-gray-300 transition-colors">Privacy</Link>
            <Link href="/terms" class="hover:text-gray-300 transition-colors">Terms</Link>
            <Link href="/contact" class="hover:text-gray-300 transition-colors">Contact</Link>
          </div>
        </div>
      </div>
    </footer>

    <button v-if="showBackToTop" @click="scrollToTop" class="fixed bottom-6 right-6 z-40 p-3.5 bg-white border border-gray-200 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
      <ArrowUpIcon class="h-5 w-5 text-gray-700" />
    </button>

    <MobileMenu :open="mobileOpen" @close="mobileOpen = false">
      <div v-for="item in headerMenu" :key="item.label" class="space-y-1">
        <Link :href="item.url" class="block px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl" @click="mobileOpen = false">{{ item.label }}</Link>
        <div v-if="item.children" class="ml-4 space-y-0.5">
          <Link v-for="child in flattenChildren(item.children)" :key="child.label" :href="child.url" class="block px-4 py-2 text-sm text-gray-500 hover:bg-gray-50 rounded-xl pl-10" @click="mobileOpen = false">{{ child.label }}</Link>
        </div>
      </div>
      <div class="border-t border-gray-100 pt-4 mt-4 space-y-2 px-4">
        <Link :href="user ? '/account' : '/login'" class="block w-full text-center py-3 bg-gray-900 text-white rounded-xl text-sm font-medium hover:bg-gray-800 transition-colors">{{ user ? 'My Account' : 'Sign In' }}</Link>
      </div>
    </MobileMenu>

    <SearchOverlay :open="searchOpen" @close="searchOpen = false" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { MenuItem } from '@headlessui/vue'
import {
  Bars3Icon, MagnifyingGlassIcon, ShoppingBagIcon, HeartIcon,
  UserIcon, ArrowsRightLeftIcon, LanguageIcon, ArrowUpIcon,
} from '@heroicons/vue/24/outline'
import { useCartStore } from '@/Stores/cart'
import { useWishlistStore } from '@/Stores/wishlist'
import { useCompareStore } from '@/Stores/compare'
import Toast from '@/Components/Shared/UI/Toast.vue'
import CookieConsent from '@/Components/Shared/UI/CookieConsent.vue'
import AnnouncementBar from '@/Components/Shared/Layout/AnnouncementBar.vue'
import MobileMenu from '@/Components/Shared/Layout/MobileMenu.vue'
import SearchOverlay from '@/Components/Shared/Layout/SearchOverlay.vue'
import MegaMenu from '@/Components/Shared/UI/MegaMenu.vue'
import MiniCart from '@/Components/Shared/UI/MiniCart.vue'
import Dropdown from '@/Components/Shared/UI/Dropdown.vue'

const page = usePage()
const storeName = computed(() => page.props?.settings?.site_name || 'NexusMart')
const announcement = computed(() => page.props?.settings?.announcement || {})
const headerMenu = computed(() => page.props?.header_menu || [])
const footer = computed(() => page.props?.footer || { columns: [] })
const user = computed(() => page.props?.user)

const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const compareStore = useCompareStore()

const cartTotal = computed(() => cartStore.totalItems)
const wishlistCount = computed(() => wishlistStore.count)
const compareCount = computed(() => compareStore.count)

const scrolled = ref(false)
const showBackToTop = ref(false)
const mobileOpen = ref(false)
const searchOpen = ref(false)
const cartOpen = ref(false)
const activeMega = ref(null)

const currencies = ['USD', 'EUR', 'GBP']
const currency = ref('USD')
const languages = ref([{ code: 'en', label: 'English' }, { code: 'es', label: 'Spanish' }, { code: 'fr', label: 'French' }])

const socialLinks = [
  { label: 'Instagram', icon: 'InstagramIcon', url: '#' },
  { label: 'Twitter', icon: 'TwitterIcon', url: '#' },
  { label: 'Pinterest', icon: 'PinterestIcon', url: '#' },
]

const footerColumns = [
  {
    title: 'Shop',
    links: [
      { label: 'Electronics', url: '/shop?category=electronics' },
      { label: 'Fashion', url: '/shop?category=fashion' },
      { label: 'Home & Garden', url: '/shop?category=home-garden' },
      { label: 'Premium Collection', url: '/shop?category=premium' },
    ],
  },
  {
    title: 'Support',
    links: [
      { label: 'Help Center', url: '/help' },
      { label: 'Shipping Info', url: '/shipping' },
      { label: 'Returns', url: '/returns' },
      { label: 'Contact Us', url: '/contact' },
    ],
  },
  {
    title: 'Company',
    text: 'Founded in 2024, NexusMart is the premier destination for luxury and premium products, serving discerning customers worldwide.',
  },
]

const flattenChildren = (children) => {
  const items = []
  children.forEach(col => { if (col.links) items.push(...col.links) })
  return items
}

const scrollToTop = () => { window.scrollTo({ top: 0, behavior: 'smooth' }) }

let scrollHandler
onMounted(() => {
  scrollHandler = () => {
    scrolled.value = window.scrollY > 10
    showBackToTop.value = window.scrollY > 500
  }
  window.addEventListener('scroll', scrollHandler)
})
onUnmounted(() => window.removeEventListener('scroll', scrollHandler))
</script>

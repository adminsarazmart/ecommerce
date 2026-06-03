<template>
  <div class="min-h-screen bg-white dark:bg-gray-950">
    <Toast />
    <CookieConsent />

    <AnnouncementBar v-if="announcement?.enabled" :text="announcement.text" :link="announcement.link" />

    <header ref="headerRef" class="sticky top-0 z-40 bg-white/80 dark:bg-gray-950/80 backdrop-blur-xl border-b border-gray-100 dark:border-gray-800 transition-all duration-300" :class="{ 'shadow-sm': scrolled }">
      <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
          <div class="flex items-center gap-6 lg:gap-10">
            <button class="lg:hidden p-2 -ml-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800" @click="mobileOpen = true">
              <Bars3Icon class="h-6 w-6 text-gray-700 dark:text-gray-300" />
            </button>
            <Link href="/" class="flex items-center gap-2">
              <div class="w-9 h-9 bg-gradient-to-br from-brand-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold">M</div>
              <span class="font-heading font-bold text-xl text-gray-900 dark:text-white hidden sm:inline">{{ storeName }}</span>
            </Link>
            <nav class="hidden lg:flex items-center gap-1">
              <div v-for="item in headerMenu" :key="item.label" class="relative" @mouseenter="item.children && (activeMega = item.label)" @mouseleave="activeMega = null">
                <Link :href="item.url" :class="['nav-link px-3 py-2 rounded-lg']">
                  {{ item.label }}
                </Link>
                <MegaMenu v-if="item.children" :open="activeMega === item.label" :columns="item.children" @mouseenter="activeMega = item.label" @mouseleave="activeMega = null" />
              </div>
            </nav>
          </div>

          <div class="flex items-center gap-2">
            <button @click="searchOpen = true" class="hidden sm:flex items-center gap-2 glass px-4 py-2 rounded-xl text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
              <MagnifyingGlassIcon class="h-4 w-4" />
              <span>Search products...</span>
              <kbd class="hidden lg:inline px-1.5 py-0.5 text-xs bg-gray-100 dark:bg-gray-800 rounded">/</kbd>
            </button>
            <button class="sm:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800" @click="searchOpen = true">
              <MagnifyingGlassIcon class="h-5 w-5 text-gray-600 dark:text-gray-400" />
            </button>

            <ThemeToggle variant="ghost" />

            <div class="hidden sm:flex items-center gap-1">
              <Dropdown label="" button-class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                <template #button>
                  <LanguageIcon class="h-5 w-5 text-gray-600 dark:text-gray-400" />
                </template>
                <MenuItem v-for="lang in languages" :key="lang.code" v-slot="{ active }">
                  <button :class="['block w-full text-left px-4 py-2 text-sm', active ? 'bg-gray-50 dark:bg-gray-800' : '']">{{ lang.label }}</button>
                </MenuItem>
              </Dropdown>
              <Dropdown label="" button-class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                <template #button>
                  <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ currency }}</span>
                </template>
                <MenuItem v-for="cur in currencies" :key="cur" v-slot="{ active }">
                  <button :class="['block w-full text-left px-4 py-2 text-sm', active ? 'bg-gray-50 dark:bg-gray-800' : '']">{{ cur }}</button>
                </MenuItem>
              </Dropdown>
            </div>

            <div class="flex items-center gap-1">
              <Link href="/compare" class="relative p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                <ArrowsRightLeftIcon class="h-5 w-5 text-gray-600 dark:text-gray-400" />
                <span v-if="compareCount" class="absolute -top-0.5 -right-0.5 h-4 w-4 bg-brand-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ compareCount }}</span>
              </Link>
              <Link href="/wishlist" class="relative p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                <HeartIcon class="h-5 w-5 text-gray-600 dark:text-gray-400" />
                <span v-if="wishlistCount" class="absolute -top-0.5 -right-0.5 h-4 w-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ wishlistCount }}</span>
              </Link>
              <div class="relative">
                <button @click="cartOpen = !cartOpen" class="relative p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                  <ShoppingBagIcon class="h-5 w-5 text-gray-600 dark:text-gray-400" />
                  <span v-if="cartTotal" class="absolute -top-0.5 -right-0.5 h-4 w-4 bg-brand-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ cartTotal }}</span>
                </button>
                <MiniCart :open="cartOpen" @close="cartOpen = false" />
              </div>
              <Link :href="user ? '/account' : '/login'" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                <UserIcon class="h-5 w-5 text-gray-600 dark:text-gray-400" />
              </Link>
            </div>
          </div>
        </div>
      </div>
    </header>

    <main>
      <slot />
    </main>

    <footer class="bg-gray-900 dark:bg-black text-gray-300">
      <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
          <div v-for="col in footer?.columns" :key="col.title" class="space-y-4">
            <h4 class="font-heading font-semibold text-white text-lg">{{ col.title }}</h4>
            <ul v-if="col.type === 'menu'" class="space-y-2">
              <li v-for="link in col.links" :key="link.label">
                <Link :href="link.url" class="text-sm hover:text-white transition-colors">{{ link.label }}</Link>
              </li>
            </ul>
            <div v-else-if="col.type === 'newsletter'" class="space-y-3">
              <p class="text-sm">{{ col.description }}</p>
              <div class="flex gap-2">
                <input v-model="newsletterEmail" type="email" placeholder="Your email" class="flex-1 bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500/50" />
                <button class="btn-primary btn-sm" @click="subscribeNewsletter">Subscribe</button>
              </div>
            </div>
            <div v-else-if="col.type === 'social'" class="flex gap-3">
              <a v-for="social in col.links" :key="social.label" :href="social.url" class="p-2 bg-white/10 rounded-xl hover:bg-white/20 transition-colors" :title="social.label">
                <component :is="social.icon" class="h-5 w-5" />
              </a>
            </div>
            <p v-else-if="col.type === 'text'" class="text-sm leading-relaxed">{{ col.content }}</p>
          </div>
        </div>
        <div class="mt-10 pt-8 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
          <p>&copy; {{ new Date().getFullYear() }} {{ storeName }}. All rights reserved.</p>
          <div class="flex gap-4">
            <Link href="/privacy" class="hover:text-gray-300">Privacy</Link>
            <Link href="/terms" class="hover:text-gray-300">Terms</Link>
            <Link href="/contact" class="hover:text-gray-300">Contact</Link>
          </div>
        </div>
      </div>
    </footer>

    <button v-if="showBackToTop" @click="scrollToTop" class="fixed bottom-6 right-6 z-40 p-3 glass rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
      <ArrowUpIcon class="h-5 w-5 text-gray-700 dark:text-gray-300" />
    </button>

    <MobileMenu :open="mobileOpen" @close="mobileOpen = false">
      <div v-for="item in headerMenu" :key="item.label" class="space-y-1">
        <Link :href="item.url" class="block sidebar-item sidebar-item-inactive" @click="mobileOpen = false">{{ item.label }}</Link>
        <div v-if="item.children" class="ml-4 space-y-0.5">
          <Link v-for="child in flattenChildren(item.children)" :key="child.label" :href="child.url" class="block sidebar-item sidebar-item-inactive pl-10 text-sm" @click="mobileOpen = false">{{ child.label }}</Link>
        </div>
      </div>
      <div class="border-t border-gray-100 dark:border-gray-800 pt-4 mt-4 space-y-2">
        <Link :href="user ? '/account' : '/login'" class="block w-full text-center btn-primary">{{ user ? 'My Account' : 'Sign In' }}</Link>
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
  XMarkIcon,
} from '@heroicons/vue/24/outline'
import { useCartStore } from '@/Stores/cart'
import { useWishlistStore } from '@/Stores/wishlist'
import { useCompareStore } from '@/Stores/compare'
import { useAppStore } from '@/Stores/app'
import Toast from '@/Components/Shared/UI/Toast.vue'
import CookieConsent from '@/Components/Shared/UI/CookieConsent.vue'
import ThemeToggle from '@/Components/Shared/Layout/ThemeToggle.vue'
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
const newsletterEmail = ref('')

const currencies = ['USD', 'EUR', 'GBP']
const currency = ref('USD')
const languages = ref([{ code: 'en', label: 'English' }, { code: 'es', label: 'Spanish' }, { code: 'fr', label: 'French' }])

const flattenChildren = (children) => {
  const items = []
  children.forEach(col => { if (col.links) items.push(...col.links) })
  return items
}

const subscribeNewsletter = () => { newsletterEmail.value = '' }

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

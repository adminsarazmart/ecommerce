<template>
  <Transition name="mini-cart">
    <div v-if="open" class="absolute top-full right-0 z-50 mt-2 w-96">
      <div class="glass rounded-2xl shadow-2xl p-4 max-h-[80vh] flex flex-col">
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Cart ({{ totalItems }})</h3>
          <button @click="$emit('close')" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
            <XMarkIcon class="h-4 w-4 text-gray-500" />
          </button>
        </div>
        <div v-if="items.length === 0" class="py-8 text-center">
          <ShoppingBagIcon class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600 mb-2" />
          <p class="text-sm text-gray-400">Your cart is empty</p>
        </div>
        <div v-else class="flex-1 overflow-y-auto space-y-3 mb-3">
          <div v-for="item in items" :key="item.id" class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50">
            <img :src="item.image || '/placeholder.jpg'" :alt="item.name" class="w-14 h-14 rounded-lg object-cover flex-shrink-0" />
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ item.name }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">Qty: {{ item.quantity }}</p>
              <Price :value="item.price * item.quantity" size="sm" />
            </div>
            <button @click="removeItem(item.id)" class="p-1 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
              <TrashIcon class="h-4 w-4 text-red-500" />
            </button>
          </div>
        </div>
        <div v-if="items.length > 0" class="border-t border-gray-100 dark:border-gray-800 pt-3 space-y-3">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Subtotal</span>
            <Price :value="subtotal" size="sm" />
          </div>
          <Link href="/cart" class="btn-primary w-full text-center text-sm">
            View Cart
          </Link>
          <Link href="/checkout" class="btn-secondary w-full text-center text-sm">
            Checkout
          </Link>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { XMarkIcon, ShoppingBagIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { useCartStore } from '@/Stores/cart'
import Price from './Price.vue'

const props = defineProps({ open: Boolean })
defineEmits(['close'])

const store = useCartStore()
const items = computed(() => store.items)
const totalItems = computed(() => store.totalItems)
const subtotal = computed(() => store.subtotal)
const removeItem = (id) => store.removeItem(id)
</script>

<style scoped>
.mini-cart-enter-active { transition: all 0.2s ease-out; }
.mini-cart-leave-active { transition: all 0.15s ease-in; }
.mini-cart-enter-from, .mini-cart-leave-to { opacity: 0; transform: translateY(-8px) scale(0.96); }
</style>

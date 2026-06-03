<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :crumbs="[{ label: 'Home', url: '/' }, { label: 'Shopping Cart' }]" />
      <h1 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mt-4 mb-8">Shopping Cart</h1>

      <div v-if="items.length === 0" class="text-center py-20">
        <ShoppingBagIcon class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600 mb-4" />
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Your cart is empty</h2>
        <p class="text-gray-500 mb-6">Looks like you haven't added anything yet.</p>
        <Link href="/shop" class="btn-primary">Continue Shopping</Link>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
          <div v-for="item in items" :key="item.id" class="glass-card p-4 flex items-center gap-4">
            <img :src="item.image || '/placeholder.jpg'" class="w-20 h-20 rounded-xl object-cover flex-shrink-0" />
            <div class="flex-1 min-w-0">
              <Link :href="`/products/${item.slug}`" class="text-sm font-semibold text-gray-900 dark:text-white hover:text-brand-600">{{ item.name }}</Link>
              <p v-if="item.variant_label" class="text-xs text-gray-500">{{ item.variant_label }}</p>
              <Price :value="item.price" size="sm" />
            </div>
            <QuantityInput v-model="item.quantity" :max="99" />
            <div class="text-right">
              <Price :value="item.price * item.quantity" size="lg" />
              <button @click="removeItem(item.id)" class="block text-xs text-red-500 hover:text-red-400 mt-1">Remove</button>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div class="glass-card p-5">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Summary</h3>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>{{ formatCurrency(subtotal) }}</span></div>
              <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span class="text-emerald-600">Free</span></div>
              <div class="flex justify-between"><span class="text-gray-500">Tax</span><span>{{ formatCurrency(tax) }}</span></div>
              <div v-if="discount" class="flex justify-between text-emerald-600"><span>Discount</span><span>-{{ formatCurrency(discount) }}</span></div>
              <div class="flex justify-between font-semibold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-700">
                <span>Total</span><span>{{ formatCurrency(total) }}</span>
              </div>
            </div>
            <Link href="/checkout" class="btn-primary w-full text-center mt-4">Proceed to Checkout</Link>
          </div>

          <div class="glass-card p-4">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Coupon Code</h4>
            <div class="flex gap-2">
              <input v-model="couponCode" type="text" placeholder="Enter code" class="glass-input flex-1 text-sm" />
              <Button variant="secondary" size="sm" @click="applyCoupon">Apply</Button>
            </div>
          </div>

          <div class="glass-card p-4">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">You May Also Like</h4>
            <div class="space-y-3">
              <div v-for="p in crossSells" :key="p.id" class="flex items-center gap-3">
                <img :src="p.image || '/placeholder.jpg'" class="w-12 h-12 rounded-lg object-cover" />
                <div class="flex-1 min-w-0">
                  <p class="text-sm text-gray-900 dark:text-white truncate">{{ p.name }}</p>
                  <Price :value="p.price" size="xs" />
                </div>
                <Button size="xs" variant="ghost" @click="addItem(p)">+ Add</Button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ShoppingBagIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import Price from '@/Components/Shared/UI/Price.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import QuantityInput from '@/Components/Shared/UI/QuantityInput.vue'
import { useCart } from '@/Composables/useCart'

const { items, subtotal, tax, shipping, discount, total, addItem, removeItem, applyCoupon: applyCouponAction } = useCart()
const couponCode = ref('')

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)
const applyCoupon = () => { if (couponCode.value) applyCouponAction(couponCode.value); couponCode.value = '' }

const crossSells = ref([
  { id: 1, name: 'Phone Case', price: 29.99, slug: 'phone-case', image: '' },
  { id: 2, name: 'Screen Protector', price: 14.99, slug: 'screen-protector', image: '' },
])
</script>

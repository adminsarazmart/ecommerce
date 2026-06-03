<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :crumbs="[{ label: 'Home', url: '/' }, { label: 'Cart', url: '/cart' }, { label: 'Checkout' }]" />
      <h1 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mt-4 mb-8">Checkout</h1>

      <Stepper v-model="currentStep" :steps="steps" class="mb-8" />

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
          <Transition name="fade" mode="out-in">
            <div v-if="currentStep === 0" key="shipping" class="glass-card p-6">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Shipping Address</h2>
              <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                  <Input v-model="form.first_name" label="First Name" />
                  <Input v-model="form.last_name" label="Last Name" />
                </div>
                <Input v-model="form.address" label="Address" />
                <div class="grid grid-cols-2 gap-4">
                  <Input v-model="form.city" label="City" />
                  <Input v-model="form.zip" label="ZIP Code" />
                </div>
                <Select v-model="form.country" :options="countries" placeholder="Country" />
              </div>
              <div class="flex justify-end mt-6">
                <Button @click="currentStep = 1">Continue to Shipping Method</Button>
              </div>
            </div>

            <div v-else-if="currentStep === 1" key="shipping-method" class="glass-card p-6">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Shipping Method</h2>
              <div class="space-y-3">
                <label v-for="method in shippingMethods" :key="method.value" class="flex items-center justify-between p-4 glass rounded-xl cursor-pointer hover:bg-white/30" :class="{ 'border-2 border-brand-500': form.shipping_method === method.value }">
                  <div class="flex items-center gap-3">
                    <input v-model="form.shipping_method" :value="method.value" type="radio" class="text-brand-500" />
                    <div>
                      <p class="text-sm font-medium text-gray-900 dark:text-white">{{ method.label }}</p>
                      <p class="text-xs text-gray-500">{{ method.eta }}</p>
                    </div>
                  </div>
                  <span class="text-sm font-semibold">{{ method.price === 0 ? 'Free' : formatCurrency(method.price) }}</span>
                </label>
              </div>
              <div class="flex justify-between mt-6">
                <Button variant="ghost" @click="currentStep = 0">Back</Button>
                <Button @click="currentStep = 2">Continue to Payment</Button>
              </div>
            </div>

            <div v-else-if="currentStep === 2" key="payment" class="glass-card p-6">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Payment Method</h2>
              <div class="space-y-3">
                <label v-for="method in paymentMethods" :key="method.value" class="flex items-center gap-3 p-4 glass rounded-xl cursor-pointer hover:bg-white/30" :class="{ 'border-2 border-brand-500': form.payment_method === method.value }">
                  <input v-model="form.payment_method" :value="method.value" type="radio" class="text-brand-500" />
                  <component :is="method.icon" class="h-6 w-6 text-gray-600 dark:text-gray-400" />
                  <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ method.label }}</p>
                    <p class="text-xs text-gray-500">{{ method.description }}</p>
                  </div>
                </label>
              </div>
              <div class="flex justify-between mt-6">
                <Button variant="ghost" @click="currentStep = 1">Back</Button>
                <Button @click="currentStep = 3">Review Order</Button>
              </div>
            </div>

            <div v-else-if="currentStep === 3" key="review" class="glass-card p-6">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Review Your Order</h2>
              <div class="space-y-4">
                <div class="space-y-2">
                  <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Shipping To</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ form.first_name }} {{ form.last_name }}, {{ form.address }}, {{ form.city }}</p>
                </div>
                <div class="space-y-2">
                  <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Items</p>
                  <div v-for="item in items" :key="item.id" class="flex items-center gap-3 text-sm">
                    <span class="text-gray-500">{{ item.quantity }}x</span>
                    <span class="text-gray-900 dark:text-white">{{ item.name }}</span>
                    <span class="ml-auto">{{ formatCurrency(item.price * item.quantity) }}</span>
                  </div>
                </div>
              </div>
              <div class="flex justify-between mt-6">
                <Button variant="ghost" @click="currentStep = 2">Back</Button>
                <Button @click="placeOrder" :loading="placing">
                  <CubeTransparentIcon class="h-5 w-5 animate-spin" v-if="placing" />
                  Place Order
                </Button>
              </div>
            </div>
          </Transition>
        </div>

        <div class="lg:col-span-1">
          <div class="glass-card p-5 sticky top-24">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Summary</h3>
            <div class="space-y-3 text-sm">
              <div v-for="item in items" :key="item.id" class="flex items-center gap-3">
                <img :src="item.image || '/placeholder.jpg'" class="w-12 h-12 rounded-lg object-cover" />
                <div class="flex-1 min-w-0">
                  <p class="text-sm text-gray-900 dark:text-white truncate">{{ item.name }}</p>
                  <p class="text-xs text-gray-500">Qty: {{ item.quantity }}</p>
                </div>
                <span class="text-sm font-medium">{{ formatCurrency(item.price * item.quantity) }}</span>
              </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2 text-sm">
              <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>{{ formatCurrency(subtotal) }}</span></div>
              <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span class="text-emerald-600">Free</span></div>
              <div class="flex justify-between"><span class="text-gray-500">Tax</span><span>{{ formatCurrency(tax) }}</span></div>
              <div class="flex justify-between font-semibold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-700">
                <span>Total</span><span>{{ formatCurrency(total) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { CreditCardIcon, BanknotesIcon, DevicePhoneMobileIcon, CubeTransparentIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import Stepper from '@/Components/Shared/UI/Stepper.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Select from '@/Components/Shared/UI/Select.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import { useCart } from '@/Composables/useCart'

const { items, subtotal, tax, shipping, discount, total, clearCart } = useCart()

const currentStep = ref(0)
const placing = ref(false)

const steps = [
  { label: 'Shipping' },
  { label: 'Method' },
  { label: 'Payment' },
  { label: 'Review' },
]

const form = reactive({
  first_name: '', last_name: '', address: '', city: '', zip: '', country: '',
  shipping_method: '', payment_method: '',
})

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const countries = [
  { value: 'US', label: 'United States' },
  { value: 'CA', label: 'Canada' },
  { value: 'GB', label: 'United Kingdom' },
]

const shippingMethods = [
  { value: 'standard', label: 'Standard Shipping', eta: '5-7 business days', price: 0 },
  { value: 'express', label: 'Express Shipping', eta: '2-3 business days', price: 12.99 },
  { value: 'overnight', label: 'Overnight Shipping', eta: '1 business day', price: 24.99 },
]

const paymentMethods = [
  { value: 'card', label: 'Credit/Debit Card', description: 'Visa, Mastercard, Amex', icon: CreditCardIcon },
  { value: 'cod', label: 'Cash on Delivery', description: 'Pay when you receive', icon: BanknotesIcon },
  { value: 'mobile', label: 'Mobile Payment', description: 'Apple Pay, Google Pay', icon: DevicePhoneMobileIcon },
]

const placeOrder = async () => {
  placing.value = true
  await new Promise(r => setTimeout(r, 2000))
  placing.value = false
  clearCart()
}
</script>

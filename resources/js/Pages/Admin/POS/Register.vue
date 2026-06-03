<template>
  <AdminLayout>
    <div class="flex h-[calc(100vh-8rem)] -m-6 gap-0">
      <div class="flex-1 flex flex-col">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800">
          <div class="flex items-center gap-3">
            <div class="relative flex-1 max-w-xl">
              <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
              <input v-model="searchQuery" type="text" placeholder="Search products by name or scan barcode..." class="glass-input w-full pl-9 pr-4" @keydown.enter="handleBarcode" />
            </div>
            <Input v-model="barcode" placeholder="Scan barcode..." class="w-48 font-mono" @keydown.enter="handleBarcode" />
            <div class="flex gap-1">
              <button v-for="cat in categories" :key="cat" :class="['px-3 py-1.5 rounded-lg text-xs font-medium', activeCategory === cat ? 'bg-brand-500 text-white' : 'glass hover:bg-white/30']" @click="activeCategory = cat">{{ cat }}</button>
            </div>
          </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4">
          <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <button v-for="product in filteredProducts" :key="product.id" class="glass p-3 rounded-xl text-center hover:shadow-lg transition-all hover:-translate-y-0.5" @click="addToCart(product)">
              <div class="w-full aspect-square bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-lg mb-2 flex items-center justify-center text-xs text-gray-400">IMG</div>
              <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ product.name }}</p>
              <p class="text-xs text-brand-600 dark:text-brand-400 mt-1">{{ formatCurrency(product.price) }}</p>
            </button>
          </div>
        </div>
      </div>

      <div class="w-96 glass border-l border-glass-border dark:border-glass-border-dark flex flex-col">
        <div class="p-4 border-b border-gray-100 dark:border-gray-800">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Current Sale</h3>
            <button @click="clearCart" class="text-xs text-red-500 hover:text-red-400">Clear All</button>
          </div>
          <button class="w-full text-left glass p-2 rounded-lg text-sm flex items-center gap-2 hover:bg-white/30" @click="showCustomerModal = true">
            <UserIcon class="h-4 w-4 text-gray-400" />
            <span class="text-gray-500">{{ selectedCustomer || 'Select Customer' }}</span>
          </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-2">
          <div v-for="(item, i) in cart" :key="i" class="flex items-center gap-2 p-2 glass rounded-lg">
            <div class="flex-1 min-w-0">
              <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ item.name }}</p>
              <p class="text-xs text-gray-500">{{ formatCurrency(item.price) }} x {{ item.quantity }}</p>
            </div>
            <QuantityInput v-model="item.quantity" :max="99" class="scale-75" />
            <p class="text-xs font-semibold w-16 text-right">{{ formatCurrency(item.price * item.quantity) }}</p>
            <button @click="cart.splice(i, 1)" class="text-red-400 hover:text-red-500 p-0.5"><XMarkIcon class="h-3 w-3" /></button>
          </div>
          <div v-if="cart.length === 0" class="text-center py-8 text-sm text-gray-400">Cart is empty</div>
        </div>

        <div class="p-4 border-t border-gray-100 dark:border-gray-800 space-y-3">
          <div class="space-y-1 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>{{ formatCurrency(subtotal) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Tax (10%)</span><span>{{ formatCurrency(tax) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Discount</span>
              <button @click="applyDiscount" class="text-brand-600 text-xs">+ Add</button>
            </div>
            <div class="flex justify-between font-semibold text-gray-900 dark:text-white pt-1 border-t border-gray-200 dark:border-gray-700">
              <span>Total</span><span>{{ formatCurrency(total) }}</span>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <Button variant="secondary" @click="pay('cash')">Cash</Button>
            <Button @click="pay('card')">Card</Button>
          </div>
          <Button block size="lg" :disabled="cart.length === 0" @click="processPayment">Charge {{ formatCurrency(total) }}</Button>
        </div>
      </div>
    </div>

    <Modal v-model="showCustomerModal" title="Select Customer" size="sm">
      <Input v-model="customerSearch" placeholder="Search customers..." class="mb-3" />
      <div class="space-y-1 max-h-48 overflow-y-auto">
        <button v-for="c in customers" :key="c.id" class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 text-sm" @click="selectCustomer(c)">{{ c.name }}</button>
      </div>
    </Modal>

    <Modal v-model="showPaymentModal" title="Process Payment" size="sm">
      <div class="text-center space-y-4">
        <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(total) }}</p>
        <div class="grid grid-cols-2 gap-2">
          <Button variant="secondary" block @click="completeSale">Cash</Button>
          <Button block @click="completeSale">Card Terminal</Button>
        </div>
      </div>
    </Modal>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { MagnifyingGlassIcon, UserIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Modal from '@/Components/Shared/UI/Modal.vue'
import QuantityInput from '@/Components/Shared/UI/QuantityInput.vue'

const searchQuery = ref('')
const barcode = ref('')
const activeCategory = ref('All')
const categories = ['All', 'Electronics', 'Clothing', 'Food', 'Accessories']

const cart = ref([])
const selectedCustomer = ref(null)
const showCustomerModal = ref(false)
const showPaymentModal = ref(false)
const customerSearch = ref('')

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)

const products = ref([
  { id: 1, name: 'Wireless Mouse', price: 29.99, category: 'Electronics' },
  { id: 2, name: 'USB-C Hub', price: 49.99, category: 'Electronics' },
  { id: 3, name: 'T-Shirt', price: 19.99, category: 'Clothing' },
  { id: 4, name: 'Coffee Mug', price: 12.99, category: 'Food' },
  { id: 5, name: 'Phone Stand', price: 15.99, category: 'Accessories' },
])

const customers = ref([
  { id: 1, name: 'Walk-in Customer' },
  { id: 2, name: 'John Smith' },
  { id: 3, name: 'Sarah Johnson' },
])

const filteredProducts = computed(() => {
  let filtered = products.value
  if (activeCategory.value !== 'All') filtered = filtered.filter(p => p.category === activeCategory.value)
  if (searchQuery.value) filtered = filtered.filter(p => p.name.toLowerCase().includes(searchQuery.value.toLowerCase()))
  return filtered
})

const subtotal = computed(() => cart.value.reduce((s, i) => s + i.price * i.quantity, 0))
const tax = computed(() => subtotal.value * 0.1)
const total = computed(() => subtotal.value + tax.value)

const addToCart = (product) => {
  const existing = cart.value.find(i => i.id === product.id)
  if (existing) existing.quantity++
  else cart.value.push({ ...product, quantity: 1 })
}

const clearCart = () => { cart.value = []; selectedCustomer.value = null }
const handleBarcode = () => {}
const applyDiscount = () => {}
const selectCustomer = (c) => { selectedCustomer.value = c.name; showCustomerModal.value = false }
const pay = (method) => { showPaymentModal.value = true }
const processPayment = () => {}
const completeSale = () => { showPaymentModal.value = false; clearCart() }
</script>

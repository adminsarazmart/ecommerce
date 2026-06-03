import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCartStore = defineStore('cart', () => {
    const items = ref([])
    const coupon = ref(null)
    const taxRate = ref(0.1)
    const shippingRate = ref(0)

    const totalItems = computed(() => items.value.reduce((sum, item) => sum + (item.quantity || 1), 0))

    const subtotal = computed(() => items.value.reduce((sum, item) => sum + (item.price * (item.quantity || 1)), 0))

    const discount = computed(() => {
        if (!coupon.value) return 0
        if (coupon.value.type === 'percentage') return subtotal.value * (coupon.value.value / 100)
        return coupon.value.value
    })

    const tax = computed(() => (subtotal.value - discount.value) * taxRate.value)
    const shipping = computed(() => shippingRate.value)
    const total = computed(() => subtotal.value - discount.value + tax.value + shipping.value)

    function addItem(product) {
        const existing = items.value.find(i => i.id === product.id && i.variant_id === product.variant_id)
        if (existing) {
            existing.quantity += product.quantity || 1
        } else {
            items.value.push({ ...product, quantity: product.quantity || 1 })
        }
        persist()
    }

    function removeItem(itemId) { items.value = items.value.filter(i => i.id !== itemId); persist() }

    function updateQuantity(itemId, qty) {
        const item = items.value.find(i => i.id === itemId)
        if (item) { item.quantity = Math.max(1, qty); persist() }
    }

    function applyCoupon(code) { coupon.value = { code, type: 'percentage', value: 10 }; persist() }
    function removeCoupon() { coupon.value = null; persist() }
    function clearCart() { items.value = []; coupon.value = null; persist() }

    function persist() {
        try { localStorage.setItem('cart', JSON.stringify({ items: items.value, coupon: coupon.value })) } catch {}
    }

    function load() {
        try {
            const saved = localStorage.getItem('cart')
            if (saved) {
                const data = JSON.parse(saved)
                items.value = data.items || []
                coupon.value = data.coupon || null
            }
        } catch { items.value = []; coupon.value = null }
    }

    load()

    return { items, coupon, totalItems, subtotal, tax, shipping, discount, total, addItem, removeItem, updateQuantity, applyCoupon, removeCoupon, clearCart }
})

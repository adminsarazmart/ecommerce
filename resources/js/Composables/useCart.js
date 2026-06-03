import { computed } from 'vue'
import { useCartStore } from '@/Stores/cart'

export function useCart() {
    const store = useCartStore()

    const addItem = (product, quantity = 1, variant = null) => {
        store.addItem({ ...product, quantity, variant_id: variant?.id, variant_label: variant?.label })
    }

    const removeItem = (itemId) => store.removeItem(itemId)
    const updateQuantity = (itemId, quantity) => store.updateQuantity(itemId, quantity)
    const applyCoupon = (code) => store.applyCoupon(code)
    const removeCoupon = () => store.removeCoupon()
    const clearCart = () => store.clearCart()

    return {
        items: computed(() => store.items),
        totalItems: computed(() => store.totalItems),
        subtotal: computed(() => store.subtotal),
        tax: computed(() => store.tax),
        shipping: computed(() => store.shipping),
        discount: computed(() => store.discount),
        total: computed(() => store.total),
        coupon: computed(() => store.coupon),
        addItem, removeItem, updateQuantity, applyCoupon, removeCoupon, clearCart,
    }
}

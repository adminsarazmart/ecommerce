import { computed } from 'vue'
import { useWishlistStore } from '@/Stores/wishlist'

export function useWishlist() {
    const store = useWishlistStore()

    const toggleItem = (product) => store.toggleItem(product)
    const removeItem = (productId) => store.removeItem(productId)
    const isInWishlist = (productId) => store.isInWishlist(productId)
    const clearWishlist = () => store.clearWishlist()

    return {
        items: computed(() => store.items),
        count: computed(() => store.count),
        toggleItem, removeItem, isInWishlist, clearWishlist,
    }
}

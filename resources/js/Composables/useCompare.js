import { computed } from 'vue'
import { useCompareStore } from '@/Stores/compare'

export function useCompare() {
    const store = useCompareStore()

    const toggleItem = (product) => store.toggleItem(product)
    const removeItem = (productId) => store.removeItem(productId)
    const isInCompare = (productId) => store.isInCompare(productId)
    const clearCompare = () => store.clearCompare()

    return {
        items: computed(() => store.items),
        count: computed(() => store.count),
        canAddMore: computed(() => store.items.length < 4),
        toggleItem, removeItem, isInCompare, clearCompare,
    }
}

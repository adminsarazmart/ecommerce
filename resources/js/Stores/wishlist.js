import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useWishlistStore = defineStore('wishlist', () => {
    const items = ref([])

    const count = computed(() => items.value.length)

    function toggleItem(product) {
        const index = items.value.findIndex(i => i.id === product.id)
        if (index > -1) items.value.splice(index, 1)
        else items.value.push({ ...product })
        persist()
    }

    function removeItem(productId) { items.value = items.value.filter(i => i.id !== productId); persist() }

    function isInWishlist(productId) { return items.value.some(i => i.id === productId) }

    function clearWishlist() { items.value = []; persist() }

    function persist() { localStorage.setItem('wishlist', JSON.stringify(items.value)) }

    function load() {
        const saved = localStorage.getItem('wishlist')
        if (saved) items.value = JSON.parse(saved)
    }

    load()

    return { items, count, toggleItem, removeItem, isInWishlist, clearWishlist }
})

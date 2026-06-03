import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCompareStore = defineStore('compare', () => {
    const items = ref([])

    const count = computed(() => items.value.length)

    function toggleItem(product) {
        const index = items.value.findIndex(i => i.id === product.id)
        if (index > -1) items.value.splice(index, 1)
        else if (items.value.length < 4) items.value.push({ ...product })
        persist()
    }

    function removeItem(productId) { items.value = items.value.filter(i => i.id !== productId); persist() }

    function isInCompare(productId) { return items.value.some(i => i.id === productId) }

    function clearCompare() { items.value = []; persist() }

    function persist() { try { localStorage.setItem('compare', JSON.stringify(items.value)) } catch {} }

    function load() {
        try {
            const saved = localStorage.getItem('compare')
            if (saved) items.value = JSON.parse(saved)
        } catch { items.value = [] }
    }

    load()

    return { items, count, toggleItem, removeItem, isInCompare, clearCompare }
})

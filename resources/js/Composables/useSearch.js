import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

export function useSearch(routeName = 'search') {
    const query = ref('')
    const results = ref([])
    const isSearching = ref(false)
    const showResults = ref(false)
    let debounceTimer = null

    watch(query, (val) => {
        clearTimeout(debounceTimer)
        if (!val || val.length < 2) {
            results.value = []
            showResults.value = false
            return
        }
        debounceTimer = setTimeout(async () => {
            isSearching.value = true
            try {
                const response = await fetch(`/${routeName}?q=${encodeURIComponent(val)}`)
                const data = await response.json()
                results.value = data.results || data
                showResults.value = true
            } catch (e) {
                results.value = []
            } finally {
                isSearching.value = false
            }
        }, 300)
    })

    const selectResult = (item) => {
        showResults.value = false
        query.value = ''
        router.visit(item.url || `/products/${item.slug}`)
    }

    return { query, results, isSearching, showResults, selectResult }
}

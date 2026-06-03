import { ref, onMounted, onUnmounted } from 'vue'

export function useInfiniteScroll(callback, options = {}) {
    const sentinel = ref(null)
    const isLoading = ref(false)
    const hasMore = ref(true)
    let observer = null

    onMounted(() => {
        if (!sentinel.value) return
        observer = new IntersectionObserver(async ([entry]) => {
            if (entry.isIntersecting && hasMore.value && !isLoading.value) {
                isLoading.value = true
                await callback()
                isLoading.value = false
            }
        }, { threshold: 0.1, ...options })
        observer.observe(sentinel.value)
    })

    onUnmounted(() => observer?.disconnect())

    return { sentinel, isLoading, hasMore }
}

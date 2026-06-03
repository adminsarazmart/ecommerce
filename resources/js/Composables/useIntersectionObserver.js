import { ref, onMounted, onUnmounted } from 'vue'

export function useIntersectionObserver(options = {}) {
    const element = ref(null)
    const isVisible = ref(false)
    let observer = null

    onMounted(() => {
        if (!element.value) return
        observer = new IntersectionObserver(([entry]) => {
            if (entry.isIntersecting) {
                isVisible.value = true
                if (options.once !== false) observer.unobserve(element.value)
            } else if (options.once === false) {
                isVisible.value = false
            }
        }, { threshold: options.threshold || 0.1, ...options })
        observer.observe(element.value)
    })

    onUnmounted(() => observer?.disconnect())

    return { element, isVisible }
}

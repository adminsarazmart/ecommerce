import { onMounted, onUnmounted } from 'vue'

export function useClickOutside(elementRef, callback) {
    const handler = (e) => {
        if (elementRef.value && !elementRef.value.contains(e.target)) {
            callback(e)
        }
    }

    onMounted(() => document.addEventListener('click', handler, true))
    onUnmounted(() => document.removeEventListener('click', handler, true))
}

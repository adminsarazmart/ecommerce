import { ref, watch } from 'vue'

export function useDebounce(value, delay = 300) {
    const debouncedValue = ref(value)
    let timeout = null

    watch(value, (val) => {
        clearTimeout(timeout)
        timeout = setTimeout(() => {
            debouncedValue.value = val
        }, delay)
    })

    return debouncedValue
}

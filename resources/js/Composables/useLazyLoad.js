import { ref } from 'vue'

export function useLazyLoad() {
    const imageRef = ref(null)
    const isLoaded = ref(false)
    const isError = ref(false)

    const onLoad = () => { isLoaded.value = true }
    const onError = () => { isError.value = true }

    return { imageRef, isLoaded, isError, onLoad, onError }
}

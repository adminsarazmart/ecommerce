import { ref, onMounted, onUnmounted } from 'vue'

const isMobile = ref(false)
const isTablet = ref(false)
const isDesktop = ref(true)

function detect() {
    const width = window.innerWidth
    isMobile.value = width < 768
    isTablet.value = width >= 768 && width < 1024
    isDesktop.value = width >= 1024
}

export function useDeviceDetect() {
    onMounted(() => {
        detect()
        window.addEventListener('resize', detect)
    })
    onUnmounted(() => window.removeEventListener('resize', detect))

    return { isMobile, isTablet, isDesktop }
}

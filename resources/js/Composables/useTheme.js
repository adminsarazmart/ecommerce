import { ref, watch, computed } from 'vue'

const isDark = ref(false)
const isLoaded = ref(false)

export function useTheme() {
    const init = () => {
        const stored = localStorage.getItem('theme')
        if (stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            isDark.value = true
        }
        applyTheme()
        isLoaded.value = true
    }

    const applyTheme = () => {
        if (isDark.value) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    }

    const toggle = () => {
        isDark.value = !isDark.value
    }

    const setTheme = (value) => {
        isDark.value = value
    }

    watch(isDark, (val) => {
        localStorage.setItem('theme', val ? 'dark' : 'light')
        applyTheme()
    })

    const themeIcon = computed(() => isDark.value ? 'sun' : 'moon')

    return { isDark, isLoaded, init, toggle, setTheme, themeIcon, applyTheme }
}

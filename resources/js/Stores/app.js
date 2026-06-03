import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useAppStore = defineStore('app', () => {
    const sidebarOpen = ref(true)
    const sidebarCollapsed = ref(false)
    const theme = ref(localStorage.getItem('theme') || 'light')
    const locale = ref('en')
    const currency = ref('USD')
    const currencies = ref(['USD', 'EUR', 'GBP', 'INR', 'AED'])
    const exchangeRates = ref({})
    const loading = ref(false)
    const pageTitle = ref('')

    const isDark = computed(() => theme.value === 'dark')

    function toggleSidebar() { sidebarOpen.value = !sidebarOpen.value }
    function toggleSidebarCollapse() { sidebarCollapsed.value = !sidebarCollapsed.value }
    function setTheme(t) { theme.value = t; localStorage.setItem('theme', t) }
    function toggleTheme() { setTheme(theme.value === 'dark' ? 'light' : 'dark') }
    function setLocale(l) { locale.value = l }
    function setCurrency(c) { currency.value = c }
    function setLoading(l) { loading.value = l }
    function setPageTitle(t) { pageTitle.value = t }

    return {
        sidebarOpen, sidebarCollapsed, theme, locale, currency, currencies,
        exchangeRates, loading, pageTitle, isDark,
        toggleSidebar, toggleSidebarCollapse, setTheme, toggleTheme,
        setLocale, setCurrency, setLoading, setPageTitle,
    }
})

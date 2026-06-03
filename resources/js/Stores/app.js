import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAppStore = defineStore('app', () => {
    const sidebarOpen = ref(true)
    const sidebarCollapsed = ref(false)
    const locale = ref('en')
    const currency = ref('USD')
    const currencies = ref(['USD', 'EUR', 'GBP', 'INR', 'AED'])
    const exchangeRates = ref({})
    const loading = ref(false)
    const pageTitle = ref('')

    function toggleSidebar() { sidebarOpen.value = !sidebarOpen.value }
    function toggleSidebarCollapse() { sidebarCollapsed.value = !sidebarCollapsed.value }
    function setLocale(l) { locale.value = l }
    function setCurrency(c) { currency.value = c }
    function setLoading(l) { loading.value = l }
    function setPageTitle(t) { pageTitle.value = t }

    return {
        sidebarOpen, sidebarCollapsed, locale, currency, currencies,
        exchangeRates, loading, pageTitle,
        toggleSidebar, toggleSidebarCollapse,
        setLocale, setCurrency, setLoading, setPageTitle,
    }
})

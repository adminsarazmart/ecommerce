import { computed } from 'vue'
import { useAppStore } from '@/Stores/app'

export function useCurrency() {
    const app = useAppStore()

    const format = (amount, currency = null) => {
        const cur = currency || app.currency || 'USD'
        return new Intl.NumberFormat(app.locale || 'en-US', {
            style: 'currency',
            currency: cur,
        }).format(amount || 0)
    }

    const convert = (amount, from, to) => {
        if (from === to) return amount
        const rate = app.exchangeRates?.[to] || 1
        return amount * rate
    }

    return {
        format,
        convert,
        currency: computed(() => app.currency),
        currencies: computed(() => app.currencies),
        setCurrency: app.setCurrency,
    }
}

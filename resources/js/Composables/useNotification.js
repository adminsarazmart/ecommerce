import { useNotificationStore } from '@/Stores/notification'

export function useNotification() {
    const store = useNotificationStore()

    const notify = (type, message, title = '', duration = 5000) => {
        store.add({ type, message, title, duration })
    }

    const success = (message, title = 'Success') => notify('success', message, title)
    const error = (message, title = 'Error') => notify('error', message, title)
    const warning = (message, title = 'Warning') => notify('warning', message, title)
    const info = (message, title = 'Info') => notify('info', message, title)

    return { notify, success, error, warning, info, notifications: store.notifications, remove: store.remove }
}

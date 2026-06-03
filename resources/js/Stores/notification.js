import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useNotificationStore = defineStore('notification', () => {
    const notifications = ref([])
    let counter = 0

    function add(notification) {
        const id = ++counter
        const n = { ...notification, id }
        notifications.value.push(n)
        if (notification.duration !== 0) {
            setTimeout(() => remove(id), notification.duration || 5000)
        }
        return id
    }

    function remove(id) {
        notifications.value = notifications.value.filter(n => n.id !== id)
    }

    function clear() { notifications.value = [] }

    return { notifications, add, remove, clear }
})

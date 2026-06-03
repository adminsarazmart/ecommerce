import { computed, ref } from 'vue'

const user = ref(null)
const permissions = ref([])
const roles = ref([])

export function useAuth() {
    const setUser = (u) => { user.value = u }
    const setPermissions = (p) => { permissions.value = p }
    const setRoles = (r) => { roles.value = r }

    const isAuthenticated = computed(() => !!user.value)
    const isAdmin = computed(() => roles.value.includes('admin'))
    const isVendor = computed(() => roles.value.includes('vendor'))
    const can = (permission) => permissions.value.includes(permission)

    return {
        user, permissions, roles, isAuthenticated, isAdmin, isVendor, can,
        setUser, setPermissions, setRoles,
    }
}

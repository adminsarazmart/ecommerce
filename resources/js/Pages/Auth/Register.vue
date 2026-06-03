<script setup>
import { useForm } from '@inertiajs/vue3'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'

defineOptions({ layout: StorefrontLayout })

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post('/register', {
        preserveState: true,
        onSuccess: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="glass-card rounded-2xl p-8 shadow-xl">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Create Account</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Join our marketplace</p>
                </div>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input v-model="form.name" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input v-model="form.email" type="email" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <input v-model="form.password" type="password" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                        <input v-model="form.password_confirmation" type="password" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" :disabled="form.processing" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-all duration-200">
                        {{ form.processing ? 'Creating...' : 'Create Account' }}
                    </button>
                </form>
                <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    Already have an account?
                    <a href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</template>

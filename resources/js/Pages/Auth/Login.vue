<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'

defineOptions({ layout: StorefrontLayout })

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post('/login', {
        preserveState: true,
        onSuccess: () => form.reset('password'),
    })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="glass-card rounded-2xl p-8 shadow-xl">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome Back</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Sign in to your account</p>
                </div>
                <form @submit.prevent="submit" class="space-y-6">
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
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                        </label>
                        <a href="/forgot-password" class="text-sm text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                    </div>
                    <button type="submit" :disabled="form.processing" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-all duration-200">
                        {{ form.processing ? 'Signing in...' : 'Sign in' }}
                    </button>
                </form>
                <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account?
                    <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">Register</a>
                </p>
            </div>
        </div>
    </div>
</template>

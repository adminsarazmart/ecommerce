<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-indigo-50/30 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div class="bg-white rounded-2xl p-8 shadow-xl shadow-gray-200/50 border border-gray-100">
        <div class="text-center mb-8">
          <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg mx-auto mb-4">M</div>
          <h2 class="text-2xl font-heading font-bold text-gray-900">Welcome Back</h2>
          <p class="mt-1.5 text-sm text-gray-500">Sign in to your account</p>
        </div>
        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input v-model="form.email" type="email" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-300 transition-all" placeholder="you@example.com" />
            <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input v-model="form.password" type="password" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-300 transition-all" placeholder="••••••••" />
            <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
          </div>
          <div class="flex items-center justify-between">
            <label class="flex items-center gap-2">
              <input v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
              <span class="text-sm text-gray-600">Remember me</span>
            </label>
            <a href="/forgot-password" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Forgot password?</a>
          </div>
          <button type="submit" :disabled="form.processing" class="w-full py-3 px-4 rounded-xl text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 disabled:opacity-50 transition-all">
            {{ form.processing ? 'Signing in...' : 'Sign in' }}
          </button>
        </form>
        <p class="mt-6 text-center text-sm text-gray-500">
          Don't have an account? <a href="/register" class="font-semibold text-indigo-600 hover:text-indigo-700">Register</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({ email: '', password: '', remember: false })

const submit = () => {
  form.post('/login', {
    preserveState: true,
    onSuccess: () => form.reset('password'),
  })
}
</script>

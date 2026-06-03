<template>
  <StorefrontLayout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">My Profile</h1>
      <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="space-y-1">
          <Link v-for="link in navLinks" :key="link.label" :href="link.url" class="sidebar-item sidebar-item-inactive"><component :is="link.icon" class="h-5 w-5" />{{ link.label }}</Link>
        </aside>
        <div class="lg:col-span-3 max-w-2xl">
          <FormSection title="Personal Information" description="Update your profile details">
            <div class="grid grid-cols-2 gap-4">
              <Input v-model="form.first_name" label="First Name" />
              <Input v-model="form.last_name" label="Last Name" />
            </div>
            <Input v-model="form.email" label="Email" type="email" />
            <Input v-model="form.phone" label="Phone" />
          </FormSection>
          <FormActions @submit="saveProfile" @cancel="cancelForm" />
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import { reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { UserIcon, ShoppingCartIcon, HeartIcon, MapPinIcon, StarIcon, GiftTopIcon, WalletIcon } from '@heroicons/vue/24/outline'
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue'
import FormSection from '@/Components/Shared/Forms/FormSection.vue'
import FormActions from '@/Components/Shared/Forms/FormActions.vue'
import Input from '@/Components/Shared/UI/Input.vue'

const navLinks = [
  { label: 'Dashboard', url: '/account', icon: UserIcon },
  { label: 'Orders', url: '/account/orders', icon: ShoppingCartIcon },
  { label: 'Profile', url: '/account/profile', icon: UserIcon },
  { label: 'Addresses', url: '/account/addresses', icon: MapPinIcon },
  { label: 'Wishlist', url: '/account/wishlist', icon: HeartIcon },
]

const form = reactive({ first_name: 'John', last_name: 'Doe', email: 'john@example.com', phone: '+1 555-1234' })
const saveProfile = () => {}
const cancelForm = () => { router.get('/account') }
</script>

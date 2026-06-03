<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Create Vendor</h1>
        <p class="text-sm text-gray-500 mt-1">Register a new marketplace vendor</p>
      </div>
    </template>

    <div class="max-w-2xl mx-auto space-y-6">
      <FormSection title="Vendor Information" description="Enter the vendor's basic details">
        <div class="grid grid-cols-2 gap-4">
          <Input v-model="form.first_name" label="First Name" />
          <Input v-model="form.last_name" label="Last Name" />
        </div>
        <Input v-model="form.email" label="Email" type="email" />
        <Input v-model="form.phone" label="Phone" />
        <Input v-model="form.store_name" label="Store Name" />
        <Select v-model="form.commission_rate" :options="commissionRates" label="Commission Rate (%)" />
      </FormSection>
      <FormActions @submit="submitForm" @cancel="cancelForm" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormSection from '@/Components/Shared/Forms/FormSection.vue'
import FormActions from '@/Components/Shared/Forms/FormActions.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Select from '@/Components/Shared/UI/Select.vue'

const form = reactive({ first_name: '', last_name: '', email: '', phone: '', store_name: '', commission_rate: null })

const commissionRates = [
  { value: 5, label: '5%' }, { value: 10, label: '10%' }, { value: 15, label: '15%' }, { value: 20, label: '20%' },
]

const submitForm = () => { router.post(route('admin.vendors.store'), form) }
const cancelForm = () => { router.get(route('admin.vendors.index')) }
</script>

<template>
  <VendorLayout>
    <template #header>
      <div>
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Add Product</h1>
        <p class="text-sm text-gray-500 mt-1">List a new product in your store</p>
      </div>
    </template>

    <div class="max-w-3xl mx-auto space-y-6">
      <FormSection title="Product Details">
        <Input v-model="form.name" label="Product Name" />
        <div class="grid grid-cols-2 gap-4">
          <Input v-model="form.sku" label="SKU" />
          <Input v-model="form.price" label="Price" type="number" />
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Description</label>
          <textarea v-model="form.description" rows="4" class="glass-input w-full resize-none" />
        </div>
        <FileUpload v-model="form.images" accept="image/*" multiple />
      </FormSection>
      <FormSection title="Inventory">
        <div class="grid grid-cols-2 gap-4">
          <Input v-model="form.stock" label="Stock Quantity" type="number" />
          <Input v-model="form.low_stock_threshold" label="Low Stock Alert" type="number" />
        </div>
      </FormSection>
      <FormActions submit-text="Create Product" @submit="submitForm" @cancel="cancelForm" />
    </div>
  </VendorLayout>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import VendorLayout from '@/Layouts/VendorLayout.vue'
import FormSection from '@/Components/Shared/Forms/FormSection.vue'
import FormActions from '@/Components/Shared/Forms/FormActions.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import FileUpload from '@/Components/Shared/UI/FileUpload.vue'

const form = reactive({ name: '', sku: '', price: '', description: '', images: [], stock: 0, low_stock_threshold: 5 })

const submitForm = () => { router.post(route('vendor.products.store'), form) }
const cancelForm = () => { router.get(route('vendor.products.index')) }
</script>

<template>
  <AdminLayout>
    <template #header>
      <div>
        <Breadcrumb :crumbs="[{ label: 'Purchase Orders', url: route('admin.inventory.purchase-orders') }, { label: 'New Purchase Order' }]" />
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mt-2">New Purchase Order</h1>
      </div>
    </template>

    <div class="max-w-3xl mx-auto space-y-6">
      <FormSection title="Supplier Information">
        <Select v-model="form.supplier_id" :options="suppliers" placeholder="Select Supplier" searchable />
        <Input v-model="form.delivery_date" label="Expected Delivery" type="date" />
      </FormSection>

      <div class="glass-card p-5">
        <SectionHeader title="Items" size="md">
          <template #actions><Button size="sm" @click="addItem"><PlusIcon class="h-4 w-4" />Add Item</Button></template>
        </SectionHeader>
        <div class="mt-4 space-y-3">
          <div v-for="(item, i) in form.items" :key="i" class="flex items-center gap-3">
            <Select v-model="item.product_id" :options="products" placeholder="Product" class="flex-1" />
            <Input v-model="item.quantity" type="number" placeholder="Qty" class="w-20" />
            <Input v-model="item.unit_price" type="number" placeholder="Price" class="w-24" />
            <span class="text-sm font-medium w-20 text-right">{{ formatCurrency(item.quantity * item.unit_price) }}</span>
            <button @click="form.items.splice(i, 1)" class="text-red-500"><TrashIcon class="h-4 w-4" /></button>
          </div>
          <div class="flex justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
            <span class="font-semibold text-gray-900 dark:text-white">Total</span>
            <span class="font-semibold text-gray-900 dark:text-white">{{ formatCurrency(total) }}</span>
          </div>
        </div>
      </div>

      <FormActions submit-text="Create Purchase Order" @submit="submitForm" @cancel="cancelForm" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { reactive, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import FormSection from '@/Components/Shared/Forms/FormSection.vue'
import FormActions from '@/Components/Shared/Forms/FormActions.vue'
import Select from '@/Components/Shared/UI/Select.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'

const form = reactive({ supplier_id: null, delivery_date: '', items: [{ product_id: null, quantity: 1, unit_price: 0 }] })

const suppliers = ref([{ value: 1, label: 'TechSupply Co.' }, { value: 2, label: 'Global Parts Inc.' }])
const products = ref([{ value: 1, label: 'Wireless Headphones' }, { value: 2, label: 'USB Cable - 6ft' }])

const total = computed(() => form.items.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0))

const formatCurrency = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val)
const addItem = () => { form.items.push({ product_id: null, quantity: 1, unit_price: 0 }) }
const submitForm = () => {}
const cancelForm = () => { router.get(route('admin.inventory.purchase-orders')) }
</script>

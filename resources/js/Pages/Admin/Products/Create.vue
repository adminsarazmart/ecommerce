<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">{{ isEditing ? 'Edit Product' : 'Create Product' }}</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ isEditing ? 'Update product details' : 'Add a new product to your catalog' }}</p>
        </div>
      </div>
    </template>

    <Stepper v-model="currentStep" :steps="steps" class="mb-8" />

    <div class="max-w-4xl mx-auto">
      <Transition name="fade" mode="out-in">
        <div v-if="currentStep === 0" key="basic" class="space-y-6">
          <FormSection title="Basic Information" description="Enter the fundamental product details">
            <Input v-model="form.name" label="Product Name" error="" />
            <div class="grid grid-cols-2 gap-4">
              <Input v-model="form.sku" label="SKU" />
              <Input v-model="form.barcode" label="Barcode (UPC/EAN)" />
            </div>
            <div>
              <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Description</label>
              <textarea v-model="form.description" rows="4" class="glass-input w-full resize-none" placeholder="Enter product description..." />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <Select v-model="form.category_id" :options="categories" label="Category" placeholder="Select category" searchable />
              <Select v-model="form.brand_id" :options="brands" placeholder="Select brand" label="Brand" />
            </div>
          </FormSection>
        </div>

        <div v-else-if="currentStep === 1" key="media" class="space-y-6">
          <FormSection title="Media" description="Upload product images and media">
            <FileUpload v-model="form.images" multiple accept="image/*" />
          </FormSection>
        </div>

        <div v-else-if="currentStep === 2" key="pricing" class="space-y-6">
          <FormSection title="Pricing" description="Set your pricing strategy">
            <div class="grid grid-cols-2 gap-4">
              <Input v-model="form.price" label="Price" type="number" />
              <Input v-model="form.compare_price" label="Compare at Price" type="number" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <Input v-model="form.cost_price" label="Cost Price" type="number" />
              <Input v-model="form.profit_margin" label="Profit Margin (%)" type="number" disabled />
            </div>
          </FormSection>
        </div>

        <div v-else-if="currentStep === 3" key="inventory" class="space-y-6">
          <FormSection title="Inventory" description="Manage stock levels">
            <div class="grid grid-cols-2 gap-4">
              <Input v-model="form.stock" label="Stock Quantity" type="number" />
              <Input v-model="form.low_stock_threshold" label="Low Stock Threshold" type="number" />
            </div>
            <Checkbox v-model="form.track_inventory">Track Inventory</Checkbox>
          </FormSection>
        </div>

        <div v-else-if="currentStep === 4" key="variants" class="space-y-6">
          <FormSection title="Variants" description="Add product variants (size, color, etc.)">
            <div v-for="(group, gi) in form.variant_groups" :key="gi" class="glass p-4 rounded-xl mb-3">
              <div class="flex items-center justify-between mb-2">
                <Input v-model="group.name" label="Attribute Name" class="flex-1 mr-3" />
                <button @click="form.variant_groups.splice(gi, 1)" class="text-red-500 p-1"><TrashIcon class="h-4 w-4" /></button>
              </div>
              <div class="flex flex-wrap gap-2">
                <div v-for="(val, vi) in group.values" :key="vi" class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-lg text-sm">
                  <span v-if="group.type === 'color'" class="w-4 h-4 rounded-full border" :style="{ background: val.color }" />
                  {{ val.label }}
                  <button @click="group.values.splice(vi, 1)" class="text-gray-400 hover:text-red-500 ml-1">&times;</button>
                </div>
                <button @click="addVariantValue(group)" class="text-xs text-brand-600 hover:text-brand-500 px-2 py-1">+ Add</button>
              </div>
            </div>
            <Button variant="ghost" size="sm" @click="addVariantGroup">+ Add Attribute Group</Button>
          </FormSection>
        </div>

        <div v-else-if="currentStep === 5" key="seo" class="space-y-6">
          <FormSection title="SEO Metadata" description="Optimize for search engines">
            <Input v-model="form.meta_title" label="Meta Title" />
            <div>
              <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Meta Description</label>
              <textarea v-model="form.meta_description" rows="3" class="glass-input w-full resize-none" />
            </div>
            <Input v-model="form.slug" label="Slug" />
            <Input v-model="form.tags" label="Tags (comma separated)" />
          </FormSection>
        </div>
      </Transition>

      <div class="flex items-center justify-between mt-8">
        <Button variant="ghost" @click="prevStep" :disabled="currentStep === 0">
          <ChevronLeftIcon class="h-4 w-4" /> Previous
        </Button>
        <div class="flex items-center gap-2">
          <Button variant="ghost" @click="saveDraft">Save as Draft</Button>
          <Button v-if="currentStep < steps.length - 1" @click="nextStep">Next <ChevronRightIcon class="h-4 w-4" /></Button>
          <Button v-else @click="submitProduct">{{ isEditing ? 'Update Product' : 'Create Product' }}</Button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import { ChevronLeftIcon, ChevronRightIcon, TrashIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Stepper from '@/Components/Shared/UI/Stepper.vue'
import FormSection from '@/Components/Shared/Forms/FormSection.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Select from '@/Components/Shared/UI/Select.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import FileUpload from '@/Components/Shared/UI/FileUpload.vue'
import Checkbox from '@/Components/Shared/UI/Checkbox.vue'

const props = defineProps({ product: { type: Object, default: null } })
const isEditing = computed(() => !!props.product)

const steps = [
  { label: 'Basic Info' }, { label: 'Media' }, { label: 'Pricing' },
  { label: 'Inventory' }, { label: 'Variants' }, { label: 'SEO' },
]

const currentStep = ref(0)

const form = reactive({
  name: props.product?.name || '',
  sku: props.product?.sku || '',
  barcode: '',
  description: '',
  category_id: null,
  brand_id: null,
  price: '',
  compare_price: '',
  cost_price: '',
  profit_margin: '',
  stock: 0,
  low_stock_threshold: 10,
  track_inventory: true,
  variant_groups: [],
  images: [],
  meta_title: '',
  meta_description: '',
  slug: '',
  tags: '',
})

const categories = ref([
  { value: 1, label: 'Electronics' }, { value: 2, label: 'Fashion' },
  { value: 3, label: 'Home & Garden' }, { value: 4, label: 'Sports' },
])

const brands = ref([
  { value: 1, label: 'TechPro' }, { value: 2, label: 'FashionHub' },
  { value: 3, label: 'HomeGoods' },
])

const addVariantGroup = () => {
  form.variant_groups.push({ name: '', type: 'text', values: [] })
}

const addVariantValue = (group) => {
  const label = prompt('Enter value label:')
  if (label) group.values.push({ label, value: label.toLowerCase().replace(/\s+/g, '-') })
}

const nextStep = () => { if (currentStep.value < steps.length - 1) currentStep.value++ }
const prevStep = () => { if (currentStep.value > 0) currentStep.value-- }
const saveDraft = () => {}
const submitProduct = () => {}
</script>

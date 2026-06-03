<template>
  <AdminLayout>
    <template #header>
      <div>
        <Breadcrumb :crumbs="[{ label: 'Sliders', url: route('admin.cms.sliders') }, { label: isEditing ? 'Edit Slider' : 'New Slider' }]" />
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mt-2">{{ isEditing ? 'Edit Slider' : 'New Slider' }}</h1>
      </div>
    </template>

    <div class="max-w-3xl mx-auto space-y-6">
      <FormSection title="Slider Settings">
        <Input v-model="form.name" label="Slider Name" />
        <Select v-model="form.location" :options="locations" placeholder="Location" />
      </FormSection>

      <div class="glass-card p-5">
        <SectionHeader title="Slides" size="md">
          <template #actions><Button size="sm" @click="addSlide"><PlusIcon class="h-4 w-4" />Add Slide</Button></template>
        </SectionHeader>
        <div class="mt-4 space-y-4">
          <div v-for="(slide, i) in form.slides" :key="i" class="glass p-4 rounded-xl">
            <div class="flex items-start justify-between mb-3">
              <span class="text-sm font-semibold text-gray-900 dark:text-white">Slide {{ i + 1 }}</span>
              <button @click="form.slides.splice(i, 1)" class="text-red-500 hover:text-red-400"><TrashIcon class="h-4 w-4" /></button>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <Input v-model="slide.title" label="Title" />
              <Input v-model="slide.subtitle" label="Subtitle" />
            </div>
            <div class="grid grid-cols-2 gap-3 mt-3">
              <Input v-model="slide.button_text" label="Button Text" />
              <Input v-model="slide.button_url" label="Button URL" />
            </div>
            <div class="mt-3">
              <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Background Image</p>
              <FileUpload v-model="slide.image" accept="image/*" />
            </div>
          </div>
        </div>
      </div>

      <FormActions :submit-text="isEditing ? 'Update Slider' : 'Create Slider'" @submit="saveSlider" @cancel="cancelForm" />
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
import Input from '@/Components/Shared/UI/Input.vue'
import Select from '@/Components/Shared/UI/Select.vue'
import Button from '@/Components/Shared/UI/Button.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import FileUpload from '@/Components/Shared/UI/FileUpload.vue'

const props = defineProps({ slider: { type: Object, default: null } })
const isEditing = computed(() => !!props.slider)

const form = reactive({
  name: props.slider?.name || '', location: 'homepage',
  slides: props.slider?.slides || [],
})

const locations = [
  { value: 'homepage', label: 'Homepage Hero' },
  { value: 'shop', label: 'Shop Page' },
  { value: 'category', label: 'Category Page' },
]

const addSlide = () => { form.slides.push({ title: '', subtitle: '', button_text: '', button_url: '', image: [] }) }

const saveSlider = () => {}
const cancelForm = () => { router.get(route('admin.cms.sliders')) }
</script>

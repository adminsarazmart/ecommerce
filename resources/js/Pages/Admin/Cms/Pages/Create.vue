<template>
  <AdminLayout>
    <template #header>
      <div>
        <Breadcrumb :crumbs="[{ label: 'Pages', url: route('admin.cms.pages') }, { label: isEditing ? 'Edit Page' : 'New Page' }]" />
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mt-2">{{ isEditing ? 'Edit Page' : 'New Page' }}</h1>
      </div>
    </template>

    <div class="max-w-4xl mx-auto space-y-6">
      <FormSection title="Page Content" description="Create and edit page content">
        <Input v-model="form.title" label="Page Title" />
        <Input v-model="form.slug" label="Slug" />
        <div>
          <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Content</label>
          <div class="glass rounded-xl overflow-hidden">
            <div class="flex items-center gap-1 px-3 py-2 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
              <button v-for="tool in editorTools" :key="tool" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400">
                <component :is="tool" class="h-4 w-4" />
              </button>
            </div>
            <textarea v-model="form.content" rows="16" class="w-full bg-transparent p-4 text-sm font-mono focus:outline-none resize-none" placeholder="Write your page content here..." />
          </div>
        </div>
      </FormSection>
      <div class="flex items-center gap-2">
        <Select v-model="form.status" :options="statusOptions" placeholder="Status" class="w-40" />
        <Switch v-model="form.show_in_menu" /> Show in Menu
      </div>
      <FormActions :submit-text="isEditing ? 'Update Page' : 'Create Page'" @submit="savePage" @cancel="cancelForm" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { reactive, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { BoldIcon, ItalicIcon, ListBulletIcon, LinkIcon, PhotoIcon, CodeBracketIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import FormSection from '@/Components/Shared/Forms/FormSection.vue'
import FormActions from '@/Components/Shared/Forms/FormActions.vue'
import Input from '@/Components/Shared/UI/Input.vue'
import Select from '@/Components/Shared/UI/Select.vue'
import Switch from '@/Components/Shared/UI/Switch.vue'

const props = defineProps({ page: { type: Object, default: null } })
const isEditing = computed(() => !!props.page)

const form = reactive({
  title: props.page?.title || '', slug: props.page?.slug || '',
  content: props.page?.content || '', status: 'draft', show_in_menu: false,
})

const statusOptions = [{ value: 'draft', label: 'Draft' }, { value: 'published', label: 'Published' }]
const editorTools = [BoldIcon, ItalicIcon, ListBulletIcon, LinkIcon, PhotoIcon, CodeBracketIcon]

const savePage = () => { router.post(isEditing.value ? route('admin.cms.pages.update', props.page?.id) : route('admin.cms.pages.store'), form) }
const cancelForm = () => { router.get(route('admin.cms.pages')) }
</script>

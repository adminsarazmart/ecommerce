<template>
  <div class="relative">
    <Listbox v-model="selected" :disabled="disabled">
      <div class="relative">
        <ListboxButton :class="['glass-input w-full flex items-center justify-between gap-2 text-left', disabled ? 'opacity-50 cursor-not-allowed' : '']">
          <span class="block truncate">{{ selected?.label || placeholder || 'Select option' }}</span>
          <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
        </ListboxButton>
        <Transition leave="transition ease-in duration-100" leave-from="opacity-100" leave-to="opacity-0">
          <ListboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-xl bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black/5 dark:ring-white/10 focus:outline-none">
            <div v-if="searchable" class="sticky top-0 px-2 py-2 bg-white dark:bg-gray-800">
              <input v-model="search" type="text" placeholder="Search..." class="glass-input w-full text-sm py-1.5" />
            </div>
            <ListboxOption v-for="option in filteredOptions" :key="option.value" :value="option" as="template" v-slot="{ active, selected: sel }">
              <li :class="['relative cursor-pointer select-none py-2.5 pl-10 pr-4 text-sm', active ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-300' : 'text-gray-700 dark:text-gray-300']">
                <span :class="['block truncate', sel ? 'font-medium' : 'font-normal']">{{ option.label }}</span>
                <span v-if="sel" class="absolute inset-y-0 left-0 flex items-center pl-3 text-brand-600">
                  <CheckIcon class="h-5 w-5" />
                </span>
              </li>
            </ListboxOption>
            <div v-if="filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">No options found</div>
          </ListboxOptions>
        </Transition>
      </div>
    </Listbox>
    <div v-if="error" class="mt-1 text-xs text-red-500">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue'
import { ChevronUpDownIcon, CheckIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
  modelValue: [String, Number],
  options: { type: Array, default: () => [] },
  placeholder: String,
  error: String,
  disabled: Boolean,
  searchable: Boolean,
})

const emit = defineEmits(['update:modelValue'])
const search = ref('')

const selected = computed({
  get: () => props.options.find(o => o.value === props.modelValue) || null,
  set: (val) => emit('update:modelValue', val?.value ?? null),
})

const filteredOptions = computed(() => {
  if (!search.value) return props.options
  return props.options.filter(o => o.label.toLowerCase().includes(search.value.toLowerCase()))
})

watch(() => props.options, () => { search.value = '' })
</script>

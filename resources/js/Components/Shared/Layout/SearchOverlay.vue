<template>
  <Transition name="search-overlay">
    <div v-if="open" class="fixed inset-0 z-[60] bg-black/80 backdrop-blur-lg" @click.self="$emit('close')">
      <div class="max-w-3xl mx-auto pt-20 px-4">
        <div class="relative">
          <MagnifyingGlassIcon class="absolute left-5 top-1/2 -translate-y-1/2 h-6 w-6 text-gray-400" />
          <input ref="inputRef" v-model="query" type="text" placeholder="Search products, categories, brands..." class="w-full bg-white/10 border border-white/20 text-white placeholder-gray-400 text-xl pl-14 pr-12 py-5 rounded-2xl focus:outline-none focus:ring-2 focus:ring-brand-500/50" />
          <button @click="query = ''" v-if="query" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
            <XMarkIcon class="h-5 w-5" />
          </button>
        </div>
        <div v-if="results.length > 0" class="mt-6 glass rounded-2xl p-4 space-y-2 max-h-[50vh] overflow-y-auto">
          <Link v-for="item in results" :key="item.id" :href="item.url" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition-colors" @click="$emit('close')">
            <img :src="item.image || '/placeholder.jpg'" class="w-12 h-12 rounded-lg object-cover" />
            <div>
              <p class="text-sm font-medium text-white">{{ item.name }}</p>
              <p class="text-xs text-gray-400">{{ item.price }}</p>
            </div>
          </Link>
        </div>
        <div v-if="query && isSearching" class="mt-6 text-center text-gray-400">Searching...</div>
        <div class="mt-8 flex justify-center gap-3 text-sm text-gray-500">
          <span>Press <kbd class="px-2 py-0.5 glass rounded text-xs">ESC</kbd> to close</span>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ open: Boolean })
const emit = defineEmits(['close'])

const query = ref('')
const results = ref([])
const isSearching = ref(false)
const inputRef = ref(null)
let debounceTimer = null

watch(query, (val) => {
  clearTimeout(debounceTimer)
  if (val.length < 2) { results.value = []; return }
  debounceTimer = setTimeout(async () => {
    isSearching.value = true
    try {
      const res = await fetch(`/search?q=${encodeURIComponent(val)}`)
      const data = await res.json()
      results.value = data.results || data
    } catch { results.value = [] }
    isSearching.value = false
  }, 300)
})

onMounted(() => { if (props.open) inputRef.value?.focus() })
</script>

<style scoped>
.search-overlay-enter-active { transition: all 0.3s ease-out; }
.search-overlay-leave-active { transition: all 0.2s ease-in; }
.search-overlay-enter-from, .search-overlay-leave-to { opacity: 0; }
</style>

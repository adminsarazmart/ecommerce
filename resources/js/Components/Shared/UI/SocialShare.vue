<template>
  <div class="flex items-center gap-2">
    <span class="text-sm text-gray-500 dark:text-gray-400">{{ label }}</span>
    <button v-for="net in networks" :key="net.name" :class="['p-2 rounded-xl transition-all duration-200', net.color]" @click="share(net.name)">
      <component :is="net.icon" class="h-4 w-4" />
    </button>
  </div>
</template>

<script setup>
defineProps({
  url: { type: String, required: true },
  title: { type: String, default: '' },
  label: { type: String, default: 'Share:' },
})

const networks = [
  { name: 'facebook', icon: 'svg', color: 'hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-600' },
  { name: 'twitter', icon: 'svg', color: 'hover:bg-sky-50 dark:hover:bg-sky-900/20 text-sky-500' },
  { name: 'pinterest', icon: 'svg', color: 'hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600' },
  { name: 'whatsapp', icon: 'svg', color: 'hover:bg-green-50 dark:hover:bg-green-900/20 text-green-600' },
  { name: 'email', icon: 'svg', color: 'hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600' },
]

const share = (network) => {
  const urls = {
    facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
    twitter: `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`,
    pinterest: `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(url)}&description=${encodeURIComponent(title)}`,
    whatsapp: `https://api.whatsapp.com/send?text=${encodeURIComponent(title + ' ' + url)}`,
    email: `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(url)}`,
  }
  window.open(urls[network], '_blank', 'width=600,height=400')
}
</script>

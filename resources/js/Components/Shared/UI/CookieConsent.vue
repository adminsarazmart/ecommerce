<template>
  <Transition name="slide-up">
    <div v-if="!accepted" class="fixed bottom-0 left-0 right-0 z-50 p-4">
      <div class="glass rounded-2xl p-4 max-w-4xl mx-auto flex items-center justify-between gap-4 flex-col sm:flex-row">
        <p class="text-sm text-gray-600 dark:text-gray-400">We use cookies to enhance your experience. By continuing, you agree to our use of cookies.</p>
        <div class="flex gap-2 flex-shrink-0">
          <Button variant="ghost" size="sm" @click="decline">Decline</Button>
          <Button size="sm" @click="accept">Accept All</Button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref } from 'vue'
import Button from './Button.vue'

const accepted = ref(localStorage.getItem('cookie-consent') === 'true')

const accept = () => { accepted.value = true; localStorage.setItem('cookie-consent', 'true') }
const decline = () => { accepted.value = true; localStorage.setItem('cookie-consent', 'false') }
</script>

<template>
  <div class="relative">
    <canvas ref="canvasRef" />
  </div>
</template>

<script setup>
import { ref, onMounted, watch, onUnmounted } from 'vue'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, BarElement, ArcElement, RadialLinearScale, Tooltip, Legend, Filler } from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, ArcElement, RadialLinearScale, Tooltip, Legend, Filler)

const props = defineProps({
  type: { type: String, default: 'line' },
  data: { type: Object, required: true },
  options: { type: Object, default: () => ({}) },
})

const canvasRef = ref(null)
let chartInstance = null

function createChart() {
  if (!canvasRef.value) return
  if (chartInstance) chartInstance.destroy()
  const ctx = canvasRef.value.getContext('2d')
  const isDark = document.documentElement.classList.contains('dark')

  const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { labels: { color: isDark ? '#9ca3af' : '#6b7280', font: { family: 'Inter' } } },
    },
    scales: {
      x: { grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }, ticks: { color: isDark ? '#9ca3af' : '#6b7280' } },
      y: { grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }, ticks: { color: isDark ? '#9ca3af' : '#6b7280' } },
    },
  }

  const mergedOptions = { ...defaultOptions, ...props.options }
  chartInstance = new ChartJS(ctx, { type: props.type, data: props.data, options: mergedOptions })
}

onMounted(createChart)
watch(() => props.data, createChart, { deep: true })

const observer = new MutationObserver(() => { if (chartInstance) createChart() })
onMounted(() => observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] }))
onUnmounted(() => { observer.disconnect(); chartInstance?.destroy() })
</script>

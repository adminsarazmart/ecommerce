import './bootstrap';
import { createApp, h, watch } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import { useAppStore } from '@/Stores/app';

const appName = import.meta.env.VITE_APP_NAME || 'NexusMart';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(createPinia());
        app.use(ZiggyVue);
        app.mount(el);

        const appStore = useAppStore();
        watch(() => appStore.theme, (val) => {
            try { document.documentElement.classList.toggle('dark', val === 'dark') } catch {}
        }, { immediate: true });
    },
    progress: {
        color: '#6366f1',
        showSpinner: true,
        includeCSS: true,
    },
});

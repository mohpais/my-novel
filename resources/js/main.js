import '@/assets/vendors/fontawesome-free-6.2.1-web/css/all.min.css';
import 'flatpickr/dist/flatpickr.min.css';
import 'vue3-toastify/dist/index.css';
import 'leaflet/dist/leaflet.css'

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import VueApexCharts from 'vue3-apexcharts';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';

import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-quartz.min.css";
import "ag-grid-community/styles/ag-theme-balham.min.css";
import { AgGridVue } from "ag-grid-vue3";

import Vue3Toastify from 'vue3-toastify';

// Import Pinia Store Anda
import { useAuthStore } from '@/stores/useAuthStore';
import { useTranslation } from '@/composables/useTranslation';

import App from '@/App.vue';
import router from '@/routes';
const { i18n } = useTranslation();

import CommonComponents from "@/components/commons";

const CommonsComponents = {
    install(app) {
        for (const prop in CommonComponents) {
            if (Object.prototype.hasOwnProperty.call(CommonComponents, prop)) {
                const component = CommonComponents[prop];
                app.component(component.__name, component);
            }
        }
    },
};
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate); // Pasang plugin persistensi

const app = createApp(App);
app
    .use(pinia)
    .use(router)
    .use(i18n)
    .use(CommonsComponents)
    .use(Vue3Toastify, {
        autoClose: 2000, // Durasi default toast
        appendToBody: true,      // ⬅️ ini penting
        theme: 'light'
    })
    .use(VueApexCharts)
    .component("AgGridVue", AgGridVue);

// Panggil action initializeAuth dari store
router.isReady().then(async () => {
    const authStore = useAuthStore();
    await authStore.initializeAuth();

    // Set global property
    app.config.globalProperties.$auth = authStore;
});

// Global error handler yang lebih ringkas
app.config.errorHandler = (err, instance, info) => {
    console.error('Vue Error:', err, info);
};

app.mount('#app')

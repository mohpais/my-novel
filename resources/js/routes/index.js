// src/routes/index.js
import { createRouter, createWebHistory } from "vue-router";
import { usePageTransition } from '@/composables/usePageTransition';
import { useAuthStore } from '@/stores/useAuthStore';
import { queueToastAfterLayout } from '@/composables/useToastAfterLayout'

import routeDefinitions from './routes-definitions.js';

const appName = import.meta.env.VITE_APP_NAME || 'Fixed Asset Management System';

const router = createRouter({
    history: createWebHistory(),
    routes: routeDefinitions,
    scrollBehavior(to, from, savedPosition) {
        return savedPosition || { top: 0 };
    },
});

router.beforeEach(async (to, from, next) => {
    // Jika bukan aset statis, lanjutkan dengan routing Vue seperti biasa.
    const authStore = useAuthStore();
    const { start } = usePageTransition();

    start();
    document.title = to.meta.title || appName;
    
    // Inisialisasi Auth Store dari localStorage
    if (!authStore.isLoggedIn && localStorage.getItem('X-TOKEN')) {
        await authStore.fetchUser().catch(() => {
            // Jika fetch user gagal, biarkan navigation guard di bawah menangani redirect
        });
    }

    const { requiresAuth, redirectIfLoggedIn, roles } = to.meta;

    // Guard: Jika halaman memerlukan otentikasi
    if (requiresAuth && !authStore.isLoggedIn) {
        queueToastAfterLayout('Anda perlu login untuk mengakses halaman ini.', { type: 'warning' });
        return next({ name: 'LoginPage', query: { redirect: to.fullPath } });
    }

    // Guard: Jika halaman tidak boleh diakses oleh yang sudah login
    if (redirectIfLoggedIn && authStore.isLoggedIn) {
        queueToastAfterLayout('Anda sudah login.', { type: 'info' });
        return next({ name: 'home' });
    }

    // Guard: Role-Based Access Control (RBAC)
    if (roles && roles.length > 0) {
        // Logika isLoggedIn sudah di atas, jadi tidak perlu cek lagi
        if (!authStore.hasRole(roles)) {
            queueToastAfterLayout('Anda tidak memiliki izin untuk mengakses halaman ini.', { type: 'error' });
            return next({ name: 'home' }); // Redirect ke halaman 403 atau home
        }
    }
    
    // Jika semua guard lolos
    next();
});

router.afterEach((to, from, failure) => {
    const { stop } = usePageTransition();
    stop(300);
});

export default router;
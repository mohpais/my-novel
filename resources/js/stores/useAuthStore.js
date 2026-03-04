// src/stores/useAuthStore.js
import { defineStore } from 'pinia'
import apiService from '@/utils/apiService'
import router from '@/routes'
import { queueToastAfterLayout, flushToastQueue } from '@/composables/useToastAfterLayout'

export const useAuthStore = defineStore('useAuthStore', {
    state: () => ({
        user: null,
        token: localStorage.getItem('X-TOKEN') || null,
        isAuthenticated: false,
        isLoading: false,
        error: null,
    }),

    getters: {
        isLoggedIn: state => state.isAuthenticated && state.user !== null && state.token !== null,
        currentUser: state => state.user,
        authToken: state => state.token,
        authLoading: state => state.isLoading,
        authError: state => state.error,
        hasRole: state => roles => {
            if (!state.isAuthenticated || !state.user?.role) return false
            if (!Array.isArray(roles)) roles = [roles]
            return roles.some(r => state.user.role?.code === r)
        },

        isRequester: state => state.user?.role?.code === 'requester',   // pakai optional chaining
        isCurrentApproval: state => role => {
            if (!state.user?.role || !role) return false
            return state.user.role.code === role
        },
    },

    actions: {
        /**
         * Simpan token & set status auth
         */
        setAuthData(tokenValue) {
            localStorage.setItem('X-TOKEN', tokenValue);

            this.token = tokenValue
            this.isAuthenticated = true
        },

        /**
         * Login
         */
        async doLogin(credentials) {
            this.isLoading = true
            this.error = null

            try {
                const { data } = await apiService.post('/auth/login', credentials)
                if (!data.success) {
                    flushToastQueue('Email atau password salah!', { type: 'error' })
                    return
                }

                const { user, access_token } = data.data
                this.setAuthData(access_token)
                this.user = user
                this.lastAuthAttempt = Date.now()

                // 🔑 Ambil redirect dari query string (misal: ?redirect=/dashboard)
                const redirectPath = router.currentRoute.value.query.redirect
                let redirectRoute = { name: 'Novel' };
                if (redirectPath) {
                    redirectRoute = redirectPath // langsung pakai path
                }

                // 🚀 Redirect berdasarkan role
                // const currentRole = user.role?.code || null;
                // let redirectRoute = { name: 'Home' };

                // const adminRoles = ['superadmin', 'admin'];

                // if (adminRoles.includes(currentRole)) {
                //     redirectRoute = { name: 'Dashboard' }
                // }

                router.push(redirectRoute)

                queueToastAfterLayout(`Selamat datang, ${user.name || user.email}!`, { type: 'success' })
            } catch (error) {
                this.error = error.response?.data?.message || 'Login gagal. Mohon coba lagi.'

                flushToastQueue(this.error, { type: 'error' })
                this.isAuthenticated = false
                throw error
            } finally {
                this.isLoading = false
            }
        },

        /**
         * Logout
         */
        async doLogout() {
            this.isLoading = true
            try {
                // Opsional: kirim request logout ke backend
                await apiService.post('/auth/logout')
            } catch (error) {
                console.warn('Gagal logout dari backend (mungkin token sudah kadaluarsa):', error)
            } finally {
                this.clearAuthData()

                queueToastAfterLayout('Anda telah berhasil logout.', { type: 'info' })

                this.isLoading = false

                // Redirect ke halaman login
                router.push({ name: 'Login' })
            }
        },

        /**
         * Bersihkan data auth
         */
        clearAuthData() {
            localStorage.removeItem('X-TOKEN');
            this.token = null
            this.user = null
            this.isAuthenticated = false
            this.lastAuthAttempt = null
        },

        /**
         * Fetch user (validasi token)
         */
        async fetchUser() {
            this.isLoading = true
            this.error = null

            try {
                const response = await apiService.get('/auth/me');
                this.user = response.data;

                this.isAuthenticated = true
            } catch (error) {
                // this.clearAuthData()
                throw error
            } finally {
                this.isLoading = false
            }
        },

        /**
         * Jalankan saat init app
         */
        async initializeAuth() {
            if (this.token && this.user === null) {
                await this.fetchUser()
            }
        },
    },
})

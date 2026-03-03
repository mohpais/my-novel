// src/utils/apiService.js
import axios from 'axios';
import { useAuthStore } from '@/stores/useAuthStore';
import { queueToastAfterLayout } from '@/composables/useToastAfterLayout'
import router from '../routes';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/';

const apiService = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Accept': 'application/json',
  },
  timeout: 10000,
});

// Request Interceptor: Tambahkan token jika ada
apiService.interceptors.request.use(
  (config) => {
    if (config.skipAuth === undefined || config.skipAuth !== true) {
      const authStore = useAuthStore();
      if (authStore.token) {
        config.headers.Authorization = `Bearer ${authStore.token}`;
      }
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response Interceptor: Penanganan error global
apiService.interceptors.response.use(
  (response) => response,
  (error) => {
    const authStore = useAuthStore();

    const status = error.response?.status;
    const originalRequest = error.config;

    // Logika penanganan token kadaluwarsa (401)
    if (status === 401 && !originalRequest._retry) {
      originalRequest._retry = true; // Tandai request agar tidak diulang lagi

      // Coba refresh token atau logout langsung
      authStore.clearAuthData();
      queueToastAfterLayout('Sesi Anda telah habis. Silakan login kembali.', { type: 'error' })
      // Redirect ke halaman login. Gunakan router.push jika memungkinkan
      // window.location.href = '/auth/login'; // Atau cara ini jika perlu hard-reload
      router.push({ name: 'Login' });

      return Promise.reject(error);
    }
    
    // Global error toast untuk error lainnya
    if (!originalRequest.skipErrorToast) {
      const msg = error.response?.data?.message || 'Terjadi kesalahan pada server.';
      queueToastAfterLayout(msg, { type: 'error' })
    }

    return Promise.reject(error);
  }
);

// Sederhanakan export dengan fungsi yang lebih ringkas
export const api = {
  get: (url, config) => apiService.get(url, config).then(res => res.data),
  post: (url, data, config) => apiService.post(url, data, config).then(res => res.data),
  put: (url, data, config) => apiService.put(url, data, config).then(res => res.data),
  del: (url, config) => apiService.delete(url, config).then(res => res.data),
};

export default apiService;
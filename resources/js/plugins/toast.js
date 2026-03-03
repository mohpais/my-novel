// src/plugins/toast.js
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export const useToast = () => ({
    success: (msg) => toast.success(msg, { position: 'top-right', theme: 'colored' }),
    error: (msg) => toast.error(msg, { position: 'top-right', theme: 'colored' }),
    info: (msg) => toast.info(msg, { position: 'top-right', theme: 'colored' }),
    warn: (msg) => toast.warn(msg, { position: 'top-right', theme: 'colored' }),
});

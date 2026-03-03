// src/composables/useDebounce.js

import { ref, watch, customRef } from 'vue';

/**
 * Composable untuk menerapkan debounce pada sebuah nilai ref.
 * Fungsi ini menunda pembaruan nilai ref hingga periode waktu tertentu berlalu
 * tanpa adanya perubahan lebih lanjut pada nilai input.
 *
 * @param {import('vue').Ref} value - Ref yang ingin di-debounce.
 * @param {number} delay - Waktu tunda dalam milidetik (ms).
 * @returns {import('vue').Ref} - Ref yang sudah di-debounce.
 */
export function useDebounce(value, delay = 500) {
    // Gunakan ref untuk menyimpan nilai yang sudah di-debounce
    const debouncedValue = ref(value.value);

    // Gunakan watcher untuk memantau perubahan pada nilai asli
    watch(value, (newValue) => {
        // Setiap kali nilai asli berubah, reset timer
        clearTimeout(debouncedValue.timeout);

        // Set timer baru
        debouncedValue.timeout = setTimeout(() => {
            debouncedValue.value = newValue;
        }, delay);
    }, {
        immediate: true // Jalankan watcher segera untuk inisialisasi awal
    });

    return debouncedValue;
}

// --- Alternatif: useDebouncedRef (Lebih canggih menggunakan customRef) ---
// Ini memungkinkan Anda untuk langsung mendeklarasikan ref yang sudah didebounce.
// Mungkin sedikit lebih kompleks, tapi lebih "Vue-y" dalam implementasinya.

/**
 * Membuat ref yang secara otomatis di-debounce.
 * Nilai ref hanya akan diperbarui setelah jeda waktu tertentu berlalu
 * tanpa adanya perubahan.
 *
 * @param {*} value - Nilai awal untuk ref.
 * @param {number} delay - Waktu tunda dalam milidetik (ms).
 * @returns {import('vue').Ref} - Ref yang sudah di-debounce.
 */
export function useDebouncedRef(value, delay = 500) {
    let timeout;
    return customRef((track, trigger) => {
        return {
            get() {
                track(); // Beri tahu Vue untuk melacak dependensi ini
                return value;
            },
            set(newValue) {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                value = newValue;
                trigger(); // Beri tahu Vue bahwa nilai telah berubah dan perlu di-render ulang
                }, delay);
            },
        };
    });
}

// --- Alternatif lain: useDebouncedFunction (untuk mendebounce fungsi, bukan nilai ref) ---
// Berguna jika Anda ingin menunda eksekusi fungsi callback, terlepas dari nilai reaktif.

/**
 * Composable untuk mendebounce eksekusi sebuah fungsi.
 *
 * @param {Function} func - Fungsi yang ingin di-debounce.
 * @param {number} delay - Waktu tunda dalam milidetik (ms).
 * @returns {Function} - Fungsi yang sudah di-debounce.
 */
export function useDebouncedFunction(func, delay = 500) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
}

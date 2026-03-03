<template>
  <div class="flex items-center">
    <label
      :for="id"
      class="flex cursor-pointer select-none items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-400"
    >
      <div class="relative">
        <input
          type="checkbox"
          :id="id"
          class="sr-only"
          :checked="modelValue"
          @change="handleChange"
          :disabled="disabled"
        />
        <div
          class="block h-6 w-11 rounded-full"
          :class="{
            'bg-brand-500 dark:bg-brand-500': modelValue,
            'bg-gray-300 dark:bg-gray-600': !modelValue,
            'opacity-70 cursor-not-allowed': disabled
          }"
        ></div>
        <div
          class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow-theme-sm duration-300 ease-linear"
          :class="{
            'translate-x-full': modelValue,
            'translate-x-0': !modelValue
          }"
        ></div>
      </div>
      <span v-if="label">{{ label }}</span>
    </label>
  </div>
</template>

<script setup>
const props = defineProps({
  /**
   * Status saat ini dari toggle (true untuk dicentang, false untuk tidak dicentang).
   * Gunakan dengan `v-model`.
   */
  modelValue: {
    type: Boolean,
    default: false,
  },
  /**
   * ID unik untuk input checkbox. Penting untuk aksesibilitas.
   */
  id: {
    type: String,
    required: true,
  },
  /**
   * Teks label yang ditampilkan di samping toggle.
   */
  label: {
    type: String,
    default: '',
  },
  /**
   * Menentukan apakah toggle dinonaktifkan.
   */
  disabled: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['update:modelValue', 'change']);

const handleChange = (event) => {
  if (props.disabled) return;
  const newValue = event.target.checked;
  emit('update:modelValue', newValue);
  emit('change', newValue);
};
</script>

<style scoped>
/*
  Anda mungkin perlu mendefinisikan warna kustom seperti 'brand-500'
  dan 'shadow-theme-sm' di konfigurasi Tailwind CSS Anda (tailwind.config.js)
  jika belum ada.
*/

/* Contoh definisi jika menggunakan JIT mode atau tanpa kustomisasi theme */
/* Anda dapat menghapus ini jika sudah ada di tailwind.config.js */

/* Contoh:
.bg-brand-500 {
  background-color: #4f46e5; // Contoh warna biru indigo
}
.dark\:bg-brand-500 {
  background-color: #6366f1; // Contoh warna biru indigo yang sedikit lebih terang untuk dark mode
}
.shadow-theme-sm {
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); // Contoh shadow kecil
}
*/
</style>
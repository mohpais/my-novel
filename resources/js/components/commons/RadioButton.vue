<template>
  <div :class="['flex', isInline ? 'gap-4 flex-wrap' : 'flex-col gap-2', additionalClasses]">
    <div
      v-for="option in options"
      :key="option.value || option.id || option"
      class="flex items-center gap-2 cursor-pointer"
    >
      <input
        type="radio"
        :id="`${name}-${option.value || option.id || option}`"
        :name="name"
        :value="option.value || option.id || option"
        class="form-check-input hidden"
        :checked="modelValue === (option.value || option.id || option)"
        :disabled="disabled || option.disabled"
        :readonly="readonly"
        @change="handleRadioChange($event, option)"
        @blur="handleBlurInternal"
      />
      <label
        :for="`${name}-${option.value || option.id || option}`"
        class="flex gap-2 items-center cursor-pointer"
        :class="{
          'opacity-50 pointer-events-none': disabled || option.disabled || readonly,
        }"
      >
        <div
          class="border border-secondary rounded-full w-4.5 h-4.5 flex items-center justify-center transition-all duration-200 ease-in-out"
          :class="{
            '!border-2 !border-primary': modelValue === (option.value || option.id || option)
          }"
        >
          <div
            class="rounded-full w-2.5 h-2.5 transition-all duration-200 ease-in-out"
            :class="{ 'bg-primary': modelValue === (option.value || option.id || option) }"
          ></div>
        </div>
        <div class="text-md text-zinc-600 dark:text-white">
          {{ option.text || option.name || option }}
        </div>
      </label>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  // Nama field, penting untuk mengelompokkan radio button agar hanya satu yang bisa dipilih
  name: {
    type: String,
    required: true,
  },
  // Nilai v-model. Hanya bisa String, Number, atau Boolean untuk single target.
  modelValue: {
    type: [String, Number, Boolean, Object], // Object bisa digunakan jika value adalah object
    default: undefined,
  },
  // Daftar pilihan radio button yang akan di-loop
  // Format: [{ value: 'id1', text: 'Option 1' }, { value: 'id2', text: 'Option 2' }, 'id3', ...]
  options: {
    type: Array,
    required: true,
  },
  // Mengatur layout pilihan secara inline (horizontal) atau block (vertikal)
  isInline: {
    type: Boolean,
    default: false,
  },
  // Status disabled untuk seluruh grup radio button
  disabled: {
    type: Boolean,
    default: false,
  },
  // Status readonly untuk seluruh grup radio button
  readonly: {
    type: Boolean,
    default: false,
  },
  // Kelas CSS tambahan untuk div pembungkus
  additionalClasses: {
    type: [String, Array, Object],
    default: '',
  },
  // Prop yang diteruskan oleh FormField untuk penanganan nilai VeeValidate
  handleChange: {
    type: Function,
    default: undefined,
  },
  // Prop yang diteruskan oleh FormField untuk penanganan blur VeeValidate
  handleBlur: {
    type: Function,
    default: undefined,
  },
});

const emit = defineEmits(['update:modelValue', 'change', 'blur']);

// Handler untuk event 'change' pada input radio
const handleRadioChange = (event, option) => {
  // Ambil nilai dari opsi yang dipilih
  const selectedValue = option.value || option.id || option;

  // Panggil handleChange dari FormField jika ada, atau emit update:modelValue secara mandiri
  if (props.handleChange) {
    props.handleChange(selectedValue);
  } else {
    emit('update:modelValue', selectedValue);
  }
  emit('change', selectedValue); // Emit event change juga
};

// Handler internal untuk blur yang akan memanggil handleBlur dari FormField jika ada
const handleBlurInternal = (event) => {
  if (props.handleBlur) {
    props.handleBlur(event); // Teruskan event objek ke handleBlur VeeValidate
  }
  emit('blur', props.modelValue); // Emit event blur juga
};
</script>

<style scoped>
/* Anda bisa menambahkan gaya kustom di sini jika perlu */
/* Pastikan warna primary terdefinisi di konfigurasi Tailwind CSS Anda */
/* .form-check-input:checked + label > div > div {
  background-color: theme('colors.primary');
} */

/* .form-check-input:checked + label > div {
  border-color: theme('colors.primary');
} */
</style>
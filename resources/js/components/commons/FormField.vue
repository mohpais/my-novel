<template>
    <div>
        <label v-if="labelName" :for="props.id" class="mb-1.5 block font-medium text-gray-700 dark:text-gray-400">
            {{ labelName }}
            <span v-if="isRequired" class="text-error-500">*</span>
        </label>

        <component
            v-if="as !== 'custom' && !hasDefaultSlot"
            :is="componentType"
            :id="props.id"
            :name="name"
            :modelValue="inputValue"
            v-bind="dynamicProps"
            @update:modelValue="onInput"
            @blur="onBlur"
            :disabled="disabled"
            :readonly="readonly"
            :aria-invalid="!!errorMessage"
            :aria-describedby="errorMessage ? `${props.id}-error` : undefined"
            ref="inputRef"
        />

        <slot
            v-else
            :id="props.id"
            :field-name="name"
            :modelValue="inputValue"
            :error="errorMessage"
            :handleChange="onInput"
            :handleBlur="onBlur"
            :disabled="disabled"
            :readonly="readonly"
            :aria-invalid="!!errorMessage"
            :aria-describedby="errorMessage ? `${id}-error` : undefined"
            ref="inputRef"
        />

        <Transition name="fade">
            <p v-if="errorMessage" :id="`${id}-error`" class="text-red-600 mt-1.5 text-theme-xs" aria-live="assertive">
                {{ errorMessage }}
            </p>
        </Transition>
    </div>
</template>

<script setup>
    import { computed, defineAsyncComponent, inject, onMounted, ref, toRefs, useSlots } from 'vue';
    import { defineRule, configure, useField } from 'vee-validate';
    import { all } from '@vee-validate/rules';
    import { localize, setLocale } from '@vee-validate/i18n';

    import en from "@/locales/en.js"; // Pastikan path benar
    import id from "@/locales/id.js"; // Pastikan path benar

    // Import komponen dinamis (Pastikan path dan nama file benar)
    const Input = defineAsyncComponent(() => import("./Input.vue"));
    const Checkbox = defineAsyncComponent(() => import("./Checkbox.vue"));
    const Textarea = defineAsyncComponent(() => import("./Textarea.vue"));
    const RadioButton = defineAsyncComponent(() => import("./RadioButton.vue"));
    const Select = defineAsyncComponent(() => import("./Select.vue"));
    const Datepicker = defineAsyncComponent(() => import("./Datepicker.vue")); // Import Datepicker.vue

    const props = defineProps({
        // Tipe komponen yang akan dirender (input, radio, checkbox, select, textarea, custom)
        as: {
            type: String,
            default: 'input',
            validator: (value) => ['input', 'radio', 'checkbox', 'select', 'textarea', 'custom', 'datepicker'].includes(value)
        },
        // Nama field, penting untuk VeeValidate
        name: {
            type: String,
            required: true,
        },
        // ID unik untuk elemen input, default ke name
        id: {
            type: String,
            required: true,
        },
        // Label yang ditampilkan untuk field
        labelName: {
            type: String,
            default: '',
        },
        // Label yang ditampilkan untuk checkbox (jika as adalah checkbox)
        checkboxName: {
            type: String,
            default: '',
        },
        // Kelas CSS tambahan untuk div pembungkus FormField
        className: {
            type: String,
            default: '',
        },
        isNumber: {
            type: Boolean,
            default: false,
        },
        // Placeholder untuk input (jika relevan)
        placeholder: {
            type: String,
            default: '',
        },
        // Tipe input HTML (text, number, email, password, dll)
        type: {
            type: String,
            default: 'text',
        },
        // Opsi untuk radio, checkbox, select
        optionsValue: {
            type: Array,
            default: () => [],
        },
        // Aturan validasi VeeValidate
        rules: {
            type: [String, Object, Function],
            default: '',
        },
        // Nilai model saat ini (v-model)
        modelValue: {
            type: [String, Number, Array, Boolean, Object],
            default: undefined,
        },
        // Properti required (bukan aturan validasi, tapi untuk indikator visual)
        required: {
            type: Boolean,
            default: false,
        },
        // Status disabled
        disabled: {
            type: Boolean,
            default: false,
        },
        // Status readonly
        readonly: {
            type: Boolean,
            default: false,
        },
        // Jumlah baris untuk textarea
        rows: {
            type: [String, Number],
            default: 3,
        },
        // Kelas CSS tambahan yang akan diteruskan ke komponen input internal
        additionalClasses: {
            type: [String, Array, Object],
            default: '',
        },
        // Prop khusus untuk checkbox/radio, misal: isInline
        isInline: {
            type: Boolean,
            default: false,
        },
        // Prop untuk select (misal searchable)
        searchable: {
            type: Boolean,
            default: false,
        },
        minDate: { type: String, default: '' },
        maxDate: { type: String, default: '' },
        displayFormat: { type: String, default: 'YYYY-MM-DD HH:mm' },
        yearRange: { type: [String, Number], default: 50 }, // Tahun range untuk datepicker
        // Untuk menandai apakah field sedang dalam status loading (misal untuk select dengan data async)
        isLoading: { type: Boolean, default: false },
    });

    const { name, as, type, optionsValue, label, rules, modelValue, disabled, readonly, rows, required, isInline, isLoading } = toRefs(props);

    const slots = useSlots();
    // Memeriksa apakah slot default memiliki node yang dirender
    const hasDefaultSlot = computed(() => {
        return !!slots.default && slots.default().some(vnode => {
            // filters out whitespace-only nodes and comments
            return vnode.type !== Comment && vnode.type !== Text && typeof vnode.type !== 'symbol';
        });
    });

    const emit = defineEmits(['update:modelValue', 'change', 'blur']);

    // Menggunakan useField dari vee-validate untuk mengelola state input dan validasi
    // `meta` memberikan informasi tentang state field (dirty, touched, valid)
    const {
        value: inputValue,
        errorMessage,
        handleChange,
        handleBlur,
        meta,
        validate // Tambahkan validate untuk memicu validasi secara manual
    } = useField(name.value, rules.value, {
        initialValue: modelValue.value,
        // syncVModel: true, // v4 VeeValidate secara otomatis menyinkronkan dengan modelValue
        // Jika Anda ingin validasi terjadi "on blur" secara default untuk semua field VeeValidate
        // Anda bisa mengkonfigurasi `validateOnBlur` di `configure` global,
        // atau jika per field, gunakan: validateOnValueUpdate: false, validateOnBlur: true
    });

    // Tambahkan ref untuk elemen input
    const inputRef = ref(null);
    const registerField = inject("registerField", null);

    onMounted(() => {
        if (registerField) {
            registerField(props.name, { inputRef });
        }
    });

    // Wrapper untuk handleChange VeeValidate agar juga emit 'update:modelValue' dan 'change'
    const onInput = (e) => {
        // Ambil nilai dari event atau langsung dari parameter (jika komponen custom langsung memancarkan nilai)
        const newValue = e && typeof e === 'object' && e.target && e.target.value !== undefined ? e.target.value : e;
        handleChange(newValue); // Panggil handleChange VeeValidate
        emit('update:modelValue', newValue);
        emit('change', newValue);
    };

    // Wrapper untuk handleBlur VeeValidate agar juga emit 'blur'
    const onBlur = (e) => {
        handleBlur(e); // Panggil handleBlur VeeValidate
        emit('blur', inputValue.value);
    };

    // Dynamic component type
    const componentType = computed(() => {
        switch (as.value) {
            case 'radio':
                return RadioButton;
            case 'checkbox':
                return Checkbox;
            case 'select': // Jika Anda punya komponen CustomSelect
                return Select;
            case 'datepicker': // Tambahkan case untuk datepicker
                return Datepicker;
            case 'textarea': // Jika Anda punya komponen Textarea
                return Textarea;
            case 'input':
            default:
                return Input;
        }
    });

    // Dynamic props untuk komponen input standar
    const dynamicProps = computed(() => {
        const commonProps = {
            additionalClasses: props.additionalClasses,
            disabled: disabled.value,
            readonly: readonly.value,
            // Teruskan handleChange dan handleBlur dari useField
            // Ini penting agar komponen internal dapat memicu validasi VeeValidate
            handleChange: onInput, // Penting: Teruskan handleChange dari useField
            handleBlur: onBlur,     // Penting: Teruskan handleBlur dari useField
        };

        switch (as.value) {
            case 'radio':
                return {
                    ...commonProps,
                    options: optionsValue.value, // Prop optionsValue dari FormField diteruskan ke RadioButton sebagai options
                    isInline: props.isInline,    // Teruskan prop isInline
                };
            case 'checkbox':
                return {
                    ...commonProps,
                    labelName: props.checkboxName, // Label untuk checkbox/radio
                };
            case 'select':
                return {
                    ...commonProps,
                    isLoading: isLoading.value,
                    options: optionsValue.value, // optionsValue dari FormField diteruskan ke Select.vue
                    multiple: type.value === 'multiple-select', // Asumsi type 'multiple-select' untuk select multiple
                    searchable: props.searchable, // Teruskan prop searchable
                    placeholder: props.placeholder,
                };
            case 'textarea':
                return {
                    ...commonProps,
                    rows: rows.value,
                    placeholder: props.placeholder,
                };
            case 'datepicker': // Tambahkan prop spesifik untuk datepicker
                return {
                    ...commonProps,
                    minDate: props.minDate,
                    maxDate: props.maxDate,
                    displayFormat: props.displayFormat,
                    yearRange: props.yearRange,
                };
            case 'input':
                return {
                    ...commonProps,
                    placeholder: props.placeholder,
                    isNumber: props.isNumber,
                    type: type.value,
                };
            default:
                return {
                    ...commonProps,
                    placeholder: props.placeholder,
                    type: type.value,
                };
        }
    });

    // Menentukan apakah field required (untuk indikator visual pada label)
    const isRequired = computed(() => {
        // Cek jika prop `required` langsung true
        if (required.value) {
            return true;
        }
        // Jika rules adalah string, cek apakah mengandung 'required'
        if (typeof rules.value === 'string') {
            return rules.value.includes('required');
        }
        // Jika rules adalah objek, cek properti 'required'
        if (typeof rules.value === 'object' && rules.value !== null) {
            return rules.value.required === true;
        }
        // Jika rules adalah fungsi, kita tidak bisa dengan mudah menentukan
        // tapi jika fungsi tersebut akan memvalidasi required, kita mungkin ingin
        // menambahkan cara untuk memberi tahu FormField.
        return false;
    });

    // --- Definisi Aturan VeeValidate ---
    // Definisikan semua aturan bawaan dari VeeValidate
    Object.keys(all).forEach((rule) => defineRule(rule, all[rule]));

    // Aturan Kustom (Pindahkan di sini agar lebih terstruktur)
    defineRule('min_selected', (value, [limit]) => {
        // value diharapkan adalah array ID yang dipilih
        if (!Array.isArray(value) || value.length === 0) {
            // Jika tidak ada pilihan, dan field ini tidak required, biarkan validasi 'required' yang menangani
            // atau jika rules tidak termasuk required, anggap valid untuk kasus 0 pilihan.
            // Jika kita mengharapkan array dan tidak kosong, maka ini akan gagal.
            // Untuk kasus SingleChoicePK, kita tahu selalu ada setidaknya satu pilihan jika valid,
            // jadi array kosong berarti tidak valid untuk min_selected (jika > 0).
            return false; // Anggap tidak valid jika bukan array atau kosong
        }
        return value.length >= Number(limit);
    });

    defineRule('phone', (value) => {
        if (!value) return true;
        const phonePattern = /^(\+?\d{1,3}[- ]?)?0?\d{8,15}$/;
        return phonePattern.test(value);
    });

    defineRule('max_selected', (value, [limit]) => {
        if (!Array.isArray(value)) {
            return true; // Jika bukan array, biarkan aturan lain yang menangani
        }
        return value.length <= Number(limit);
    });

    defineRule('confirmed', (value, [targetField], ctx) => {
        return value === ctx.form[targetField];
    });

    defineRule('numeric', (value) => {
        return typeof value === 'number' || /^\d+$/.test(value);
    });

    defineRule('min', (value, [length]) => {
        if (typeof value === 'string') {
            return value.length >= Number(length);
        }
        return true; // Biarkan aturan lain menangani tipe non-string
    });

    // Definisi aturan kustom VeeValidate untuk perbandingan tanggal
    defineRule('min_date', (value, [minDate]) => {
        if (!value || !minDate) {
            return true; // Biarkan aturan 'required' yang menangani jika kosong
        }
        const selected = new Date(value);
        const min = new Date(minDate);
        // Tambahkan 1 hari ke minDate untuk memastikan pemilihan tanggal setelahnya
        // (tergantung kebutuhan bisnis, apakah check-out bisa di hari yang sama dengan check-in)
        min.setDate(min.getDate() + 0); // Jika bisa di hari yang sama, gunakan 0
        return selected >= min;
    });

    defineRule('max', (value, [length]) => {
        if (typeof value === 'string') {
            return value.length <= Number(length);
        }
        return true; // Biarkan aturan lain menangani tipe non-string
    });

    defineRule('email', (value) => {
        if (!value) return true; // Allow empty if not required
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(value);
    });

    defineRule('required', (value) => {
        if (typeof value === 'undefined' || value === null || value === '' || (Array.isArray(value) && value.length === 0)) {
            return false;
        }
        return true;
    });

    // --- Konfigurasi VeeValidate Global ---
    configure({
        generateMessage: localize({ id, en }),
        // Mengkonfigurasi kapan validasi dipicu secara default
        validateOnBlur: true,        // Validasi saat field kehilangan fokus
        validateOnChange: false,     // Jangan validasi saat setiap keystroke (lebih baik pakai onBlur/onSubmit)
        validateOnInput: false,      // Jangan validasi saat setiap input (kecuali untuk kasus tertentu)
        validateOnModelUpdate: true, // Validasi saat modelValue diperbarui (penting untuk v-model)
    });
    setLocale(localStorage.getItem('locale') || 'id'); // Set locale berdasarkan localStorage atau default

    // --- Watchers ---
    import { watch } from 'vue'; // Import watch di sini

    // Sinkronkan inputValue VeeValidate dengan modelValue prop
    watch(
        () => props.modelValue,
        (newValue) => {
            if (newValue !== inputValue.value) {
                inputValue.value = newValue;
                // Panggil `validate` secara manual untuk memicu validasi setelah perubahan eksternal
                // Ini penting jika Anda ingin feedback validasi segera setelah nilai berubah dari luar
                // Atau hanya validasi jika sudah disentuh/diubah
                if (meta.value.touched || meta.value.dirty) {
                    validate();
                }
            }
        },
        { deep: true } // Gunakan deep watch untuk array/objek
    );

    // Watch for rules changes to re-run validation
    watch(rules, () => {
        validate(); // Re-validate if rules prop changes
    }, { deep: true });

    // Watch for `disabled` or `readonly` changes (opsional, tergantung kebutuhan)
    // Jika Anda ingin validasi diset ulang ketika field dinonaktifkan/diaktifkan
    watch([disabled, readonly], () => {
        validate();
    });

    // PENTING: Mengekspos inputRef agar bisa diakses oleh parent
    defineExpose({
        inputRef,
        name // Mengekspos nama juga untuk memudahkan pencarian
    });
</script>

<style>
    /* Optional: Tambahkan transisi untuk pesan kesalahan */
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.3s ease;
    }
    .fade-enter-from, .fade-leave-to {
        opacity: 0;
    }
</style>

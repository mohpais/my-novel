<template>
    <input
        :id="id"
        :name="name"
        :type="type"
        :value="displayValue"
        :inputmode="isNumber ? 'numeric' : type"
        :placeholder="placeholder"
        :disabled="disabled"
        :readonly="readonly"
        :class="inputClasses"
        v-bind="$attrs"
        :autocomplete="preventAutocomplete ? 'on' : 'off'"
        @input="handleInput"
        @blur="handleBlur"
        @keypress="restrictToNumeric"
    />
</template>

<script setup>
    import { computed, defineEmits, ref, watch } from 'vue';

    const props = defineProps({
        // ID unik untuk elemen input
        id: {
            type: String,
            required: true,
        },
        // Nama field untuk form submission (penting untuk VeeValidate)
        name: {
            type: String,
            required: true,
        },
        // Tipe input HTML (text, number, email, password, dll.)
        type: {
            type: String,
            default: 'text',
        },
        isNumber: {
            type: Boolean,
            default: false,
        },
        // Nilai model saat ini (digunakan dengan v-model)
        modelValue: {
            type: [String, Number],
            default: '',
        },
        // Placeholder teks
        placeholder: {
            type: String,
            default: '',
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
        // Kelas CSS tambahan yang akan diterapkan ke elemen input
        additionalClasses: {
            type: [String, Array, Object],
            default: '',
        },
        // Prop yang diteruskan oleh FormField untuk penanganan nilai dan validasi
        // Ini memungkinkan Input.vue untuk menerima fungsi handleChange/handleBlur dari VeeValidate
        // saat digunakan di dalam FormField
        handleChange: {
            type: Function,
            default: undefined,
        },
        handleBlur: {
            type: Function,
            default: undefined,
        },
        preventAutocomplete: { type: Boolean, default: false },
    });

    const emit = defineEmits(['update:modelValue', 'change', 'blur']);

    // ===== Formatting =====
    const formatNumber = (val) => {
        if (val === null || val === undefined || val === "") return "";
        return Number(val).toLocaleString("id-ID"); // jadi 200.000
    };
    const unformatNumber = (val) => {
        return val.replace(/\./g, ""); // hapus titik pemisah
    };

    // ===== Display Value (formatted) =====
    const displayValue = ref("");

    watch(
        () => props.modelValue,
        (val) => {
            if (props.isNumber) {
                displayValue.value = formatNumber(val);
            } else {
                displayValue.value = val;
            }
        },
        { immediate: true }
    );

    // Computed property untuk kelas CSS
    const inputClasses = computed(() => {
        let baseClasses = 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 placeholder:text-gray-400 focus:shadow-focus-ring focus:ring-0 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-2 focus:ring-brand-500/20 focus:outline-none dark:focus:border-brand-800 dark:focus:ring-brand-800/30 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 placeholder:text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30';
        if (props.type === 'file') {
            baseClasses = 'focus:border-ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-gray-500 shadow-theme-xs transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pl-3.5 file:pr-3 file file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden focus:file:ring-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400'
        }
        const stateClasses = [];
        if (props.disabled) {
            stateClasses.push('!bg-transparent !text-gray-800 disabled:border-gray-100 disabled:bg-gray-50 disabled:placeholder:text-gray-300 dark:disabled:border-gray-800 dark:disabled:bg-white/[0.03] dark:disabled:placeholder:text-white/15');
        }
        if (props.readonly) {
            stateClasses.push('!bg-transparent !text-gray-400 border-gray-100 bg-gray-50 placeholder:!text-gray-600 dark:!border-gray-700 dark:bg-gray-800 dark:text-gray-500');
        }
        return [baseClasses, ...stateClasses, props.additionalClasses];
    });

    // Validate the input value
    const isValid = (val) => {
        return !isNaN(val) && Number.isFinite(val);
    };

    /// Helper function to format the value with a period as the decimal separator
    const formatValue = (val) => {
        if (isValid(val)) {
            const formatted = val.toLocaleString("en-US", {
                minimumFractionDigits: 0,
            });
            return formatted;
        }

        return "";
    };

    const restrictToNumeric = (event) => {
        if (props.isNumber) {
            if (!/[0-9]/.test(event.key) && event.key !== "Backspace" && event.key !== "Delete") {
                event.preventDefault();
            }
        }
    };

    // ===== Event Handlers =====
    const handleInput = (event) => {
        let value = event.target.value;

        if (props.isNumber) {
            const raw = unformatNumber(value); // 200.000 → 200000
            displayValue.value = formatNumber(raw); // tetap tampil 200.000
            emit("update:modelValue", raw); // kirim nilai asli tanpa titik
            emit("change", raw);
        } else {
            displayValue.value = value;
            emit("update:modelValue", value);
            emit("change", value);
        }
    };

    // Handler untuk event 'blur'
    const handleBlur = (event) => {
        if (props.handleBlur) {
            // Jika ada handleBlur dari FormField, panggil itu
            props.handleBlur(event); // Teruskan event objek ke handleBlur VeeValidate
        }
        emit('blur', props.modelValue); // Emit event blur juga
    };
</script>

<style scoped>
/* Anda bisa menambahkan gaya scoped di sini jika perlu */
</style>

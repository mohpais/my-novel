<template>
    <textarea 
        :placeholder="placeholder"
        :id="id"
        :name="name"
        :rows="rows" 
        :value="modelValue"  v-bind="$attrs"
        :autocomplete="preventAutocomplete ? 'on' : 'off'"
        @input="handleInput"
        @blur="handleBlur"
        :disabled="disabled"
        :readonly="readonly"
        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full resize-none rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:ring-gray-700 focus:outline-none dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
    ></textarea>
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
        // Jumlah baris untuk textarea
        rows: {
            type: [String, Number],
            default: 3,
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

    // ===== Event Handlers =====
    const handleInput = (event) => {
        const value = event.target.value;
        
        // Emit ke parent (untuk v-model standar)
        emit("update:modelValue", value);
        emit("change", value);

        // Jika digunakan di dalam FormField dengan VeeValidate
        if (props.handleChange) {
            props.handleChange(event); 
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
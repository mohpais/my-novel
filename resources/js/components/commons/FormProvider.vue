<template>
    <Form
        class="w-full"
        :class="className"
        @submit="handleFormSubmit"
        @invalid-submit="onInvalidSubmit"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        v-slot="{ errors, values, isSubmitting, isValid, resetForm, meta, validate }"
    >
        <slot
            :errors="errors"
            :values="values"
            :is-submitting="isSubmitting"
            :is-valid="isValid"
            :reset-form="resetForm"
            :meta="meta"
            :validate="validate"
            name="touch-form"
        />
        
        <!-- Slot Custom Button -->
        <slot name="submit-button" :is-submitting="isSubmitting" />
    </Form>
</template>

<script setup>
    import { Form } from 'vee-validate';
    import { watch, provide, ref } from "vue";

    const props = defineProps({
        className: String,
        submitLabel: {
            type: String,
            default: 'Submit',
        },
        initialValues: {
            type: Object,
            default: () => {},
        },
        resetForm: { type: Boolean, default: false },
        validationSchema: Object,
    });

    // const { handleSubmit, resetForm } = useForm();
    const emit = defineEmits(['on-submit', "update:modelValue", "onInvalidSubmit"]);

    const fieldRefs = ref({}); // simpan semua field berdasarkan name

    function registerField(name, fieldRef) {
        fieldRefs.value[name] = fieldRef;
    }

    provide("registerField", registerField);

    /**
     * Handle ketika formulir disubmit.
     * Memicu event 'on-submit' dengan nilai formulir dan actions VeeValidate.
     */
    function handleFormSubmit(values, actions) {
        console.log('Form Submitted with values:', values); // Log untuk debugging

        emit('on-submit', { values, actions });
    }

    function onInvalidSubmit({ values, errors }) {
        emit("onInvalidSubmit", { values, errors });

        const firstErrorFieldName = Object.keys(errors)[0];
        if (!firstErrorFieldName) return;

        const el = fieldRefs.value[firstErrorFieldName]?.inputRef;
        const targetElement = el?.$el || el || document.querySelector(`label[for="${firstErrorFieldName}"]`);
        
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: "smooth", block: "center" });
            targetElement.focus?.();
        }
    }

    watch(
        () => props.resetForm,
        (newValue) => {
            if (newValue) {
                resetForm();
                emit("update:modelValue", false);
            }
        }
    );
</script>

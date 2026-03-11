<template>
    <Form
        class="w-full"
        :class="className"
        @submit="handleSubmitForm"
        @invalid-submit="handleInvalidSubmit"
        :validation-schema="validationSchema"
        :initial-values="safeInitialValues"
        v-slot="slotProps"
    >
        <FormWatcher :meta="slotProps.meta" />
        
        <slot
            name="touch-form"
            :errors="slotProps.errors"
            :values="slotProps.values"
            :isSubmitting="slotProps.isSubmitting"
            :isValid="slotProps.isValid"
            :meta="slotProps.meta"
            :resetForm="slotProps.resetForm"
            :validate="slotProps.validate"
        />
        
        <!-- Slot Custom Button -->
        <slot
            name="submit-button"
            :isSubmitting="slotProps.isSubmitting"
        />
    </Form>
</template>

<script setup>
    import { Form } from 'vee-validate';
    import {
        ref,
        provide,
        watch,
        onMounted,
        onBeforeUnmount,
        toRaw
    } from "vue";

    import { onBeforeRouteLeave } from "vue-router"

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
        preventNav: {
            type: Boolean,
            default: false
        },
    });

    // const { handleSubmit, resetForm } = useForm();
    const emit = defineEmits(['on-submit', "update:modelValue", "onInvalidSubmit"]);

    /*
    |--------------------------------------------------------------------------
    | Safe Initial Values
    |--------------------------------------------------------------------------
    |
    | Menghindari reactive reference dari Pinia
    |
    */
    
    const safeInitialValues = JSON.parse(
        JSON.stringify(toRaw(props.initialValues || {}))
    )

    /*
    |--------------------------------------------------------------------------
    | Dirty State
    |--------------------------------------------------------------------------
    */

    const isDirty = ref(false)
    const allowLeave = ref(false)

    function updateDirtyState(meta) {
        isDirty.value = meta.dirty
    }

    /*
    |--------------------------------------------------------------------------
    | Navigation Guard
    |--------------------------------------------------------------------------
    */

    onBeforeRouteLeave(() => {

        if (!props.preventNav) return true
        if (allowLeave.value) return true
        if (!isDirty.value) return true

        return window.confirm(
            "Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?"
        )
    })

    /*
    |--------------------------------------------------------------------------
    | Browser Close Guard
    |--------------------------------------------------------------------------
    */

    function beforeUnload(e) {

        if (!props.preventNav) return
        if (!isDirty.value) return

        e.preventDefault()
        e.returnValue = ""
    }

    onMounted(() => {
        window.addEventListener("beforeunload", beforeUnload)
    })

    onBeforeUnmount(() => {
        window.removeEventListener("beforeunload", beforeUnload)
    })

    /*
    |--------------------------------------------------------------------------
    | Field Registration (scroll to error)
    |--------------------------------------------------------------------------
    */

    const fieldRefs = ref({}); // simpan semua field berdasarkan name

    function registerField(name, fieldRef) {
        fieldRefs.value[name] = fieldRef;
    }

    provide("registerField", registerField);

    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    function handleSubmitForm(values, actions) {

        allowLeave.value = true
        isDirty.value = false

        emit("on-submit", { values, actions })
    }

    /*
    |--------------------------------------------------------------------------
    | Invalid Submit
    |--------------------------------------------------------------------------
    */

    function handleInvalidSubmit({ values, errors }) {

        emit("onInvalidSubmit", { values, errors })

        const firstError = Object.keys(errors)[0]
        if (!firstError) return

        const el = fieldRefs.value[firstError]?.inputRef

        const target =
            el?.$el ||
            el ||
            document.querySelector(`[name="${firstError}"]`)

        if (target) {

            target.scrollIntoView({
                behavior: "smooth",
                block: "center"
            })

            target.focus?.()
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Form Trigger
    |--------------------------------------------------------------------------
    */

    watch(
        () => props.resetForm,
        (val) => {

            if (!val) return

            isDirty.value = false
            allowLeave.value = true

            emit("update:modelValue", false)
        }
    )

    /*
    |--------------------------------------------------------------------------
    | Provide Dirty State (optional)
    |--------------------------------------------------------------------------
    */

    provide("formDirty", isDirty)

    /*
    |--------------------------------------------------------------------------
    | Internal watcher component
    |--------------------------------------------------------------------------
    */

    const FormWatcher = {
        props: ["meta"],
        setup(props) {

            watch(
                () => props.meta?.dirty,
                val => updateDirtyState(props.meta),
                { immediate: true }
            )

            return () => null
        }
    }
</script>

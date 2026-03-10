<template>
    <Breadcrumb pageTitle="Create Novel" :withBackButton="true" />
    <FormLayout />
</template>

<script setup>
    import { defineAsyncComponent } from 'vue';

    const FormLayout = defineAsyncComponent(() => import("../components/forms/Layout.vue"));
</script>

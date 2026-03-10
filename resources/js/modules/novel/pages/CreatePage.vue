<template>
    <Breadcrumb pageTitle="Create Novel" :withBackButton="true" />
    <FormLayout />
</template>

<script setup>
    import { defineAsyncComponent, onMounted } from 'vue';
    import { useNovelStore } from '../store';

    const FormLayout = defineAsyncComponent(() => import("../components/forms/Layout.vue"));

    const store = useNovelStore();
    const { isLoading } = store;


    // onMounted(async () => {
    //     // await store.checkActiveRequest();
    //     await store.fetchBudget();
    // })
</script>

<template>
    <LoadingFrom v-if="isLoading.fetchBudget" />
    <RequestLayout v-else />
</template>

<script setup>
    import { defineAsyncComponent, onMounted } from 'vue';
    import { useRequestStore } from '../store';

    const RequestLayout = defineAsyncComponent(() => import("../components/forms/Layout.vue"));
    const LoadingFrom = defineAsyncComponent(() => import('../components/forms/LoadingFrom.vue'));

    const store = useRequestStore();
    const { isLoading } = store;

    onMounted(async () => {
        // await store.checkActiveRequest();
        await store.fetchBudget();
    })
</script>

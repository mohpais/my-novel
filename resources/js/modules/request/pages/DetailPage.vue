<template>
    <Loading v-if="isLoading.fetchRequest" />
    <DetailLayout v-else />
    <ModalApprove />
    <ModalReject />
</template>

<script setup>
    import { onMounted, defineAsyncComponent } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import { useRequestStore } from '../store';

    // Lazy load components
    const ModalApprove = defineAsyncComponent(() => import('../components/modals/ModalApprove.vue'));
    const ModalReject = defineAsyncComponent(() => import('../components/modals/ModalReject.vue'));
    const DetailLayout = defineAsyncComponent(() => import('../components/request-details/Layout.vue'));
    const Loading = defineAsyncComponent(() => import('../components/request-details/LoadingDetail.vue'));

    const route = useRoute();
    const router = useRouter();

    const store = useRequestStore();

    const { isLoading } = store;

    onMounted(async () => {
        const { request_code } = route.params;
        if (!request_code) {
            router.push('/page-not-found');
            return
        }
        await store.fetchRequest(request_code);
    })
</script>

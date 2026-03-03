<template>
    <Breadcrumb pageTitle="Request Detail" :with-back-button="true" />
    <div class="space-y-6">
        <Header :asset_request="asset_request" @action="handleActionButton" />
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="space-y-6 divide-y-4 divide-dashed divide-gray-100 dark:divide-gray-800">
                        <div class="w-full">
                            <RequestDetail :asset_request="asset_request" />
                        </div>
                        <div class="w-full pt-3">
                            <QuotationTable :quotations="asset_request.quotations" />
                        </div>
                        <div class="w-full pt-3">
                            <JustificationTable :justifications="asset_request.justifications" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-6 col-span-12 md:col-span-4 2xl:col-span-3">
                <RequesterInfo :requester="asset_request.requester" />
                <Timeline :logs="asset_request.approval_logs" />
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed, defineAsyncComponent } from 'vue';
    import { useRequestStore } from '../../store'

    import Header from './Header.vue'
    import RequestDetail from './RequestDetail.vue';

    // Lazy load QuotationTable component
    const QuotationTable = defineAsyncComponent(() =>
        import("../QuotationTable.vue"),
    );

    const JustificationTable = defineAsyncComponent(() =>
        import("../JustificationTable.vue"),
    );

    const RequesterInfo = defineAsyncComponent(() =>
        import("./RequesterInfo.vue"),
    );

    const Timeline = defineAsyncComponent(() =>
        import("./Timeline.vue"),
    );

    const store = useRequestStore();
    const { data, btn_OpenModal } = store;

    const asset_request = computed(() => data.request || {});

    const handleActionButton = (payload) => {
        btn_OpenModal(payload.type, asset_request.value);
    }
</script>

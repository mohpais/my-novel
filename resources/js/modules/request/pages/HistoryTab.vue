<template>
    <DataTable
        class="h-full"
        :rowData="dataTable.data"
        :columnDefs="dataTable.column"
        :loading="isLoading.fetchHistoryListRequests"
        :isServerSide="true"
        v-model:paginationConfig="dataTable.paginationConfig"
        @onSortChanged="handlePageChanged"
        @page-changed="handlePageChanged"
    />
</template>

<script setup>
    import { onMounted } from 'vue';
    import { useRequestStore } from '../store';
    import { storeToRefs } from 'pinia'

    // Store
    const store = useRequestStore();

    // State
    const { isLoading, dataTable } = storeToRefs(store);

    // Action
    const { fetchHistoryListRequests, handlePageChanged } = store;

    onMounted(async () => {
        await fetchHistoryListRequests();
    })
</script>
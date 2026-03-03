<template>
    <DataTable
        class="h-full"
        :rowData="financeStore.dataTable.data"
        :columnDefs="financeStore.dataTable.column"
        :loading="financeStore.isLoading.getListPagination"
        :isServerSide="true"
        v-model:paginationConfig="financeStore.dataTable.paginationConfig"
        @onSortChanged="financeStore.handlePageChanged"
        @page-changed="financeStore.handlePageChanged"
    />
    <!-- <ConfirmDeleteModal /> -->
</template>

<script setup>
    import { defineAsyncComponent, onMounted, ref } from 'vue';
    import { useFinanceStore } from '../store';

    const financeStore = useFinanceStore();

    onMounted(async () => {
        await financeStore.getListPagination();
    })

    defineProps({
        tab: String,
    });
</script>

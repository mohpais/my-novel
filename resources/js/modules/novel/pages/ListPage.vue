<template>
    <div class="">
        <Breadcrumb pageTitle="My Novel" />

        <TableCard :withHeader="true" title="List of Novels" description="Track your novel's progress to boost your sales.">
            <template #actions>
                <button 
                    @click="$router.push('/novel/create')"
                    class="inline-flex h-10 items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
                >
                    + Add Novel 
                </button>
            </template>
            <template #datatable>
                <DataTable
                    class="h-full"
                    :rowData="dataTable.data"
                    :columnDefs="dataTable.column"
                    :loading="isLoading.fetchListNovels"
                    :isServerSide="true"
                    v-model:paginationConfig="dataTable.paginationConfig"
                    @onSortChanged="handlePageChanged"
                    @page-changed="handlePageChanged"
                />
            </template>
        </TableCard>
    </div>
</template>

<script setup>
    import { onMounted, defineAsyncComponent } from 'vue';
    import { useNovelStore } from '../store';
    import { storeToRefs } from "pinia";
    
    const TableCard = defineAsyncComponent(() => import('@/components/ui/table-cards/Index.vue'));

    // Store
    const store = useNovelStore();

    // State
    const { isLoading, dataTable } = storeToRefs(store);

    // Action
    const { fetchListNovels, handlePageChanged } = store;

    onMounted(async () => {
        await fetchListNovels();
    })
</script>

<template>
    <div class="">
        <Breadcrumb pageTitle="Request Page" />
        
        <div class="h-[calc(100vh-169px)] flex flex-col rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-theme-md">
            <div class="flex flex-col justify-between gap-5 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center dark:border-gray-800">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">List of Requests</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400"> Daftar permintaan asset yang sudah diajukan.</p>
                </div>
                <div class="flex gap-3">
                    <Button
                        size="xs"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800"
                        @click="$router.push({ name: 'RequestCreatePage' })"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        New Request
                    </Button>
                </div>
            </div>
            <div class="grow text-gray-800">
                <DataTable
                    class="h-full"
                    :rowData="dataTable.data"
                    :columnDefs="dataTable.column"
                    :loading="isLoading.fetchListRequests"
                    :isServerSide="true"
                    v-model:paginationConfig="dataTable.paginationConfig"
                    @onSortChanged="handlePageChanged"
                    @page-changed="handlePageChanged"
                />
            </div>
        </div>
    </div>
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
    const { fetchListRequests, handlePageChanged } = store;

    onMounted(async () => {
        await fetchListRequests();
    })
</script>

<template>
    <div class="">
        <Breadcrumb pageTitle="User Page" />
        <div
            class="h-[calc(100vh-181px)] flex flex-col mx-auto rounded-2xl w-full bg-white dark:bg-white/[0.03]"
        >
            <div class="flex flex-col justify-between gap-5 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center dark:border-gray-800">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $t('App.DataTable_User') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400"> Track your store's progress to boost your sales. </p>
                </div>
                <div class="flex gap-3">
                    <button class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                        Export
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M16.667 13.3333V15.4166C16.667 16.1069 16.1074 16.6666 15.417 16.6666H4.58295C3.89259 16.6666 3.33295 16.1069 3.33295 15.4166V13.3333M10.0013 13.3333L10.0013 3.33325M6.14547 9.47942L9.99951 13.331L13.8538 9.47942" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                    <a href="#" class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M5 10.0002H15.0006M10.0002 5V15.0006" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        Add User
                    </a>
                </div>
            </div>
            <div class="grow text-gray-800">
                <DataTable
                    class="h-full"
                    :rowData="userStore.dataTable.data"
                    :columnDefs="userStore.dataTable.column"
                    :loading="userStore.isLoading.getUserPagination"
                    :isServerSide="true"
                    v-model:paginationConfig="userStore.dataTable.paginationConfig"
                    @onSortChanged="userStore.handlePageChanged"
                    @page-changed="userStore.handlePageChanged"
                />
            </div>
        </div>
    </div>
    <!-- <ConfirmDeleteModal /> -->
</template>

<script setup>
    import { defineAsyncComponent, onMounted, ref } from 'vue';
    import { useUserStore } from '../store';

    const userStore = useUserStore();
    const currentPageTitle = ref("List of Users");

    onMounted(async () => {
        await userStore.getUserPagination();
    })
</script>

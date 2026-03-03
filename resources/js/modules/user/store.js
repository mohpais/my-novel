import { reactive } from "vue";
import { defineStore } from 'pinia';
import service from './service';
import { columnDefs } from './useColumnDefsTable';
import { queueToastAfterLayout, flushToastQueue } from '@/composables/useToastAfterLayout'

export const useUserStore = defineStore('user', () => {
    const data = reactive({
        user: null,
    });

    const isLoading = reactive({
        getUserPagination: false,
        changeProfilePicture: false,
    });

    const dataTable = reactive({
        paginationConfig: {
            currentPage: 1,
            pageSize: 25,
            totalPages: 0,
            totalRecord: 0,
            rowItems: [10, 25, 50, 100],
        },
        filterParams: [],
        sortParams: [],
        column: columnDefs,
        data: [],
    });

    async function getUserPagination() {
        isLoading.getUserPagination = true;
        try {
            const result = await service.getList(
                dataTable.paginationConfig.currentPage, dataTable.paginationConfig.pageSize,
                {
                    filterParams: dataTable.filterParams,
                    sortParams: dataTable.sortParams,
                }
            );

            let { success, records, pagination } = result;

            if (success) {
                dataTable.data = records;
                dataTable.paginationConfig.currentPage = pagination.current_page;
                dataTable.paginationConfig.totalPages = pagination.total_pages;
                dataTable.paginationConfig.totalRecord = pagination.total_records;
            }
        } catch (error) {
            console.error("❌ Error saat memuat user pagination:", error);
            flushToastQueue(error, { type: 'error' });
        } finally {
            isLoading.getUserPagination = false;
        }
    };

    async function handlePageChanged(params) {
        if (params.currentPage && params.pageSize) {
            dataTable.paginationConfig.currentPage = params.currentPage;
            dataTable.paginationConfig.pageSize = params.pageSize;
        } else if (params[0].sortBy) {
            dataTable.sortParams = params;
        } else {
            dataTable.filterParams = params;
        }

        await getUserPagination();
    }

    function btnClickAction(type, item = null) {
        switch (type) {
            default:
                break;
        }
    }

    async function changeProfilePicture(formData) {
        isLoading.changeProfilePicture = true;
        try {
            const result = await service.chageProfilePicture(formData);
            let { success, message, picture_url } = result;

            if (success) {
                queueToastAfterLayout(message, { type: 'success' });
            }
        } catch (error) {
            console.error("❌ Error saat mengubah photo profile:", error);
            flushToastQueue(error, { type: 'error' });
        } finally {
            isLoading.changeProfilePicture = false;
        }
    }

    return {
        data,
        dataTable,
        isLoading,
        btnClickAction,
        getUserPagination,
        handlePageChanged,
        changeProfilePicture
    }
});

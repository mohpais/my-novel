import { reactive } from "vue";
import { defineStore } from 'pinia';
import financeService from './service';
import { useRouter } from "vue-router";
import { getColumnDefs } from './useColumnDefsTable';
import { flushToastQueue } from '@/composables/useToastAfterLayout'

export const useFinanceStore = defineStore('finance', () => {
    const data = reactive({
        user: null,
    });

    const count = reactive({
        business_unit: 0,
        cost_center: 0,
        profit_center: 0,
    })

    const router = useRouter();

    const isLoading = reactive({
        getCountData: false,
        getListPagination: false,
    });

    const dataTable = reactive({
        paginationConfig: {
            currentType: 'cost_center',
            currentPage: 1,
            pageSize: 25,
            totalPages: 0,
            totalRecord: 0,
            rowItems: [10, 25, 50, 100],
        },
        filterParams: [],
        sortParams: [],
        column: getColumnDefs('cost_center'),
        data: [],
    });

    async function getCountData() {
        isLoading.getCountData = true;
        try {
            const result = await financeService.getCount();
            let { success, data } = result;

            if (success) {
                count.business_unit = data.business_unit;
                count.cost_center = data.cost_center;
                count.profit_center = data.profit_center;
            }
        } catch (error) {
            console.error("❌ Error saat memuat count data:", error);
            flushToastQueue(error, { type: 'error' });
        } finally {
            isLoading.getCountData = false;
        }
    };

    async function getListPagination() {
        isLoading.getListPagination = true;
        try {
            const result = await financeService.getList(
                dataTable.paginationConfig.currentType, dataTable.paginationConfig.currentPage, dataTable.paginationConfig.pageSize,
                {
                    filterParams: dataTable.filterParams,
                    sortParams: dataTable.sortParams,
                }
            );

            let { success, records, pagination } = result;

            if (success) {
                dataTable.data = records;
                dataTable.column = getColumnDefs(dataTable.paginationConfig.currentType);
                dataTable.paginationConfig.currentPage = pagination.current_page;
                dataTable.paginationConfig.totalPages = pagination.total_pages;
                dataTable.paginationConfig.totalRecord = pagination.total_records;
            }
        } catch (error) {
            console.error("❌ Error saat memuat list pagination:", error);
            flushToastQueue(error, { type: 'error' });
        } finally {
            isLoading.getListPagination = false;
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

        await getListPagination();
    }

    async function handleTabChanged(tab) {
        dataTable.paginationConfig.currentType = tab;
        dataTable.paginationConfig.currentPage = 1;
        dataTable.paginationConfig.pageSize = 25;
        dataTable.sortParams = [];
        dataTable.filterParams = [];

        await getListPagination();
    }

    function btnClickAction(type, item = null) {
        switch (type) {
        default:
            break;
        }
    }

    return {
        data,
        count,
        dataTable,
        isLoading,
        getCountData,
        btnClickAction,
        getListPagination,
        handlePageChanged,
        handleTabChanged
    }
});

import { computed, reactive, ref, watch } from "vue";
import service from './service';
import { defineStore } from 'pinia';
import { queueToastAfterLayout, flushToastQueue } from '@/composables/useToastAfterLayout'
import listNovelsColumnDefs from "./useColumnDefsTable";
import { useRouter } from "vue-router";

export const useNovelStore = defineStore('novel', () => {
    const data = reactive({
        novels: [],
    });

    const form = reactive({
        title: "",
        synopsis: "",
        genres: [],
        tags: []
    });

    const router = useRouter();

    const isLoading = reactive({
        createNovel: false,
        fetchListNovels: false,
        fetchNovel: false,
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
        column: listNovelsColumnDefs,
        data: [],
    });

    const showModal = reactive({
        approve: false,
        reject: false,
        cancel: false,
        selectedItem: null,
        payload: {},
    })

    async function fetchListNovels() {
        isLoading.fetchListNovels = true;

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
        isLoading.fetchListRequests = false;
    }

    async function fetchNovel(novel_id) {
        isLoading.fetchNovel = true;
        const result = await service.get(novel_id);

        let { success, data: novels } = result;

        if (success) {
            data.novels = novels;
        }
        isLoading.fetchNovel = false;
    }

    async function btnCreateNovel({ values }) {
        if (values) {
            isLoading.createNovel = true;
            try {
                const result = await service.create(form);
                let { success, message, data: responseData } = result;

                if (success) {
                    queueToastAfterLayout(message, { type: 'success' });
                    router.push({ name: 'RequestListPage' });
                }
            } catch (error) {
                console.error("❌ Error saat memuat count data:", error);
                flushToastQueue(error, { type: 'error' });
            } finally {
                isLoading.createNovel = false;
            }
        }
    }

    return {
        data,
        dataTable,
        form,
        isLoading,
        showModal,
        fetchListNovels,
        fetchNovel,
        btnCreateNovel,
    }
});

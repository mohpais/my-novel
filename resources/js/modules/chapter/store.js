import { reactive } from "vue";
import service from './service';
import { defineStore } from 'pinia';
import listNovelsColumnDefs from "./useColumnDefsTable";

export const useChapterStore = defineStore('chapter', () => {
    const data = reactive({
        chapters: [],
    });

    const form = reactive({
        number: 0,
        title: "",
        content: "",
    });


    const isLoading = reactive({
        createChapter: false,
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

    async function btnCreateChapter({ values }) {
        console.log(values);
        
        // if (values) {
        //     isLoading.createNovel = true;
        //     try {
        //         const result = await service.create(form);
        //         let { success, message, data: responseData } = result;

        //         if (success) {
        //             queueToastAfterLayout(message, { type: 'success' });
        //             router.push({ name: 'RequestListPage' });
        //         }
        //     } catch (error) {
        //         console.error("❌ Error saat memuat count data:", error);
        //         flushToastQueue(error, { type: 'error' });
        //     } finally {
        //         isLoading.createNovel = false;
        //     }
        // }
    }

    return {
        data,
        dataTable,
        form,
        isLoading,
        btnCreateChapter,
    }
});

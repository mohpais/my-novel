import { reactive } from "vue";
import service from './service';
import { defineStore } from 'pinia';
import listNovelsColumnDefs from "./useColumnDefsTable";

export const useNovelStore = defineStore('novel', () => {
    const data = reactive({
        novels: [],
        novel: null,
        chapters: []
    });

    const form = reactive({
        cover_image: null,
        title: "",
        synopsis: "",
        languages: "",
        genre_ids: [],
        tag_ids: []
    });

    const isLoading = reactive({
        createNovel: false,
        fetchListNovels: false,
        fetchNovel: false,
        fetchChapters: false,
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
            {
                page: dataTable.paginationConfig.currentPage,
                limit: dataTable.paginationConfig.pageSize,
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
        isLoading.fetchListNovels = false;
    }

    async function fetchNovel(novel_slug) {
        isLoading.fetchNovel = true;
        const result = await service.get(novel_slug);

        let { success, data: novel } = result;

        if (success) {
            data.novel = novel;
            form.cover_image = novel.cover_image;
            form.title = novel.title;
            form.synopsis = novel.synopsis;
            form.languages = novel.languages;
            form.genre_ids = novel.genres.map(genre => genre.id);
            form.tag_ids = novel.tags.map(tag => tag.id);
        }
        isLoading.fetchNovel = false;
    }

    async function fetchChapters(novel_slug) {
        isLoading.fetchChapters = true;
        try {
            // Kita panggil service dengan slug
            // Jika API butuh body (seperti pagination), kirimkan object di parameter kedua
            const result = await service.getChapters(novel_slug, {
                // contoh payload jika dibutuhkan:
                // page: 1, limit: 100 
            });

            if (result.success) {
                data.chapters = result.records || result.data; 
            }
        } catch (error) {
            console.error("Error fetching chapters:", error);
        } finally {
            isLoading.fetchChapters = false;
        }
    }

    async function btnCreateNovel({ values }) {
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
        showModal,
        fetchListNovels,
        fetchNovel,
        fetchChapters,
        btnCreateNovel,
    }
});

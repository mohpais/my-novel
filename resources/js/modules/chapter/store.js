import { reactive } from "vue";
import service from './service';
import { defineStore } from 'pinia';
import { useNovelStore } from "@/modules/novel/store";
import listNovelsColumnDefs from "./useColumnDefsTable";
import { queueToastAfterLayout, flushToastQueue } from '@/composables/useToastAfterLayout'
import { useRouter } from "vue-router";

export const useChapterStore = defineStore('chapter', () => {
    const data = reactive({
        chapters: [],
    });
    
    const form = reactive({
        number: localStorage.getItem('autosave_chapter_number') || 0,
        title: localStorage.getItem('autosave_chapter_title') || "",
        content: localStorage.getItem('autosave_chapter_content') || "",
    });
    
    var router = useRouter();

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
        // console.log(values);
        
        if (values) {
            isLoading.createChapter = true;
            try {
                const novelStore = useNovelStore();
                form.novel_id = novelStore.data.novel?.id;
                if (!form.novel_id) {
                    throw new Error("Novel ID tidak ditemukan");
                }

                const result = await service.create(form);
                let { success, message, data: responseData } = result;

                if (success) {
                    // Hapus semua autosave setelah berhasil
                    ['number', 'title', 'content'].forEach(key => 
                        localStorage.removeItem(`autosave_chapter_${key}`)
                    );

                    queueToastAfterLayout(message, { type: 'success' });

                    const slug = novelStore.data.novel?.slug;
                    router.push({ 
                        name: 'NovelDetailPage', 
                        params: { slug: slug },
                        // Tambahkan ini agar langsung membuka tab Table of Contents
                        query: { tab: 'contents' } 
                    });
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
        btnCreateChapter,
    }
});

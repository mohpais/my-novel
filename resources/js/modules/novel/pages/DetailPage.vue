<template>
    <Breadcrumb pageTitle="Detail Novel" :withBackButton="true" />
    <div v-if="isLoading.fetchNovel" class="animate-tw-pulse space-y-4">
        <div class="h-12 bg-gray-200 dark:bg-gray-700 rounded-xl w-full"></div>
        <div class="h-64 bg-gray-100 dark:bg-gray-800 rounded-xl w-full"></div>
    </div>
    <TabCard 
        v-else
        :tabs="tabs" 
        :active-tab="activeTab" 
        @change="activeTab = $event"
    >
        <KeepAlive>
            <component 
                :is="activeComponent" 
                :novel-data="data" 
            />
        </KeepAlive>
    </TabCard>
</template>

<script setup>
    import { computed, onMounted, defineAsyncComponent, markRaw, ref } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import { useNovelStore } from '../store';
    import { storeToRefs } from "pinia";
    import {
        ListIcon,
        GridIcon,
        AnalyticsIcon,
        NotesIcon
    } from "@/components/icons";

    
    // Import komponen konten secara Lazy Load
    const TabCard = defineAsyncComponent(() => import('@/components/ui/tab-cards/Index.vue'));
    const FormLayout = defineAsyncComponent(() => import("../components/forms/Layout.vue"));
    const TableOfContents = defineAsyncComponent(() => import('../components/tabs/TableOfContents.vue'));
    const StoryAnalytics = defineAsyncComponent(() => import('../components/tabs/StoryAnalytics.vue'));
    const StoryNotes = defineAsyncComponent(() => import('../components/tabs/StoryNotes.vue'));

    // 
    const route = useRoute();
    const router = useRouter();

    // Store
    const store = useNovelStore();

    // state
    const { data, isLoading } = storeToRefs(store);
    
    // Action
    const { fetchNovel, fetchChapters } = store;
    
    // State untuk tab yang aktif
    const tabs = ref([
        { id: 'overview', name: "Story Overview", icon: markRaw(GridIcon), component: markRaw(FormLayout) },
        { id: 'contents', name: "Table of Contents", icon: markRaw(ListIcon), component: markRaw(TableOfContents) },
        { id: 'analytics', name: "Story Analytics", icon: markRaw(AnalyticsIcon), component: markRaw(StoryAnalytics) },
        { id: 'notes', name: "Story Notes", icon: markRaw(NotesIcon), component: markRaw(StoryNotes) },
    ]);

    // SYNC ACTIVE TAB WITH URL QUERY
    const activeTab = computed({
        get() {
            // Ambil dari URL ?tab=xxx, jika kosong default ke 'overview'
            return route.query.tab || 'overview';
        },
        set(newTab) {
            // Ubah URL tanpa merefresh halaman
            router.replace({ 
                query: { ...route.query, tab: newTab } 
            });
        }
    });

    // Mengambil komponen berdasarkan tab yang aktif
    const activeComponent = computed(() => {
        // Tambahkan .value setelah tabs
        const currentTab = tabs.value.find(t => t.id === activeTab.value);
        
        // Pastikan juga akses index menggunakan .value
        return currentTab ? currentTab.component : tabs.value[0].component;
    });

    onMounted(async () => {
        const { slug } = route.params;
        // Panggil keduanya secara paralel untuk efisiensi
        await Promise.all([
            fetchNovel(slug),
            fetchChapters(slug)
        ]);
    });
</script>
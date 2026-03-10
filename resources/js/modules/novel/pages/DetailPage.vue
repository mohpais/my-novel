<template>
    <Breadcrumb pageTitle="Detail Novel" :withBackButton="true" />
    <div v-if="isLoading.fetchNovel" class="animate-pulse space-y-4">
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
    import { useNovelStore } from '../store';
    import { storeToRefs } from "pinia";
    import { useRoute } from 'vue-router';

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

    // State untuk tab yang aktif
    const activeTab = ref('overview');
    const tabs = ref([
        { id: 'overview', name: "Story Overview", icon: markRaw(GridIcon), component: markRaw(FormLayout) },
        { id: 'contents', name: "Table of Contents", icon: markRaw(ListIcon), component: markRaw(TableOfContents) },
        { id: 'analytics', name: "Story Analytics", icon: markRaw(AnalyticsIcon), component: markRaw(StoryAnalytics) },
        { id: 'notes', name: "Story Notes", icon: markRaw(NotesIcon), component: markRaw(StoryNotes) },
    ]);

    // Mengambil komponen berdasarkan tab yang aktif
    const activeComponent = computed(() => {
        const current = tabs.value.find(tab => tab.id === activeTab.value);
        return current ? current.component : null;
    });

    // 
    const route = useRoute();

    // Store
    const store = useNovelStore();

    // state
    const { data, isLoading } = storeToRefs(store);
    
    // Action
    const { fetchNovel, fetchChapters } = store;

    onMounted(async () => {
        const { slug } = route.params;
        // Panggil keduanya secara paralel untuk efisiensi
        await Promise.all([
            fetchNovel(slug),
            fetchChapters(slug)
        ]);
    });
</script>
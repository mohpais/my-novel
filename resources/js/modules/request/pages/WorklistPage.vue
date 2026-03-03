<template>
    <div class="">
        <Breadcrumb pageTitle="Worklist Page" />

        <TableCard 
            :with-header="true"
            :title="currentTab?.title" 
            :description="currentTab?.description"
        >
            <template #actions>
                <Tabs :items="tabList" />
            </template>
            <template #datatable>
                <router-view />
            </template>
        </TableCard>
    </div>
</template>

<script setup>
    import { computed, defineAsyncComponent } from 'vue';
    import { useRequestStore } from '../store';
    import { storeToRefs } from "pinia";
    

    const TableCard = defineAsyncComponent(() => import('@/components/ui/table-cards/Index.vue'));
    const Tabs = defineAsyncComponent(() => import("@/components/ui/tabs/Index.vue"));

    // Store
    const store = useRequestStore();
    
    // State
    const { tabList, currentTabId } = storeToRefs(store);

    const currentTab = computed(() => 
        tabList.value.find(t => t.id === currentTabId.value)
    )
</script>

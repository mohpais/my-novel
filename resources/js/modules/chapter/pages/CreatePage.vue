<template>
    <Breadcrumb pageTitle="New Chapter" :withBackButton="true" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        
        <!-- HEADER -->
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            
            <!-- Skeleton -->
            <div v-if="isLoading.fetchNovel" class="animate-tw-pulse">
                <div class="h-5 w-56 rounded bg-gray-200 dark:bg-gray-700"></div>
            </div>

            <!-- Title -->
            <h4
                v-else
                class="mb-2 text-base font-semibold text-gray-800 dark:text-white/90"
            >
                {{ novel?.title }}
            </h4>
            <span></span>
        </div>

        <!-- BODY -->
        <div class="border-b border-gray-200 p-4 sm:p-8 dark:border-gray-800">

            <!-- Skeleton Form -->
            <div v-if="isLoading.fetchNovel" class="animate-tw-pulse space-y-4">
                <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded-lg w-full"></div>
                <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded-lg w-full"></div>
                <div class="h-40 bg-gray-200 dark:bg-gray-700 rounded-lg w-full"></div>
            </div>

            <!-- Form -->
            <FormLayout v-else />

        </div>
    </div>
</template>

<script setup>
import { computed, defineAsyncComponent, onMounted } from "vue";
import { useRoute } from "vue-router";
import { storeToRefs } from "pinia";

import { useNovelStore } from "@/modules/novel/store";

const route = useRoute();
const store = useNovelStore();

const { data, isLoading } = storeToRefs(store);

// novel dari store
const novel = computed(() => data.value.novel);

// lazy load form
const FormLayout = defineAsyncComponent(() =>
    import("../components/forms/Layout.vue")
);

// jika refresh dan store kosong → fetch lagi
onMounted(async () => {
    if (!data.value.novel) {
        await store.fetchNovel(route.params.slug);
    }
});
</script>
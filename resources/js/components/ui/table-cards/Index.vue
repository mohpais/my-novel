<!-- Table Card Component -->
<template>
    <div class="h-[calc(100vh-169px)] flex flex-col rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-theme-md">
        <div class="flex flex-col justify-between gap-5 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center dark:border-gray-800">
            <div>
                <h3 v-if="!title" class="w-48 h-6 bg-gray-200 rounded animate-tw-pulse dark:bg-gray-700"></h3>
                <h3 v-else class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ title }}</h3>
                
                <p v-if="!description" class="w-64 h-3 mt-1 bg-gray-200 rounded animate-tw-pulse dark:bg-gray-700"></p>
                <p v-else class="text-xs text-gray-500 dark:text-gray-400"> {{ description }} </p>
            </div>
            <slot name="actions"></slot>
        </div>
        <div class="grow text-gray-800">
            <slot name="datatable"></slot>
        </div>
    </div>
</template>

<script setup>
    defineProps({
        withHeader: {
            type: Boolean,
            default: false
        },
        title: String,
        description: String
    });
</script>
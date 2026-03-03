<template>
    <div class="flex flex-col gap-4 sm:flex-row lg:items-center">
        <div class="inline-flex h-11 w-full flex-1 gap-0.5 overflow-x-auto rounded-lg bg-gray-200 p-0.5 sm:w-auto lg:min-w-fit dark:bg-gray-900">
            <router-link 
                v-for="(item, index) in items"
                :key="index"
                :to="{ path: item.path }"
                aria-current="page"
                class="h-10 flex items-center gap-1 justify-center flex-1 rounded-md px-2 py-2 text-xs font-medium sm:px-3 sm:text-sm lg:flex-initial text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white"
            >
                <i
                    :class="[
                    item.icon,
                    {
                        'animate-spin': item.id === 'Pending' && item.count > 0,
                    },
                ]"></i>
                <span class="capitalize">{{ $t(item.id) }}</span>
                <div
                    v-if="item.count == 0"
                    class="ms-2 rounded-lg border border-zinc-300 bg-white h-5 w-5 flex items-center justify-center text-xs font-bold text-zinc-600 p-2 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                    {{ item.count }}
                </div>
            </router-link>
        </div>
    </div>
</template>

<script setup>
    defineProps({
        items: {
            type: Array,
            default: [],
        },
    });
</script>

<style scoped>
    .router-link-active {
        @apply shadow-theme-xs text-gray-100 dark:text-white bg-brand-500 dark:bg-gray-800;
    }
</style>
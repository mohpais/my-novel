<template>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <h2 class="mb-5 text-sm font-semibold text-gray-800 dark:text-white/90">Approval History</h2>
        <div 
            class="relative pl-11"
            v-for="(item, index) in logs"
            :key="index"
            :class="{
                ' pb-7': index !== logs.length -1
            }"
        >
            <div 
                class="absolute top-0 left-0 z-10 flex h-4 w-4 items-center justify-center border-2 border-gray-50 dark:border-gray-800 rounded-full"
                :class="{
                    'bg-orange-400 ring ring-orange-400/85': index === 0,
                    'bg-gray-300 dark:bg-gray-700': index !== 0 && item.status
                }"></div>
            <div class="ml-0 flex flex-col justify-between">
                <div>
                    <h4 class="font-semibold mb-1 text-gray-800 dark:text-white/90">{{ item.status.label }}</h4>
                    <p class="text-gray-500 dark:text-gray-400">
                        <span v-if="item.user">{{ item.user?.name }} -</span> 
                        {{ item.role.name }}
                    </p>
                </div>
                <div v-if="item.user">
                    <small class="text-gray-500 dark:text-gray-400">{{ dateFormatter(item.updated_at) }}</small>
                </div>
                <div class="mt-1 flex flex-col rounded-md bg-gray-200 dark:bg-gray-700 py-2 px-2.5 text-gray-500 dark:text-gray-400" v-if="item.notes">
                    <span class="font-medium">Note:</span>
                    <small class="text-xs">{{ item.notes }}</small>
                </div>
            </div>
            <div v-if="index !== logs.length -1" class="absolute top-0 left-[7px] h-full w-px border border-dashed border-gray-300 dark:border-gray-700"></div>
        </div>


        <!-- <div class="relative pb-7 pl-11">
            <div class="absolute top-0 left-0 z-10 flex h-4 w-4 items-center justify-center rounded-full border-2 border-gray-50 bg-white text-gray-700 ring ring-gray-200 dark:border-gray-800 dark:bg-orange-400 dark:text-gray-400 dark:ring-orange-400/85"></div>
            <div class="ml-0 flex flex-col justify-between">
                <div>
                    <h4 class="font-semibold mb-1 text-gray-800 dark:text-white/90">Waiting Approval</h4>
                    <p class="text-gray-500 dark:text-gray-400">Procurement</p>
                </div>
            </div>
            <div class="absolute top-0 left-[8.5px] h-full w-px border border-dashed border-gray-300 dark:border-gray-700"></div>
        </div>
        <div class="relative pb-7 pl-11">
            <div class="absolute top-0 left-0 z-10 flex h-4 w-4 items-center justify-center rounded-full bg-gray-300 text-gray-700 dark:bg-gray-700"></div>
            <div class="ml-0 flex flex-col justify-between">
                <div>
                    <h4 class="font-semibold mb-1 text-gray-800 dark:text-white/90">Approved</h4>
                    <p class="font-medium text-gray-500 dark:text-gray-400">Julian Valentino</p>
                    <p class="text-gray-600 dark:text-gray-500">Budget Owner</p>
                </div>
                <div>
                    <small class="text-gray-500 dark:text-gray-400">12th Apr 28, 20:30</small>
                </div>
            </div>
            <div class="absolute top-0 left-[8.5px] h-full w-px border border-dashed border-gray-300 dark:border-gray-700"></div>
        </div>
        <div class="relative pl-11">
            <div class="absolute top-0 left-0 z-10 flex h-4 w-4 items-cent4 justify-center rounded-full bg-gray-300 text-gray-700 dark:bg-gray-700 "></div>
            <div class="ml-0 flex flex-col justify-between">
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white/90">Submitted</h4>
                    <p class="font-medium text-gray-500 dark:text-gray-400">Mohamad Pais</p>
                    <p class="text-gray-600 dark:text-gray-500">Requester</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">12th Apr 28, 12:58</p>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script setup>
    import { dateFormatter } from "@/utils";
    
    defineProps({   
        logs: {
            type: Array,
            required: false,
            default: () => [],
        },
    });
</script>

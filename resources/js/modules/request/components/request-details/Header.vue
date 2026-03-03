<template>
    <div class="flex flex-col justify-between gap-6 rounded-2xl border border-gray-200 bg-white px-6 py-5 sm:flex-row sm:items-center dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-col items-center gap-2.5 divide-gray-300 sm:flex-row sm:divide-x dark:divide-gray-700">
            <div class="flex items-center gap-2 sm:pr-3">
                <div class="flex flex-col">
                    <span class="font-medium text-gray-700 dark:text-gray-400"> Request Code : {{ asset_request.request_code }}</span>
                    <small class="text-gray-500 dark:text-gray-400">Request date:&nbsp;{{ dateFormatter(asset_request.created_at) }}</small>
                </div>
                <span
                    class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-1 !text-[.75rem] font-medium"
                    :class="{
                        'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 ': asset_request.status.code === 'approved' || asset_request.status.code === 'completed',
                        'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500': asset_request.status.code === 'waiting_approval',
                        'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-500': asset_request.status.code === 'rejected' || asset_request.status.code === 'canceled',
                    }"
                >
                    {{ asset_request.status.label }} {{ currentAwaitingApprovalStage.role.name }}
                </span>
            </div>
            <!-- <p class="text-gray-500 sm:pl-3 dark:text-gray-400">Request date:&nbsp;{{ dateFormatter(asset_request.created_at) }}</p> -->
        </div>
        <div v-if="isCurrentAppoval" class="flex gap-3">
            <Button @click="handleAction('approve')" class="bg-success-500 shadow-theme-xs hover:bg-success-600" size="sm"> Approve </Button>
            <Button @click="handleAction('reject')" class="bg-red-500 shadow-theme-xs hover:bg-red-600" size="sm"> Reject </Button>
            <!-- <button class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-2 text-xs font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]"> Refund </button> -->
        </div>
    </div>
</template>

<script setup>
    import { dateFormatter } from "@/utils";
    import { useAuthStore } from '@/stores/useAuthStore';
    import { computed } from "vue";

    const props = defineProps({
        asset_request: {
            type: Object,
            required: true
        }
    });

    const emits = defineEmits(['action']);

    const authStore = useAuthStore();

    const currentAwaitingApprovalStage = computed(() => {
        return props.asset_request.approval_stages
            .filter(stage => !stage.actioned_by && !stage.actioned_at)
            .sort((a, b) => a.stage_order - b.stage_order)[0];
    });

    const isCurrentAppoval = computed(() => {
        return authStore.isCurrentApproval(currentAwaitingApprovalStage.value.role.code)
    });

    // Fungsi tunggal untuk menangani Approve dan Reject
    const handleAction = (type) => {
        emits('action', { type });
    };
</script>

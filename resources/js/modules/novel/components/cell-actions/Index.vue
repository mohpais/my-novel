<template>
    <div>
        <Button
            @click="$router.push({ name: 'RequestDetailPage', params: { request_code: params.data.request_code } })"
            size="xs"
            class="bg-purple-500 text-white me-1"
            :title="$t('Commons.Detail')"
        >
            <i class="fa-solid fa-magnifying-glass"></i>
        </Button>
        <!-- <Button
            v-if="
                !params.data.superiorApprovedAt &&
                (params.data.requestStatusName === 'Menunggu Persetujuan' ||
                params.data.requestStatusName === 'Menunggu Diketahui')
            "
            @click="onClickAction('edit', params.data)"
            size="xs"
            class="bg-tertiary text-white me-1"
            :title="$t('Commons.Edit')"
        >
            <i class="fa-solid fa-pen"></i>
        </Button> -->
        <!-- <Button
            size="xs"
            class="bg-red-500 text-white me-1"
            :title="$t('Commons.Cancel')"
        >
            <i class="fa-solid fa-xmark"></i>
        </Button> -->
    </div>
</template>

<script setup>
    import { useRequestStore } from "../../store";

    // Store
    const requestStore = useRequestStore();

    // Action
    const { onClickAction } = requestStore;

    const props = defineProps({
        params: Object,
    });
</script>

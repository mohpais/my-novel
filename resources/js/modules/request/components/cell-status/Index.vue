<template>
    <span :class="theme" class="rounded-full px-2 py-0.5 text-theme-xs font-medium">
        {{ $t(params.data.request_status_name) }}
    </span>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    params: Object,
});

const theme = ref(null);

switch (props.params.data.request_status_code) {
    case "waiting_approval":
        theme.value = "bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400";
        break;
    case "rejected":
        theme.value = "bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500";
        break;
    case "approved":
    case "completed":
        theme.value = "bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500";
        break;

    default:
        break;
    }
</script>

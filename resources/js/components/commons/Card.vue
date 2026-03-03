<template>
    <div
        :class="[
            'rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6',
            className,
        ]"
    >
        <!-- Card Header -->
        <slot name="header"></slot>

        <slot></slot>
    </div>
</template>

<script setup >
defineProps({
    title: {
        type: String,
    },
    className: {
        type: String,
        default: ''
    },
    desc: {
        type: String,
        default: ''
    }
})
</script>

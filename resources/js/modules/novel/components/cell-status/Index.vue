<template>
    <span 
        class="p-1 capitalize rounded-md text-[10px]"
        :class="{
            'bg-green-500': params.data.status === 'published',
            'bg-yellow-500': params.data.status === 'draft',
            'bg-primary-500': params.data.status === 'ongoing',
        }"
    >{{ params.data.status }}</span>
</template>

<script setup>
    defineProps({
        params: Object,
    });
</script>
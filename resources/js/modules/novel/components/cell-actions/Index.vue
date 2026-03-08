<template>
    <div>
        <Button
            @click="$router.push({ name: 'NovelDetailPage', params: { slug: params.data.slug } })"
            size="xs"
            class="bg-purple-500 text-white me-1"
            :title="$t('Commons.Detail')"
        >
            <i class="fa-solid fa-magnifying-glass"></i>
        </Button>
    </div>
</template>

<script setup>
    const props = defineProps({
        params: Object,
    });
</script>

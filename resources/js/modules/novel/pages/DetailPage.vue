<template>
    <div class="">
        <Breadcrumb pageTitle="Novel Detail" />

        <TableCard :withHeader="true" title="Novel Detail" description="Track your novel's progress to boost your sales.">
            <template #datatable>
                <div class="p-4">
                    <h1 class="text-2xl font-bold mb-4">{{ novelDetail.title }}</h1>
                    <p class="mb-4">{{ novelDetail.synopsis }}</p>
                    <!-- Add more details as needed -->
                </div>
            </template>
        </TableCard>
    </div>
</template>

<script setup>
    import { onMounted, defineAsyncComponent } from 'vue';
    import { useNovelStore } from '../store';
    import { storeToRefs } from "pinia";
    import { useRoute } from 'vue-router';

    // You can use vue-router's useRouter function to get the slug from the route
    const route = useRoute();
    const slug = route.query.slug;
    console.log(slug);
    
    
    const TableCard = defineAsyncComponent(() => import('@/components/ui/table-cards/Index.vue'));

    // Store
    const store = useNovelStore();

    // State
    const { novelDetail, isLoading } = storeToRefs(store);

    // Action
    const { fetchNovel } = store;

    onMounted(async () => {
        const { slug } = route.params
        await fetchNovel(slug);
    })
</script>
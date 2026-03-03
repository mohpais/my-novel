<template>
    <h5 class="text-sm font-medium text-gray-800 dark:text-white/90 mb-5">
        Justification Questions
    </h5>
    <div class="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800">
        <div class="custom-scrollbar overflow-x-auto">
            <table class="min-w-full text-left text-gray-700 dark:border-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="border-b border-gray-100 whitespace-nowrap dark:border-gray-800">
                        <th scope="col" class="px-5 py-4 font-medium whitespace-nowrap text-gray-700 dark:text-gray-400"></th>
                        <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $t("App.Question") }}</th>
                        <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $t("App.Answer") }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-white/[0.03]">
                    <template v-if="isLoading.fetchQuestion">
                        <tr v-for="i in 3" :key="i">
                            <td
                                colspan="3"
                                class="py-4 px-2 text-center"
                            >
                                <div class="flex items-center space-x-4 animate-tw-pulse">
                                    <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-10"></div>
                                    <div class="flex-1">
                                        <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-full mb-2"></div>
                                        <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-1/2"></div>
                                    </div>
                                    <div class="h-9.5 w-1/4 bg-gray-300 dark:bg-gray-700 rounded"></div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template v-else-if="dataJustifications.length === 0">
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data</td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr v-for="(item, index) in dataJustifications" :key="index">
                            <td
                                class="px-5 py-4 w-[2%] text-center text-gray-500 dark:text-gray-400"
                            >
                                {{ index + 1 }}
                            </td>
                            <td class="px-5 py-4 w-1/2 text-gray-800 dark:text-white/90">
                                {{ item.question }}
                            </td>
                            <td class="px-2 py-4">
                                <template v-if="props.justifications.length > 0">
                                    <span class="text-gray-800 dark:text-white/90">
                                        {{ item.answer }}
                                    </span>
                                </template>
                                <template v-else>
                                    <FormField
                                        v-model="item.answer"
                                        :name="`answer_${index + 1}`"
                                        id="answer"
                                        placeholder="Isi jawaban..."
                                        rules="required"
                                    />
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
    import { onMounted, computed } from "vue";
    import { useRequestStore } from "../store";
    import { storeToRefs } from "pinia";

    // Define the component's props
    const props = defineProps({
        justifications: {
            type: Array,
            default: () => []
        }
    });

    // Store
    const requestStore = useRequestStore();
    const { form, isLoading } = storeToRefs(requestStore);

    // Use a computed property to determine the data source
    const dataJustifications = computed(() => {
        // If justifications prop is not empty, use it.
        // Otherwise, use the data from the store.
        return props.justifications && props.justifications.length > 0 ? props.justifications.map(item => ({
            question: item.question.question_text,
            answer: item.answer
        })) : form.value.justifications;
    });

    const { fetchQuestion } = requestStore;
    onMounted(async () => {
        if (props.justifications.length === 0) {
            await fetchQuestion();
        }
    });
</script>


<style scoped>
    table {
        @apply border p-2 border-gray-300 dark:border-gray-800 w-full;
    }

    table > thead {
        @apply bg-gray-100 dark:bg-gray-800 text-xs;
    }

    table > thead > tr > th {
        @apply border border-gray-300 dark:border-gray-800 font-medium p-2;
    }

    table > tbody > tr > td {
        @apply border border-gray-300 dark:border-gray-800 p-2;
    }
</style>

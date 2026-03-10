import { reactive } from "vue";
import service from './service';
import { defineStore } from 'pinia';

export const useTagStore = defineStore('tag', () => {
    const data = reactive({
        options: [],
    });

    const isLoading = reactive({
        fetchOptions: false,
    });

    async function fetchOptions() {
        isLoading.fetchOptions = true;

        const result = await service.options();

        let { success, data: options } = result;

        if (success) {
            data.options = options;
        }
        isLoading.fetchOptions = false;
    }

    return {
        data,
        isLoading,
        fetchOptions,
    }
});

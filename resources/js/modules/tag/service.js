import apiService from '@/utils/apiService';

const tagService = {
    getList: async (page, limit, payload) => {
        let response = await apiService.post(
            `/tag/list/${page}/${limit}`,
            payload
        );

        return response.data;
    },
    get: async (slug) => {
        let response = await apiService.get(`/tag/${slug}`);
        return response.data;
    },
    options: async () => {
        let response = await apiService.get(`/tag/options`);
        return response.data;
    },
    create: async (payload) => {
        let response = await apiService.post(`/tag/create`, payload);
        return response.data;
    },
}

export default tagService;

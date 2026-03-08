import apiService from '@/utils/apiService';

const novelService = {
    getList: async (payload) => {
        let response = await apiService.post(
            `/novel/list`,
            payload
        );

        return response.data;
    },
    get: async (slug) => {
        let response = await apiService.get(`/novel/show/${slug}`);
        return response.data;
    },
    create: async (payload) => {
        let response = await apiService.post(`/novel/create`, payload);
        return response.data;
    },
}

export default novelService;

import apiService from '@/utils/apiService';

const novelService = {
    getList: async (page, limit, payload) => {
        let response = await apiService.post(
            `/novel/list/${page}/${limit}`,
            payload
        );

        return response.data;
    },
    get: async (slug) => {
        let response = await apiService.get(`/novel/${slug}`);
        return response.data;
    },
    create: async (payload) => {
        let response = await apiService.post(`/novel/create`, payload);
        return response.data;
    },
}

export default novelService;

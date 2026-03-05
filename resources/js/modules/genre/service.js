import apiService from '@/utils/apiService';

const genreService = {
    getList: async (page, limit, payload) => {
        let response = await apiService.post(
            `/genre/list/${page}/${limit}`,
            payload
        );

        return response.data;
    },
    get: async (slug) => {
        let response = await apiService.get(`/genre/${slug}`);
        return response.data;
    },
    options: async () => {
        let response = await apiService.get(`/genre/options`);
        return response.data;
    },
    create: async (payload) => {
        let response = await apiService.post(`/genre/create`, payload);
        return response.data;
    },
}

export default genreService;

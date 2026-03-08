import apiService from '@/utils/apiService';

const chapterService = {
    getList: async (novelSlug, payload) => {
        let response = await apiService.post(
            `/novel/${novelSlug}/chapters`,
            payload
        );

        return response.data;
    },
    get: async (novelSlug, slug) => {
        let response = await apiService.get(`/novel/${novelSlug}/chapter/${slug}`);
        return response.data;
    },
    create: async (payload) => {
        let response = await apiService.post(`/novel/chapter/store`, payload);
        return response.data;
    },
}

export default chapterService;

import apiService from '@/utils/apiService';

const assetService = {
    getCount: async () => {
        let response = await apiService.get('/asset/count');

        return response.data;
    },
    getList: async (type, page, limit, payload) => {
        let response = await apiService.post(
            `/asset/list/${type}/${page}/${limit}`,
            payload
        );
        return response.data;
    },
    getCurrentRequest: async () => {
        let response = await apiService.get(`/asset/show`);
        return response.data;
    },
    create: async (payload) => {
        let response = await apiService.post(`/asset/create`, payload);
        return response.data;
    },
    getBudgets: async () => {
        let response = await apiService.get(`/budget/list`);
        return response.data;
    },
    getBudgetOptions: async () => {
        let response = await apiService.get(`/budget/options`);
        return response.data;
    },
}

export default assetService;

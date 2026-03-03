import apiService from '@/utils/apiService';

const requestService = {
    getCount: async () => {
        let response = await apiService.get('/request/count');

        return response.data;
    },
    getList: async (page, limit, payload) => {
        let response = await apiService.post(
            `/request/list/${page}/${limit}`,
            payload
        );

        return response.data;
    },
    getPendingList: async (page, limit, payload) => {
        let response = await apiService.post(
            `/request/pending/${page}/${limit}`,
            payload
        );

        return response.data;
    },
    getWorkList: async (tab, page, limit, payload) => {
        let response = await apiService.post(
            `/request/${tab}/${page}/${limit}`,
            payload
        );

        return response.data;
    },
    getDraftRequest: async () => {
        let response = await apiService.get(`/request/draft`);
        return response.data;
    },
    get: async (request_code) => {
        let response = await apiService.get(`/request/${request_code}`);
        return response.data;
    },
    create: async (payload) => {
        let response = await apiService.post(`/request/create`, payload);
        return response.data;
    },
    approve: async (payload) => {
        let response = await apiService.post(`/request/approve`, payload);
        return response.data;
    },
    getBudgets: async () => {
        let response = await apiService.get(`/budget/list`);
        return response.data;
    },
    getQuestions: async () => {
        let response = await apiService.get(`/question/list`);
        return response.data;
    },
    getBudgetOptions: async () => {
        let response = await apiService.get(`/budget/options`);
        return response.data;
    },
}

export default requestService;

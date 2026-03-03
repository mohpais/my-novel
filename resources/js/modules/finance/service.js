import apiService from '@/utils/apiService';

const financeService = {
    getCount: async () => {
        let response = await apiService.get('/finance/count');

        return response.data;
    },
    getList: async (type, page, limit, payload) => {
        let response = await apiService.post(
            `/finance/list/${type}/${page}/${limit}`,
            payload
        );
        return response.data;
    },
}

export default financeService;

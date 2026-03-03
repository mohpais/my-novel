import apiService from '@/utils/apiService';

const responseService = {
    getList: async (page, limit, payload) => {
        let response = await apiService.post(
            `/user/list/${page}/${limit}`,
            payload
        );
        return response.data;
    },
    chageProfilePicture: async (payload) => {
        let response = await apiService.post(
            `/auth/change-profile-picture`, payload);

        return response.data;
    },
}

export default responseService;

import { useApi } from '../../composables/useApi';

const api = useApi();

const authService = {
    doLogin: async (payload) => {
        try {
            return await api.post('/auth/login', payload, { skipAuth: true });
        } catch (error) {
            throw new Error(error);
        }
    },
    doRegist: async (payload) => {
        try {
            return await api.post('/auth/register', payload, { skipAuth: true });
        } catch (error) {
            console.log(error);
            throw new Error(error);
        }
    },
    doLogout: async (endpoint) => {
        try {
            return await api.get(endpoint);
        } catch (error) {
            console.log(error);
            throw new Error(error);
        }
    },
    refreshUser: async () => {
        const profile = await api.get('/user/profile') // pastikan endpoint ini valid
        auth.user = profile
        localStorage.setItem('user', JSON.stringify(profile))
    }
};

export default authService;

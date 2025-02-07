import axios from 'axios';
import { useAuthStore } from '../store';
import router from '../router';

const apiClient = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

apiClient.interceptors.request.use(
    (config) => {
        const authStore = useAuthStore();
        if (authStore.token) {
            config.headers.Authorization = `Bearer ${authStore.token}`;
        } else {
            console.warn('No token found in Pinia store');
        }
        return config;
    },
    (error) => Promise.reject(error)
);


apiClient.interceptors.response.use(

    (response) => response,
    async (error) => {
        const authStore = useAuthStore();
        if (error.response?.status === 401) {
            console.warn('Unauthorized (401) - Logging out...');

            authStore.logout();
            localStorage.removeItem('user');
            localStorage.clear();
            router.push({ name: 'Unauthorized' })
            return Promise.reject(error);
        }
        return Promise.reject(error);
    }
);

export default apiClient;

import axios from 'axios';
import { useAuthStore } from '../store';

const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api',
  //baseURL : 'https://careerhub.onrender.com',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

apiClient.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore();
    const token = authStore.token;

    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    } else {
      console.warn('No token found in Pinia store');
    }

    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default apiClient;

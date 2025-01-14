import { defineStore } from 'pinia';
import apiService from '../api/apiService';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    currentUser: JSON.parse(localStorage.getItem('user')) || null,
    isLoggedIn: !!localStorage.getItem('user'),
    loading: false,
    authError: null,
  }),

  getters: {
    token: (state) => state.currentUser?.token || null,
  },

  actions: {
    async login(credentials) {
      this.loading = true;
      this.authError = null;

      try {
        const res = await apiService.login(credentials);
        this.currentUser = {
          ...res.user,
          token: res.token,
        };
        this.isLoggedIn = true;
        localStorage.setItem('user', JSON.stringify(this.currentUser));
      } catch (err) {
        this.authError = err.response?.data?.message || err.message;
        throw err;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        await apiService.logout();
        this.currentUser = null;
        this.isLoggedIn = false;
        localStorage.removeItem('user');
      } catch (err) {
        console.error('Logout error:', err);
        throw err;
      }
    },
  }
});

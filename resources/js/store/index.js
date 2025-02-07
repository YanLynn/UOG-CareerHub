import { defineStore } from 'pinia';
import apiService from '@components/js/api/apiService';
import { useTokenRefresh } from '@components/js/composables/tokenRefresh';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        currentUser: JSON.parse(localStorage.getItem('user')) || null,
        isLoggedIn: !!localStorage.getItem('user'),
        loading: false,
        authError: null,
        isVisible: false,
        message: '',
        type: 'success',
        userLists: [],
        jobSeekerProfile:[],
    }),

    getters: {
        token: (state) => state.currentUser?.token || null,
        isAdmin: (state) => state.currentUser?.role === 'Admin',
        isEmployer: (state) => state.currentUser?.role === 'Employer',
        isJobseeker: (state) => state.currentUser?.role === 'Jobseeker',
    },

    actions: {
        async getJobSeekerProfile() {

            try {
                const res = await apiService.getJobSeekerProfile();
                this.jobSeekerProfile = res;
                return Promise.resolve();
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },

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
                const { startTokenRefreshTimer } = useTokenRefresh();
                startTokenRefreshTimer();
                return Promise.resolve();
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },

        async register(credentials) {
            this.loading = true;
            this.authError = null;

            try {
                const res = await apiService.register(credentials);
                this.currentUser = {
                    ...res.user,
                    token: res.token,
                };
                this.isLoggedIn = true;
                localStorage.setItem('user', JSON.stringify(this.currentUser));
                const { startTokenRefreshTimer } = useTokenRefresh();
                startTokenRefreshTimer();
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },

        async userList() {

            this.loading = true;
            try {
                const users = await apiService.userList();

                if (!users || !users.user) {
                    this.authError = 'No users found';
                    this.userLists = [];
                }
                return this.userLists = users.user;

            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },
        async logout() {
            try {
                //await apiService.logout();
                this.currentUser = null;
                this.isLoggedIn = false;
                localStorage.removeItem('user');
            } catch (err) {
                console.error('Logout error:', err);
                throw err;
            }
        },

        async tokenRefresh() {
            try {
                if (!this.token) {
                    throw new Error('No token available for refresh');
                }

                const res = await apiService.tokenRefresh();
                this.currentUser = {
                    ...res.user,
                    token: res.token,
                };
                localStorage.setItem('user', JSON.stringify(this.currentUser));
            } catch (err) {
                console.error('Token refresh error:', err);
                await this.logout();
                throw err;
            }
        },

        showToast(message, type = 'success') {
            this.message = message;
            this.type = type;
            this.isVisible = true;

            // Auto-hide after 3 seconds
            setTimeout(() => {
                this.isVisible = false;
            }, 3000);
        },

        hideToast() {
            this.isVisible = false;
        },
    }
});

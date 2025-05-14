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
        checkJobAppliedList:[],
        EmployerProfile:[]
    }),

    getters: {
        token: (state) => state.currentUser?.token || null,
        isAdmin: (state) => state.currentUser?.role === 'Admin',
        isEmployer: (state) => state.currentUser?.role === 'Employer',
        isJobseeker: (state) => state.currentUser?.role === 'JobSeeker',
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
        async searchCountry($query) {
            try {
                const res = await apiService.searchCountry($query);
                return res;
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },
        async updateProfile($formData) {
            try {
                const res = await apiService.updateProfile($formData);
                return res;
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },
        async updateSkill($formData) {
            try {
                const res = await apiService.updateSkill($formData);
                return res;
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },
        async uploadResumeFile($formData, progressCallback = null) {
            try {
                const res = await apiService.uploadResumeFile($formData,progressCallback);
                return res;
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },
        async addWorkExp($formData) {
            try {
                const res = await apiService.addWorkExp($formData);
                return res;
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },
        async deleteHistory($formData) {
            try {
                const res = await apiService.deleteHistory($formData);
                return res;
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },
        async saveEdu($formData) {
            try {
                const res = await apiService.saveEdu($formData);
                return res;
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },
        async deleteEdu(eduID) {
            try {
                const res = await apiService.deleteEdu(eduID);
                return res;
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },
        async jobSeekerJobList(status) {
            try {
                const res = await apiService.jobSeekerJobList(status);
                return res;
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

        async getCompany() {
            try {
                const res = await apiService.getCompany();
                return res;
            } catch (err) {
                this.authError = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },

        async changeEmail(param){
            try {
                const res = await apiService.changeEmail(param);
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },
        async changePassword(param){
            try {
                const res = await apiService.changePassword(param);
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },

        async getJobsList(params){
            try {
                const res = await apiService.getJobsList(params);
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },
        async getJobById(jID){
            try {
                const res = await apiService.getJobById(jID);
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },
        async getCountry(){
            try {
                const res = await apiService.getCountry();
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },
        async getCategory(){
            try {
                const res = await apiService.getCategory();
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },
        async jobApplyAndSave(params){

            try {
                const res = await apiService.jobApplyAndSave(params);
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },
        async checkJobApplied(){
            try {
                const res = await apiService.checkJobApplied();
                return this.checkJobAppliedList = res.data
                // return Promise.resolve();
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },
        async getEmployerProfile(){
            try {
                const res = await apiService.getEmployerProfile();
                 this.EmployerProfile = res.data
                return Promise.resolve();
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },

        async createJobPost(params){
            try {
                const res = await apiService.createJobPost(params);
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },

        async deleteJob(jID){
            try {
                const res = await apiService.deleteJob(jID);
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },

        async updateJob(params){
            try {
                const res = await apiService.updateJob(params);
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },
        async getApplicationsByJob(){
            try {
                const res = await apiService.getApplicationsByJob();
                return res;
            } catch (error) {
                this.authError = error.response?.data?.message || error.message;
                throw error;
            }
        },

        async getContacts(param) {
            try {
                return await apiService.getContacts(param)
            } catch (error) {
                console.error("Contacts error:", error.message)
                throw error
            }
        },

        async getMessages(chatRoomId) {
            try {
                return await apiService.getMessages(chatRoomId)
            } catch (error) {
                console.error("Messages error:", error.message)
                throw error
            }
        },

        async sendMessage(chatRoomId, payload) {
            try {
                return await apiService.sendMessage(chatRoomId, payload)
            } catch (error) {
                console.error("Send message error:", error.message)
                throw error
            }
        },

        async sendTyping(chatRoomId, typing = true) {
            try {
                return await apiService.sendTyping(chatRoomId, typing = true)
            } catch (error) {
                console.error("Send message error:", error.message)
                throw error
            }
        },
        async updateJobApplicationStatus(param) {
            try {
                return await apiService.updateJobApplicationStatus(param)
            } catch (error) {
                console.error("Send message error:", error.message)
                throw error
            }
        },



    }



});

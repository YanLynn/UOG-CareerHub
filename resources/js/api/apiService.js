import api from './apiBasePath';

const apiService = {
  async login(credentials) {
    try {
      const response = await api.post('/login',credentials);
      return response.data;
    } catch (err) {
      console.error('Logout error:', err.response?.data || err.message);
      throw err;
    }
  },
  async logout() {
    try {
      const response = await api.post('/logout');  // Axios will attach the token
      return response.data;
    } catch (err) {
      console.error('Logout error:', err.response?.data || err.message);
      throw err;
    }
  },

  getLoggedInUser() {
    const userStr = localStorage.getItem('user');
    console.log('suerstr',userStr)
    return userStr ? JSON.parse(userStr) : null;
  },
};

export default apiService;

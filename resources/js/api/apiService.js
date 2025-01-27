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
      const response = await api.post('/logout');
      return response.data;
    } catch (err) {
      console.error('Logout error:', err.response?.data || err.message);
      throw err;
    }
  },

  async register(credentials) {
    try {
      const response = await api.post('/register',credentials);
      return response.data;
    } catch (err) {
      console.error('Logout error:', err.response?.data || err.message);
      throw err;
    }
  },

  async tokenRefresh() {
    try {
      const response = await api.post('/refresh');
      return response.data;
    } catch (err) {
      console.error('Logout error:', err.response?.data || err.message);
      throw err;
    }
  },
  async userList() {
    try {
      const response = await api.get('/user');
      console.log('API Response:', response);
      if (!response.data || !response.data.user) {
        throw new Error('No user data found in response.......');
      }
      return response.data;
    } catch (err) {
      console.error('User list error:', err.response?.data || err.message);
      throw err;
    }
  },

  getLoggedInUser() {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
  },
};

export default apiService;

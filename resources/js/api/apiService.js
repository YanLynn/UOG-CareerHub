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

  async getJobSeekerProfile(){
    try {
        const response = await api.get('/getJobSeekerProfile');
        if (!response.data) {
          throw new Error('No user data found in response.......');
        }
        return response.data;
      } catch (err) {
        console.error('jobSeeker Profile:', err.response?.data || err.message);
        throw err;
      }
  },

  async searchCountry($query){
    try {
        const response = await api.get(`/countries?query=${$query}`);
        if (!response.data) {
          throw new Error('No country data found in response.......');
        }
        return response.data;
      } catch (err) {
        console.error('country:', err.response?.data || err.message);
        throw err;
      }
  },
  async updateProfile($formData){
    try {
        const response = await api.post('/updateProfile',$formData);
        if (!response.data) {
          throw new Error('No profile data found in response.......');
        }
        return response.data;
      } catch (err) {
        console.error('profile:', err.response?.data || err.message);
        throw err;
      }
  },

  async updateSkill($formData){
    try {
        const response = await api.post('/updateSkill',$formData);
        if (!response.data) {
          throw new Error('No skill data found in response.......');
        }
        return response.data;
      } catch (err) {
        console.error('skill:', err.response?.data || err.message);
        throw err;
      }
  },
  async  uploadResumeFile($formData, progressCallback = null) {
    try {
        const response = await api.post('/uploadResumeFile', $formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
            onUploadProgress: progressCallback ? progressCallback : (progressEvent) => {
                console.log("Upload Progress:", Math.round((progressEvent.loaded * 100) / progressEvent.total));
            },
        });

        if (!response.data) {
            throw new Error('No document data found in response.......');
        }
        return response.data;
    } catch (err) {
        console.error('resumeFile:', err.response?.data || err.message);
        throw err;
    }
}




};

export default apiService;

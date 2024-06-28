import apiClient from './axios';

export default {
    register(user) {
        return apiClient.post('/register', user);
    },


    async login(credentials) {
        try {
            const response = await apiClient.post('/login', credentials);
            console.log("login data:", response.data.token)
            localStorage.setItem('token', response.data.token); // Store token in localStorage
            apiClient.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`; // Set Authorization header globally
            return response.data;
        } catch (error) {
            throw error;
        }
    },


    logout() {
        return apiClient.post('/logout');
    },
    getUser() {
        try {
            const token = localStorage.getItem('token'); // Get token from localStorage
            if (!token) {
                throw new Error('No token available');
            }
            
            // Set Authorization header for axios instance
            apiClient.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            
            return apiClient.get('/user');
        } catch (error) {
            throw error;
        }
    },
}

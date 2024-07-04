import apiClient from './axios';

export default {
    register(user) {
        return apiClient.post('/register', user);
    },


    async login(credentials) {
        try {
            const response = await apiClient.post('/login', credentials);
            console.log("store js log: 2?", response.data )
            localStorage.setItem('token', response.data.token); // Store token in localStorage
            localStorage.setItem('user', JSON.stringify(response.data.user));
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
            const token = localStorage.getItem('token'); 
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

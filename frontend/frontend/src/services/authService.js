import apiClient from './axios';

export default {
    register(user) {
        return apiClient.post('/register', user);
    },
    login(credentials) {
        return apiClient.post('/login', credentials);
    },
    logout() {
        return apiClient.post('/logout');
    },
    getUser() {
        return apiClient.get('/user');
    }
}

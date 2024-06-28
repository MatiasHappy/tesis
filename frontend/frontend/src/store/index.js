import { createStore } from 'vuex';
import authService from '../services/authService';
import apiClient from '../services/axios';


const store = createStore({
    state: {
        token: localStorage.getItem('token') || '',
        user: {}
    },
    mutations: {
        SET_TOKEN(state, token) {
            state.token = token;
        },
        SET_USER(state, user) {
            state.user = user;
        }
    },
    actions: {
        async register({ commit }, user) {
            await authService.register(user);
        },
        async login({ commit }, credentials) {
            const response = await authService.login(credentials);
            console.log("store js log: ", response )
            commit('SET_TOKEN', response.token);
            localStorage.setItem('token', response.token);
            apiClient.defaults.headers.common['Authorization'] = `Bearer ${response.token}`;
        },
        async logout({ commit }) {
            await authService.logout();
            commit('SET_TOKEN', '');
            localStorage.removeItem('token');
            delete apiClient.defaults.headers.common['Authorization'];
        },
        async fetchUser({ commit }) {
            const response = await authService.getUser();
            commit('SET_USER', response.data);
        }
    },
    getters: {
        isAuthenticated: state => !!state.token,
        user: state => state.user
    }
});

export default store;

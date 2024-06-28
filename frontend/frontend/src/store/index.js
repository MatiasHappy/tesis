import Vue from 'vue';
import Vuex from 'vuex';
import authService from '../services/authService';

Vue.use(Vuex);

export default new Vuex.Store({
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
            commit('SET_TOKEN', response.data.token);
            localStorage.setItem('token', response.data.token);
            apiClient.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
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

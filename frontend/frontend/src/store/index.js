// store/index.js

import { createStore } from 'vuex';
import authService from '../services/authService';
import apiClient from '../services/axios';

const store = createStore({
  state: {
    token: localStorage.getItem('token') || '',
    user: null, // Initialize user as null

    successState: false,
    failedState: false,
    loading: false
  },
  mutations: {
    SET_TOKEN(state, token) {
      state.token = token;
    },
    SET_USER(state, user) {
      state.user = user;
    },
    CLEAR_USER(state) {
      state.user = null; // Clear user data
    },
    SET_SUCCESS(state, value) {
      state.successState = value;
    },
    SET_FAILED(state, value) {
      state.failedState = value;
    },
    SET_LOADING(state, value) {
      state.loading = value
    }
  },
  actions: {
    async register({ commit }, user) {
      await authService.register(user);
    },
    async login({ commit }, credentials) {
      try {
        const response = await authService.login(credentials);
        commit('SET_TOKEN', response.token);
        commit('SET_USER', response.user);
        localStorage.setItem('token', response.token);
        apiClient.defaults.headers.common['Authorization'] = `Bearer ${response.token}`;
        return response; // Optionally return response data
      } catch (error) {
        throw error;
      }
    },
    async logout({ commit }) {
      try {
        await authService.logout();
        commit('SET_TOKEN', '');
        commit('CLEAR_USER'); // Clear user data from state
        localStorage.removeItem('token');
        delete apiClient.defaults.headers.common['Authorization'];
      } catch (error) {
        throw error;
      }
    },
    async fetchUser({ commit }) {
      try {
        const response = await authService.getUser();
        commit('SET_USER', response.data);
      } catch (error) {
        console.error('Error fetching user:', error);
        throw error;
      }
    }
  },
  getters: {
    isAuthenticated: state => !!state.token,
    user: state => state.user,

    successState: state => state.successState,
    failedState: state => state.failedState,
    loading: state => state.loading
  }
});

export default store;

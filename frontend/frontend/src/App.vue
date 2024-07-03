<template>
  <div class="flex flex-col h-screen font-SourceSans font-semibold">
    <nav class="flex-none hidden">
      <Logo />
   <!--
      
       Add login/logout button or links based on isAuthenticated
      <button v-if="isAuthenticated" @click="logout">Logout</button>
      <router-link v-else to="/login">Login</router-link> -->
    </nav>
    <router-view class="flex-1"></router-view>
    <footer class="flex-none">
      <FooterMobile v-if="isAuthenticated" />
    </footer>
  </div>
</template>

<script>
import { computed } from 'vue';
import { useStore } from 'vuex';
import authService from './services/authService';
import Logo from './components/partials/Logo.vue';
import FooterMobile from './components/partials/FooterApp.vue';

export default {
  name: 'App',
  components: {
    Logo,
    FooterMobile
  },
  computed: {
    isAuthenticated() {
      return this.$store.getters.isAuthenticated;
    },
    user() {
      return this.$store.state.user;
    }
  },
  methods: {
    async logout() {
      try {
        await this.$store.dispatch('logout');
        // Optionally, redirect to login page or perform other actions after logout
      } catch (error) {
        console.error('Error logging out:', error);
      }
    }
  },
  async created() {
    // Fetch user data when component is created
    if (this.isAuthenticated && !this.user) {
      try {
        await this.$store.dispatch('fetchUser');
      } catch (error) {
        console.error('Error fetching user:', error);
      }
    }
  }
};
</script>

<style>
nav {
  padding: 1em;
}
nav a {
  margin: 0 1em;
  text-decoration: none;
  color: #42b983;
}
</style>

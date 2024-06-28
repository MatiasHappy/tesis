<template>
  <div class="flex flex-col h-screen">
    <nav class="flex-none">
      <Logo />
      
     <!-- <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link>-->
    </nav>
    <router-view class="flex-1 overflow-y-auto"></router-view>
    <footer class="flex-none">
      <FooterMobile v-if="user" />
    </footer>
  </div>
</template>

<script>
import authService from './services/authService'; // Import your authentication service

import FooterMobile from './components/partials/FooterApp.vue'
import Logo from './components/partials/Logo.vue';

export default {
  name: 'App',
  components: {
    FooterMobile,  Logo
  },
    data() {
    return {
      user: null, // user object
      modalState: false,
      
    };
  },
  methods: {
    toggleModal() {
      this.modalState = !this.modalState;
    }
  },
  async created() {
    // Fetch user data when component is created (on application start)
    try {
      const response = await authService.getUser(); // Example: Fetch user data from authService
      this.user = response.data; // Set user object
    } catch (error) {
      console.error('Error fetching user:', error);
    }
  },
}
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

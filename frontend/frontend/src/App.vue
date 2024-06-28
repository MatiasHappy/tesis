<template>
  <div class="flex flex-col h-screen">
    <nav class="flex-none">
      <Logo />
     <!-- <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link>-->
    </nav>
    <router-view class="flex-1 overflow-y-auto"></router-view>
    <footer class="flex-none">
      <FooterMobile />
    </footer>
  </div>
</template>

<script>
import FooterMobile from './components/partials/FooterApp.vue'
import Logo from './components/partials/Logo.vue';

export default {
  name: 'App',
  components: {
    FooterMobile,  Logo
  },
    data() {
    return {
      modalState: false
    };
  },
  methods: {
    toggleModal() {
      this.modalState = !this.modalState;
    }
  }
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

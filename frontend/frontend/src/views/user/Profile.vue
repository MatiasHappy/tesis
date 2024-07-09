<template>
    <div>
      <h1>User Profile</h1>
      <ul v-if="user">
        <li><strong>Name:</strong> {{ user.name }}</li>
        <li><strong>Email:</strong> {{ user.household }}</li>
        <!-- Add more user fields as needed -->
      </ul>
      <p v-else>Loading user information...</p>
    </div>
  </template>
  <script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const isAuthenticated = computed(() => store.getters.isAuthenticated);
const user = computed(() => store.state.user);

// Log the user object for debugging
console.log(user);
</script>

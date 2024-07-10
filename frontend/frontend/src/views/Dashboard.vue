<template>
  <MainH1 class="sr-only m-auto text-center mt-4 mb-2 text-5xl text-duty"> 
    Weekly <span class="font-NovecentoRegular text-5xl text-fun">FLOW</span>
  </MainH1>
  <h2 class="sr-only m-auto text-center mt-4 mb-2 text-5xl text-duty"> 
  Current Tasks status
  </h2>
  <Logo3 class="w-10/12 m-auto mb-4 mt-8" />
    <div class="h-full ">
    
     <!-- <div v-if="user">
        <p>Hello! {{ user.name }}</p>
      
          <p>Email: {{ user.email }}</p>
      </div>-->
     
      <Tabs/>
     
    </div>
  </template>
  
  <script>
  import Tabs from '../components/Tabs.vue'
  import Textarea from '../components/Textarea.vue'
  import authService from '../services/authService'; // Import your authentication service
import Logo3 from '../components/partials/Logo3.vue'

  export default {
    name: 'Dashboard',
    data() {
    return {
      user: null, // user object
     
      
    };
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
    components: {
    Tabs,
    Textarea,
    Logo3
  }
  }
  </script>
  
<template>
    <div class="iso w-8/12 m-auto" @click="startSpinning">
      <img src="../../assets/imgs/iso.png" alt="DutyFlow iso" :class="{ spinning: isSpinning }">
    </div>
  </template>
  
  <script>
  export default {
    name: 'Loader',
    data() {
      return {
        isSpinning: false
      };
    },
    methods: {
      startSpinning() {
        // Simulating loading for demonstration purposes
        this.isSpinning = true;
  
        // Simulating asynchronous loading (replace with actual loading logic)
        setTimeout(() => {
          // After loading is complete
          this.isSpinning = false;
        }, 3000); // Replace 3000 with the actual time your loading operation takes
      }
    }
  };
  </script>
  
  <style scoped>
  @keyframes spin {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(360deg);
    }
  }
  
  .spinning {
    animation: spin 1s infinite linear; /* Adjust animation duration as needed */
  }
  </style>
  
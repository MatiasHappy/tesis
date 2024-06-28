<template>
    <div class="iso w-8/12 m-auto"  @click="spinImage">
      <img src="../../assets/imgs/iso.png" alt="DutyFlow iso" :class="{ spinning: isSpinning }">
    </div>
  </template>
  

  <script>
  export default {
    name: 'IsoButton',
    data() {
      return {
        isSpinning: false
      };
    },
    methods: {
      spinImage() {
        this.isSpinning = true;
       
        setTimeout(() => {
          this.isSpinning = false;
        }, 1000);
      }
    }
  }
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
    animation: spin 0.8s linear;
  }
  </style>
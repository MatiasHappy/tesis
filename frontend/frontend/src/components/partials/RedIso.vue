<template>
    <div class="iso  m-auto"  @click="spinImage">
      <img src="../../assets/imgs/redIso.png" alt="DutyFlow red iso" :class="{ spinning: isSpinning }">
    </div>
  </template>
  

  <script>
  export default {
    name: 'RedIso',
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
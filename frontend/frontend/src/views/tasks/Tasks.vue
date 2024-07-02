<template>
    <div class="">
      asdas
      <MainH1 class="m-auto text-center mb-6 text-red-500"> 
        Today's Schedule
      </MainH1>
      <Success v-if="state.showSuccess" @close="showSuccess = false" />
      <Tabs/>
  
    </div>
  </template>
  
  <script>
  import Tabs from '../../components/Tabs.vue';
  import Success from '../../components/modals/Success.vue';
  import { computed } from 'vue';
  import { useStore } from 'vuex';


      
  console.log("stas")
  export default {
    name: 'Tasks',
    components: {
      Success,
      Tabs
    },
    setup() {

    
 
      return {
        //successState,
       //failedState
      };
    }
  };
  </script>
  
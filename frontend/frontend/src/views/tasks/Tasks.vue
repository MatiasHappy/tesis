<template>
    <div class="">
        <Success v-if="state.showSuccess" @close="showSuccess = false" />
      <Tabs/>
  
    </div>
  </template>
  
  <script>
  import Tabs from '../../components/Tabs.vue';
  import Textarea from '../../components/Textarea.vue';
  import Success from '../../components/modals/Success.vue';
  import { state } from '../../services/state'; // Import the shared state
  
  export default {
    name: 'Tasks',
    components: {
      Success,
      Tabs,
      Textarea
    },
    data() {
      return {
      
        state // Make the state available in the component's data
      };
    },
    methods: {
      handleTaskCreated() {
        this.showSuccess = true;
      },
      updateMessage() {
        this.state.sharedMessage = 'Message changed by Tasks Component';
      }
    }
  };
  </script>
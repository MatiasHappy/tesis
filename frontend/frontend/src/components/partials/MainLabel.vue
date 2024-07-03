<!-- MainLabel.vue -->
<template>
    <label :for="for" class="block text-duty text-md font-SourceSans font-bold">
      {{ text }}
    </label>
  </template>
  
  <script>
  export default {
    name: 'MainLabel',
    props: {
      for: {
        type: String,
        required: true
      },
      text: {
        type: String,
        required: true
      }
    }
  };
  </script>
  
  <style scoped>
  /* Add any additional styling if necessary */
  </style>
  
<template>
  <div class="container px-3">
      <!-- Conditional Rendering: If task is loaded, show the task name; otherwise, show loading message -->
      <MainH1 v-if="task" class="m-auto text-center mb-6 text-red-500"> 
          {{ task.name }}
      </MainH1>
      <p v-if="task" class="text-xl mb-2 mt">Task Attempts {{ task.task_attempts.length }}</p>
      <p v-else>Loading...</p>
      
      <div class="flex gap-2">
      <!-- Iterating over task attempts if task is available -->
      <div v-if="task" v-for="attempt in task.task_attempts" :key="attempt.id" class="  "
      >
          <!-- Details about each attempt can be added here, such as attempt status or date -->
          <div class="h-4 w-4 bg-green-400" v-if="attempt.status === 'completed'"></div>
           <RedIso v-if="attempt.status === 'failed'" />
      </div>
    </div>
  </div>
</template>

  
  <script>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  import { fetchTask } from '../../services/taskService';
  
  export default {
    name: 'TaskView',
    setup() {
      const task = ref(null);
      const error = ref(null);
      const isLoading = ref(false);
      const route = useRoute();
  
      onMounted(async () => {
        isLoading.value = true;
        const taskId = route.params.id;
        if (taskId) {
          try {
            task.value = await fetchTask(taskId);
          } catch (err) {
            error.value = 'Failed to fetch task';
            console.error(err);
          } finally {
            isLoading.value = false;
          }
        } else {
          error.value = 'Task ID is undefined';
        }
      });
  
      return {
        task,
        error,
        isLoading
      };
    },
  };
  </script>
  
  
  <style scoped>
  /* Add your styles here */
  </style>
  
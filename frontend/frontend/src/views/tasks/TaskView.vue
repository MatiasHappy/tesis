<template>
  <div class="container px-3">
    <!-- Conditional Rendering: If task is loaded, show the task details; otherwise, show loading message -->

   
    <div v-if="task">
   
      <ul>
        <li
          :class="[
            'relative p-3 py-4 my-3',
            {
              'bg-duty': task.category_name === 'Duty',
              'bg-habit': task.category_name === 'Habit',
              'bg-fun': task.category_name === 'Fun',
            },
          ]"
        >
          <ul class="mb-2 flex space-x-1 text-md font-normal leading-4 text-black">
            <li
              v-if="task.task_times && task.task_times[0]"
              class="relative rounded-md px-3 py-0 tracking-wide text-lg font-NovecentoCondBold flex justify-center items-center"
              :class="[
                {
                  'bg-morning': task.task_times[0]?.time === 'morning',
                  'bg-afternoon': task.task_times[0]?.time === 'afternoon',
                  'bg-evening text-white': task.task_times[0]?.time === 'evening',
                  'bg-night text-white': task.task_times[0]?.time === 'night',
                },
                {
                  'text-black': ['Duty', 'Habit', 'Fun'].includes(task.task_category_id),
                },
              ]"
            >
              {{ task.task_times[0]?.time }}
            </li>
            <li v-if="task.category_name">&middot;</li>
            <li
              v-if="task.category_name"
              :class="[
                'relative rounded-md px-3 py-0 tracking-widest bg-black text-lg font-NovecentoCondBold',
                {
                  'text-duty': task.category_name === 'Duty',
                  'text-habit': task.category_name === 'Habit',
                  'text-fun': task.category_name === 'Fun',
                },
              ]"
            >
              {{ task.category_name }}
            </li>
            <li v-if="task.duration != null">&middot;</li>
            <li
              v-if="task.duration != null"
              class="rounded-full bg-white px-3 py-1 flex items-center justify-center"
            >
              {{ task.duration }}
            </li>
          </ul>
          <h3
            class="text-2xl mt-4 text-white tracking-wide leading-5 font-NovecentoCondBold"
            :class="[
              'relative rounded-md uppercase font-bold text-3xl font-SourceSans',
              {
                '': task.task_category_id === 'Duty',
                '': task.task_category_id === 'Habit',
                '': task.task_category_id === 'Fun',
              },
            ]"
          >
          <MainH1 class=" uppercase text-2xl   text-white"> 
            {{ task.name }}
          </MainH1>
          </h3>
          <router-link
            :to="`/task/${task.id}`"
            class="absolute top-2 right-12 text-white rounded-full px-2 py-1 text-xs"
          >
            <PencilSquareIcon class="h-8 w-8 text-white" />
          </router-link>
          <button
            v-if="idx == 0"
            @click="completeTask(day, task.id, task.task_times[0]?.time)"
            class="absolute top-2 right-2 text-white rounded-full px-2 py-1 text-xs"
          >
            <CheckCircleIcon class="h-8 w-8 text-white" />
          </button>
        </li>
      </ul>
    </div>
    

    <div v-if="task">
     
      
      <div class="">

        <h2 class="font-SourceSans font-semibold text-duty text-2xl">Current Task Status:{{ completedPercentage }}%</h2>
        <div class="progress-bar-container">
      
          <div
            v-for="attempt in task.task_attempts"
            :key="attempt.id"
            :class="['progress-segment', { ' failed bg-red-500': attempt.status === 'failed', 'bg-duty': attempt.status === 'completed' }]"
          ></div>
        </div>

      </div>



      <p class="text-xl mb-2 mt-4">Description: {{ task.description }}</p>
      <p class="text-xl mb-2">Repeat Interval: {{ task.repeat_interval }}</p>
      <p class="text-xl mb-2">Start Date: {{ task.start_date }}</p>
      <p class="text-xl mb-2">End Date: {{ task.end_date }}</p>
      <p class="text-xl mb-2">Category ID: {{ task.task_category_id }}</p>
      <p class="text-xl mb-2">Time of Day: {{ task.task_times.map(t => t.time).join(', ') }}</p>
      <p class="text-xl mb-2">Task Attempts: {{ task.task_attempts.length }}</p>

     
     <!-- <div class="flex gap-2 mt-4">
        <div v-for="attempt in task.task_attempts" :key="attempt.id" class="attempt-details">
          <p>Attempt ID: {{ attempt.id }}</p>
          <p>Status: 
            <span v-if="attempt.status === 'completed'" class="text-green-500">Completed</span>
            <span v-if="attempt.status === 'failed'" class="text-red-500">Failed</span>
            <span v-else class="text-gray-500">{{ attempt.status }}</span>
          </p>
        </div>
      </div>-->
      
      <button @click="openEditModal" class="mt-4 inline-flex justify-center rounded-md border border-transparent bg-duty px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Edit Task</button>
      <button @click="openDeleteModal" class="mt-4 ml-2 inline-flex justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Delete Task</button>
    </div>
    <p v-else>Loading...</p>

    <!-- TaskEdit Modal -->
    <TaskEdit :open="isEditModalOpen" :task="task" @update:open="isEditModalOpen = $event" @task-updated="handleTaskUpdated" />
    <!-- Delete Confirmation Modal -->
    <TransitionRoot as="template" :show="isDeleteModalOpen">
      <Dialog class="relative z-10" @close="closeDeleteModal">
        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
          <div class="flex min-h-full w-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
              <DialogPanel class="relative w-full transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                <div>
                  <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                   
                  </div>
                  <div class="mt-3 text-center sm:mt-5">
                    <DialogTitle as="h3" class="text-lg font-medium text-gray-900">Delete Task</DialogTitle>
                    <div class="mt-2">
                      <p class="text-sm text-gray-500">Are you sure you want to delete this task? This action cannot be undone.</p>
                    </div>
                  </div>
                </div>
                <div class="mt-5 sm:mt-6">
                  <button @click="handleDeleteTask" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Delete</button>
                  <button @click="closeDeleteModal" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Cancel</button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { fetchTask, deleteTask } from '../../services/taskService';
import MainH1 from '../../components/partials/MainH1.vue';
import RedIso from '../../components/partials/RedIso.vue'; // Adjust the path as necessary
import TaskEdit from '../../components/modals/EditTask.vue'; // Adjust the path as necessary
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';

export default {
  name: 'TaskView',
  components: {
    MainH1,
    RedIso,
    TaskEdit,
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
  },
  setup() {
    const task = ref(null);
    const taskId = ref(null);
    const error = ref(null);
    const isLoading = ref(false);
    const isEditModalOpen = ref(false);
    const isDeleteModalOpen = ref(false);
    const route = useRoute();
    const router = useRouter();

    onMounted(async () => {
      isLoading.value = true;
      taskId.value = route.params.id;
      if (taskId.value) {
        try {
          task.value = await fetchTask(taskId.value);
          console.log(task.value, "task")
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

    const openEditModal = () => {
      isEditModalOpen.value = true;
    };

    const openDeleteModal = () => {
      isDeleteModalOpen.value = true;
    };

    const closeDeleteModal = () => {
      isDeleteModalOpen.value = false;
    };

    const handleDeleteTask = async () => {
      try {
        await deleteTask(taskId.value);
        closeDeleteModal();
        router.push('/'); // Redirect to another page after deletion
      } catch (err) {
        console.error('Failed to delete task:', err);
      }
    };

    const handleTaskUpdated = (updatedTask) => {
      task.value = updatedTask;
      isEditModalOpen.value = false;
    };

    const completedPercentage = computed(() => {
      if (!task.value || !task.value.task_attempts) return 0;
      const completedAttempts = task.value.task_attempts.filter(attempt => attempt.status === 'completed').length;
      const totalAttempts = task.value.task_attempts.length;
      return totalAttempts > 0 ? Math.round((completedAttempts / totalAttempts) * 100) : 0;
    });

    return {
      task,
      taskId,
      error,
      isLoading,
      isEditModalOpen,
      isDeleteModalOpen,
      openEditModal,
      openDeleteModal,
      closeDeleteModal,
      handleDeleteTask,
      handleTaskUpdated,
      completedPercentage,
    };
  },
};
</script>

<style scoped>
.attempt-details {
  padding: 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
  background-color: #f7fafc;
  margin-bottom: 0.5rem;
}

.progress-bar-container {
  display: flex;
  width: 100%;
  height: 20px;
  background-color: #e0e0e0;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 20px;
}

.progress-segment {
  flex-grow: 1;
 }
</style>
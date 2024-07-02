<template>
  <TransitionRoot as="template" :show="open">
    <Dialog class="relative z-10 font-NovecentoRegular text-black" @close="closeModal">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full w-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel class="relative w-full transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
              <div>
                <div class="hidden mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                  <CheckIcon class="h-6 w-6 text-green-600" aria-hidden="true" />
                </div>
                <div class="mt-3 text-center sm:mt-5">
                  <DialogTitle as="h3" class="text-2xl tracking-widest  font-NovecentoCondBold text-duty">What’s our next priority?</DialogTitle>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">Choose your Flow</p>
                  </div>
                </div>
              </div>
              <div class="mt-2 sm:mt-6">
                <form @submit.prevent="handleCreateTask">


                  <fieldset aria-label="Choose a Category" class="mb-8">
                    <div class="hidden flex items-center justify-between">
                      <div class="text-sm font-medium leading-6 text-gray-900">RAM</div>

                    </div>
                    
                    <RadioGroup v-model="task.task_category_id" class="mt-2 grid grid-cols-3 gap-3 sm:grid-cols-6">
                      <RadioGroupOption as="template" v-for="category in categories" :key="category.id" :value="category.id" v-slot="{ active, checked }">
                        <div :class="[ active ? 'ring-2 ring-black ring-duty' : '', checked ? 'bg-habit text-white hover:bg-habit' : 'bg-white text-gray-900 ring-1 ring-inset ring-duty hover:bg-gray-50', 'flex items-center justify-center rounded-md px-3 py-3 text-sm font-semibold uppercase sm:flex-1']">{{ category.name }}</div>
                      </RadioGroupOption>
                    </RadioGroup>
                  </fieldset>


                  <div class="form-group my-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">

                       Name
                    
                    
                    </label>
                    <input type="text" id="name" v-model="task.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name[0] }}</span>
                  </div>

                  <div class="form-group mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" v-model="task.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    <span v-if="errors.description" class="text-red-500 text-sm">{{ errors.description[0] }}</span>
                  </div>


                  <div class="form-group mb-4">
                    <label for="time_of_day" class="block text-sm font-medium text-gray-700">Time of Day</label>
                    <div class="mt-1">
                      <div class="flex items-center mb-2">
                        <input type="checkbox" id="morning" v-model="task.time_of_day" value="morning" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <label for="morning" class="ml-2 text-sm text-gray-700">Morning</label>
                      </div>
                      <div class="flex items-center mb-2">
                        <input type="checkbox" id="afternoon" v-model="task.time_of_day" value="afternoon" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <label for="afternoon" class="ml-2 text-sm text-gray-700">Afternoon</label>
                      </div>
                      <div class="flex items-center mb-2">
                        <input type="checkbox" id="evening" v-model="task.time_of_day" value="evening" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <label for="evening" class="ml-2 text-sm text-gray-700">Evening</label>
                      </div>
                      <div class="flex items-center mb-2">
                        <input type="checkbox" id="night" v-model="task.time_of_day" value="night" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <label for="night" class="ml-2 text-sm text-gray-700">Night</label>
                      </div>
                    </div>
                    <span v-if="errors.time_of_day" class="text-red-500 text-sm">{{ errors.time_of_day[0] }}</span>
                  </div>

                  
                  <div class="form-group mb-4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" id="start_date" v-model="task.start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    <span v-if="errors.start_date" class="text-red-500 text-sm">{{ errors.start_date[0] }}</span>
                  </div>

                  <div class="form-group mb-4">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" id="end_date" v-model="task.end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" >
                    <span v-if="errors.end_date" class="text-red-500 text-sm">{{ errors.start_date[0] }}</span>
                  </div>


                  <div class="form-group mb-4">
                    <label for="repeat_interval" class="block text-sm font-medium text-gray-700">Repeat Interval (days)</label>
                    <input type="number" id="repeat_interval" v-model="task.repeat_interval" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <small class="form-text text-muted">Leave empty if this is a one-time task.</small>
                    <span v-if="errors.repeat_interval" class="text-red-500 text-sm">{{ errors.repeat_interval[0] }}</span>
                  </div>

                 

                

                  <div class="mt-5 sm:mt-6">
                    <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-duty px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Create Task</button>
                  </div>
                </form>

                <div class="mt-5 sm:mt-6">
                  <button type="button" class="inline-flex w-full justify-center rounded-md bg-duty px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-duty" @click="closeModal">Go back to dashboard</button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot, RadioGroup, RadioGroupOption } from '@headlessui/vue';
import { CheckIcon } from '@heroicons/vue/24/outline';
import { useRouter, useRoute } from 'vue-router'
import { fetchCategories, createTask } from '../../services/taskService';
import { state } from '../../services/state';



const props = defineProps({
  open: {
    type: Boolean,
    default: false
  }
});
const emit = defineEmits(['update:open', 'task-created']);

const task = reactive({
  name: '',
  description: '',
  repeat_interval: null,
  start_date: '',
  end_date: '',
  task_category_id: null,
  time_of_day: [],
});
const categories = ref([]);
const errors = reactive({});

const router = useRouter()
const route = useRoute()

console.log("categories", categories);

const handleCreateTask = async () => {
  console.log(task, "SUPER IMPORTANT TASK")
  await createTask(task, errors,emit, categories);
 // router.go(0)
};

const closeModal = () => {
  emit('update:open', false);
};

onMounted(() => {
  fetchCategories(categories);

});

watch(() => props.open, (newValue) => {
  if (!newValue) {
    task.name = '';
    task.description = '';
    task.repeat_interval = null;
    task.start_date = '';
    task.end_date = '';
    task.task_category_id = null;
    task.time_of_day = [];
    Object.keys(errors).forEach(key => {
      delete errors[key];
    });
  }
});
</script>

<style>
/* Add any required CSS here */
</style>

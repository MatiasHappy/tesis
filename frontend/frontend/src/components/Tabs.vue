<template>
  <div class="w-full max-w-md  py-2 sm:px-0 relative">
   <!--TODO:: COMPLETED DIALOG FOR CREATEDTASK-->
    <!--<Success v-if="successState" @close="successState = false" />-->
    <Completed v-if="successState" @close="successState = false" />
    <p v-if="failedState">Failed to create task.</p>
    <TabGroup>
      <TabList class="flex space-x-12  top-0 py-2 bg-black p-1 overflow-auto  top-0 z-10">
        <Tab
          v-for="day in orderedDays"
          :key="day"
          as="template"
          v-slot="{ selected }"
        >
          <button
            :class="[
              'w-full px-6 rounded-lg py-2.5 text-xl font-medium leading-5',
              'ring-white/60 ring-offset-2 ring-offset-fun focus:outline-none focus:ring-2',
              selected
                ? 'bg-white text-black shadow font-NovecentoCondBold tracking-wide'
                : 'text-white hover:bg-white/[0.12] hover:text-red font-NovecentoRegular ',
            ]"
          >
            {{ day }}
          </button>
        </Tab>
      </TabList>

      <TabPanels class=" px-2 h-full overflow-y-auto" style=" max-height:57vh">
        <TabPanel
          v-for="(day, idx) in orderedDays"
          :key="idx"
          :class="[
            'rounded-md',
            '',
          ]"
        >
          <ul>
            <li


              v-for="item in orderedCategories[day]"
              :key="item.id"
              :class="[
                'relative rounded-md p-3  my-4 ',
                {
                  'bg-duty': item.category_name === 'Duty',
                  'bg-habit': item.category_name === 'Habit',
                  'bg-fun': item.category_name === 'Fun',
                },
              ]"
            >

              <ul
                class="mb-2 flex space-x-1 text-md font-normal leading-4 text-black"
              >
                <li class="relative rounded-md px-3 py-0 tracking-wide text-lg font-NovecentoCondBold  flex justify-center items-center"
                :class="[
               
                {
                  'bg-morning': item.task_times[0].time == 'morning',
                  'bg-afternoon': item.task_times[0].time == 'afternoon',
                  'bg-evening text-white': item.task_times[0].time == 'evening',
                  'bg-night text-white': item.task_times[0].time == 'night',
                },
                {
                  'text-black': item.category_name === 'Duty',
                  'text-black': item.category_name === 'Habit',
                  'text-black': item.category_name === 'Fun',
                },
              ]"

                >{{ item.task_times[0].time}}</li>
                <li>&middot;</li>
                <li 
                :class="[
                'relative rounded-md px-3 py-0 tracking-widest text-lg font-NovecentoCondBold',
                {
                  'text-duty': item.category_name === 'Duty',
                  'text-habit': item.category_name === 'Habit',
                  'text-fun': item.category_name === 'Fun',
                },
              ]"
                class="rounded-full bg-black  flex items-center justify-center">{{ item.category_name }}</li>
                <li v-if="item.duration != null">&middot;</li>
                <li v-if="item.duration != null" class="rounded-full bg-white px-3 py-1 flex items-center justify-center">{{ item.duration }}</li>
              </ul>
              <h3 class="text-3xl py-2 font-medium leading-5 font-NovecentoCondBold"
              
              :class="[
                'relative rounded-md  font-NovecentoCondBold',
                {
                  'font-NovecentoCondBold text-white': item.category_name === 'Duty',
                  'font-NovecentoCondRegular': item.category_name === 'Habit',
                  'font-NovecentoRegular': item.category_name === 'Fun',
                },]"
              >
                {{ item.name }}
              </h3>
              <router-link :to="`/task/${item.id}`"
                class="absolute top-2 right-12  text-white rounded-full px-2 py-1 text-xs"
              > 
               <PencilSquareIcon  class="h-8 w-8 text-white"/>
              </router-link>
              <button v-if="idx == 0"
                @click="completeTask(day, item.id, item.task_times[0].time)"
                class="absolute top-2 right-2  text-white rounded-full px-2 py-1 text-xs"
              >
               <CheckCircleIcon  class="h-8 w-8 text-white"/>
              </button>
            </li>
          </ul>
        </TabPanel>
      </TabPanels>
    </TabGroup>
  </div>
</template>

<script setup>
  import Success from './modals/Success.vue';
  import Completed from './modals/Completed.vue';
  
import { state } from '../services/state';
import { useStore } from 'vuex';
import { ref, onMounted, computed } from 'vue'
  import { CheckCircleIcon, PencilSquareIcon } from '@heroicons/vue/20/solid'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { fetchTasksForDay, markAsCompleted  } from '../services/taskService';


const store = useStore();
  // Computed properties to access Vuex state
  const successState = computed(() => store.getters.successState);
  const failedState = computed(() => store.getters.failedState);

  console.log("store in comp", store)
 
  

// Static days array
const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
const props = defineProps({
    open: {
      type: Boolean,
      default: false
    }
  });
// Function to generate ordered days starting from today
const getOrderedDays = () => {
  const todayIndex = new Date().getDay()
  return days.slice(todayIndex).concat(days.slice(0, todayIndex))
}

// Computed property for ordered days
const orderedDays = computed(() => getOrderedDays())

// Initialize categories with all days
const categories = ref({
  Sunday: [],
  Monday: [],
  Tuesday: [],
  Wednesday: [],
  Thursday: [],
  Friday: [],
  Saturday: []
})

console.log(categories.value, "cats")
// Computed property for ordered categories based on ordered days
const orderedCategories = computed(() => {
  const ordered = {};
  orderedDays.value.forEach(day => {
    ordered[day] = categories.value[day];
  });
  return ordered;
});

// Function to fetch all tasks for all ordered days
const fetchAllTasks = async () => {
  for (const day of orderedDays.value) {
    console.log(day, "day")
    await fetchTasksForDay(day, categories)
  }
}

// Function to mark a task as completed
const completeTask = (day, taskId, taskTime) => {
  markAsCompleted(day, taskId, taskTime, categories);
}

// Fetch tasks when the component is mounted
onMounted(() => {
  fetchAllTasks()
})
</script>

<style>
.tabs button {
  margin-right: 5px;
}

.tabs button.active {
  font-weight: bold;
}

.tasks {
  margin-top: 20px;
}
</style>

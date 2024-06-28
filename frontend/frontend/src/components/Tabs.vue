<template>
  <div class="w-full max-w-md px-2 py-2 sm:px-0">
   
    <TabGroup>
      <TabList class="flex space-x-12 rounded-xl bg-blue-900/20 p-1 overflow-auto">
        <Tab
          v-for="day in orderedDays"
          :key="day"
          as="template"
          v-slot="{ selected }"
        >
          <button
            :class="[
              'w-full px-6 rounded-lg py-2.5 text-sm font-medium leading-5',
              'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
              selected
                ? 'bg-white text-black shadow'
                : 'text-black hover:bg-white/[0.12] hover:text-red',
            ]"
          >
            {{ day }}
          </button>
        </Tab>
      </TabList>

      <TabPanels class="mt-2">
        <TabPanel
          v-for="(day, idx) in orderedDays"
          :key="idx"
          :class="[
            'rounded-xl',
            'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
          ]"
        >
          <ul>
            <li


              v-for="item in orderedCategories[day]"
              :key="item.id"
              :class="[
                'relative rounded-md p-3 hover:bg-gray-100 my-4',
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
                <li class="rounded-full bg-white px-3 py-1 flex items-center justify-center">{{ item.task_times[0].time}}</li>
                <li>&middot;</li>
                <li class="rounded-full bg-white px-3 py-1 flex items-center justify-center">{{ item.category_name }}</li>
                <li v-if="item.duration != null">&middot;</li>
                <li v-if="item.duration != null" class="rounded-full bg-white px-3 py-1 flex items-center justify-center">{{ item.duration }}</li>
              </ul>
              <h3 class="text-2xl py-2 font-medium leading-5">
                {{ item.name }}
              </h3>
              <router-link :to="`/task/${item.id}`"
                class="absolute top-2 right-12  text-white rounded-full px-2 py-1 text-xs"
              > 
               <PencilSquareIcon  class="h-8 w-8 text-green-300"/>
              </router-link>
              <button v-if="idx == 0"
                @click="completeTask(day, item.id, item.task_times[0].time)"
                class="absolute top-2 right-2  text-white rounded-full px-2 py-1 text-xs"
              >
               <CheckCircleIcon  class="h-8 w-8 text-green-300"/>
              </button>
            </li>
          </ul>
        </TabPanel>
      </TabPanels>
    </TabGroup>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
  import { CheckCircleIcon, PencilSquareIcon } from '@heroicons/vue/20/solid'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { fetchTasksForDay, markAsCompleted  } from '../services/taskService';

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

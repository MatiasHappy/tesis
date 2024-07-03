// taskService.js
import axios from 'axios';

import { useStore } from 'vuex';

import { state } from './state';
import { ref } from 'vue';

import store from '../store/index'; // Adjust the path as per your actual file structure




const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

export const fetchTasksForDay = async (day, categories, createdFlag) => {
//console.log(createdFlag)
if (createdFlag) {
  //console.log("flag!!")
  localStorage.removeItem(`tasks_${day}`);

}
  //console.log(`Checking localStorage for tasks_${day}`);
  const localData = localStorage.getItem(`tasks_${day}`);
  if (localData) {
    console.log(`Loaded tasks for ${day} from localStorage`);
    categories.value[day] = JSON.parse(localData);
    return;
  }

  //console.log(`Fetching tasks for ${day} from API`);
  try {
    const response = await axios.get(`http://localhost:8000/api/tasks/${day.toLowerCase()}`);
   console.log("API response for", day, response.data);
    if (Array.isArray(response.data)) {
      const tasks = response.data.map(task => ({
        ...task,
        duration: task.duration || null,
      }));
      categories.value[day] = tasks;
      localStorage.setItem(`tasks_${day}`, JSON.stringify(tasks));
     // console.log(`Tasks for ${day} saved to localStorage`);
    } else {
      console.error(`Unexpected response data for ${day}:`, response.data);
    }
  } catch (error) {
    console.error(`Error fetching tasks for ${day}:`, error);
  }
};

export const markAsCompleted = (day, taskId, taskTime, categories) => {
  store.commit('SET_LOADING', true);
  console.log(`Marking task ${taskId} (${taskTime}) as completed for ${day}`);
  //console.log(localStorage.getItem(`tasks_${day}`), "getitem")
  console.log("00", taskTime)
  console.log(1, categories.value[day])
  if (categories.value[day]) {
    categories.value[day] = categories.value[day].filter(task => !(task.id === taskId && task.time_of_day === taskTime));
    console.log("day::",  categories.value[day])
    
    localStorage.setItem(`tasks_${day}`, JSON.stringify(categories.value[day]));
    //console.log(JSON.stringify(categories.value[day]));
    
    checkAttempt(taskId, 'completed'); // Record attempt for the specific task time
    
    console.log(`Updated tasks for ${day} saved to localStorage`);
  } else {
    console.error(`Tasks for ${day} are not defined`);
  }
};


// Function to check and mark tasks as failed if not completed
/*export const markAsFailed = async (day, taskId, taskTime, categories) => {
  const currentHour = new Date().getHours();
console.log('1 entering failed fc' , currentHour);

console.log(Object.keys(categories.value), '2 object keys categories value')
  // Iterate over each day and its tasks
  for (const day of Object.keys(categories.value)) {
    console.log('3 entering first for' );

    for (const task of categories.value[day]) {
      console.log('4 entering second for' );
      console.log(task, '5 task in second for>> ')
      // Check if morning task is not completed and mark as failed
      const morningTask = task.task_times.find(time => time.time === 'morning');

      console.log(morningTask, '6 find in task.task_times')


      if (morningTask && !morningTask.completed && currentHour >= 12) {
        morningTask.failed = true; // Mark task time as failed
        
        // Register failed attempt via API call
        await checkAttempt(task.id, 'failed');
      }
      // Add similar checks for other times of day if needed (afternoon, evening, night)
    }
  }
}; */


export const markAsFailed = async (day, taskId, taskTime, categories) => {
  const currentHour = new Date().getHours();
console.log('1 entering failed fc' , currentHour);

console.log(Object.keys(categories.value), '2 object keys categories value')
  // Iterate over each day and its tasks
  for (const day of Object.keys(categories.value)) {
    console.log('3 entering first for' );

    for (const task of categories.value[day]) {
      console.log('4 entering second for' );
      console.log(task, '5 task in second for>> ')
      // Check if morning task is not completed and mark as failed
      const morningTask = task.task_times.find(time => time.time === 'morning');

      console.log(morningTask, '6 find in task.task_times')


      if (morningTask && !morningTask.completed && currentHour >= 12) {
        morningTask.failed = true; // Mark task time as failed
        
        // Register failed attempt via API call
        //await checkAttempt(task.id, 'failed');
      }
      // Add similar checks for other times of day if needed (afternoon, evening, night)
    }
  }
};



export const checkAttempt = async (taskId, status) => {
  const attemptData = {
    attempt_date: new Date().toISOString().split('T')[0], // Current date in YYYY-MM-DD format
    status, 
  
  };
console.log('checkAttempt FC' , attemptData)
  try {
    const response = await axios.post(`http://localhost:8000/api/tasks/${taskId}/record-attempt`, attemptData);
    store.commit('SET_SUCCESS', true);
    
    console.log('Task attempt recorded:', response.data);
  } catch (error) {
    console.error('Error recording task attempt:', error);
  }
};







export const fetchCategories = async (categories) => {
  try {
    const response = await axios.get('http://localhost:8000/api/task-categories');
    categories.value = response.data;
  } catch (error) {
    console.error("There was an error fetching the categories:", error);
  }
};



export const createTask = async (task, errors, emit, categories) => {
 
  try {
    console.log('Task being sent:', task);
    const response = await axios.post('http://localhost:8000/api/tasks', task);
    emit('update:open', false);
    console.log('Response data:', response.data);

    // Calculate the correct day of the week using local time
    const startDate = new Date(task.start_date);
    const localStartDate = new Date(startDate.getTime() + startDate.getTimezoneOffset() * 60000);
    const dayIndex = localStartDate.getDay();
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const day = daysOfWeek[dayIndex];
    console.log('Day:', day);
    console.log('Day index:', dayIndex);
    console.log('Start date:', startDate);

  /*  // Update local storage with the new task
    const storedTasks = JSON.parse(localStorage.getItem(`tasks_${day}`)) || [];
    storedTasks.push(response.data);
    localStorage.setItem(`tasks_${day}`, JSON.stringify(storedTasks));
    console.log(`New task added to localStorage for ${day}`); */
  

    for (const day of daysOfWeek) {
      console.log(day, "day")
      await fetchTasksForDay(day, categories, true)
    }

    // Update the categories to include the new task
   /* if (categories.value[day]) {
      categories.value[day].push(response.data);
    } else {
      categories.value[day] = [response.data];
    }*/

   // emit('task-created', { day, task: response.data });
    state.showSuccess = true;



    store.commit('SET_SUCCESS', true);
    

  } catch (error) {
    if (error.response) {
      store.commit('SET_FAILED', true);
      console.error('Error response:', error.response);
      if (error.response.data && error.response.data.errors) {
        Object.assign(errors, error.response.data.errors);
      }
    } else {
      console.error("There was an error creating the task:", error);
    }
  }
};


// Function to rotate tasks in localStorage based on the current day of the week


export const fetchTask = async (id) => {
  try {
    const response = await axios.get(`http://localhost:8000/api/task/${id}`);
    console.log(response, "RESPONSE");
    console.log(response.data, "data");
    return response.data; // Return the task data directly
  } catch (error) {
    console.error('Error fetching task:', error);
    if (error.response) {
      console.error('Error response data:', error.response.data);
      console.error('Error response status:', error.response.status);
      console.error('Error response headers:', error.response.headers);
    }
    throw error; // Re-throw the error to be handled by the caller
  }
};

// ATTEMPT FUNCTIONALITY 

export const updateTask = async (taskId, taskData) => {
  try {
    const response = await axios.put(`http://localhost:8000/api/tasks/${taskId}`, taskData);
    return response.data;
  } catch (error) {
    if (error.response) {
      if (error.response.status === 422) {
        throw error.response.data.errors; // Throw validation errors
      } else {
        // Throw any other errors returned by the server
        throw new Error(error.response.data.message || 'Failed to update task');
      }
    } else {
      // Handle any network or other errors
      throw new Error('Network error or server is not reachable');
    }
  }
};


export const deleteTask = async (taskId) => {
  try {
    await axios.delete(`http://localhost:8000/api/tasks/${taskId}`);
    const token = localStorage.getItem('token'); // Save the token
    localStorage.clear(); // Clear all items
    localStorage.setItem('token', token); // Restore the token
 
  } catch (error) {
    throw new Error('Failed to delete task');
  }
};
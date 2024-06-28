// taskService.js
import axios from 'axios';
import { state } from './state';
import { ref } from 'vue';
const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

export const fetchTasksForDay = async (day, categories) => {
  console.log(`Checking localStorage for tasks_${day}`);
  const localData = localStorage.getItem(`tasks_${day}`);
  if (localData) {
    console.log(`Loaded tasks for ${day} from localStorage`);
    categories.value[day] = JSON.parse(localData);
    return;
  }

  //console.log(`Fetching tasks for ${day} from API`);
  try {
    const response = await axios.get(`http://localhost:8000/api/tasks/${day.toLowerCase()}`);
   //console.log("API response for", day, response.data);
    if (Array.isArray(response.data)) {
      const tasks = response.data.map(task => ({
        ...task,
        timeOfDay: 'morning',
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

export const markAsCompleted = (day, taskId, categories) => {
  console.log(`Marking task ${taskId} as completed for ${day}`);
  if (categories.value[day]) {
    categories.value[day] = categories.value[day].filter(task => task.id !== taskId);
    localStorage.setItem(`tasks_${day}`, JSON.stringify(categories.value[day]));
    console.log( JSON.stringify(categories.value[day]))
    checkAttempt(taskId )
    console.log(`Updated tasks for ${day} saved to localStorage`);
  } else {
    console.error(`Tasks for ${day} are not defined`);
  }
};


export const checkAttempt = async (taskId) => {
  const attemptData = {
    attempt_date: new Date().toISOString().split('T')[0], // Current date in YYYY-MM-DD format
    status: 'completed' // Assuming the status is 'completed'
  };
console.log("came here:: ")
  try {
    const response = await axios.post(`http://localhost:8000/api/tasks/${taskId}/record-attempt`, attemptData);
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

    // Update local storage with the new task
    const storedTasks = JSON.parse(localStorage.getItem(`tasks_${day}`)) || [];
    storedTasks.push(response.data);
    localStorage.setItem(`tasks_${day}`, JSON.stringify(storedTasks));
    console.log(`New task added to localStorage for ${day}`);

    // Update the categories to include the new task
    if (categories.value[day]) {
      categories.value[day].push(response.data);
    } else {
      categories.value[day] = [response.data];
    }

    emit('task-created', { day, task: response.data });
    state.showSuccess = true;
  } catch (error) {
    if (error.response) {
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

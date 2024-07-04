import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue'
import store from '../store/index.js';
import Tasks from '../views/tasks/Tasks.vue'
import TaskView from '../views/tasks/TaskView.vue'


const routes = [
  { path: '/login', component: Login, meta: { guestOnly: true } }, 
  { path: '/register', component: Register, meta: { guestOnly: true } }, 
  { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/', component: Dashboard, meta: { requiresAuth: true } },
  {
    path: '/tasks',
    name: 'Tasks',
    component: Tasks
  },

  {
    path: '/task/:id',
    name: 'TaskView',
    component: TaskView,
    props: true,
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})


router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.getters.isAuthenticated) {
    // Redirect to login if route requires authentication and user is not authenticated
    next('/login');
  } else if (to.meta.guestOnly && store.getters.isAuthenticated) {
    // Redirect to dashboard if route is for guests only and user is authenticated
    next('/dashboard');
  } else {
    // Proceed to the next middleware or route
    next();
  }
});
export default router;
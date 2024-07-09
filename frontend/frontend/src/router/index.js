import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue';
import store from '../store/index.js';
import Tasks from '../views/tasks/Tasks.vue';
import TaskView from '../views/tasks/TaskView.vue';
import Profile from '../views/user/Profile.vue';
const routes = [
  { path: '/login', component: Login, meta: { guestOnly: true } }, 
  { path: '/register', component: Register, meta: { guestOnly: true } }, 
  { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true }, name: 'Dashboard' },
  { path: '/', component: Dashboard, meta: { requiresAuth: true }, name: 'Home' },
  { path: '/profile', component: Profile, meta: { requiresAuth: true }, name: 'Profile' },

  {
    path: '/tasks',
    name: 'Tasks',
    component: Tasks,
    meta: { requiresAuth: true }
  },
  {
    path: '/task/:id',
    name: 'TaskView',
    component: TaskView,
    props: true,
    meta: { requiresAuth: true }
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

router.beforeEach((to, from, next) => {
  try {
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
  } catch (error) {
    console.error('Error during route guard execution:', error);
    next(false); // Cancel the navigation
  }
});

export default router;

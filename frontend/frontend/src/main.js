import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import './style.css';
import MainH1 from './components/partials/MainH1.vue';
import RedIso from './components/partials/RedIso.vue';
// Create the Vue application instance
const app = createApp(App);

// Register the 'main-h1' component globally
app.component('MainH1', MainH1);
app.component('RedIso', RedIso);
// Use the router and mount the app to the DOM
app.use(router).mount('#app');

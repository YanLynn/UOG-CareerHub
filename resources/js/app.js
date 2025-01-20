import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from '@components/js/App.vue';
import router from '@components/js/router';
import '@components/css/app.css'
import PrimeVue from 'primevue/config';
import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';

const app = createApp(App);
const pinia = createPinia();
app.use(PrimeVue, { ripple: true });
app.use(router);
app.use(pinia);
app.mount('#app');

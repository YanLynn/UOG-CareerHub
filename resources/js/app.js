import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from '@components/js/App.vue';
import router from '@components/js/router';
import '@components/css/app.css'
import "primeicons/primeicons.css";
import "@components/css/flags.css";
import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice'
import DialogService from 'primevue/dialogservice'
import ToastService from 'primevue/toastservice';
import AppState from './composables/plugins/appState.js';
import Noir from './composables/plugins/presets/Noir.js';
import "quill/dist/quill.snow.css";
import "quill";
import { useAuthStore } from '@/store'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

const app = createApp(App);
const pinia = createPinia();
app.use(PrimeVue, {
    theme: {
        preset: Noir,
        options: {
            prefix: 'p',
            darkModeSelector: '.p-dark',
            cssLayer: false,
        }
    }
});

app.use(AppState);
app.use(ConfirmationService);
app.use(ToastService);
app.use(DialogService);
app.use(router);
app.use(pinia);
app.mount('#app');

const authStore = useAuthStore()

window.Pusher = Pusher
const user = JSON.parse(localStorage.getItem('user'))

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  forceTLS: true,
  authEndpoint: 'http://localhost:8000/broadcasting/auth',
  auth: {
    headers: {
      Authorization: `Bearer ${user?.token}`
    }
  }
})

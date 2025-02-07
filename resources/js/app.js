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

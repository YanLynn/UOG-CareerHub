import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from '../js/App.vue';
import router from '../js/router';

const app = createApp(App);
const pinia = createPinia();

app.use(router);
app.use(pinia);
app.mount('#app');

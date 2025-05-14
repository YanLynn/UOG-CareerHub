/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { useAuthStore } from '../js/store/index';
window.Pusher = Pusher;
const authStore = useAuthStore();
// Dynamically get JWT token from localStorage or Pinia/Vuex/etc.
console.log('token',authStore.token)

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
//     forceTLS: false,
//     enabledTransports: ['ws', 'wss'],
//     disableStats: true,
//     authorizer: (channel, options) => {
//         return {
//             authorize: (socketId, callback) => {
//                 axios.post('/api/broadcasting/auth', {
//                     socket_id: socketId,
//                     channel_name: channel.name
//                 })
//                 .then(response => {
//                     callback(false, response.data);
//                 })
//                 .catch(error => {
//                     callback(true, error);
//                 });
//             }
//         };
//     },
//     // ✅ Pass JWT token in Authorization header
//     authEndpoint: 'http://localhost:8000/broadcasting/auth',
//     auth: {
//         headers: {
//             Authorization: `Bearer ${authStore.token}`
//         }
//     }
// });


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    encrypted: false,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: 'http://localhost:8000/broadcasting/auth',
    auth: {
        headers: {
            Authorization: `Bearer ${authStore.token}` // ✅ Must include Bearer token
        },
    },
});

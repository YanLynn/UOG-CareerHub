import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/auth/Login.vue';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';

const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/login', name: 'Login', component: Login },
    // {
    //     path: '/dashboard',
    //     name: 'Dashboard',
    //     component: Dashboard,
    //     meta: { requiresAuth: true },
    // },
];

NProgress.configure({
    showSpinner: false, // Disable spinner
    trickleSpeed: 200,  // Speed of loading bar
});
const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    NProgress.start();
    const user = JSON.parse(localStorage.getItem('user'));
    const token = user?.token;

    if (to.meta.requiresAuth && !token) {
        next({ name: 'Login' });
    } else if (to.name === 'Login' && token) {
        next({ name: 'Dashboard' });
    } else {
        next();
    }
});
router.afterEach(() => {
    NProgress.done();
});

export default router;

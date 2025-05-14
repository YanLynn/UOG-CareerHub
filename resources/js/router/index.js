import { createRouter, createWebHistory } from 'vue-router';
import Home from '@components/js/views/Home.vue';
import Login from '@components/js/views/auth/Login.vue';
import Register from '@components/js/views/auth/Register.vue';
import User from '@components/js/views/admin/user/index.vue';
import Dashboard from '@components/js/views/admin/Dashboard.vue';
import MainLayout from '@components/js/views/layout/MainLayout.vue';
import AdminLayout from '@components/js/views/layout/AdminLayout.vue';
import Unauthorized from '@components/js/views/errors/Unauthorized.vue'; // Custom unauthorized page
import NotFound from '@components/js/views/errors/NotFound.vue'; // Custom 404 page
import JobSeekerProfile from '@components/js/views/JobSeeker/Profile.vue'
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';
import ProfileContent from '../views/JobSeeker/ProfileContent.vue';
import JobApplication from '../views/JobSeeker/JobApplication.vue';
import Settings from '../views/JobSeeker/Settings.vue';
import JobSearch from '../views/jobSearch/index.vue';
import EmployerProfile from '@components/js/views/Employer/profile.vue'
import EmployerProfileContent from '../views/Employer/ProfileContent.vue';
import ManageJobs from '../views/Employer/ManageJobs.vue';
import CreateJob from '../views/Employer/CreateJob.vue';
import EditJob from '../views/Employer/EditJob.vue';
import ViewJob from '../views/Employer/ViewJob.vue';
import JobApplicationsList from '../views/Employer/JobApplicationsList.vue';
import ChatRoom from '../views/chat-room/index.vue'
import EmployerSettings from '../views/Employer/Settings.vue';
import ViewDetail from '../views/admin/user/ViewDetail.vue';
// Define routes
const routes = [
    {
        path: '/',
        component: MainLayout,
        children: [
            { path: '', name: 'Home', component: Home },
            { path: 'login', name: 'Login', component: Login },
            { path: 'register', name: 'Register', component: Register },
            {
                path: '/job-search',
                name: 'jobSearch',
                component: JobSearch,
            },
        ],
    },

    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true, roles: ['Admin'] },
        children: [
            { path: 'dashboard', name: 'Dashboard', component: Dashboard },
            { path: 'user',
                name: 'User',
                component: User,
                meta: { requiresAuth: true, roles: ['Admin'] }
             },
             { path: 'viewDetail',
                name: 'ViewDetail',
                component: ViewDetail,
                meta: { requiresAuth: true, roles: ['Admin'] }
             },
        ],
    },
    {
        path: '/',
        component: MainLayout,
        children: [
            {
                path: '',
                name: '',
                component: JobSeekerProfile,
                meta: { requiresAuth: true, roles: ['JobSeeker'] },
                children: [{
                    path: '/jobSeeker/profile',
                    name: 'JobSeekerProfile',
                    component: ProfileContent,
                },
                {
                    path: '/jobSeeker/job-application',
                    name: 'JobApplication',
                    component: JobApplication,
                },
                {
                    path: '/jobSeeker/settings',
                    name: 'JobSeekerSettings',
                    component: Settings,
                },


                ]
            },
        ],
    },
    {
        path: '/',
        component: MainLayout,
        children: [
            {
                path: '',
                name: '',
                component: EmployerProfile,
                meta: { requiresAuth: true, roles: ['Employer'] },
                children: [
                    {
                        path: '/employer/profile',
                        name: 'EmployerProfile',
                        component: EmployerProfileContent,
                    },
                    {
                        path: '/employer/manage-jobs',
                        name: 'ManageJobs',
                        component: ManageJobs,
                    },
                    {
                        path: '/employer/create-job',
                        name: 'CreateJob',
                        component: CreateJob,
                    },
                    {
                        path: '/employer/manage-jobs/edit//:id',
                        name: 'EditJob',
                        component: EditJob,
                    },
                    {
                        path: '/employer/manage-jobs/view/:id',
                        name: 'ViewJob',
                        component: ViewJob,
                    },
                    {
                        path: '/employer/applications-list',
                        name: 'ApplicationsList',
                        component: JobApplicationsList,
                    },
                    {
                        path: '/employer/settings',
                        name: 'EmployerSettings',
                        component: EmployerSettings,
                    },
                ]
            }
        ]
    },
    {
        path: '/',
        component: MainLayout,
        children: [
            {
                path: '/chat-room',
                name: '',
                component: ChatRoom,
                meta: { requiresAuth: true, roles: ['JobSeeker','Employer'] },
            }
        ],
    },


    // Unauthorized Access Page
    {
        path: '/unauthorized',
        name: 'Unauthorized',
        component: Unauthorized,
    },
    // 404 Not Found Page
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound,
    },
];

// Configure NProgress
NProgress.configure({ showSpinner: false, trickleSpeed: 200 });

// Create Router
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation Guards
router.beforeEach((to, from, next) => {
    NProgress.start();

    const user = JSON.parse(localStorage.getItem('user'));
    const token = user?.token;
    const userRole = user?.role;

    // Check if route requires authentication
    if (to.meta.requiresAuth) {
        if (!token) {
            return next({ name: 'Login' });
        }

        // Check if user role is authorized
        if (to.meta.roles && !to.meta.roles.includes(userRole)) {
            return next({ name: 'Unauthorized' });
        }
    }

    // Prevent logged-in users from accessing the login/register page
    if ((to.name === 'Login' || to.name === 'Register') && token) {
        if (userRole === 'Admin') {
            return next({ name: 'Dashboard' });
        } else {
            return next({ name: 'Home' });
        }
    }

    next();
});

// Complete NProgress after navigation
router.afterEach(() => {
    NProgress.done();
});

export default router;

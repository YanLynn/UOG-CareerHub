<template>
    <div
        class="min-h-screen  bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100
           relative bg-cover bg-center
           bg-[url('https://readymadeui.com/bg-effect.svg')]
           dark:bg-gradient-to-b dark:from-gray-900 dark:via-gray-800 dark:to-gray-900
           transition-all duration-500 ease-in-out">

        <!-- Mobile Sidebar Toggle Button -->
        <button @click="toggleSidebar"
            class="md:hidden fixed top-4 left-4 z-50 bg-blue-600 text-white p-2 rounded-md focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>

        <SideBarVue />
        <!-- Main Content -->
        <main :class="['transition-all duration-300', isSidebarOpen ? 'ml-64' : 'ml-0', 'md:ml-64', 'p-4']">
            <router-view />
        </main>
        <Toast/>
    </div>
</template>

<script setup>

import SideBarVue from '@components/js/components/SideBar.vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Toast from '@components/js/components/Toast.vue'
const isSidebarOpen = ref(false);
const router = useRouter();

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const logout = () => {
    localStorage.removeItem('user');
    router.push('/login');
};
</script>

<style>
/* For smooth sidebar transition */
@media (min-width: 768px) {
    nav {
        transform: translateX(0) !important;
    }
}
</style>

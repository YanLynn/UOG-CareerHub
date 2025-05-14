<template>
    <div
        class="flex min-h-screen bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-all duration-500 ease-in-out">

        <!-- Sidebar -->
        <aside
            class="w-64 fixed top-0 left-0 h-full z-40 transition-transform duration-300 bg-gradient-to-b from-blue-100 via-white to-blue-50 shadow-lg md:translate-x-0"
            :class="{ '-translate-x-full': !isSidebarOpen, 'translate-x-0': isSidebarOpen }">
            <SideBarVue />
        </aside>

        <!-- Content -->
        <div class="flex-1 ml-0 md:ml-64 p-4 transition-all duration-300 w-full">
            <!-- Mobile Toggle Button -->
            <button @click="toggleSidebar"
                class="md:hidden fixed top-4 left-4 z-50 bg-blue-600 text-white p-2 rounded-md">
                <i class="pi pi-bars"></i>
            </button>

            <router-view />
        </div>

        <Toast />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import SideBarVue from '@components/js/components/SideBar.vue';
import Toast from '@components/js/components/Toast.vue';

const isSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

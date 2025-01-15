<template>
    <div>
        <nav class="bg-white dark:bg-gray-800 shadow p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold">Oversea Job</h1>
                <div class="flex space-x-8">
                    <!-- Left Navigation -->
                    <ul class="flex space-x-4 items-center">
                        <li>
                            <router-link to="/" class="hover:underline">Home</router-link>
                        </li>
                        <li>
                            <router-link to="/" class="hover:underline">Jobs</router-link>
                        </li>
                        <li>
                            <router-link to="/" class="hover:underline">Companies</router-link>
                        </li>
                        <li v-if="!isLoggedIn">
                            <router-link to="/login" class="hover:underline">Login</router-link>
                        </li>
                        <li v-if="!isLoggedIn">
                            <router-link to="/" class="hover:underline">Register</router-link>
                        </li>
                        <li v-if="isLoggedIn">
                            <button @click="logout" class="hover:underline">Logout</button>
                        </li>
                        <li v-if="isLoggedIn">
                            <button class="hover:underline">{{authStore.currentUser.name}}</button>
                        </li>
                        <li>
                            <button @click="toggleDarkMode"
                                class="px-3 py-2 bg-white-800 text-black dark:text-white rounded-md flex items-center justify-center">
                                <svg v-if="!isDarkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m14.14 6.14l-.7-.7M6.56 6.56l-.7-.7m12.02 0l-.7.7M6.56 17.44l-.7.7M12 5a7 7 0 100 14 7 7 0 000-14z" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M17.293 14.707A8 8 0 019.293 6.707a8.002 8.002 0 108 8z" />
                                </svg>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store';

const router = useRouter();
const authStore = useAuthStore();


const isLoggedIn = computed(() => authStore.isLoggedIn);

const logout = async () => {
    try {
        await authStore.logout();
        console.log('Logout successful');
        router.push({ name: 'Home' });
    } catch (error) {
        console.error('Logout failed:', error.message);
        alert('Logout failed');
    }
};



const isDarkMode = ref(false);

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    document.documentElement.classList.toggle('dark', isDarkMode.value);
    localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light');
};

onMounted(() => {
    isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches || localStorage.getItem('theme') === 'dark';
    document.documentElement.classList.toggle('dark', isDarkMode.value);
});
</script>

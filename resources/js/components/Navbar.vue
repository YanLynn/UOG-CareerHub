<template>
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">
                <router-link to="/" class="hover:underline">My App</router-link>
            </h1>
            <ul class="flex space-x-4">
                <li>
                    <router-link to="/" class="hover:underline">Home</router-link>
                </li>
                <li v-if="!isLoggedIn">
                    <router-link to="/login" class="hover:underline">Login</router-link>
                </li>
                <li v-if="isLoggedIn">
                    <router-link to="/dashboard" class="hover:underline">Dashboard</router-link>
                </li>
                <li v-if="isLoggedIn">
                    <button @click="logout" class="hover:underline">Logout</button>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store';

const router = useRouter();
const authStore = useAuthStore();


const isLoggedIn = computed(() => authStore.isLoggedIn);

const logout = async () => {
    try {
        await authStore.logout();
        console.log('Logout successful');
        router.push({ name: 'Login' });
    } catch (error) {
        console.error('Logout failed:', error.message);
        alert('Logout failed');
    }
};
</script>

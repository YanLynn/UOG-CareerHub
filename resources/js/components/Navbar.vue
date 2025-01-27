<template>
    <div>
        <Menubar :model="items" class="flex justify-between">
            <!-- Start Slot -->
            <template #start>
                <router-link to="/">
                    <h1
                        class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-semibold text-blue-600 dark:text-blue-400">
                        Career<span class="text-gray-900 dark:text-white">Hub</span>
                    </h1>
                </router-link>
            </template>

            <!-- Center Slot -->
            <template #center>
                <div class="flex justify-center space-x-6">
                    <template v-for="(item, index) in items" :key="index">
                        <a
                            class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400"
                            v-bind="item.action">
                            <span>{{ item.label }}</span>
                        </a>
                    </template>
                </div>
            </template>

            <!-- End Slot -->
            <template #end>
                <div class="flex items-center space-x-4">
                    <template v-if="isLoggedIn">
                        <a href="#" class="text-sm font-medium hover:underline" @click.prevent="logout">Logout</a>
                    </template>
                    <template v-else>
                        <router-link
                            to="/login"
                            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                            Login
                        </router-link>
                        <router-link
                            to="/register"
                            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                            Register
                        </router-link>
                    </template>

                    <!-- Dark Mode Toggle -->
                    <button
                        type="button"
                        class="inline-flex w-8 h-8 items-center justify-center surface-0 dark:surface-800 border border-surface-200 dark:border-surface-600 rounded"
                        @click="toggleDarkMode"
                        :aria-label="darkMode ? 'Activate light mode' : 'Activate dark mode'">
                        <i :class="`pi ${darkMode ? 'pi-sun' : 'pi-moon'} dark:text-white`"></i>
                    </button>
                </div>
            </template>
        </Menubar>
    </div>
</template>

<script setup>
import Menubar from 'primevue/menubar';
import Badge from 'primevue/badge';
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store';
import { storeToRefs } from 'pinia';

// Router and Auth Store
const router = useRouter();
const authStore = useAuthStore();
const { isLoggedIn, isAdmin, isEmployer, isJobseeker } = storeToRefs(authStore);

// Logout Functionality
const logout = async () => {
    await authStore.logout();
    router.push('/');
};

// Menu Items
const items = ref([
    {
        label: 'Home',
        icon: 'pi pi-home',
        command: () => router.push('/'),
    },
    {
        label: 'Projects',
        icon: 'pi pi-search',
        badge: 3,
        items: [
            { label: 'Core', icon: 'pi pi-bolt' },
            { label: 'Blocks', icon: 'pi pi-server' },
            { separator: true },
            { label: 'UI Kit', icon: 'pi pi-pencil' },
        ],
    },
]);

// Dark Mode State
const darkMode = ref(false);

// Toggle Dark Mode
const toggleDarkMode = () => {
    const root = document.documentElement;
    darkMode.value = !darkMode.value;
    if (darkMode.value) {
        root.classList.add('p-dark');
    } else {
        root.classList.remove('p-dark');
    }
};
</script>

<style scoped>
.menubar-center {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>

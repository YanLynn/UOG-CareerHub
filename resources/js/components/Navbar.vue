<template>
    <div>
        <Menubar :model="items" class="w-full flex justify-between items-center">
            <!-- Start Slot (Left-Aligned Logo) -->
            <template #start>
                <router-link to="/" class="flex items-center">
                    <h1 class="text-xl font-semibold text-blue-600 dark:text-blue-400">
                        Career<span class="text-gray-900 dark:text-white">Hub</span>
                    </h1>
                </router-link>
            </template>

            <!-- Center Slot (Move Menu Items to Right) -->
            <template #end>
                <div class="flex items-center justify-end w-full space-x-6">
                    <template v-for="(item, index) in items" :key="index">
                        <router-link v-if="item.route" :to="item.route"
                            class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">
                            {{ item.label }}
                        </router-link>
                        <a v-else-if="item.url" :href="item.url" target="_blank"
                            class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">
                            {{ item.label }}
                        </a>
                    </template>
                    <Menu as="div" class="relative inline-block text-left" v-if="isLoggedIn">
                        <div>
                            <MenuButton
                                class="uppercase inline-flex w-full justify-center gap-x-1.5  bg-white px-3 py-2 text-sm text-gray-900 ring-0  ring-gray-300 ring-inset  dark:bg-black/10 dark:text-white">
                                {{currentUser.name}}
                                <ChevronDownIcon class="-mr-1 size-5 text-gray-400" aria-hidden="true" />
                            </MenuButton>
                        </div>

                        <transition enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95" >
                            <MenuItems
                                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-black dark:text-white ring-1 shadow-lg ring-black/5 focus:outline-hidden ">
                                <div class="py-1">
                                    <MenuItem v-slot="{ active }" v-if="isAdmin">
                                    <router-link to="/dashboard"
                                        :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700 dark:text-gray-300', 'block px-4 py-2 text-sm']">
                                        Dashboard</router-link>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }" v-if="!isEmployer">
                                        <router-link :to="{ name:'JobSeekerProfile' }"
                                        :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700 dark:text-gray-300', 'block px-4 py-2 text-sm']">Profile</router-link>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }" v-if="isEmployer">
                                        <router-link :to="{name:'EmployerProfile'}"
                                        :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700 dark:text-gray-300', 'block px-4 py-2 text-sm']">Profile</router-link>
                                    </MenuItem>

                                    <form method="POST" action="#">
                                        <MenuItem v-slot="{ active }">
                                        <button type="submit" @click.prevent="logout"
                                            :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700 dark:text-gray-300', 'block w-full px-4 py-2 text-left text-sm']">Sign
                                            out</button>
                                        </MenuItem>
                                    </form>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>

                    <template v-else>
                        <router-link to="/login"
                            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">Login</router-link>
                        <router-link to="/register"
                            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">Register</router-link>
                    </template>

                    <!-- Dark Mode Toggle -->
                    <button type="button"
                        class="inline-flex w-8 h-8 items-center justify-center border border-gray-300 dark:border-gray-600 rounded"
                        @click="toggleDarkMode" :aria-label="darkMode ? 'Activate light mode' : 'Activate dark mode'">
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
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
// Router and Auth Store
const router = useRouter();
const authStore = useAuthStore();
const { isLoggedIn, currentUser, isAdmin,isEmployer } = storeToRefs(authStore);

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
        label: 'Job Search',
        icon: 'pi pi-search',
        command: () => router.push('job-search')


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
.p-menubar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.p-menubar-end {
    flex: 1;
    display: flex;
    justify-content: flex-end;
    align-items: center;
}
</style>

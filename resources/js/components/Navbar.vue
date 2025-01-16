<template>
    <Disclosure as="nav" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-100'" v-slot="{ open }">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <!-- Mobile Menu Button -->
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <DisclosureButton
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="absolute -inset-0.5" />
                        <span class="sr-only">Open main menu</span>
                        <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
                        <XMarkIcon v-else class="block size-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>

                <!-- Logo and Navigation -->
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex shrink-0 items-center">
                        <img class="h-8 w-auto"
                            src="../../../public/src/images/logo.png"
                            alt="Oversea Job Portal" />
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <ul class="flex space-x-6">
                            <!-- Dynamic Navigation Items -->
                            <li v-for="item in navigation" :key="item.name">
                                <a :href="item.href" :class="[
                                    item.current
                                        ? 'text-blue-600 dark:text-blue-400 font-semibold'
                                        : 'text-gray-700 dark:text-gray-300',
                                    'hover:underline'
                                ]">
                                    {{ item.name }}
                                </a>
                            </li>

                            <!-- User-Specific Navigation -->

                            <li v-if="isLoggedIn">
                                <button class="hover:underline dark:text-white">{{ authStore.currentUser.name
                                    }}</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Right Icons (Notifications, Dark Mode, Profile) -->
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">

                    <div v-if="!isLoggedIn">
                        <router-link to="/login" class="hover:underline">Login </router-link>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode"
                        class="ml-3 p-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">
                        <svg v-if="!isDarkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m14.14 6.14l-.7-.7M6.56 6.56l-.7-.7m12.02 0l-.7.7M6.56 17.44l-.7.7M12 5a7 7 0 100 14 7 7 0 000-14z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.293 14.707A8 8 0 019.293 6.707a8.002 8.002 0 108 8z" />
                        </svg>
                    </button>

                    <!-- Profile Dropdown -->
                    <Menu as="div" class="relative ml-3">
                        <MenuButton class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2">
                            <img class="h-8 w-8 rounded-full"
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="User profile" />
                        </MenuButton>

                        <transition enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95">
                            <MenuItems
                                class="absolute right-0 mt-2 w-48 rounded-md bg-white dark:bg-gray-700 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <MenuItem>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    Your Profile
                                </a>
                                </MenuItem>
                                <MenuItem>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    Settings
                                </a>
                                </MenuItem>
                                <MenuItem v-if="isLoggedIn">
                                <a href="#" @click="logout"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    Sign out
                                </a>
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
        </div>
    </Disclosure>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store';
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const navigation = ref([
    { name: 'Home', href: '/', current: true },
    { name: 'Team', href: '/', current: false },
    { name: 'Projects', href: '/', current: false },
    { name: 'Calendar', href: '/', current: false },
]);
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

<template>
    <Disclosure as="nav" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-100'" v-slot="{ open }">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center px-4 py-2">
    <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-semibold text-blue-600 dark:text-blue-400">
        Oversea<span class="text-gray-900 dark:text-white">Jobs</span>
    </h1>
</div>
                <!-- Desktop Navigation -->
                <div class="hidden sm:flex sm:items-center sm:space-x-6">
                    <ul class="flex space-x-6">
                        <li v-for="item in navigation" :key="item.name">
                            <a :href="item.href" :class="[
                                item.current ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300',
                                'hover:underline'
                            ]">
                                {{ item.name }}
                            </a>
                        </li>

                    </ul>
                </div>

                <!-- Mobile Menu Button -->
                <div class="sm:hidden flex items-center">
                    <DisclosureButton
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <Bars3Icon v-if="!open" class="h-6 w-6" aria-hidden="true" />
                        <XMarkIcon v-else class="h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>

                <!-- Right Icons -->
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    <button @click="toggleDarkMode" class="p-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <svg v-if="!isDarkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m14.14 6.14l-.7-.7M6.56 6.56l-.7-.7m12.02 0l-.7.7M6.56 17.44l-.7.7M12 5a7 7 0 100 14 7 7 0 000-14z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.293 14.707A8 8 0 019.293 6.707a8.002 8.002 0 108 8z" />
                        </svg>
                    </button>

                    <Menu as="div" class="relative">
                        <MenuButton class="flex rounded-full bg-gray-800 text-sm">
                            <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e" alt="User profile" />
                        </MenuButton>
                        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                            <MenuItems class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 shadow-lg rounded-md py-1">
                                <MenuItem>
                                    <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">Your Profile</a>
                                </MenuItem>
                                <MenuItem>
                                    <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">Settings</a>
                                </MenuItem>
                                <MenuItem v-if="isLoggedIn">
                                    <a href="#" @click="logout" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">Sign out</a>
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>
                    <div class="hidden sm:flex sm:items-right sm:space-x-6">
                    <ul class="flex space-x-6">
                        <li v-if="isLoggedIn">
                            <span class="hover:underline dark:text-white">{{ authStore.currentUser.name }}</span>
                        </li>
                        <li v-if="!isLoggedIn">
                            <router-link to="/login" class="hover:underline">Login</router-link>
                        </li>
                        <li v-if="!isLoggedIn">
                            <router-link to="/register" class="hover:underline">Register</router-link>
                        </li>

                    </ul>
                </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <DisclosurePanel class="sm:hidden">
                <ul class="space-y-1 px-2 pt-2 pb-3">
                    <li v-for="item in navigation" :key="item.name">
                        <a :href="item.href" class="block px-3 py-2 rounded-md text-base font-medium" :class="[
                            item.current ? 'text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300',
                            'hover:bg-gray-200 dark:hover:bg-gray-600'
                        ]">
                            {{ item.name }}
                        </a>
                    </li>
                </ul>
            </DisclosurePanel>
        </div>
    </Disclosure>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store';
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';

const navigation = ref([
    { name: 'Home', href: '/', current: true },
    { name: 'Team', href: '/team', current: false },
    { name: 'Projects', href: '/projects', current: false },
]);

const router = useRouter();
const authStore = useAuthStore();
const isLoggedIn = computed(() => authStore.isLoggedIn);

const logout = async () => {
    await authStore.logout();
    router.push('/');
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

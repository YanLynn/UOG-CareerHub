<template>
    <div class="flex items-center justify-center md:h-screen p-6 bg-gradient-to-b from-gray-100 via-gray-200 to-gray-300 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="shadow-xl max-w-6xl max-md:max-w-lg rounded-lg p-6 bg-white dark:bg-gray-800">
            <div class="grid md:grid-cols-2 items-center gap-8">
                <!-- Image Section -->
                <div class="max-md:order-2">
                    <img src="@images/signin-image.webp" class="w-full object-contain" alt="login-image" />
                </div>

                <!-- Form Section -->
                <form class="md:max-w-md w-full mx-auto" @submit.prevent="handleLogin">
                    <div class="mb-8">
                        <h3 class="text-3xl font-bold text-blue-600 dark:text-gray-100">Sign in</h3>

                    </div>

                    <!-- Email Input -->
                    <div class="relative mb-6">
                        <label for="email" class="block mb-2 text-xs text-gray-700 dark:text-gray-300">Email</label>
                        <input
                            id="email"
                            v-model="email"
                            type="email"
                            placeholder="Enter email"
                            required
                            class="signin_input"
                        />
                        <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                    </div>

                    <!-- Password Input -->
                    <div class="relative mb-6">
                        <label for="password" class="block mb-2 text-xs text-gray-700 dark:text-gray-300">Password</label>
                        <input
                            id="password"
                            v-model="password"
                            type="password"
                            placeholder="Enter password"
                            required
                            class="signin_input"
                        />
                        <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
                        <p v-if="errors.general" class="text-red-500 text-xs mt-1">{{ errors.general }}</p>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input
                                id="remember-me"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                            />
                            <label for="remember-me" class="ml-2 text-xs text-gray-700 dark:text-gray-300">Remember me</label>
                        </div>
                        <a href="#" class="text-xs text-blue-600 hover:underline dark:text-blue-400">Forgot Password?</a>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-6">
                        <button
                            type="submit"
                            class="w-full py-3 px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        >
                            Sign in
                        </button>
                    </div>

                    <!-- Register Link -->
                    <p class="text-xs text-gray-600 dark:text-gray-300 text-center">
                        Don't have an account?
                        <router-link
                            to="/register"
                            class="text-blue-600 hover:underline dark:text-blue-400 font-medium"
                        >
                            Register here
                        </router-link>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@components/js/store';
import { useToastStore } from '@components/js/store/toastStore';
import { storeToRefs } from 'pinia';

const email = ref('');
const password = ref('');
const errors = ref({});
const router = useRouter();
const authStore = useAuthStore();
const toast = useToastStore();
const { isAdmin, isEmployer, isJobseeker } = storeToRefs(authStore);

const handleLogin = async () => {
    errors.value = {};

    try {
        await authStore.login({ email: email.value, password: password.value });
        toast.showToast('Login successful! Welcome back 👋', 'success');
        if (authStore.isAdmin) {
            router.push({ name: 'Dashboard' });
        } else {
            router.push({ name: 'Home' });
        }
    } catch (error) {
        console.error('Login failed:', error);

        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else if (error.response && error.response.status === 401) {
            errors.value.general = 'Invalid email or password.';
        } else {
            errors.value.general = 'An unexpected error occurred.';
        }
    }
};
</script>

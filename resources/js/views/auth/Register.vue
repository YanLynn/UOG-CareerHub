<template>
    <div class="flex items-center justify-center min-h-screen p-6  dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="shadow-xl max-w-6xl w-full rounded-lg flex flex-col md:flex-row bg-white dark:bg-gray-800">
            <!-- Left Side Image -->
            <div class="md:w-1/2 flex items-center justify-center p-6 rounded-l-lg bg-gray-100 dark:bg-gray-700">
                <img
                    src="@images/signin-image.webp"
                    alt="Register Image"
                    class="w-full max-w-sm object-contain"
                />
            </div>

            <!-- Right Side Form -->
            <div class="md:w-1/2 w-full p-8">
                <h2 class="text-3xl font-bold mb-6 text-center text-blue-600 dark:text-gray-100">Create an Account</h2>

                <form @submit.prevent="register">
                    <!-- Name Field -->
                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-xs text-gray-700 dark:text-gray-300">User Name</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Enter your name"
                            required
                            class="signin_input"
                        />

                    </div>

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label for="email" class="block mb-2 text-xs text-gray-700 dark:text-gray-300">Email Address</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="Enter your email"
                            required
                            class="signin_input"
                        />

                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="block mb-2 text-xs text-gray-700 dark:text-gray-300">Password</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            placeholder="Create a password"
                            required
                            class="signin_input"
                        />

                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block mb-2 text-xs text-gray-700 dark:text-gray-300">
                            Confirm Password
                        </label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="Confirm your password"
                            required
                            class="signin_input"
                        />

                        <p v-if="errors.general" class="text-red-500 text-xs mt-1">{{ errors.general }}</p>
                    </div>

                    <!-- User Type Selection -->
                    <div class="mb-6 flex justify-between gap-4">
                        <button
                            @click.prevent="userType = 'Jobseeker'"
                            :disabled="userType === 'Jobseeker'"
                            :class="userType === 'Jobseeker' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                            class="w-full py-2 rounded-lg font-medium transition-all"
                        >
                            Job Seeker
                        </button>
                        <button
                            @click.prevent="userType = 'Employer'"
                            :disabled="userType === 'Employer'"
                            :class="userType === 'Employer' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                            class="w-full py-2 rounded-lg font-medium transition-all"
                        >
                            Employer
                        </button>
                    </div>

                    <!-- Employer Fields -->
                    <div v-if="userType === 'Employer'">
                        <div class="mb-4">
                            <label for="companyName" class="block mb-2 text-xs text-gray-700 dark:text-gray-300">
                                Company Name
                            </label>
                            <input
                                id="companyName"
                                v-model="form.companyName"
                                type="text"
                                placeholder="Company Name"
                                class="signin_input"
                            />

                        </div>
                        <div class="mb-4">
                            <label for="companyWebsite" class="block mb-2 text-xs text-gray-700 dark:text-gray-300">
                                Company Website
                            </label>
                            <input
                                id="companyWebsite"
                                v-model="form.companyWebsite"
                                type="url"
                                placeholder="Company Website"
                                class="signin_input"
                            />

                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full py-3 px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                    >
                        Register
                    </button>

                    <p class="text-xs text-center mt-4 text-gray-700 dark:text-gray-300">
                        Already have an account?
                        <router-link
                            to="/login"
                            class="text-blue-400 hover:underline font-medium"
                        >
                            Sign in here
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
import { useToastStore } from '@components/js/store/toastStore';
import { useAuthStore } from '@components/js/store';

const router = useRouter();
const toast = useToastStore();
const authStore = useAuthStore();

const userType = ref('Jobseeker');
const errors = ref({});
const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    companyName: '',
    companyWebsite: '',
});

const register = async () => {
    errors.value = {};

    if (form.value.password !== form.value.password_confirmation) {
        errors.value.general = 'Passwords do not match!';
        return;
    }

    try {
        await authStore.register({
            name: form.value.name,
            email: form.value.email,
            password: form.value.password,
            password_confirmation: form.value.password_confirmation,
            userType: userType.value,
            companyName: userType.value === 'Employer' ? form.value.companyName : null,
            companyWebsite: userType.value === 'Employer' ? form.value.companyWebsite : null,
        });

        toast.showToast('Registration successful! Welcome aboard 👋', 'success');
        router.push('/');
    } catch (err) {
        console.error('Registration error:', err);
        errors.value.general = err.response?.data?.message || 'An error occurred. Please try again.';
    }
};
</script>

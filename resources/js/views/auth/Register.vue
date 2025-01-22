<template>
    <div class="flex items-center justify-center min-h-screen p-4 ">
        <div
            class="shadow-lg max-w-6xl w-full rounded-md flex flex-col md:flex-row dark:bg-gray-800 bg-gray-100">
            <!-- Left Side Image -->
            <div class="md:w-1/2 flex items-center justify-center rounded-l-md p-6">
                <img src="@images/signin-image.webp" alt="Register Image"
                    class="w-full aspect-[12/11] object-contain" />
            </div>

            <!-- Right Side Form -->
            <div class="md:w-1/2 w-full p-8">
                <h2 class="text-4xl font-bold mb-6 text-center text-blue-600 dark:text-gray-100">Create an Account</h2>

                <form @submit.prevent="register">
                    <div class="mb-4">
                        <input v-model="form.name" type="text" placeholder="User Name" required
                            class="signin_input" />

                    </div>
                    <div class="mb-4">
                        <input v-model="form.email" type="email" placeholder="Email Address" required
                            class="signin_input" />
                    </div>

                    <div class="mb-4">
                        <input v-model="form.password" type="password" placeholder="Password" required
                            class="signin_input" />
                    </div>

                    <div class="mb-4">
                        <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" required
                            class="signin_input" />
                            <p v-if="errors" class="text-red-500 text-xs mt-1">{{ errors.general }}</p>
                    </div>

                    <!-- User Type Selection -->
                    <div class="mb-6 flex justify-between gap-4">
                        <button @click.prevent="userType = 'Jobseeker'"
                            :class="userType === 'Jobseeker' ? 'active-btn' : 'inactive-btn'" class="primary-btn">Job
                            Seeker</button>
                        <button @click.prevent="userType = 'Employer'"
                            :class="userType === 'Employer' ? 'active-btn' : 'inactive-btn'"
                            class="primary-btn">Employer</button>
                    </div>

                    <!-- Employer Fields -->
                    <div v-if="userType === 'Employer'">
                        <div class="mb-4">
                            <input v-model="form.companyName" type="text" placeholder="Company Name"
                                class="signin_input" />
                        </div>
                        <div class="mb-4">
                            <input v-model="form.companyWebsite" type="url" placeholder="Company Website"
                                class="signin_input" />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="primary-btn">Register</button>

                    <p class="text-sm text-center mt-4">Already have an account? <router-link to="/login"
                            class="text-blue-400 hover:underline">Sign in here</router-link></p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useToastStore } from '@components/js/store/toastStore';
import { useAuthStore } from '@components/js/store';
import { useRouter } from 'vue-router';
import { error } from 'laravel-mix/src/Log';

const router = useRouter();
const toast = useToastStore();
const authStore = useAuthStore();

const isDarkMode = ref(false);
const userType = ref('Jobseeker');
const errors = ref({});
const form = ref({
    name:'',
    email: '',
    password: '',
    password_confirmation: '',
    companyName: '',
    companyWebsite: '',
});

const register = async () => {
    errors.value = {};
    // Validation: Password match
    if (form.value.password !== form.value.password_confirmation) {
        errors.value.general ='Passwords do not match!';
        return;
    }

    try {
        // Attempt user registration
        await authStore.register({
            name:form.value.name,
            email: form.value.email,
            password: form.value.password,
            password_confirmation: form.value.password_confirmation,
            userType: userType.value,
            companyName: userType.value === 'Employer' ? form.value.companyName : null,
            companyWebsite: userType.value === 'Employer' ? form.value.companyWebsite : null,
        });

        // Show success toast and redirect
        toast.showToast('Registration successful! Welcome aboard 👋', 'success');
        router.push('/'); // Redirect to home or dashboard
    } catch (err) {
        console.error('Registration error:', err);
        errors.value.general = err.response?.data?.message;
        //toast.showToast(err.response?.data?.message || 'Registration failed. Please try again.', 'error');
    }
};
</script>


<style scoped>

@media (max-width: 768px) {
    .flex {
        flex-direction: column;
    }

    .md\:w-1\/2 {
        width: 100%;
    }
}
</style>

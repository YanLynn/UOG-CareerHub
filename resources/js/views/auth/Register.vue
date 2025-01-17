<template>
    <div class="font-[sans-serif] flex items-center justify-center min-h-screen p-4 ">
        <div
            class="shadow-lg max-w-6xl w-full rounded-md flex flex-col md:flex-row dark:bg-gray-800 bg-gray-100">
            <!-- Left Side Image -->
            <div class="md:w-1/2 flex items-center justify-center rounded-l-md p-6">
                <img src="https://readymadeui.com/signin-image.webp" alt="Register Image"
                    class="w-full aspect-[12/11] object-contain" />
            </div>

            <!-- Right Side Form -->
            <div class="md:w-1/2 w-full p-8">
                <h2 class="text-4xl font-bold mb-6 text-center text-blue-600 dark:text-gray-100">Create an Account</h2>

                <form @submit.prevent="register">
                    <div class="mb-4">
                        <input v-model="form.name" type="text" placeholder="Full Name" required class="signin_input" />
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
                        <input v-model="form.confirmPassword" type="password" placeholder="Confirm Password" required
                            class="signin_input" />
                    </div>

                    <!-- User Type Selection -->
                    <div class="mb-6 flex justify-between gap-4">
                        <button @click.prevent="userType = 'jobseeker'"
                            :class="userType === 'jobseeker' ? 'active-btn' : 'inactive-btn'" class="primary-btn">Job
                            Seeker</button>
                        <button @click.prevent="userType = 'employer'"
                            :class="userType === 'employer' ? 'active-btn' : 'inactive-btn'"
                            class="primary-btn">Employer</button>
                    </div>

                    <!-- Employer Fields -->
                    <div v-if="userType === 'employer'">
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

const isDarkMode = ref(false);
const userType = ref('jobseeker');
const form = ref({
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
    companyName: '',
    companyWebsite: '',
});

const register = () => {
    if (form.value.password !== form.value.confirmPassword) {
        alert('Passwords do not match');
        return;
    }
    const payload = {
        userType: userType.value,
        ...form.value,
    };
    console.log('Registering user:', payload);
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

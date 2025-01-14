<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store';

const email = ref('');
const password = ref('');
const router = useRouter();
const authStore = useAuthStore();

const handleLogin = async () => {
  try {
    await authStore.login({ email: email.value, password: password.value });
    console.log('Navigating to dashboard...');
    router.push({ name: 'Dashboard' });
  } catch (error) {
    console.error('Login failed:', error);
    alert('Login failed');
  }
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Welcome Back 👋</h2>
        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- Email Input -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
              v-model="email"
              type="email"
              id="email"
              placeholder="Enter your email"
              class="w-full px-4 py-2 mt-1 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 focus:outline-none focus:ring-2"
              required
            />
          </div>

          <!-- Password Input -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
              v-model="password"
              type="password"
              id="password"
              placeholder="Enter your password"
              class="w-full px-4 py-2 mt-1 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 focus:outline-none focus:ring-2"
              required
            />
          </div>

          <!-- Login Button -->
          <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300 ease-in-out shadow-md"
          >
            Login
          </button>

          <!-- Divider -->
          <div class="flex items-center justify-between">
            <hr class="w-full border-gray-300" />
            <span class="px-2 text-gray-500 text-sm">or</span>
            <hr class="w-full border-gray-300" />
          </div>

          <!-- Social Login -->
          <button
            type="button"
            class="w-full flex items-center justify-center border border-gray-300 rounded-md py-2 hover:bg-gray-100 transition duration-300"
          >
            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
              <path
                fill="#EA4335"
                d="M12 2a10 10 0 1 0 10 10 10 10 0 0 0-10-10zm1.7 15h-2.8v-6h2.8v6zm-1.4-6.7a1.4 1.4 0 1 1 0-2.8 1.4 1.4 0 0 1 0 2.8z"
              />
            </svg>
            Continue with Google
          </button>

          <!-- Forgot Password -->
          <p class="text-center text-sm text-gray-600">
            Forgot your password?
            <a href="#" class="text-blue-600 hover:underline">Reset it</a>
          </p>

          <!-- Sign Up Link -->
          <p class="text-center text-sm text-gray-600">
            Don't have an account?
            <a href="#" class="text-blue-600 hover:underline">Sign up</a>
          </p>
        </form>
      </div>
    </div>
  </template>

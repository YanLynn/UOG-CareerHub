<template>
    <div class="flex items-center justify-center  md:h-screen p-4">
      <div class="shadow-lg max-w-6xl max-md:max-w-lg rounded-md p-6 dark:bg-gray-700 bg-gray-100">

        <div class="grid md:grid-cols-2 items-center gap-8">
          <div class="max-md:order-1">
            <img src="@images/signin-image.webp" class="w-full aspect-[12/11] object-contain" alt="login-image" />
          </div>

          <form class="md:max-w-md w-full mx-auto"  @submit.prevent="handleLogin">
            <div class="mb-12">
              <h3 class="text-4xl font-bold text-blue-600 dark:text-gray-100">Sign in</h3>
            </div>

            <div>
              <div class="relative flex items-center">
                <input name="email" v-model="email" type="text" required class="signin_input" placeholder="Enter email" />
                <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-[18px] h-[18px] absolute right-2" viewBox="0 0 682.667 682.667">
                  <defs>
                    <clipPath id="a" clipPathUnits="userSpaceOnUse">
                      <path d="M0 512h512V0H0Z" data-original="#000000"></path>
                    </clipPath>
                  </defs>
                  <g clip-path="url(#a)" transform="matrix(1.33 0 0 -1.33 0 682.667)">
                    <path fill="none" stroke-miterlimit="10" stroke-width="40" d="M452 444H60c-22.091 0-40-17.909-40-40v-39.446l212.127-157.782c14.17-10.54 33.576-10.54 47.746 0L492 364.554V404c0 22.091-17.909 40-40 40Z" data-original="#000000"></path>
                    <path d="M472 274.9V107.999c0-11.027-8.972-20-20-20H60c-11.028 0-20 8.973-20 20V274.9L0 304.652V107.999c0-33.084 26.916-60 60-60h392c33.084 0 60 26.916 60 60v196.653Z" data-original="#000000"></path>
                  </g>
                </svg>
                <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
              </div>
            </div>

            <div class="mt-8">
              <div class="relative flex items-center">
                <input name="password" v-model="password" type="password" required class="signin_input" placeholder="Enter password" />
                <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-[18px] h-[18px] absolute right-2 cursor-pointer" viewBox="0 0 128 128">
                  <path d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z" data-original="#000000"></path>
                </svg>

              </div>
              <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
                <p v-if="errors" class="text-red-500 text-xs mt-1">{{ errors.general }}</p>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
              <div class="flex items-center">
                <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 shrink-0 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                <label for="remember-me" class="text-gray-800 ml-3 block text-sm dark:text-gray-100">
                  Remember me
                </label>
              </div>
              <div>
                <a href="jajvascript:void(0);" class="text-blue-600 font-semibold text-sm hover:underline dark:text-gray-100">
                  Forgot Password?
                </a>
              </div>
            </div>

            <div class="mt-12">
              <button type="submit" class="primary-btn">
                Sign in
              </button>
              <p class="text-gray-800 text-sm text-center mt-6 dark:text-gray-100">Don't have an account <router-link to="/register" class="text-blue-600 font-semibold hover:underline ml-1 whitespace-nowrap">Register here</router-link></p>
            </div>
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
    if(isAdmin){
        router.push({ name: 'Dashboard' });
    }else{
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

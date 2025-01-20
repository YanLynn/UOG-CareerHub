<template>
    <div
        class="flex flex-col items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <!-- Error Code -->
        <h1 class="text-6xl font-bold text-blue-600 dark:text-blue-400 mb-4">
            404
        </h1>
        <!-- Error Message -->
        <p class="text-lg md:text-xl text-gray-700 dark:text-gray-300 text-center mb-6 px-4">
            Oops! The page you're looking for doesn't exist.
        </p>
        <!-- Navigation Buttons -->
        <div class="flex space-x-4">
            <router-link to="/"
                class="bg-blue-600 text-white px-6 py-3 rounded-md shadow-md text-sm md:text-base font-semibold hover:bg-blue-500 transition">
                Go Home
            </router-link>
            <router-link to="/contact"
                class="bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 px-6 py-3 rounded-md shadow-md text-sm md:text-base font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                Contact Support
            </router-link>
        </div>
        <!-- Illustration -->
        <img src="@images/404-error.svg" alt="404 Illustration"
            class="mt-10 max-w-full md:max-w-lg" />
    </div>
</template>

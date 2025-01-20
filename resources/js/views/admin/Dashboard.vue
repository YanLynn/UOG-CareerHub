<template>
    <div class="p-6">
        <header class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold">Dashboard Overview</h1>
        </header>

        <!-- Dashboard Widgets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-300">
                    Total Users
                </h2>
                <p class="mt-2 text-3xl font-bold text-blue-600">1,234</p>
            </div>
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-300">
                    Active Sessions
                </h2>
                <p class="mt-2 text-3xl font-bold text-green-500">567</p>
            </div>
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-300">
                    Revenue
                </h2>
                <p class="mt-2 text-3xl font-bold text-yellow-500">$12,345</p>
            </div>
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-300">
                    Issues
                </h2>
                <p class="mt-2 text-3xl font-bold text-red-500">12</p>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-medium text-gray-700 dark:text-gray-300">
                Recent Activity
            </h2>
            <ul class="mt-4">
                <li class="border-b border-gray-300 dark:border-gray-700 py-2 flex justify-between">
                    <span>User John Doe logged in</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">5 mins ago</span>
                </li>
                <li class="border-b border-gray-300 dark:border-gray-700 py-2 flex justify-between">
                    <span>New user registered: Jane Smith</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">15 mins ago</span>
                </li>
                <li class="py-2 flex justify-between">
                    <span>System maintenance completed</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">30 mins ago</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
/* Add any JavaScript logic for dynamic content if needed */
</script>

<style>
/* Optional: Customize scrollbar for smooth experience */
body {
    scroll-behavior: smooth;
}

::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-thumb {
    background-color: #a0aec0;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background-color: #718096;
}
</style>

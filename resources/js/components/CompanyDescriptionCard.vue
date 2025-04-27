<template>
    <Card class="shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 rounded-xl overflow-hidden">
        <template #content>
            <div class="p-6 bg-gradient-to-br from-blue-50 to-purple-100 dark:from-gray-800 dark:to-gray-900 rounded-xl relative">

                <!-- Decorative Icon -->
                <div class="absolute top-3 right-3 bg-blue-500 dark:bg-blue-700 p-3 rounded-full shadow-md">
                    <i class="pi pi-info-circle text-white text-lg"></i>
                </div>

                <!-- Title -->
                <div class="flex items-center space-x-3">
                    <i class="pi pi-building text-blue-600 dark:text-blue-400 text-2xl"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">About Our Company</h3>
                    <div class="flex-1 h-0.5 bg-blue-300 dark:bg-blue-600"></div>
                </div>

                <!-- Description -->
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-4 leading-relaxed">
                    {{ description }}
                </p>


            </div>
        </template>
    </Card>
</template>

<script setup>
import Card from "primevue/card";
import Button from "primevue/button";

defineProps({
    description: {
        type: String,
        required: true
    }
});
</script>

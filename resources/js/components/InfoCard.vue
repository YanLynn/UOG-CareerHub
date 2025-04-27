<template>
    <Card class="shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 rounded-xl">
        <template #content>
            <div class="p-5 space-y-4">
                <!-- Title -->
                <div class="flex items-center justify-between border-b pb-2">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i :class="[icon, 'text-blue-500 dark:text-blue-400 text-xl mr-2']"></i>
                        {{ title }}
                    </h3>
                    <Button icon="pi pi-info-circle" class="p-button-rounded p-button-text text-gray-500 hover:text-blue-500" />
                </div>

                <!-- Information Grid -->
                <div v-if="info.length" class="grid grid-cols-1 sm:grid-cols-1 gap-3">
                    <div v-for="(item, index) in info" :key="index" class="flex items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                        <i :class="[item.icon, 'text-blue-500 dark:text-blue-400 text-xl mr-3']"></i>
                        <span v-if="item.link">
                            <a :href="item.link" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">{{ item.text }}</a>
                        </span>
                        <span v-else class="text-gray-700 dark:text-gray-300">{{ item.text }}</span>
                    </div>
                </div>

                <!-- Social Links -->
                <div v-else-if="social.length" class="flex justify-start space-x-4">
                    <a v-for="(item, index) in social" :key="index" :href="item.link" target="_blank"
                        class="p-3 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-md hover:scale-105 transition-all">
                        <i :class="[item.icon, 'text-2xl text-gray-700 dark:text-gray-300 hover:text-blue-500 transition']"></i>
                    </a>
                </div>

                <!-- No Data Message -->
                <div v-else class="text-gray-500 text-sm text-center">
                    No information available.
                </div>
            </div>
        </template>
    </Card>
</template>

<script setup>
import Card from "primevue/card";
import Button from "primevue/button";

// Props for InfoCard
defineProps({
    title: {
        type: String,
        required: true
    },
    info: {
        type: Array,
        default: () => []
    },
    social: {
        type: Array,
        default: () => []
    },
    icon: {
        type: String,
        default: "pi pi-info-circle"
    }
});
</script>

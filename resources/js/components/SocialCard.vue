<template>
    <Card class="shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 rounded-xl">
        <template #content>
            <div class="p-5 space-y-4">
                <!-- Title -->
                <div class="flex items-center justify-between border-b pb-2">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="pi pi-share-alt text-blue-500 dark:text-blue-400 text-xl mr-2"></i>
                        Social Media
                    </h3>
                    <Button icon="pi pi-info-circle" class="p-button-rounded p-button-text text-gray-500 hover:text-blue-500" />
                </div>

                <!-- Social Media Links -->
                <div class="flex flex-wrap justify-start gap-3">
                    <a v-for="(item, index) in socialLinks" :key="index" :href="item.link" target="_blank"
                        class="flex items-center space-x-2 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-md hover:bg-blue-500 hover:text-white transition-all">
                        <i :class="[item.icon, 'text-xl']"></i>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ item.text }}</span>
                    </a>
                </div>

                <!-- No Data Message -->
                <div v-if="!socialLinks.length" class="text-gray-500 text-sm text-center">
                    No social media profiles available.
                </div>
            </div>
        </template>
    </Card>
</template>

<script setup>
import Card from "primevue/card";
import Button from "primevue/button";

// Props for SocialCard
defineProps({
    socialLinks: {
        type: Array,
        default: () => []
    }
});
</script>

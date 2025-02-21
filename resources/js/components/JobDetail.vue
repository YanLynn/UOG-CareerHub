<template>
    <Card class="dark:!bg-gray-800 p-6 shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
        <template #content>
            <!-- Header: Company Logo & Job Title -->
            <div class="flex items-center space-x-4">
                <img v-if="job.company_image" :src="job.company_image" alt="Company Logo"
                    class="w-25 h-20 object-cover rounded" />
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ job.job_title }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ job.company_name }}</p>
                </div>
            </div>

            <!-- Job Description -->
            <p class="mt-4 text-gray-700 dark:text-gray-300">
                {{ job.description || job.description }}
            </p>
        </template>

        <template #footer>
            <!-- Footer: Location & Posted Date -->
            <ul class="mt-4 space-y-2 text-gray-500 dark:text-gray-400">
                <li class="flex items-center">
                    <i class="pi pi-map-marker mr-2"></i>
                    <span>{{ job.country_name }}</span>
                </li>
                <li class="flex items-center">
                    <i class="pi pi-calendar mr-2"></i>
                    <span>{{ job.created_at }}</span>
                </li>
                <li class="flex items-center">
                    <i class="pi pi-money-bill mr-2"></i>
                    <span>{{ job.salary }}</span>
                </li>
                </ul>
        </template>
    </Card>
</template>

<script setup>
import Card from 'primevue/card'

defineProps({
    job: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped>
/* Add additional custom styles if needed */
</style>

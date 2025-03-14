<template>
    <Card class="max-w-4xl mx-auto shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md">
        <template #header>
            <!-- Header: Job Title & Company Info -->
            <div class="flex items-center space-x-6 border-b pb-4 px-6 pt-6">
                <img v-if="job.company_image" :src="job.company_image" alt="Company Logo"
                    class="w-20 h-20 object-cover rounded-lg shadow-md">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ job.job_title }}</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-300">{{ job.company_name }}</p>
                </div>
            </div>
        </template>

        <template #content>
            <!-- Job Details Section -->
            <div class="px-6 py-4 space-y-4">
                <div class="flex items-center text-gray-700 dark:text-gray-300">
                    <i class="pi pi-map-marker mr-2 text-blue-500"></i>
                    <span><strong>Location:</strong> {{ job.country_name || 'Remote' }}</span>
                </div>
                <div class="flex items-center text-gray-700 dark:text-gray-300">
                    <i class="pi pi-calendar mr-2 text-yellow-500"></i>
                    <span><strong>Posted:</strong> {{ new Date(job.created_at).toLocaleDateString() }}</span>
                </div>
                <div class="flex items-center text-gray-700 dark:text-gray-300">
                    <i class="pi pi-money-bill mr-2 text-green-500"></i>
                    <span><strong>Salary:</strong> {{ job.salary ? `$${job.salary.toLocaleString()}` : 'Not specified' }}</span>
                </div>
                <div class="flex items-center text-gray-700 dark:text-gray-300">
                    <i class="pi pi-briefcase mr-2 text-purple-500"></i>
                    <span><strong>Job Type:</strong> {{ job.job_type }}</span>
                </div>
                <div class="flex items-center text-gray-700 dark:text-gray-300">
                    <i class="pi pi-user mr-2 text-pink-500"></i>
                    <span><strong>Experience Level:</strong> {{ job.experience_level }}</span>
                </div>
                <div class="flex items-center text-gray-700 dark:text-gray-300">
                    <i class="pi pi-clock mr-2 text-red-500"></i>
                    <span><strong>Application Deadline:</strong> {{ job.application_deadline }}</span>
                </div>
            </div>

            <!-- Job Description -->
            <Divider />
            <div class="px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200">Job Description</h2>
                <p class="mt-2 text-gray-700 dark:text-gray-300 leading-relaxed">
                    {{ job.description }}
                </p>
            </div>

            <!-- Required Skills -->
            <Divider v-if="job.skills" />
            <div v-if="job.skills" class="px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200">Required Skills</h2>
                <div class="mt-2 flex flex-wrap gap-2">
                    <Tag v-for="skill in job.skills.split(',')" :key="skill" :value="skill.trim()"
                        class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-md" />
                </div>
            </div>

            <!-- Benefits -->
            <Divider v-if="job.benefits" />
            <div v-if="job.benefits" class="px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200">Benefits</h2>
                <ul class="mt-2 text-gray-700 dark:text-gray-300 list-disc list-inside">
                    <li v-for="benefit in job.benefits.split(',')" :key="benefit">
                        {{ benefit.trim() }}
                    </li>
                </ul>
            </div>
        </template>

        <template #footer>
            <!-- Apply Button -->
            <div class="mt-6 text-center pb-6">
                <Button v-if="job.apply_link" :href="job.apply_link" target="_blank" label="Apply Now"
                    class="p-button-lg p-button-rounded p-button-success text-lg w-full" icon="pi pi-send" />
            </div>
        </template>
    </Card>
</template>

<script setup>
import Card from 'primevue/card';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import Divider from 'primevue/divider';

defineProps({
    job: {
        type: Object,
        required: true,
    },
});
</script>

<style scoped>
/* Custom Styles if Needed */
</style>

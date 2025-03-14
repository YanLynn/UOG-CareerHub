<template>
    <div class="max-w-6xl mx-auto p-6 bg-white dark:bg-gray-900 shadow-lg rounded-lg">
        <!-- Profile Header -->
        <div class="flex items-center space-x-6 border-b pb-6">
            <!-- Company Logo -->
            <img :src="employer.company_image || 'https://placehold.co/100x100'" alt="Company Logo"
                class="w-28 h-28 object-cover rounded-lg shadow-md" />
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ employer.company_name }}</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">{{ employer.industry }}</p>
                <div class="mt-2 flex space-x-4">
                    <Tag v-if="employer.status === 'Active'" value="Verified Employer" severity="success" />
                    <Tag v-else value="Pending Verification" severity="warning" />
                </div>
            </div>
        </div>

        <!-- Employer Details -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Company Details</h2>
                <p class="text-gray-700 dark:text-gray-300"><i class="pi pi-globe mr-2"></i>Website:
                    <a :href="employer.company_website" target="_blank"
                        class="text-blue-600 dark:text-blue-400 hover:underline">
                        {{ employer.company_website }}
                    </a>
                </p>
                <p class="text-gray-700 dark:text-gray-300"><i class="pi pi-users mr-2"></i>Company Size:
                    <span class="font-medium">{{ employer.company_size }}</span>
                </p>
                <p class="text-gray-700 dark:text-gray-300"><i class="pi pi-calendar mr-2"></i>Founded Year:
                    <span class="font-medium">{{ employer.founded_year }}</span>
                </p>
                <p class="text-gray-700 dark:text-gray-300"><i class="pi pi-map-marker mr-2"></i>Location:
                    <span class="font-medium">{{ employer.country_name }}</span>
                </p>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Contact Info</h2>
                <p class="text-gray-700 dark:text-gray-300"><i class="pi pi-envelope mr-2"></i>Email:
                    <span class="font-medium">{{ employer.contact_email }}</span>
                </p>
                <p class="text-gray-700 dark:text-gray-300"><i class="pi pi-phone mr-2"></i>Phone:
                    <span class="font-medium">{{ employer.contact_phone }}</span>
                </p>

                <!-- Social Links -->
                <div class="flex space-x-4 mt-3">
                    <a v-if="employer.linkedin_url" :href="employer.linkedin_url" target="_blank" class="text-blue-700 text-lg">
                        <i class="pi pi-linkedin"></i>
                    </a>
                    <a v-if="employer.twitter_url" :href="employer.twitter_url" target="_blank" class="text-blue-500 text-lg">
                        <i class="pi pi-twitter"></i>
                    </a>
                    <a v-if="employer.facebook_url" :href="employer.facebook_url" target="_blank" class="text-blue-800 text-lg">
                        <i class="pi pi-facebook"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Company Description -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">About the Company</h2>
            <p class="text-gray-700 dark:text-gray-300">{{ employer.company_description }}</p>
        </div>

        <!-- Employer Job Listings -->
        <div class="mt-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Available Job Openings</h2>
            <div v-if="jobs.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <Card v-for="job in jobs" :key="job.id"
                    class="shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg hover:shadow-xl cursor-pointer">
                    <template #content>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                                {{ job.job_title }}
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ job.job_location }}</p>
                            <div class="mt-3 flex space-x-2">
                                <Tag :value="job.job_type" severity="info" class="text-xs" />
                                <Tag :value="job.employment_status" severity="success" class="text-xs" />
                            </div>
                            <div class="mt-4 flex justify-end">
                                <Button label="View Details" class="p-button-outlined p-button-primary" @click="viewJob(job)" />
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
            <p v-else class="text-gray-600 dark:text-gray-400 mt-4">No job openings available.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/store';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const employer = ref({});
const jobs = ref([]);

onMounted(async () => {
    try {
        const employerId = route.params.id;
        const res = await authStore.getEmployerProfile(employerId);
        employer.value = res.data;
        jobs.value = res.data.jobs || [];
    } catch (error) {
        console.error('Error fetching employer profile:', error);
    }
});

function viewJob(job) {
    router.push(`/jobs/${job.id}`);
}
</script>

<style scoped>
/* Add any extra styling if needed */
</style>

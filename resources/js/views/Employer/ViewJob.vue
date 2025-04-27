<template>
    <div class="p-8 container mx-auto">
        <Card class="rounded-3xl shadow-4xl">
            <template #title>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <Avatar icon="pi pi-briefcase" class="bg-blue-500 text-white" size="large" shape="circle" />
                        <div>
                            <h2 class="text-2xl font-bold text-blue-900">{{ job.job_title }}</h2>
                            <p class="text-sm text-gray-500">{{ job.category?.name }} • {{ job.job_type }}</p>
                        </div>
                    </div>
                    <Tag :value="job.employment_status" :severity="getEmploymentStatusColor(job.employment_status)" />
                </div>
            </template>

            <template #content>
                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="space-y-2">
                        <div class="flex items-center text-gray-700">
                            <i class="pi pi-map-marker text-blue-600 mr-2"></i>
                            <span><strong>Location:</strong> {{ job.job_location }}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="pi pi-dollar text-green-600 mr-2"></i>
                            <span><strong>Salary:</strong> {{ job.salary }} {{ job.country?.code}}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="pi pi-users text-indigo-600 mr-2"></i>
                            <span><strong>Experience:</strong> {{ job.experience_level }}</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center text-gray-700">
                            <i class="pi pi-globe text-yellow-600 mr-2"></i>
                            <span><strong>Country:</strong> {{ job.country?.country }}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="pi pi-eye text-purple-600 mr-2"></i>
                            <span><strong>Visibility:</strong> {{ job.visibility }}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="pi pi-calendar text-red-500 mr-2"></i>
                            <span><strong>Deadline:</strong> {{ formatDate(job.application_deadline) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Description Panels -->
                <Divider class="my-6" />

                <Panel header="📝 Job Description" toggleable class="mb-4">
                    <div v-html="job.description" class="prose"></div>
                </Panel>

                <Panel header="📋 Requirements" toggleable class="mb-4">
                    <div v-html="job.requirements" class="prose"></div>
                </Panel>

                <Panel header="🎁 Benefits" toggleable>
                    <div v-html="job.benefits" class="prose"></div>
                </Panel>
            </template>
        </Card>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '@/store';
import Card from 'primevue/card';
import Avatar from 'primevue/avatar';
import Panel from 'primevue/panel';
import Divider from 'primevue/divider';
import Button from 'primevue/button';
import Tag from 'primevue/tag';

const route = useRoute();
const authStore = useAuthStore();
const job = ref({});
const categoryList = ref([]);
const countryList = ref([]);

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleDateString();
};

const getEmploymentStatusColor = (status) => {
    return status === 'open' ? 'success' : 'danger';
};

onMounted(async () => {
    const jobId = route.params.id;
    try {
        const [jobRes, catRes, countryRes] = await Promise.all([
            authStore.getJobById(jobId),
            authStore.getCategory(),
            authStore.getCountry()
        ]);

        categoryList.value = catRes.data;
        countryList.value = countryRes.data;
        const rawJob = jobRes.data;

        const matchedCategory = categoryList.value.find(cat => cat.id === rawJob.category_id);
        const matchedCountry = countryList.value.find(c => c.id === rawJob.country_id);

        job.value = {
            ...rawJob,
            category: matchedCategory || null,
            country: matchedCountry || null
        };
    } catch (error) {
        console.error('Failed to load job:', error);
    }
});
</script>

<style scoped>
.prose p {
    margin-bottom: 0.75rem;
    line-height: 1.6;
}
</style>

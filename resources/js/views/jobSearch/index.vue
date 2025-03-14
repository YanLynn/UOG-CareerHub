<template>
    <div class="max-w-7xl mx-auto p-6">
        <!-- Search & Filters -->
        <Card class="shadow-lg border border-gray-200 dark:border-gray-700">
            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Keyword Search -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Keyword</label>
                        <InputGroup>
                            <InputText v-model="filters.keyword" placeholder="Search job title, company, etc."
                                class="w-full" @input="searchJobs" />
                            <Button icon="pi pi-search" class="p-button-outlined" @click="searchJobs" />
                        </InputGroup>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Location</label>
                        <Dropdown v-model="filters.location" :options="locations" optionLabel="name" optionValue="id"
                            placeholder="Select location" class="w-full p-dropdown-sm" @change="searchJobs" />
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Category</label>
                        <Dropdown v-model="filters.category" :options="categories" optionLabel="name" optionValue="id"
                            placeholder="Select category" class="w-full p-dropdown-sm" @change="searchJobs" />
                    </div>
                </div>

                <!-- Reset Filters Button -->
                <div class="mt-4 flex justify-end">
                    <Button label="Reset Filters" class="p-button-secondary p-button-outlined" icon="pi pi-refresh"
                        @click="resetFilters" />
                </div>
            </template>
        </Card>

        <!-- Job Listings -->
        <div class="mt-6">
            <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-4 rounded-lg shadow-md mb-4">
                <div class="flex items-center space-x-3">
                    <i class="pi pi-briefcase text-blue-500 text-3xl"></i>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200">
                            Job Listings
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Find your dream job from <span class="font-medium text-blue-600">{{ totalRecords }}</span>
                            opportunities
                        </p>
                    </div>
                </div>

                <!-- Sort & View Options -->
                <div class="flex space-x-3">


                    <div class="flex bg-gray-200 dark:bg-gray-700 rounded-md p-1">
                        <Button icon="pi pi-th-large" class="p-button-sm"
                            :class="jobView === 'grid' ? 'p-button-primary' : 'p-button-text'"
                            @click="changeView('grid')" />

                        <Button icon="pi pi-bars" class="p-button-sm"
                            :class="jobView === 'list' ? 'p-button-primary' : 'p-button-text'"
                            @click="changeView('list')" />
                    </div>
                </div>
            </div>

            <div v-if="loading" class="text-center text-gray-500">
                <ProgressSpinner />
            </div>

            <div v-else-if="filteredJobs.length === 0" class="text-center text-gray-500 py-6">
                <i class="pi pi-search text-4xl text-gray-400"></i>
                <p class="mt-2">No jobs found. Try adjusting your search filters.</p>
            </div>

            <!-- Job Cards Grid -->
            <div v-else
                :class="jobView === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6' : 'space-y-4'">
                <Card v-for="job in filteredJobs" :key="job.id"
                    class="shadow-lg border border-gray-200 dark:border-gray-700 rounded-xl transition-all duration-300 hover:shadow-2xl cursor-pointer bg-white/70 dark:bg-gray-800/80 backdrop-blur-lg"
                    @click="openJobDetail(job)">

                    <template #content>
                        <div class="p-5 flex flex-col space-y-4">

                            <!-- Company Logo & Job Status -->
                            <div class="flex justify-between items-center">
                                <img :src="job?.employer?.company_image || 'https://placehold.co/50'" alt="Company Logo"
                                    class="w-14 h-14 object-cover rounded-full border shadow-md">

                                <div class="flex space-x-2">
                                    <Tag :value="job.employment_status" severity="success"
                                        class="text-xs px-3 py-1 rounded-full" />
                                    <Tag :value="job.job_type" severity="info" class="text-xs px-3 py-1 rounded-full" />
                                </div>
                            </div>

                            <!-- Job Title & Company -->
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 leading-snug">
                                {{ job.job_title }}
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                {{ job?.employer?.company_name || "Unknown Company" }}
                            </p>

                            <!-- Job Location -->
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                <i class="pi pi-map-marker text-blue-500 mr-1"></i>
                                {{ job.job_location }}
                            </div>

                            <!-- Salary Display -->
                            <div v-if="isAuthenticated"
                                class="text-green-600 dark:text-green-400 font-semibold text-md flex items-center">
                                <i class="pi pi-dollar mr-1"></i> {{ job.salary }}
                            </div>
                            <div v-else class="text-gray-500 text-xs italic flex items-center">
                                <i class="pi pi-lock mr-1"></i> Login to view salary
                            </div>

                            <!-- Job Category -->
                            <div class="flex space-x-2">
                                <Chip :label="job.category.name" icon="pi pi-tag"
                                    class="p-chip-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full" />
                            </div>

                            <!-- Action Buttons (Apply + Save Job for Authenticated Users) -->
                            <div class="mt-3 flex space-x-2">
                                <!-- Apply Button -->
                                <Button v-if="isAuthenticated" label="Apply Now"
                                    class="p-button-rounded p-button-success flex-1 text-md" icon="pi pi-send"
                                    @click="applyForJob(job.id)" />
                                <Button v-else label="Login to Apply"
                                    class="p-button-rounded p-button-warning flex-1 text-md" icon="pi pi-sign-in"
                                    @click="redirectToLogin" />

                                <!-- Save Job Button -->
                                <Button v-if="isAuthenticated" :label="savedJobs?.includes(job.id) ? 'Saved' : 'Save'"
                                    :icon="savedJobs?.includes(job.id) ? 'pi pi-check' : 'pi pi-bookmark'"
                                    :class="savedJobs?.includes(job.id) ? 'p-button-success' : 'p-button-rounded p-button-secondary'"
                                    @click.stop="toggleSaveJob(job.id)" />
                            </div>
                        </div>
                    </template>
                </Card>
            </div>

            <!-- Pagination -->
            <Paginator :rows="perPage" :totalRecords="totalRecords" @page="onPageChange" class="mt-6 !bg-transparent" />

        </div>
        <!-- Job Detail Sidebar -->
        <Sidebar v-model:visible="jobDetailVisible" position="right" class="!w-full md:!w-96 lg:!w-[35rem]">
            <template #header>
                <div class="flex items-center space-x-3">
                    <img :src="selectedJob?.employer.company_image || 'https://placehold.co/60'" alt="Company Logo"
                        class="w-12 h-12 rounded-lg border shadow-md" />
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ selectedJob?.job_title }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ selectedJob?.employer.company_name }}</p>
                    </div>
                </div>
            </template>

            <div v-if="selectedJob" class="p-4 space-y-4 text-gray-800 dark:text-gray-300">
                <!-- Job Details -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-sm">
                        <p class="font-semibold">Location:</p>
                        <p class="text-gray-600 dark:text-gray-400">{{ selectedJob.job_location }}</p>
                    </div>
                    <div class="text-sm">
                        <p class="font-semibold">Salary:</p>
                        <p v-if="isAuthenticated" class="text-green-600 dark:text-green-400 font-semibold">${{
                            selectedJob.salary }}</p>
                        <p v-else class="text-green-600 dark:text-green-400 font-semibold">login to view</p>
                    </div>
                    <div class="text-sm">
                        <p class="font-semibold">Category:</p>
                        <p class="text-gray-600 dark:text-gray-400">{{ selectedJob.category.name }}</p>
                    </div>
                    <div class="text-sm">
                        <p class="font-semibold">Experience Level:</p>
                        <Tag :value="selectedJob.experience_level" severity="info" />
                    </div>
                    <div class="text-sm">
                        <p class="font-semibold">Employment Status:</p>
                        <Tag :value="selectedJob.employment_status" severity="success" />
                    </div>
                    <div class="text-sm">
                        <p class="font-semibold">Job Type:</p>
                        <Tag :value="selectedJob.job_type" severity="warning" />
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h4 class="text-md font-semibold">Job Description</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ selectedJob.description }}
                    </p>
                </div>

                <!-- Benefits -->
                <div v-if="selectedJob.benefits">
                    <h4 class="text-md font-semibold">Benefits</h4>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 text-sm">
                        <li v-for="benefit in selectedJob.benefits.split(',')" :key="benefit">{{ benefit.trim() }}</li>
                    </ul>
                </div>

                <!-- Company Information -->
                <div>
                    <h4 class="text-md font-semibold">About the Company</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{
                        selectedJob.employer.company_description }}</p>
                    <div class="flex space-x-3 mt-2">
                        <a v-if="selectedJob.employer.linkedin_url" :href="selectedJob.employer.linkedin_url"
                            target="_blank">
                            <i class="pi pi-linkedin text-blue-600 text-lg"></i>
                        </a>
                        <a v-if="selectedJob.employer.twitter_url" :href="selectedJob.employer.twitter_url"
                            target="_blank">
                            <i class="pi pi-twitter text-blue-400 text-lg"></i>
                        </a>
                        <a v-if="selectedJob.employer.facebook_url" :href="selectedJob.employer.facebook_url"
                            target="_blank">
                            <i class="pi pi-facebook text-blue-700 text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Apply Button with Authentication Check -->
                <div class="mt-6">
                    <Button v-if="isAuthenticated" label="Apply Now" class="p-button-success w-full py-3 text-lg"
                        icon="pi pi-send" @click="applyForJob(selectedJob.id)" />

                    <Button v-else label="Login to Apply" class="p-button-warning w-full py-3 text-lg" icon="pi pi-user"
                        @click="redirectToLogin" />
                </div>
            </div>
        </Sidebar>
    </div>
</template>
<script setup>
import { ref, onMounted, computed } from 'vue';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import Dropdown from 'primevue/dropdown';
import Sidebar from 'primevue/sidebar';
import ProgressSpinner from 'primevue/progressspinner';
import Tag from 'primevue/tag';
import Chip from 'primevue/chip';
import Button from 'primevue/button';
import Paginator from 'primevue/paginator';
import { useAuthStore } from '@/store';
import { useRouter } from 'vue-router';
const router = useRouter();
const authStore = useAuthStore();
const filters = ref({
    keyword: '',
    location: null,
    category: null,
});
const jobs = ref([]);
const filteredJobs = ref([]);
const locations = ref([]);
const categories = ref([]);
const selectedJob = ref(null);
const jobDetailVisible = ref(false);
const loading = ref(false);
const totalRecords = ref(0);
const currentPage = ref(1);
const perPage = ref(10);
const savedJobs = ref([])
const jobView = ref('grid');

function changeView(viewType) {
    jobView.value = viewType;
}
const isAuthenticated = computed(() => !!authStore.isLoggedIn);
onMounted(async () => {
    await fetchJobs();
    await fetchFilters();
});

// Fetch paginated jobs
async function fetchJobs(page = 1) {
    loading.value = true;
    try {
        const res = await authStore.getJobsList({ page });
        jobs.value = res.data.data;
        totalRecords.value = res.data.total;
        filteredJobs.value = jobs.value;
    } catch (error) {
        console.error(error);
    }
    loading.value = false;
}

// Fetch Locations & Categories
async function fetchFilters() {
    try {
        const locationRes = await authStore.getCountry();
        const categoryRes = await authStore.getCategory();
        locations.value = locationRes.data.map(loc => ({ id: loc.id, name: loc.name }));
        categories.value = categoryRes.data.map(cat => ({ id: cat.id, name: cat.name }));
    } catch (error) {
        console.error('Error fetching filters:', error);
    }
}

// Search Jobs
function searchJobs() {
    filteredJobs.value = jobs.value.filter(job => {
        return (
            (!filters.value.keyword || job.job_title.toLowerCase().includes(filters.value.keyword.toLowerCase())) &&
            (!filters.value.location || job.country_id === filters.value.location) &&
            (!filters.value.category || job.category_id === filters.value.category)
        );
    });
}

// Reset Filters
function resetFilters() {
    filters.value = { keyword: '', location: null, category: null };
    searchJobs();
}

// Handle Page Change
function onPageChange(event) {

    currentPage.value = event.page + 1;
    console.log('eve',currentPage)
    fetchJobs(currentPage.value);
}

// Open Job Detail Sidebar
function openJobDetail(job) {
    selectedJob.value = job;
    jobDetailVisible.value = true;
}
function redirectToLogin() {
    router.push('/login');
}
function toggleSaveJob(jobId) {
    if (!savedJobs.value) savedJobs.value = []; // Ensure it's an array

    if (savedJobs.value.includes(jobId)) {
        savedJobs.value = savedJobs.value.filter(id => id !== jobId);
        console.log(`Job ${jobId} removed from saved jobs`);
    } else {
        savedJobs.value.push(jobId);
        console.log(`Job ${jobId} saved`);
    }
}
</script>

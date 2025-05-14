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
                                class="w-full" @keyup.enter="searchJobs" @keypress="searchJobs" />
                            <!-- <Button icon="pi pi-search" class="p-button-outlined" @click="searchJobs" /> -->
                        </InputGroup>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Location</label>
                        <Dropdown v-model="filters.location" :options="locations" optionLabel="country"
                            optionValue="country" placeholder="Select location" class="w-full p-dropdown-sm"
                            @change="searchJobs" />
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Category</label>
                        <Dropdown v-model="filters.category" :options="categories" optionLabel="name" optionValue="name"
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

                <!-- View Options -->
                <div class="flex bg-gray-200 dark:bg-gray-700 rounded-md p-1">
                    <Button icon="pi pi-th-large" class="p-button-sm"
                        :class="jobView === 'grid' ? 'p-button-primary' : 'p-button-text'"
                        @click="changeView('grid')" />
                    <Button icon="pi pi-bars" class="p-button-sm"
                        :class="jobView === 'list' ? 'p-button-primary' : 'p-button-text'"
                        @click="changeView('list')" />
                </div>
            </div>

            <!-- Loading Spinner -->
            <div v-if="loading" class="text-center text-gray-500">
                <template v-for="i in 6" :key="i">

                    <Skeleton height="40px" class="rounded-md mb-2" />
                    <Skeleton height="20px" width="60%" class="rounded-md mb-3" />
                    <Skeleton height="15px" width="40%" class="rounded-md mb-2" />
                    <Skeleton height="20px" width="80%" class="rounded-md mb-2" />
                    <Skeleton height="30px" class="rounded-md" />

                </template>
                <!-- <ProgressSpinner /> -->
            </div>

            <!-- No Jobs Found -->
            <div v-else-if="jobs.length === 0" class="text-center text-gray-500 py-6">
                <i class="pi pi-search text-4xl text-gray-400"></i>
                <p class="mt-2">No jobs found. Try adjusting your search filters.</p>
            </div>

            <!-- Job Cards Grid/List -->
            <div v-else
                :class="jobView === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6' : 'space-y-4'">
                <Card v-for="job in jobs" :key="job.id"
                    class="relative shadow-lg border border-gray-100 dark:border-gray-700 rounded-2xl transition-all duration-300 hover:shadow-2xl cursor-pointer bg-white dark:bg-gray-800 backdrop-blur-md bg-opacity-80 p-6 flex flex-col space-y-4"
                    @click="openJobDetail(job)">

                    <template #content>
                        <div class="flex flex-col flex-grow">

                            <!-- 🔹 Floating Save Button -->
                            <Button :icon="job.isSaved ? 'pi pi-bookmark-fill' : 'pi pi-bookmark'"
                                class="absolute top-4 right-4 p-button-rounded p-button-text transition-all duration-300 border border-gray-300 shadow-md backdrop-blur-lg"
                                :class="{
                                    'bg-gray-200 !text-yellow-500 shadow-lg scale-110 hover:bg-gray-300 hover:!text-yellow-600': job.isSaved,
                                    'bg-gray-200 text-gray-500 hover:bg-gray-300 hover:scale-105': !job.isSaved
                                }" @click.stop="toggleSaveJob(job.id, 'saved')"  v-if="!authStore.isEmployer && isAuthenticated "/>
                            <!-- 🔹 Job Card Header -->
                            <div class="flex items-center space-x-4">
                                <!-- Company Logo -->
                                <img :src="job?.employer?.company_image || 'https://placehold.co/60'" alt="Company Logo"
                                    class="w-14 h-14 object-cover rounded-full border border-gray-300 shadow-md">

                                <!-- Job Title & Employer -->
                                <div class="flex flex-col">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 leading-snug">
                                        {{ job.job_title }}
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">
                                        {{ job?.employer?.company_name || "Unknown Company" }}
                                    </p>
                                </div>
                            </div>

                            <!-- 🔹 Job Location & Country -->
                            <div class="flex items-center text-xs text-gray-600 dark:text-gray-300 mt-2">
                                <i class="pi pi-map-marker text-blue-500 mr-2"></i>
                                <span>{{ job.job_location }}, {{ job?.country?.country }}</span>
                            </div>

                            <!-- 🔹 Employment Status & Job Type -->
                            <div class="flex space-x-2 mt-3">
                                <Tag :value="job.employment_status" severity="success"
                                    class="text-xs px-3 py-1 rounded-lg shadow-sm" />
                                <Tag :value="job.job_type" severity="info"
                                    class="text-xs px-3 py-1 rounded-lg shadow-sm" />
                            </div>

                            <!-- 🔹 Salary UI (Smaller, Inline, Icon-Based) -->
                            <div v-if="isAuthenticated"
                                class="flex items-center mt-2 text-gray-700 dark:text-gray-300 text-sm bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-md shadow-sm w-fit">
                                <i class="pi pi-wallet text-green-500 text-lg mr-2"></i>
                                <span class="font-medium">{{ job.country?.code }} {{ job?.salary }}</span>
                            </div>
                            <div v-else class="text-gray-500 text-xs italic flex items-center">
                                <i class="pi pi-lock mr-1"></i> Login to view salary
                            </div>

                            <!-- 🔹 Category Tag (Styled Like Save Button) -->
                            <div class="mt-2">
                                <Chip :label="job.category.name" icon="pi pi-tag"
                                    class="p-chip-sm px-2 py-1 text-xs rounded-full transition-all duration-300 border border-gray-300"
                                    :class="savedJobs[job.id] ? 'bg-blue-500 text-white shadow-lg' : 'bg-gray-200 text-gray-600 hover:bg-gray-300'" />
                            </div>

                            <!-- 🔹 Job Posted Date -->
                            <div class="text-gray-500 text-xs italic mt-2">
                                <i class="pi pi-clock mr-1"></i> Posted {{ getDaysAgo(job.created_at) }} days ago
                            </div>

                            <!-- 🔹 Already Applied UI (Updated Design) -->
                            <div v-if="job.isApplied"
                                class="mt-3 p-2 bg-red-100 dark:bg-red-800 text-red-600 dark:text-red-300 flex items-center rounded-lg shadow-sm">
                                <i class="pi pi-check-circle text-red-500 dark:text-red-300 text-lg mr-2"></i>
                                <span class="text-xs font-semibold">Application Submitted</span>
                            </div>

                        </div>
                    </template>
                </Card>




            </div>

            <!-- Pagination -->
            <Paginator :rows="perPage" :totalRecords="totalRecords" @page="onPageChange" class="mt-6 !bg-transparent" />
        </div>
        <Sidebar v-model:visible="jobDetailVisible" position="right" class="!w-full md:!w-96 lg:!w-[35rem]">
            <template #header>
                <!-- 🔹 Header: Company Logo & Job Title -->
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

            <div v-if="selectedJob" class="p-5 space-y-5 text-gray-800 dark:text-gray-300">

                <!-- 🔹 Job Details Card -->
                <Card class="p-4 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <template #content>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="font-semibold">📍 Location:</p>
                                <p class="text-gray-600 dark:text-gray-400">{{ selectedJob.job_location }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">💰 Salary:</p>
                                <p v-if="isAuthenticated" class="text-green-600 dark:text-green-400 font-semibold">
                                    {{ formattedSalary(selectedJob.salary) }}
                                </p>
                                <p v-else class="text-gray-500 italic">Login to view</p>
                            </div>
                            <div>
                                <p class="font-semibold">🏷 Category:</p>
                                <p class="text-gray-600 dark:text-gray-400">{{ selectedJob.category.name }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">📅 Experience Level:</p>
                                <Tag :value="selectedJob.experience_level" severity="info" />
                            </div>
                            <div>
                                <p class="font-semibold">📌 Employment Status:</p>
                                <Tag :value="selectedJob.employment_status" severity="success" />
                            </div>
                            <div>
                                <p class="font-semibold">🕒 Job Type:</p>
                                <Tag :value="selectedJob.job_type" severity="warning" />
                            </div>
                        </div>
                    </template>
                </Card>

                <!-- 🔹 Job Description -->
                <div>
                    <h4 class="text-md font-semibold">📝 Job Description</h4>
                    <div class="prose dark:prose-invert text-sm leading-relaxed max-w-none"
                        v-html="sanitize(selectedJob.description)"></div>
                </div>

                <!-- 🔹 Requirements Section -->
                <div v-if="selectedJob.requirements">
                    <h4 class="text-md font-semibold">📋 Requirements</h4>
                    <div class="prose dark:prose-invert text-sm leading-relaxed max-w-none"
                        v-html="sanitize(selectedJob.requirements)"></div>

                </div>

                <!-- 🔹 Benefits Section -->
                <div v-if="selectedJob.benefits">
                    <h4 class="text-md font-semibold">🎁 Benefits</h4>
                    <div class="prose dark:prose-invert text-sm leading-relaxed max-w-none"
                        v-html="sanitize(selectedJob.benefits)"></div>
                </div>

                <!-- 🔹 Company Information -->
                <Card class="p-4 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <template #content>
                        <h4 class="text-md font-semibold">🏢 About the Company</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                            {{ selectedJob.employer.company_description }}
                        </p>
                        <div class="flex space-x-3 mt-3">
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
                    </template>
                </Card>

                <!-- 🔹 Apply Button -->
                <div class="mt-4">
                    <Button v-if="isAuthenticated && !authStore.isEmployer" :label="isJobApplied ? 'Already Applied' : 'Apply Now'"
                        :disabled="isJobApplied" class="w-full py-3 text-lg rounded-lg shadow-md transition-all"
                        :class="isJobApplied ? 'p-button-secondary cursor-not-allowed opacity-70' : 'p-button-success hover:scale-105'"
                        icon="pi pi-send" @click="!isJobApplied && applyForJob(selectedJob.id, 'pending')" />

                    <Button v-else-if="!isAuthenticated && !authStore.isEmployer" label="Login to Apply"
                        class="p-button-warning w-full py-3 text-lg rounded-lg shadow-md transition-all hover:scale-105"
                        icon="pi pi-user" @click="redirectToLogin" />
                </div>


            </div>
        </Sidebar>


    </div>
</template>

<script setup>
import { useToast } from 'primevue/usetoast';
import { ref, onMounted, computed } from 'vue';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import Dropdown from 'primevue/dropdown';
import ProgressSpinner from 'primevue/progressspinner';
import Button from 'primevue/button';
import Paginator from 'primevue/paginator';
import { useAuthStore } from '@/store';
import { useRouter } from 'vue-router';
import Skeleton from 'primevue/skeleton'
const router = useRouter();
const authStore = useAuthStore();
const filters = ref({ keyword: '', location: null, category: null });
const jobs = ref([]);
const locations = ref([]);
const categories = ref([]);
const loading = ref(false);
const totalRecords = ref(0);
const perPage = ref(10);
const savedJobs = ref([]);
const jobView = ref('grid');
const selectedJob = ref(null);
const isAuthenticated = computed(() => !!authStore.isLoggedIn);
const jobDetailVisible = ref(false);
const isJobApplied = ref(false);
const toast = useToast();
import DOMPurify from 'dompurify'


const sanitize = (html) => {
    return DOMPurify.sanitize(html);
};
onMounted(async () => {
    await authStore.checkJobApplied()
    await fetchJobs();
    await fetchFilters();
});

async function fetchJobs(page = 1) {
    loading.value = true;
    try {
        const params = {
            page,
        };
        const fromHomeParam = router.currentRoute.value.query;
        if (filters.value.keyword) params.keyword = filters.value.keyword;
        if (filters.value.location) params.location = filters.value.location;
        if (filters.value.category) params.category = filters.value.category;
        if (fromHomeParam.country) params.location = fromHomeParam.country;
        if (fromHomeParam.category) params.category = fromHomeParam.category;
        if (fromHomeParam.jobType) params.jobType = fromHomeParam.jobType;
        const res = await authStore.getJobsList(params);

        const jobApplied = authStore.checkJobAppliedList || [];

        jobs.value = res.data.data;
        totalRecords.value = res.data.total;

        jobs.value.forEach(job => {
            job.isApplied = jobApplied.some(appliedJob =>
                appliedJob.job_id === job.id && appliedJob.status === 'pending'
            );
            job.isSaved = jobApplied.some(savedJob =>
                savedJob.job_id === job.id && savedJob.type === 'saved'
            );
        });


    } catch (error) {
        console.error("Error fetching jobs:", error);
    }
    loading.value = false;
}


async function fetchFilters() {
    try {
        const locationRes = await authStore.getCountry();
        const categoryRes = await authStore.getCategory();
        locations.value = locationRes.data.map(loc => ({ id: loc.id, name: loc.name, code: loc.code, country: loc.country, countryCode: loc.countryCode }));
        categories.value = categoryRes.data.map(cat => ({ id: cat.id, name: cat.name }));
    } catch (error) {
        console.error(error);
    }
}

function searchJobs() {
    jobs.value = null
    const query = {};
    if (filters.value.keyword) query.keyword = filters.value.keyword;
    if (filters.value.location) query.location = filters.value.location;
    if (filters.value.category) query.category = filters.value.category;
    router.replace({ path: router.currentRoute.value.path, query });
    fetchJobs(1);
}

function resetFilters() {
    filters.value = { keyword: "", location: null, category: null, jobType: null };
    router.replace({ path: router.currentRoute.value.path, query: {} });
    //  const fromHomeParam = router.currentRoute.value.query;
    fetchJobs(1);
}

function onPageChange(event) {
    fetchJobs(event.page + 1);
}
function openJobDetail(job) {
    selectedJob.value = job;
    console.log('job',job)
    isJobApplied.value = authStore.checkJobAppliedList?.some(appliedJob =>
        appliedJob.job_id === job.id && appliedJob.status === 'pending'
    ) || false;

    jobDetailVisible.value = true;
}



async function toggleSaveJob(jobId, status) {
    const jobIndex = jobs.value.findIndex(job => job.id === jobId);

    if (jobIndex !== -1) {
        const newStatus = jobs.value[jobIndex].isSaved ? 'unsaved' : 'saved';
        jobs.value[jobIndex].isSaved = !jobs.value[jobIndex].isSaved;

        try {
            const params = { jobId, type: newStatus, status: null };
            const response = await authStore.jobApplyAndSave(params);

            if (response.success) {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: newStatus === 'saved' ? 'Job saved successfully!' : 'Job removed from saved list.',
                    life: 3000
                });
            } else {
                jobs.value[jobIndex].isSaved = !jobs.value[jobIndex].isSaved;
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to update job save status. Please try again.',
                    life: 3000
                });
            }
        } catch (error) {
            console.error("Error saving job:", error);
            jobs.value[jobIndex].isSaved = !jobs.value[jobIndex].isSaved;
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Something went wrong. Please try again later.',
                life: 3000
            });
        }
    }

    console.log('save', jobId, 'New isSaved:', jobs.value[jobIndex]?.isSaved);
}

function redirectToLogin() {
    router.push('/login');
}

function formattedSalary(salary) {
    return new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(salary);
}

// Calculate how many days ago the job was posted
function getDaysAgo(createdAt) {
    const jobDate = new Date(createdAt);
    const today = new Date();
    const diffTime = Math.abs(today - jobDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // Convert to days
    return diffDays;
}

function changeView(viewType) {
    jobView.value = viewType;
}


async function applyForJob(jobId, status) {
    try {
        const params = {
            jobId,
            status,
            type: null
        }
        const response = await authStore.jobApplyAndSave(params)
        if (response.success) {
            const jobIndex = jobs.value.findIndex(job => job.id === jobId);
            if (jobIndex !== -1) {
                jobs.value[jobIndex].isApplied = true;
            }
            jobDetailVisible.value = false;
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Your application has been submitted!',
                life: 3000
            });
        } else {

            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to submit your application. Please try again.',
                life: 3000
            });
        }
    } catch (error) {
        console.error(error);
    }
}
</script>

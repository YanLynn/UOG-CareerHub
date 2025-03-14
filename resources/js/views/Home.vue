<template>
    <div class="pt-6">
        <div
            class="bg-gradient-to-b from-white/30 to-white/30 dark:from-gray-900/80 dark:to-gray-800/60 backdrop-blur-md -z-0">
            <!-- Hero Section -->

            <section class="h-80 flex items-center justify-center">
    <div class="text-center px-4 sm:px-8 md:px-16">
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
            Job Market Insights
        </h2>
        <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-8">
            Stay updated with the latest salary trends and in-demand jobs.
        </p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold">💰 Avg Salary</h3>
                <p class="text-gray-500 dark:text-gray-300">$75,000 / year</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold">📈 Fastest Growing Field</h3>
                <p class="text-gray-500 dark:text-gray-300">Software Engineering</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold">🏆 Most In-Demand Job</h3>
                <p class="text-gray-500 dark:text-gray-300">Data Analyst</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold">🌎 Top Hiring Country</h3>
                <p class="text-gray-500 dark:text-gray-300">United States</p>
            </div>
        </div>
    </div>
</section>

<section class="h-80 flex items-center justify-center">
        <div class="text-center px-4 sm:px-8 md:px-16">
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
                {{ sectionData.title }}
            </h2>
            <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-8">
                {{ sectionData.description }}
            </p>
        </div>
    </section>

            <!-- Job Categories Section -->
            <section class="py-16 px-6 sm:px-10 bg-transparent" v-if="!loading">
                <div class="container mx-auto text-center">
                    <h3 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 mb-10">
                        Popular Job Companies
                    </h3>

                    <div class="my-10 mx-4">
                        <!-- Carousel from PrimeVue -->
                        <Carousel :value="companies" :numVisible="5" :numScroll="1" circular
                            :responsiveOptions="responsiveOptions" class="p-carousel-custom">
                            <!-- Template for each item in the carousel -->
                            <template #item="slotProps">
                                <div class="w-64 h-60 p-3 bg-white dark:bg-gray-700 rounded-lg
                   shadow-md hover:shadow-lg transition mx-2 flex flex-col
                   items-center text-center">
                                    <!-- Company Image/Logo -->
                                    <img v-if="slotProps.data.company_image"
                                        :src="slotProps.data.company_image || 'https://res.cloudinary.com/dm9kdkvce/image/upload/v1740382987/placeholder_ehasuo.png'"
                                        alt="Company Logo" class="w-16 h-16 object-cover rounded-full mb-3" />

                                    <!-- Company Name -->
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
                                        {{ slotProps.data.company_name }}
                                    </h4>

                                    <!-- Company Description (truncated) -->
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mb-3">
                                        {{
                                            slotProps.data.company_description?.length > 60
                                                ? slotProps.data.company_description.slice(0, 60) + '...'
                                                : slotProps.data.company_description
                                        }}
                                    </p>

                                    <!-- Social Links -->
                                    <div class="flex items-center justify-center space-x-3">
                                        <a v-if="slotProps.data.facebook_url" :href="slotProps.data.facebook_url"
                                            target="_blank" rel="noopener noreferrer"
                                            class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition">
                                            <i class="pi pi-facebook text-xl"></i>
                                        </a>
                                        <a v-if="slotProps.data.linkedin_url" :href="slotProps.data.linkedin_url"
                                            target="_blank" rel="noopener noreferrer"
                                            class="text-gray-600 dark:text-gray-300 hover:text-blue-700 transition">
                                            <i class="pi pi-linkedin text-xl"></i>
                                        </a>
                                        <a v-if="slotProps.data.twitter_url" :href="slotProps.data.twitter_url"
                                            target="_blank" rel="noopener noreferrer"
                                            class="text-gray-600 dark:text-gray-300 hover:text-blue-400 transition">
                                            <i class="pi pi-twitter text-xl"></i>
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </Carousel>
                    </div>
                </div>
            </section>

            <section v-else class="py-16 px-6 sm:px-10 bg-transparent">
                <div class="container mx-auto text-center">
                    <h3 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 mb-10">
                        Popular Job Companies
                    </h3>

                    <div class="my-10 mx-4 flex items-center justify-center space-x-4">
                        <div v-for="i in 5" :key="i" class="w-56 h-60 p-3 bg-white dark:bg-gray-700
               rounded-lg shadow-md flex flex-col
               items-center text-center">
                            <Skeleton shape="circle" size="64px" class="mb-3" />
                            <Skeleton width="80%" height="1.25rem" class="mb-2" />
                            <Skeleton width="70%" height="0.75rem" class="mb-2" />
                            <div class="flex items-center justify-center space-x-3 mt-auto">
                                <Skeleton shape="circle" size="24px" />
                                <Skeleton shape="circle" size="24px" />
                                <Skeleton shape="circle" size="24px" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <section class="py-8 px-6 bg-gray-100/20 dark:bg-gray-800/50">
                <div class="container mx-auto">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Quick Search</h3>

                    <!-- Classifications -->
                    <div class="mb-4">
                        <span class="font-medium text-gray-600 dark:text-gray-400">Classifications:</span>
                        <div class="flex flex-wrap gap-4 mt-3">
                            <a href="#" class="badge-pill-red">
                                <i class="pi pi-chart-line mr-2"></i> Accounting
                            </a>
                            <a href="#" class="badge-pill-blue">
                                <i class="pi pi-graduation-cap mr-2"></i> Education
                            </a>
                            <a href="#" class="badge-pill-green">
                                <i class="pi pi-shield mr-2"></i> Defence
                            </a>
                            <a href="#" class="badge-pill-purple">
                                <i class="pi pi-heart mr-2"></i> Healthcare
                            </a>
                        </div>
                    </div>

                    <!-- Major Countries -->
                    <div class="mb-4">
                        <span class="font-medium text-gray-600 dark:text-gray-400">Major Countries:</span>
                        <div class="flex flex-wrap gap-4 mt-3">
                            <a href="#" class="badge-pill-blue">
                                <i class="pi pi-flag mr-2"></i> USA
                            </a>
                            <a href="#" class="badge-pill-red">
                                <i class="pi pi-flag mr-2"></i> UK
                            </a>
                            <a href="#" class="badge-pill-green">
                                <i class="pi pi-flag mr-2"></i> Canada
                            </a>
                            <a href="#" class="badge-pill-yellow">
                                <i class="pi pi-flag mr-2"></i> Australia
                            </a>
                        </div>
                    </div>

                    <!-- Other Categories -->
                    <div>
                        <span class="font-medium text-gray-600 dark:text-gray-400">Other:</span>
                        <div class="flex flex-wrap gap-4 mt-3">
                            <a href="#" class="badge-pill-purple">
                                <i class="pi pi-briefcase mr-2"></i> All Jobs
                            </a>
                            <a href="#" class="badge-pill-pink">
                                <i class="pi pi-home mr-2"></i> Remote Jobs
                            </a>
                            <a href="#" class="badge-pill-blue">
                                <i class="pi pi-user mr-2"></i> Internships
                            </a>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import Carousel from 'primevue/carousel';
import Button from 'primevue/button';
import Skeleton from 'primevue/skeleton';

import { useAuthStore } from '@/store';
const authStore = useAuthStore()
const user = authStore.currentUser
const loading = ref(false);
const companies = ref([]);
const sections = [
    { title: "Top Companies Hiring Now", description: "Explore top hiring companies today!" },
    { title: "Success Stories from Job Seekers", description: "See how professionals landed their dream jobs." },
    { title: "Career Advice & Job Tips", description: "Get expert job search and resume tips." },
    { title: "Job Market Insights", description: "Discover salary trends and in-demand careers." }
]
const sectionIndex = ref(0)
const sectionData = ref(sections[0])

onMounted(async () => {
    loading.value = true
    try {
        const res = await authStore.getCompany()
        companies.value = res.data
        loading.value = false
    } catch (error) {
        console.log('error', error)
    }
    setInterval(() => {
        sectionIndex.value = (sectionIndex.value + 1) % sections.length
        sectionData.value = sections[sectionIndex.value]
    }, 5000)
})


const responsiveOptions = [
    {
        breakpoint: '1024px',
        numVisible: 3,
        numScroll: 1
    },
    {
        breakpoint: '768px',
        numVisible: 2,
        numScroll: 1
    },
    {
        breakpoint: '560px',
        numVisible: 1,
        numScroll: 1
    }
];
</script>


<style>
/* Optional: Customize the carousel navigation buttons */
.p-carousel .p-carousel-prev,
.p-carousel .p-carousel-next {
    background-color: #2563eb;
    color: white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
}

.p-carousel .p-carousel-prev:hover,
.p-carousel .p-carousel-next:hover {
    background-color: #1d4ed8;
}
</style>

<template>
    <div class="pt-6">
    <div class="bg-gradient-to-b from-white/30 to-white/30 dark:from-gray-900/80 dark:to-gray-800/60 backdrop-blur-md -z-0">
        <!-- Hero Section -->

        <section class="h-80 flex items-center justify-center">
            <div class="text-center px-4 sm:px-8 md:px-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Find the Right Job for You
                </h2>
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-8">
                    Join thousands of professionals and discover your dream career.
                </p>
                <div class="flex flex-col md:flex-row justify-center items-center gap-4">
                    <input type="text" placeholder="Job title or keyword"
                        class="w-full md:w-72 px-4 py-2 rounded-md bg-white/70 dark:bg-gray-700/70 text-gray-900 dark:text-gray-100 backdrop-blur-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                    <input type="text" placeholder="Country"
                        class="w-full md:w-72 px-4 py-2 rounded-md bg-white/70 dark:bg-gray-700/70 text-gray-900 dark:text-gray-100 backdrop-blur-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                    <button
                        class="btn-blue">
                        Search
                    </button>
                </div>
            </div>
        </section>

        <!-- Job Categories Section -->
        <section class="py-16 px-6 sm:px-10 bg-transparent">
            <div class="container mx-auto text-center">
                <h3 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 mb-10">
                    Popular Job Companies
                </h3>
                <div class="my-10 mx-4">
                    <Carousel :value="categories" :numVisible="3" :numScroll="1" circular
                        :responsiveOptions="responsiveOptions" class="p-carousel-custom">
                        <template #item="slotProps">
                            <div
                                class="w-50 h-45 p-4 bg-white dark:bg-gray-700 rounded-lg shadow-md hover:shadow-lg transition mx-2">
                                <h4 class="text-xl font-semibold mb-2">{{ slotProps.data.title }}</h4>
                                <p>{{ slotProps.data.description }}</p>
                            </div>
                        </template>
                    </Carousel>
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
import { ref } from 'vue';
import Carousel from 'primevue/carousel';
import Button from 'primevue/button';
const categories = ref([
    { title: 'Technology', description: 'Discover jobs in software development, AI, and IT support.' },
    { title: 'Marketing', description: 'Explore roles in digital marketing, SEO, and content creation.' },
    { title: 'Finance', description: 'Work with leading financial institutions and fintech startups.' },
    { title: 'Healthcare', description: 'Opportunities in healthcare and wellness industries. industries.' },
    { title: 'Technology', description: 'Discover jobs in software development, AI, and IT support.' },
    { title: 'Marketing', description: 'Explore roles in digital marketing, SEO, and content creation.' },
    { title: 'Finance', description: 'Work with leading financial institutions and fintech startups.' },
    { title: 'Healthcare', description: 'Opportunities in healthcare and wellness industries. industries.' }
]);

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

<template>
    <!-- PrimeVue Card with Tailwind classes for modern UI -->
    <Card :class="[
        'dark:!bg-gray-900 !bg-white shadow-md rounded-xl hover:shadow-lg transition-transform duration-300 transform hover:-translate-y-1 cursor-pointer border',
        isSelected ? 'border-2 border-blue-500' : 'border-gray-200 dark:border-gray-800'
    ]" @click="$emit('select', job)">

        <!-- Card Content -->
        <template #content>
            <div class="p-4">
                <!-- Top Section: Company Logo & Name -->
                <div class="flex items-center space-x-4">
                    <img v-if="job.company_image" :src="job.company_image" alt="Company Logo"
                        class="w-14 h-14 object-cover rounded-lg shadow-md border" />
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ job.job_title }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ job.company_name }}</p>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="mt-4 space-y-2">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <i class="pi pi-map-marker mr-2 text-blue-500"></i>
                        {{ job.country_name }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <i class="pi pi-calendar mr-2 text-purple-500"></i>
                        {{ job.job_type }} - {{ job.employment_status }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <i class="pi pi-clock mr-2 text-green-500"></i>
                        Posted: {{ formatDate(job.created_at) }}
                    </div>
                </div>

                <!-- Description (Shortened) -->
                <p class="mt-4 text-gray-700 dark:text-gray-300 text-sm line-clamp-2">
                    {{ job.description.length > 100 ? job.description.substring(0, 100) + '...' : job.description }}
                </p>

                <!-- Tags (Category & Salary) -->
                <div class="mt-4 flex justify-between items-center">
                    <Tag :value="job.category_name" severity="info" class="text-xs" />
                    <span class="text-md font-semibold text-green-600 dark:text-green-400">
                        <i class="pi pi-money-bill mr-1"></i> ${{ job.salary }}
                    </span>
                </div>
            </div>
        </template>

        <!-- Card Footer -->
        <!-- <template #footer>
            <div class="flex justify-between items-center p-4 border-t dark:border-gray-800">

                <Button label="Apply Now" class="p-button-primary p-button-sm" icon="pi pi-send"
                    @click.stop="$emit('apply', job.id)" />
                <Button :label="savedJobs.includes(job.id) ? 'Saved' : 'Save'" icon="pi pi-bookmark"
                    :class="savedJobs.includes(job.id) ? 'p-button-success p-button-sm' : 'p-button-outlined p-button-sm'"
                    @click.stop="toggleSaveJob(job.id)" />
            </div>
        </template> -->
    </Card>
</template>

<script setup>
import { ref, computed } from 'vue'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Tag from 'primevue/tag'

const props = defineProps({
    job: {
        type: Object,
        required: true
    },
    selectedJobId: {
        type: [Number, String],
        default: null
    },
    savedJobs: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['select', 'apply', 'save'])

const isSelected = computed(() => props.job.id === props.selectedJobId)

function toggleSaveJob(jobId) {
    emit('save', jobId)
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    })
}
</script>

<style scoped>
/* Responsive line clamp to limit text overflow */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

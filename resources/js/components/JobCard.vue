<template>
    <!-- PrimeVue Card with Tailwind classes for hover & border highlight -->
    <Card :class="[
        'dark:!bg-gray-800 !bg-slate-300 shadow-sm rounded-lg hover:shadow-md transition-shadow duration-200 cursor-pointer',
        isSelected ? 'border-2 border-sky-500' : ''
    ]" @click="$emit('select', job)">

        <!-- Card Content -->
        <template #content>
            <!-- Company Logo & Title -->
            <div class="flex items-center space-x-4">
                <img v-if="job.company_image" :src="job.company_image" alt="Company Logo"
                    class="w-12 h-12 object-cover rounded" />
                <div>
                    <h2 class="text-lg font-semibold">{{ job.job_title }}</h2>
                    <p class="text-sm text-gray-500">{{ job.company_name}}</p>
                </div>
            </div>

            <!-- Short Description -->
            <p class="mt-2 ">
                {{ job.description}}
            </p>
        </template>

        <!-- Card Footer -->
        <template #footer>
            <!-- Footer: location & link -->
            <div class="flex justify-between items-center mt-4">
                <span class="text-sm flex items-center">
                    <i class="pi pi-map-marker mr-1"></i>
                    {{ job.country_name }}
                </span>
                <span class="text-sm flex items-center text-yellow-600">
                    <i class="pi pi-money-bill mr-1 "></i>
                    ${{ job.salary }}
                </span>
                <!-- If needed, you could add a "View Details" link or button here -->
            </div>
        </template>
    </Card>
</template>

<script setup>
import { computed } from 'vue'
import Card from 'primevue/card'

const props = defineProps({
    job: {
        type: Object,
        required: true
    },
    selectedJobId: {
        type: [Number, String],
        default: null
    }
})

const emit = defineEmits(['select'])

// Check if this card should be highlighted
const isSelected = computed(() => props.job.id === props.selectedJobId)
</script>

<style scoped>
/* You can add or override styles here if needed */
</style>

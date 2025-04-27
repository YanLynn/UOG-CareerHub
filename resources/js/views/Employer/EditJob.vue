<template>
    <div class="p-6 container mx-auto">
        <Card class="shadow-2 border-round-xl">
            <template #title>
                <div class="flex items-center gap-3">
                    <Avatar icon="pi pi-briefcase" shape="circle" />
                    <span class="text-xl font-semibold">Edit Job Post</span>
                </div>
            </template>

            <template #content>
                <Message v-if="errorMessages.length" severity="error" class="col-span-1 md:col-span-2 mb-2">
                    <ul class="list-disc pl-5">
                        <li v-for="(msg, index) in errorMessages" :key="index">{{ msg }}</li>
                    </ul>
                </Message>
                <form @submit.prevent="submitForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-4">
                        <InputText v-model="form.job_title" placeholder="Job Title" />
                        <Dropdown v-model="form.category_id" :options="categories" optionLabel="name" optionValue="id"
                            placeholder="Select Category" />
                        <AutoComplete v-model="selectedCountry" dropdown="true" optionLabel="country"
                            placeholder="Select Country" fluid :suggestions="filteredCountries" @complete="search">
                            <template #option="slotProps">
                                <div class="flex items-center">
                                    <img :alt="slotProps.option.flag" :src="`${slotProps.option.flag}`"
                                        :class="`flag flag-${slotProps.option.code.toLowerCase()} mr-2`"
                                        style="width: 28px; height: 20px; border-radius: 4px; object-fit: cover" />
                                    <div>{{ slotProps.option.country }}</div>
                                </div>
                            </template>
                        </AutoComplete>
                        <div class="flex items-center gap-2">
                            <InputText v-model="form.currency_code" class="w-24" readonly />
                            <InputNumber v-model="form.salary" placeholder="Salary" :useGrouping="false"
                                class="flex-1" />
                        </div>
                        <Dropdown v-model="form.job_type" :options="jobTypes" placeholder="Job Type" />
                    </div>

                    <div class="flex flex-col gap-4">
                        <InputText v-model="form.job_location" placeholder="Job Location" />
                        <InputText v-model="form.experience_level" placeholder="Experience Level" />
                        <Dropdown v-model="form.employment_status" :options="employmentStatus"
                            placeholder="Employment Status" />
                        <Calendar v-model="form.application_deadline" showIcon placeholder="Application Deadline" />
                        <Dropdown v-model="form.visibility" :options="visibilityOptions" placeholder="Visibility" />
                    </div>

                    <div class="flex flex-col gap-4">
                        <Panel header="Requirements">
                            <Editor v-model="form.requirements" editorStyle="height: 150px"
                                placeholder="Enter requirements..." />
                        </Panel>
                    </div>

                    <div class="flex flex-col gap-4">
                        <Panel header="Benefits">
                            <Editor v-model="form.benefits" editorStyle="height: 150px"
                                placeholder="Enter benefits..." />
                        </Panel>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <Panel header="Job Description">
                            <Editor v-model="form.description" editorStyle="height: 200px"
                                placeholder="Write a job description..." />
                        </Panel>
                    </div>

                    <div class="col-span-1 md:col-span-2 text-right">
                        <Button label="Update Job" icon="pi pi-refresh" type="submit" class="mt-4" />
                    </div>
                </form>
            </template>
        </Card>
    </div>
</template>


<script setup>
import { useRoute } from 'vue-router'
import { computed, ref, onMounted, watch } from 'vue';
import { useAuthStore } from '@/store'
import { useToast } from 'primevue/usetoast'
import Card from 'primevue/card'
import Avatar from 'primevue/avatar'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Editor from 'primevue/editor'
import Dropdown from 'primevue/dropdown'
import Calendar from 'primevue/calendar'
import Button from 'primevue/button'
import Panel from 'primevue/panel'
import AutoComplete from 'primevue/autocomplete'
import Message from 'primevue/message'
import { useRouter } from 'vue-router'
import { storeToRefs } from "pinia";
const router = useRouter()
const toast = useToast()
const route = useRoute()
const authStore = useAuthStore();
const loading = ref(true);
const jobId = route.params.id // assuming route is like /jobs/edit/:id
const { EmployerProfile } = storeToRefs(authStore);
const form = ref({
    id: jobId,
    employer_id: null,
    category_id: null,
    job_title: '',
    description: '',
    country_id: null,
    currency_code: '',
    salary: null,
    job_type: 'full-time',
    job_location: '',
    experience_level: '',
    requirements: '',
    benefits: '',
    employment_status: 'open',
    application_deadline: null,
    visibility: 'public',
});

const selectedCountry = ref();
const filteredCountries = ref();
const countries = ref([]);
const categories = ref([]);

const jobTypes = ['full-time', 'part-time', 'contract', 'internship']
const employmentStatus = ['open', 'closed']
const visibilityOptions = ['public', 'private']

const errors = ref({})
const errorMessages = ref([])

const validateForm = () => {
    errors.value = {}
    errorMessages.value = []

    if (!form.value.job_title) errors.value.job_title = 'Job title is required'
    if (!form.value.category_id) errors.value.category_id = 'Category is required'
    if (!selectedCountry.value) errors.value.country_id = 'Country is required'
    if (!form.value.salary) errors.value.salary = 'Salary is required'
    if (!form.value.job_location) errors.value.job_location = 'Job location is required'
    if (!form.value.experience_level) errors.value.experience_level = 'Experience level is required'
    if (!form.value.requirements) errors.value.requirements = 'Requirements are required'
    if (!form.value.benefits) errors.value.benefits = 'Benefits are required'
    if (!form.value.description) errors.value.description = 'Job description is required'

    errorMessages.value = Object.values(errors.value)
    return errorMessages.value.length === 0
}

const submitForm = async () => {
    if (!validateForm()) return
    // if (form.value.application_deadline) {
    //     const deadline = new Date(form.value.application_deadline)
    //     form.value.application_deadline = deadline.toISOString().split('T')[0] // "YYYY-MM-DD"
    // }
    try {
        const res = await authStore.updateJob(form.value)



        // Update the job in EmployerProfile.value.jobs
        const updatedJob = res.data

        const index = EmployerProfile.value.jobs.findIndex(job => job.id === updatedJob.id)

        if (index !== -1) {
            EmployerProfile.value.jobs[index] = updatedJob
        }
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: res.data.message,
            life: 2000
        })
        // Redirect after slight delay
        setTimeout(() => {
            router.push({ name: 'ManageJobs' })
        }, 500)

    } catch (error) {
        console.log('errr',error)
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to update job post.',
            life: 3000
        })
    }
}


onMounted(async () => {
    try {
        const [resCountries, resCategories, resJob] = await Promise.all([
            authStore.getCountry(),
            authStore.getCategory(),
            authStore.getJobById(jobId)
        ])

        countries.value = resCountries.data
        categories.value = resCategories.data

        const job = resJob.data
        form.value = { ...job }

        selectedCountry.value = countries.value.find(c => c.id === job.country_id)
        loading.value = false
    } catch (error) {
        console.error("Error loading data:", error)
        loading.value = false
    }
})

watch(selectedCountry, (newVal) => {
    form.value.country_id = newVal?.id ?? null
    form.value.currency_code = newVal?.code ?? ''
})

const search = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            filteredCountries.value = [...countries.value];
        } else {
            filteredCountries.value = countries.value.filter((country) =>
                country.country.toLowerCase().startsWith(event.query.toLowerCase())
            );
        }
    }, 250);
}
</script>

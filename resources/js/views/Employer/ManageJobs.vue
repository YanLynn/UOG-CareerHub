<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-primary">Manage Jobs</h1>
            <Button label="Post New Job" icon="pi pi-plus" class="p-button-raised p-button-primary"
                @click="openNewJobForm" />
        </div>

        <!-- 🔹 Filters Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <InputText v-model="filters['job_title'].value" placeholder="🔍 Search Job Title"
                class="p-inputtext-lg w-full" />
            <Dropdown v-model="filters['category_id'].value" :options="categoryOptions" optionLabel="name"
                optionValue="id" placeholder="📂 Filter by Category" class="w-full" />
            <Dropdown v-model="filters['visibility'].value" :options="visibilityOptions" optionLabel="label"
                optionValue="value" placeholder="👁️ Filter by Visibility" class="w-full" />
            <Dropdown v-model="filters['employment_status'].value" :options="employmentStatusOptions"
                optionLabel="label" optionValue="value" placeholder="📌 Filter by Status" class="w-full" />
        </div>

        <!-- 🔹 Job Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Card v-for="job in paginatedJobs" :key="job.id"
                class="shadow-lg hover:shadow-xl transition-transform transform hover:-translate-y-1">
                <template #title>
                    <div class="flex items-center">
                        <Avatar icon="pi pi-briefcase" class="mr-2 bg-blue-500 text-white p-mr-2" />
                        <span class="text-xl font-semibold text-gray-700">{{ job.job_title }}</span>
                    </div>
                </template>

                <template #content>
                    <div class="mb-3">
                        <Tag :value="job.category?.name" class="p-mr-2 p-tag-rounded p-tag-success" />
                        <Tag :value="job.job_type" class="p-tag-rounded p-tag-info" />
                    </div>

                    <p class="text-gray-600"><i class="pi pi-map-marker"></i> {{ job.job_location }}</p>
                    <p class="text-gray-600"><i class="pi pi-calendar"></i> Deadline: {{
                        formatDate(job.application_deadline) }}</p>
                    <div class="flex items-center gap-2 text-gray-700 mt-1">
                        <i class="pi pi-dollar"></i>
                        <span class="font-medium">Salary:</span>

                        <span class="text-gray-800 font-semibold">
                            {{ job.salary.toLocaleString() }}
                        </span>
                        <Tag :value="job.country?.code" severity="info" class="text-xs ml-2" v-if="job.country?.code" />


                    </div>

                    <div class="flex items-center mt-3">
                        <Tag :value="job.visibility.toUpperCase()" :severity="getVisibilityColor(job.visibility)" />
                        <Tag :value="job.employment_status.toUpperCase()"
                            :severity="getEmploymentStatusColor(job.employment_status)" class="ml-2" />
                    </div>
                </template>

                <template #footer>
                    <div class="flex justify-between">
                        <Button icon="pi pi-eye" label="View" class="p-button-text" @click="viewJob(job.id)" />
                        <Button icon="pi pi-pencil" label="Edit" class="p-button-text p-button-warning"
                            @click="editJob(job.id)" />
                        <Button icon="pi pi-trash" label="Delete" class="p-button-text p-button-danger"
                            @click="confirmDelete(job.id)" />
                    </div>
                </template>
            </Card>
        </div>

        <!-- 🔹 Pagination -->
        <Paginator :rows="5" :totalRecords="filteredJobs.length" v-model:first="first" class="mt-6" />

        <!-- 🔹 Delete Confirmation Dialog -->
        <Dialog v-model:visible="deleteDialog" header="Confirm Deletion" modal :closable="false">
            <p>Are you sure you want to delete this job?</p>
            <template #footer>
                <Button label="Cancel" icon="pi pi-times" class="p-button-secondary" @click="deleteDialog = false" />
                <Button label="Delete" icon="pi pi-trash" class="p-button-danger" @click="deleteJob" />
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "@/store";
import { storeToRefs } from "pinia";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Dialog from "primevue/dialog";
import Tag from "primevue/tag";
import Card from "primevue/card";
import Avatar from "primevue/avatar";
import Paginator from "primevue/paginator";
import { useRouter } from "vue-router";
import { useToast } from 'primevue/usetoast'
const toast = useToast()
const authStore = useAuthStore();
const { EmployerProfile } = storeToRefs(authStore);
const router = useRouter();

const loading = ref(true);
const deleteDialog = ref(false);
const selectedJobId = ref(null);
const first = ref(0); // ✅ Pagination state

// ✅ Reference jobs inside EmployerProfile
const jobs = computed(() => EmployerProfile.value?.jobs || []);
const filters = ref({
    global: { value: "" },
    job_title: { value: "" },
    category_id: { value: "" },
    employment_status: { value: "" },
    visibility: { value: "" }
});


const categoryOptions = ref([]);

// ✅ Visibility Options
const visibilityOptions = [
    { label: "All", value: "" },
    { label: "Public", value: "public" },
    { label: "Private", value: "private" }
];

// ✅ Employment Status Options
const employmentStatusOptions = [
    { label: "All", value: "" },
    { label: "Open", value: "open" },
    { label: "Closed", value: "closed" }
];



onMounted(async () => {
    const cat = await authStore.getCategory()
    categoryOptions.value = [
        { id: '', name: 'All' }, // 👈 Add this line
        ...cat.data
    ]



})

// ✅ Filtered Jobs
const filteredJobs = computed(() => {
    console.log('filters',filters.value)
    return jobs.value.filter(job => {
        const matchesTitle = filters.value.job_title.value
            ? job.job_title.toLowerCase().includes(filters.value.job_title.value.toLowerCase())
            : true;
        const matchesCategory = filters.value.category_id.value ? job.category_id === filters.value.category_id.value : true;
        const matchesVisibility = filters.value.visibility.value ? job.visibility === filters.value.visibility.value : true;
        const matchesStatus = filters.value.employment_status.value ? job.employment_status === filters.value.employment_status.value : true;

        return matchesTitle && matchesCategory && matchesVisibility && matchesStatus;
    });
});

// ✅ Paginated Jobs (Only show current page)
const paginatedJobs = computed(() => {
    return filteredJobs.value.slice(first.value, first.value + 6);
});

// 🔹 Open New Job Form
const openNewJobForm = () => {
    router.push("/employer/create-job");
};

// 🔹 View Job
const viewJob = (jobId) => {
    router.push(`/employer/manage-jobs/view/${jobId}`);
};

// 🔹 Edit Job
const editJob = (jobId) => {
    router.push(`/employer/manage-jobs/edit/${jobId}`);
};

// 🔹 Confirm Delete Job
const confirmDelete = (jobId) => {
    selectedJobId.value = jobId;
    deleteDialog.value = true;
};

// 🔹 Delete Job
const deleteJob = async () => {

    try {
        await authStore.deleteJob(selectedJobId.value);
        EmployerProfile.value.jobs = EmployerProfile.value.jobs.filter(
            job => job.id !== selectedJobId.value
        )

        deleteDialog.value = false;
        toast.add({
            severity: 'success',
            summary: 'Deleted',
            detail: 'Job has been deleted.',
            life: 3000
        })
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Deleted',
            detail: error,
            life: 3000
        })
    }
    // authStore.deleteJob(selectedJobId.value);

};

// 🔹 Get Visibility Color
const getVisibilityColor = (visibility) => {
    return visibility === "public" ? "success" : "warning";
};

// 🔹 Get Employment Status Color
const getEmploymentStatusColor = (status) => {
    return status === "open" ? "success" : "danger";
};

// 🔹 Format Date
const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

// Fetch jobs when component loads
setTimeout(() => {
    loading.value = false;
}, 1000);
</script>

<template>
    <div class="p-6 container mx-auto">
        <h2 class="text-2xl font-bold text-blue-900 mb-6 flex items-center gap-2">
            <i class="pi pi-file" /> Applications for this Job
        </h2>

        <DataTable :value="applications" paginator :rows="5" class="shadow-lg rounded-2xl border border-gray-200">
            <Column field="name" header="Applicant">
                <template #body="slotProps">
                    <div
                        class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 transition duration-200 ease-in-out">
                        <!-- Avatar with border ring and fallback -->
                        <Avatar :image="slotProps.data.jobseeker.profile_img || 'https://placehold.co/100x100?text=👤'"
                            shape="circle" class="border border-blue-400 shadow-sm" size="xlarge" />

                        <!-- Name & Summary Block -->
                        <div class="flex flex-col justify-center">
                            <span class="text-lg font-bold text-gray-800">
                                {{ slotProps.data.jobseeker.user.name }}
                            </span>
                            <span class="text-sm text-gray-600 line-clamp-2">
                                {{ slotProps.data.jobseeker.personal_summary }}
                            </span>
                            <span class="text-sm text-gray-600 line-clamp-2">
                                <i class="pi pi-envelope text-blue-500" />
                                {{ slotProps.data.jobseeker.user.email }}
                            </span>
                        </div>
                    </div>
                </template>
            </Column>


            <Column field="job_title" header="Job Title">
                <template #body="slotProps">
                    <div class="flex items-center gap-2">
                        <span class="text-blue-700 underline hover:cursor-pointer"
                            @click="viewJob(slotProps.data.job.id)">{{ slotProps.data.job.job_title }}</span>
                    </div>
                </template>
            </Column>

            <Column field="submitted_at" header="Submitted">
                <template #body="slotProps">
                    <div class="flex items-center gap-2">
                        <i class="pi pi-calendar text-indigo-500" />
                        {{ formatDate(slotProps.data.job_apply_date) }}
                    </div>
                </template>
            </Column>

            <Column field="status" header="Status">
                <template #body="slotProps">
                    <Tag :value="slotProps.data.status" :severity="getStatusColor(slotProps.data.status)"
                        class="uppercase" />
                </template>
            </Column>

            <Column header="Actions">
                <template #body="slotProps">
                    <div class="flex flex-col items-start gap-2">
                        <!-- Top action buttons with icons and tooltips -->
                        <div class="flex gap-2">
                            <!-- <Button
                    icon="pi pi-eye"
                    size="small"
                    rounded
                    severity="info"
                    v-tooltip.top="'View Details'"
                    @click="viewApplication(slotProps.data)"
                /> -->
                            <Button icon="pi pi-check-circle" size="small" severity="success"
                                v-tooltip.top="'Approve Application'"
                                @click="showActionDialog('approve', slotProps.data)" />
                            <Button icon="pi pi-times-circle" size="small" severity="danger"
                                v-tooltip.top="'Reject Application'"
                                @click="showActionDialog('reject', slotProps.data)" />
                            <Button v-if="slotProps.data.status == 'approved'" icon="pi pi-comments" size="small"
                                v-tooltip.top="'Message'" outlined class="text-sm w-full justify-start"
                                @click="messageJobseeker(slotProps.data)" />
                        </div>

                        <!-- Message Button -->

                    </div>
                </template>
            </Column>

        </DataTable>
    </div>
    <template>
        <Dialog v-model:visible="visible" :header="dialogHeader" modal :style="{ width: '30vw' }">
            <div class="p-text-secondary mb-3">
                <p><strong>{{ dialogMessage }}</strong></p>
                <ul class="list-disc ml-5 mt-2">
                    <li v-for="item in dialogDetails" :key="item">{{ item }}</li>
                </ul>
            </div>

            <template #footer>
                <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="visible = false" />
                <Button :label="confirmLabel" :icon="confirmIcon" @click="handleConfirm" autofocus />
            </template>
        </Dialog>
    </template>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/store';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import { useToast } from 'primevue/usetoast'
const toast = useToast()

const router = useRouter();
const authStore = useAuthStore();
const applications = ref([]);
const visible = ref(false);
const actionType = ref('approve');
const selectedApplicant = ref(null);

const dialogHeader = ref('');
const dialogMessage = ref('');
const dialogDetails = ref([]);
const confirmLabel = ref('');
const confirmIcon = ref('');

onMounted(async () => {
    loadData()
});


const showActionDialog = (type, applicant) => {
    actionType.value = type;
    selectedApplicant.value = applicant;
    visible.value = true;

    if (type === 'approve') {
        dialogHeader.value = 'Approve Applicant';
        dialogMessage.value = 'Are you sure you want to approve this applicant?';
        dialogDetails.value = [
            'An approval email will be sent automatically.',
            'You’ll be able to message the applicant directly within the system.'
        ];
        confirmLabel.value = 'Confirm';
        confirmIcon.value = 'pi pi-check';
    } else if (type === 'reject') {
        dialogHeader.value = 'Reject Applicant';
        dialogMessage.value = 'Are you sure you want to reject this applicant?';
        dialogDetails.value = null;
        // dialogDetails.value = [
        //     'No email will be sent to the applicant.',
        //     'The applicant will remain visible in your list.'
        // ];
        confirmLabel.value = 'Reject';
        confirmIcon.value = 'pi pi-times-circle';
    }
};

const handleConfirm = async () => {
    visible.value = false;
    if (actionType.value === 'approve') {
        const res = await authStore.updateJobApplicationStatus({
            job_id: selectedApplicant.value.job.id,
            jobseeker_id: selectedApplicant.value.jobseeker.id,
            status: 'approved',
            application: selectedApplicant.value
        })
        toast.add({
            severity: 'success',
            summary: 'Status Update!',
            detail: res.message,
            life: 3000
        })
    } else if (actionType.value === 'reject') {
        const res = await authStore.updateJobApplicationStatus({
            job_id: selectedApplicant.value.job.id,
            jobseeker_id: selectedApplicant.value.jobseeker.id,
            status: 'rejected'
        })
        toast.add({
            severity: 'success',
            summary: 'Status Update!',
            detail: res.message,
            life: 3000
        })
    }
    loadData()
};



const formatDate = (dateStr) => new Date(dateStr).toLocaleDateString();

const getStatusColor = (status) => {
    switch (status) {
        case 'pending': return 'warning';
        case 'shortlisted': return 'info';
        case 'approved': return 'success';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
};

const viewApplication = (applicant) => {
    console.log('View:', applicant);
};



const rejectApplication = (applicant) => {
    console.log('Rejected:', applicant);
    // Call backend to set status to "rejected"
};

const messageJobseeker = (applicant) => {
   // router.push('/chat-room');
    router.push({ path: '/chat-room', query:  applicant  });
    // Open chat modal / redirect to messaging component
};



const loadData = async () => {
    try {
        const res = await authStore.getApplicationsByJob();
        applications.value = res.data;
    } catch (err) {
        console.error('Failed to fetch applications:', err);
    }
}


const viewJob = (jobId) => {
    router.push(`/employer/manage-jobs/view/${jobId}`);
};


</script>

<style scoped>
.p-datatable .p-datatable-thead>tr>th {
    background: #f0f4ff;
    color: #1e3a8a;
    font-weight: 600;
    font-size: 0.95rem;
}

.p-avatar img {
    object-fit: cover;
    width: 100px;
    height: 40px;
}
</style>

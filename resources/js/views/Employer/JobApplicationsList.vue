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
                        </div>
                    </div>
                </template>
            </Column>

            <Column field="email" header="Email">
                <template #body="slotProps">
                    <div class="flex items-center gap-2">
                        <i class="pi pi-envelope text-blue-500" />
                        <span class="text-gray-700">{{ slotProps.data.jobseeker.user.email }}</span>
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
                <Button
                    icon="pi pi-eye"
                    size="small"
                    rounded
                    severity="info"
                    v-tooltip.top="'View Details'"
                    @click="viewApplication(slotProps.data)"
                />
                <Button
                    icon="pi pi-check-circle"
                    size="small"
                    rounded
                    severity="success"
                    v-tooltip.top="'Approve Application'"
                    @click="approveApplication(slotProps.data)"
                />
                <Button
                    icon="pi pi-times-circle"
                    size="small"
                    rounded
                    severity="danger"
                    v-tooltip.top="'Reject Application'"
                    @click="rejectApplication(slotProps.data)"
                />
            </div>

            <!-- Message Button -->
            <Button
                icon="pi pi-comments"
                label="Message"
                size="small"
                outlined
                class="text-sm w-full justify-start"
                @click="messageJobseeker(slotProps.data)"
            />
        </div>
    </template>
</Column>

        </DataTable>
    </div>
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

const router = useRouter();
const authStore = useAuthStore();
const applications = ref([]);

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

const approveApplication = (applicant) => {
    console.log('Approved:', applicant);
    // Call backend to set status to "approved"
};

const rejectApplication = (applicant) => {
    console.log('Rejected:', applicant);
    // Call backend to set status to "rejected"
};

const messageJobseeker = (applicant) => {
    console.log('Message:', applicant);
    router.push('/chat-room');
    // Open chat modal / redirect to messaging component
};

onMounted(async () => {
    try {
        const res = await authStore.getApplicationsByJob();
        applications.value = res.data;
    } catch (err) {
        console.error('Failed to fetch applications:', err);
    }
});
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

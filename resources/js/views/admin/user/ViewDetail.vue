<template>
    <div class="p-6 space-y-4">
        <Card>
            <template #title>
                <div class="flex items-center justify-between">
                    <div>
                        👤 User Details - {{ user.name }}
                    </div>
                    <Tag :value="user.status" :severity="user.status === 'active' ? 'success' : 'danger'" />
                </div>
            </template>
            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong>Email:</strong> {{ user.email }}</div>
                    <div><strong>Role:</strong> {{ user.role }}</div>
                    <div><strong>Country:</strong> {{ user.country }}</div>
                    <div><strong>Registered:</strong> {{ user.registered_at }}</div>
                </div>
            </template>
        </Card>

        <TabView>
            <TabPanel header="Jobs Applied" v-if="user.role === 'jobseeker'">
                <DataTable :value="jobsApplied" :paginator="true" :rows="5">
                    <Column field="title" header="Job Title" />
                    <Column field="employer" header="Employer" />
                    <Column field="status" header="Status">
                        <template #body="{ data }">
                            <Tag :value="data.status" :severity="statusColor(data.status)" />
                        </template>
                    </Column>
                    <Column field="applied_at" header="Applied At" />
                </DataTable>
            </TabPanel>

            <TabPanel header="Jobs Posted" v-if="user.role === 'employer'">
                <DataTable :value="jobsPosted" :paginator="true" :rows="5">
                    <Column field="title" header="Job Title" />
                    <Column field="location" header="Location" />
                    <Column field="status" header="Status">
                        <template #body="{ data }">
                            <Tag :value="data.status" :severity="data.status === 'open' ? 'success' : 'warning'" />
                        </template>
                    </Column>
                    <Column field="posted_at" header="Posted At" />
                </DataTable>
            </TabPanel>
        </TabView>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Card from 'primevue/card';
import Tag from 'primevue/tag';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

const user = ref({
    name: 'Alice Johnson',
    email: 'alice@gmail.com',
    role: 'jobseeker',
    status: 'active',
    country: 'Singapore',
    registered_at: '2025-04-15'
});

const jobsApplied = ref([
    { title: 'Digital Marketer', employer: 'GlobalTech Ltd.', status: 'approved', applied_at: '2025-04-16' },
    { title: 'Content Writer', employer: 'InnoWorks', status: 'pending', applied_at: '2025-04-20' }
]);

const jobsPosted = ref([
    { title: 'UX Designer', location: 'Remote', status: 'open', posted_at: '2025-04-12' },
    { title: 'Project Manager', location: 'Singapore', status: 'closed', posted_at: '2025-03-28' }
]);

const statusColor = (status) => {
    switch (status) {
        case 'approved': return 'success';
        case 'pending': return 'info';
        case 'rejected': return 'danger';
        default: return 'warning';
    }
};
</script>

<style scoped>
.grid div {
    font-size: 0.95rem;
}
</style>

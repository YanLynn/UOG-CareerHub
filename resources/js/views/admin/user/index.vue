<template>
    <div class="p-6 space-y-6">



        <!-- User Management -->
        <Card>
            <template #title>👤 User Management</template>
            <template #content>
                <div class="flex flex-col md:flex-row items-center gap-4 mb-4">
                    <Dropdown v-model="selectedRole" :options="roles" optionLabel="label" placeholder="Filter by Role"
                        class="w-60" />
                    <InputText v-model="searchEmail" placeholder="Search by Email" class="w-full md:w-72" />
                </div>

                <DataTable :value="filteredUsers" :paginator="true" :rows="5">
                    <Column field="name" header="Name" />
                    <Column field="email" header="Email" />
                    <Column field="role" header="Role">
                        <template #body="{ data }">
                            <Tag :value="data.role" :severity="data.role === 'Employer' ? 'warn' : 'success'" />
                        </template>
                    </Column>
                    <Column field="last_login" header="Last Login" />

                    <Column field="status" header="Status">
                        <template #body="{ data }">
                            <Tag :value="data.status" :severity="data.status === 'active' ? 'success' : 'danger'" />
                        </template>
                    </Column>
                    <Column header="Actions">
                        <template #body="{ data }">
                            <!-- <Button icon="pi pi-eye" class="p-button-text p-button-sm" @click="viewUser(data)"
                                v-if="['Employer', 'JobSeeker'].includes(data.role)" /> -->
                            <Button icon="pi pi-user-edit" class="p-button-text p-button-sm text-yellow-600"
                                @click="edit(data)" :disabled="data.email === 'admin@gmail.com'" />
                            <Button icon="pi pi-trash" class="p-button-text p-button-sm text-red-600"
                                @click="deleteUser(data)" :disabled="data.email === 'admin@gmail.com'" />
                        </template>
                    </Column>
                </DataTable>
            </template>
        </Card>
    </div>
    <Dialog v-model:visible="editDialog" modal header="Edit User" :style="{ width: '400px' }">
        <div class="flex flex-col gap-4">
            <InputText v-model="editUser.name" placeholder="Name" />
            <InputText v-model="editUser.email" placeholder="Email" />
            <Dropdown v-model="editUser.role" :options="roleOptions" optionLabel="label" optionValue="value"
                placeholder="Select Role" />
                <Dropdown
            v-model="editUser.status"
            :options="statusOptions"
            optionLabel="label"
            optionValue="value"
            placeholder="Select Status"
        />
        </div>

        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="editDialog = false" />
            <Button label="Save" icon="pi pi-check" @click="saveEdit" />
        </template>
    </Dialog>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Card from 'primevue/card';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import { useAuthStore } from '@/store';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import Dialog from 'primevue/dialog';


const editDialog = ref(false);
const editUser = ref({});

const authStore = useAuthStore();
const confirm = useConfirm();
const toast = useToast();
const loading = ref(true);
const users = ref([]);

const roles = ref([
    { label: 'All', value: null },
    { label: 'JobSeeker', value: 'JobSeeker' },
    { label: 'Employer', value: 'Employer' },
    { label: 'Admin', value: 'Admin' }
]);

const roleOptions = [
    { label: 'JobSeeker', value: 'JobSeeker' },
    { label: 'Employer', value: 'Employer' }
];
const statusOptions = [
    { label: 'Active', value: 'active' },
    { label: 'Inactive', value: 'inactive' }
];
const selectedRole = ref();
const searchEmail = ref('');

const filteredUsers = computed(() => {
    return users.value.filter(user => {
        const matchesRole = selectedRole.value?.value ? user.role === selectedRole.value?.value : true;
        const matchesEmail = user.email.toLowerCase().includes(searchEmail.value.toLowerCase());
        return matchesRole && matchesEmail;
    });
});

onMounted(async () => {
    try {
        const response = await authStore.userList();
        if (Array.isArray(response) && response.length > 0) {
            users.value = response.map(user => ({
                id: user?.id,
                name: user?.name || 'N/A',
                email: user?.email || 'N/A',
                last_login: user?.last_login || 'N/A',
                role: user?.role || 'Unknown',
                status: user?.status || 'N/A'
            }));
        } else {
            console.warn('No users found, initializing with empty array.');
            users.value = [];
        }
    } catch (error) {
        console.error('Failed to fetch users:', error);
        users.value = [];
    } finally {
        loading.value = false;
    }
});

const viewUser = (user) => {
    console.log("View", user);
};

const edit = (user) => {
    editUser.value = { ...user };
    editDialog.value = true;
};

const saveEdit = async () => {
    try {
        // TODO: send update request via API
        console.log('Saving user:', editUser.value);

        toast.add({ severity: 'success', summary: 'Updated', detail: 'User info updated successfully', life: 3000 });
        editDialog.value = false;
    } catch (error) {
        console.error('Edit failed:', error);
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update user', life: 3000 });
    }
};






const deleteUser = (user) => {
    console.log("Delete", user);
};







</script>

<style scoped>
h2 {
    margin-bottom: 1rem;
}
</style>

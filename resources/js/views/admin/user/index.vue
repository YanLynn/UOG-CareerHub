<template>
    <div class="card">
        <ConfirmDialog></ConfirmDialog>
        <ReusableDataTable :data="users" :loading="loading" :columns="columns"
            :globalFilterFields="['name', 'email', 'role']">
            <!-- Define the slot for the Actions column -->
            <template #body-actions="{ data }">
                <button class="btn-blue mx-2" @click="editUser(data.id), visible = true">Edit</button>
                <button class="btn-red" @click="deleteUser(data.id)" label="Delete" severity="danger">Delete</button>
            </template>
        </ReusableDataTable>
        <Drawer v-model:visible="visible" header="Edit User" position="right" class="!w-full md:!w-80 lg:!w-[30rem]">
            <p>user id = </p>
        </Drawer>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/store';
import ReusableDataTable from '../../../components/ReusableDataTable.vue';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const authStore = useAuthStore();
const users = ref([]);
const loading = ref(true);
const visible = ref(false);
const confirm = useConfirm();
const toast = useToast();

const columns = ref([
    { field: 'name', header: 'Name' },
    { field: 'email', header: 'Email' },
    { field: 'last_login', header: 'Last Login' },
    { field: 'role', header: 'Role' },
    { field: 'actions', header: 'Actions' }
]);

onMounted(async () => {
    try {
        const response = await authStore.userList();
        if (Array.isArray(response) && response.length > 0) {
            users.value = response.map(user => ({
                id: user?.id,
                name: user?.name || 'N/A',
                email: user?.email || 'N/A',
                last_login: user?.last_login || 'N/A',
                role: user?.role || 'Unknown'
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


const editUser = (userId) => {
    console.log(`Edit user with ID: ${userId}`);
};

const deleteUser = (userId) => {

    confirm.require({
        message: 'Do you want to delete this record?',
        header: 'Danger Zone',
        icon: 'pi pi-info-circle',
        rejectLabel: 'Cancel',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Delete',
            severity: 'danger'
        },
        accept: () => {
            console.log(`Delete user with ID: ${userId}`);
            users.value = users.value.filter(user => user.id !== userId);
            toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Record deleted', life: 3000 });
        },
        reject: () => {
            toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
        }
    });

};

</script>

<style scoped>
.btn {
    margin-right: 5px;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    border: none;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
}
</style>

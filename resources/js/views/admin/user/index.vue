<template>
    <div class="card">
        <ReusableDataTable :data="users" :loading="loading" :columns="columns"
            :globalFilterFields="['name', 'email', 'role']">
            <!-- Define the slot for the Actions column -->
            <template #body-actions="{ data }">
                <button class="btn-blue mx-2" @click="editUser(data.id)">Edit</button>
                <button class="btn-red" @click="deleteUser(data.id)">Delete</button>
            </template>
        </ReusableDataTable>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/store';
import ReusableDataTable from '../../../components/ReusableDataTable.vue';

const authStore = useAuthStore();
const roles = ref(['Admin', 'Employer', 'JobSeeker']);
const users = ref([]);
const loading = ref(true);

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
    if (confirm('Are you sure you want to delete this user?')) {
        console.log(`Delete user with ID: ${userId}`);
        users.value = users.value.filter(user => user.id !== userId);
    }
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

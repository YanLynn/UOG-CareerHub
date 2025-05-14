<template>
    <div class="p-6 space-y-6 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">⚙️ Employer Settings</h2>

        <!-- Password Settings -->
        <Card>
            <template #title>🔐 Change Password</template>
            <template #content>
                <div class="space-y-4 max-w-md">
                    <Password v-model="password.old" placeholder="Current Password" toggleMask class="w-full" fluid/>
                    <Password v-model="password.new" placeholder="New Password" toggleMask class="w-full" fluid />
                    <Password v-model="password.confirm" placeholder="Confirm New Password" toggleMask class="w-full" fluid/>
                    <Button label="Change Password" icon="pi pi-key" @click="changePassword" />
                </div>
            </template>
        </Card>

        <!-- Notification Preferences -->
        <Card>
            <template #title>🔔 Notifications</template>
            <template #content>
                <div class="flex flex-col space-y-2">
                    <div class="flex items-center justify-between">
                        <span>Email notifications for new applications</span>
                        <InputSwitch v-model="notifications.applicationEmails" />
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Weekly summary reports</span>
                        <InputSwitch v-model="notifications.weeklySummary" />
                    </div>
                    <Button label="Save Preferences" icon="pi pi-check" @click="saveNotifications" />
                </div>
            </template>
        </Card>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Password from 'primevue/password';
import Button from 'primevue/button';
import InputSwitch from 'primevue/inputswitch';

const profile = ref({
    name: '',
    company: '',
    email: '',
    bio: ''
});

const password = ref({
    old: '',
    new: '',
    confirm: ''
});

const notifications = ref({
    applicationEmails: true,
    weeklySummary: false
});

const updateProfile = () => {
    console.log('Profile updated:', profile.value);
};

const changePassword = () => {
    console.log('Password changed:', password.value);
};

const saveNotifications = () => {
    console.log('Notifications saved:', notifications.value);
};
</script>

<style scoped>
h2 {
    margin-bottom: 1rem;
}
</style>

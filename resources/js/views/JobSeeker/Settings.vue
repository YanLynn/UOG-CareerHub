<template>
    <div class="mt-4">
        <Card class="shadow-md hover:shadow-lg transition-shadow duration-200">
            <template #content>
                <div class="card">
                    <div class="flex mb-2 gap-2 justify-end">
                        <Button @click="setTab('account')" rounded label="1" class="w-8 h-8 p-0" :outlined="activeTab !== 'account'" />
                    </div>

                    <Tabs v-model:value="activeTab">
                        <TabList>
                            <Tab value="account">Account</Tab>
                            <Tab value="security">Security</Tab>
                        </TabList>
                        <TabPanels>
                            <!-- Account Settings -->
                            <TabPanel value="account" class="max-w-md mx-auto">
                                <div v-if="user" class="p-3 border rounded-lg flex justify-between items-center">
                                    <div>
                                        <span class="text-gray-600 text-sm">Email</span>
                                        <p class="text-black font-medium">{{ user.email || 'No email found' }}</p>
                                    </div>
                                    <Button icon="pi pi-pencil" class="p-button-text text-gray-500" @click="emailDrawer = true" />
                                </div>
                                <div v-else class="text-gray-500 text-center p-3">
                                    Loading user data...
                                </div>
                            </TabPanel>

                            <!-- Security Settings -->
                            <TabPanel value="security" class="max-w-md mx-auto">
                                <div class="p-3 border rounded-lg flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Password</span>
                                    <Button icon="pi pi-pencil" class="p-button-text text-gray-500" @click="passwordDrawer = true" />
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </Tabs>
                </div>
            </template>
        </Card>
    </div>

    <!-- Change Email Drawer -->
    <Drawer v-model:visible="emailDrawer" header="Change Email" position="right"
        class="!w-full md:!w-96 lg:!w-[30rem] dark:!bg-gray-800">
        <div class="p-6">
            <form @submit.prevent="updateEmail">
                <div class="mb-4">
                    <label class="block text-sm font-medium">New Email</label>
                    <InputText v-model="email" type="email" class="w-full" placeholder="Enter new email" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Current Password</label>
                    <Password v-model="currentPassword" fluid  toggleMask class="w-full" placeholder="Enter your password" required />
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium">Confirm Password</label>
                    <Password v-model="confirmPassword" fluid  toggleMask class="w-full" placeholder="Confirm your password" required />
                </div>
                <Message v-if="errorMessage" severity="error" class="mb-4">{{ errorMessage }}</Message>
                <Message v-if="successMessage" severity="success" class="mb-4">{{ successMessage }}</Message>
                <Button :loading="loading" label="Update Email" type="submit" class="w-full p-button-lg" />
            </form>
        </div>
    </Drawer>

    <!-- Change Password Drawer -->
    <Drawer v-model:visible="passwordDrawer" header="Change Password" position="right"
        class="!w-full md:!w-80 lg:!w-[30rem] dark:!bg-gray-800">
        <div class="p-6">
            <form @submit.prevent="updatePassword">
                <div class="mb-4">
                    <label class="block text-sm font-medium">Current Password</label>
                    <Password v-model="currentPassword" fluid  toggleMask class="w-full" placeholder="Enter current password"
                        required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">New Password</label>
                    <Password v-model="newPassword" fluid  toggleMask class="w-full" placeholder="Enter new password"
                        required />
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium">Confirm New Password</label>
                    <Password v-model="confirmPassword" fluid  toggleMask class="w-full" placeholder="Confirm new password"
                        required />
                </div>
                <Message v-if="passwordError" severity="error" class="mb-4">{{ passwordError }}</Message>
                <Message v-if="passwordSuccess" severity="success" class="mb-4">{{ passwordSuccess }}</Message>
                <Button :loading="loadingPassword" label="Update Password" type="submit" class="w-full p-button-lg" />
            </form>
        </div>
    </Drawer>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Tabs from 'primevue/tabs'
import TabList from 'primevue/tablist'
import Tab from 'primevue/tab'
import TabPanels from 'primevue/tabpanels'
import TabPanel from 'primevue/tabpanel'
import Drawer from 'primevue/drawer'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Message from 'primevue/message'
import { useAuthStore } from '@/store'

const authStore = useAuthStore()
const route = useRoute()
const router = useRouter()

const emailDrawer = ref(false)
const passwordDrawer = ref(false)
const email = ref('')
const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')
const loading = ref(false)
const loadingPassword = ref(false)
const errorMessage = ref('')
const successMessage = ref('')
const passwordError = ref('')
const passwordSuccess = ref('')
const activeTab = ref('account')

const user = computed(() => authStore.currentUser ?? null)

// Watch for URL query changes and update the tab
watch(() => route.query.tab, (newTab) => {
    if (newTab) {
        activeTab.value = newTab
    }
}, { immediate: true })

// Function to change the tab and update URL
function setTab(tabName) {
    activeTab.value = tabName
    router.replace({ query: { tab: tabName } }) // Update URL
}

onMounted(async () => {
    try {
        await authStore.currentUser
    } catch (error) {
        console.error('Failed to fetch user:', error)
    }
})

async function updateEmail() {
    loading.value = true
    errorMessage.value = ''
    successMessage.value = ''

    if (currentPassword.value !== confirmPassword.value) {
        errorMessage.value = 'Passwords do not match'
        loading.value = false
        return
    }

    try {
        await authStore.changeEmail({ email: email.value, password: currentPassword.value })
        successMessage.value = 'Email updated successfully!'
        setTimeout(() => {
            emailDrawer.value = false
        }, 2000)
    } catch (error) {
        errorMessage.value = error.response?.data?.message || "Failed to update email"
    } finally {
        loading.value = false
    }
}

async function updatePassword() {
    loadingPassword.value = true
    passwordError.value = ''
    passwordSuccess.value = ''

    if (newPassword.value !== confirmPassword.value) {
        passwordError.value = 'Passwords do not match'
        loadingPassword.value = false
        return
    }

    try {
        await authStore.changePassword({
            current_password: currentPassword.value,
            new_password: newPassword.value
        })
        passwordSuccess.value = 'Password updated successfully!'
        setTimeout(() => {
            passwordDrawer.value = false
        }, 2000)
    } catch (error) {

        passwordError.value = error.response?.data?.message || "Failed to update password"
    } finally {
        loadingPassword.value = false
    }
}
</script>

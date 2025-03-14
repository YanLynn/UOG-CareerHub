<template>
    <div class="container mx-auto p-6">
        <div class="dark:border-surface-700 bg-surface-0 dark:bg-surface-900 shadow-sm rounded-lg" v-if="loading">
            <div class="flex mb-4">
                <Skeleton width="100%" height="150px"></Skeleton>
            </div>
        </div>
        <!-- Profile Header -->
        <div v-else
            class="dark:!bg-gray-800 bg-gradient-to-r from-green-50 to-blue-50 shadow-sm rounded-lg p-6 flex items-center space-x-6 hover:shadow-md relative">
            <!-- Profile Picture -->
            <div class="relative w-24 h-24">
                <img :src="profile.profile_image || 'https://placehold.co/600x400'" alt="Profile Picture"
                    class="rounded-full w-24 h-24 object-cover border" />
            </div>

            <!-- Profile Info -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ profile.name }}
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    <i class="pi pi-briefcase"></i>
                    {{ profile.currentJob || 'Seeking Job' }}
                </p>
                <p class="text-gray-600 dark:text-gray-300">
                    <i class="pi pi-map-marker"></i>
                    {{ profile.location }}
                </p>
                <p class="text-blue-600 dark:text-blue-400 font-medium hover:underline cursor-pointer"
                    @click="openEditProfile">
                    <i class="pi pi-pencil"></i>
                    Edit Profile
                </p>
            </div>

            <!-- Navigation + Hamburger, pinned to bottom-right -->
            <div class="absolute bottom-0 right-0 flex items-center space-x-4 m-2 pr-2">
                <!-- Desktop Navigation (hidden on mobile) -->
                <nav class="hidden md:flex space-x-4">
                    <router-link to="/jobSeeker/profile"
                        class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-400/10 dark:hover:bg-slate-700/10 rounded-md p-2 transition-colors duration-200">
                        <i class="pi pi-user" style="font-size:13px"></i> Profile
                    </router-link>

                    <router-link to="/jobSeeker/job-application"
                        class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-400/10 dark:hover:bg-slate-700/10 rounded-md p-2 transition-colors duration-200">
                        <i class="pi pi-star" style="font-size:13px"></i> Job Application
                    </router-link>
                    <router-link to="/jobSeeker/settings"
                        class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-400/10 dark:hover:bg-slate-700/10 rounded-md p-2 transition-colors duration-200">
                        <i class="pi pi-cog" style="font-size:13px"></i> Settings
                    </router-link>
                    <span
                        class="relative text-gray-400 dark:text-gray-500 cursor-not-allowed rounded-md p-2 transition-colors duration-200">
                        <i class="pi pi-eye" style="font-size:13px"></i>
                        Profile Viewer
                        <i class="pi pi-crown text-yellow-500 ml-1 absolute top-0 right-2" style="font-size:13px"></i>
                    </span>
                </nav>

                <!-- Hamburger Icon (mobile only) -->
                <button
                    class="md:hidden p-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                    @click="toggleMobileMenu">
                    <i class="pi pi-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu (overlay or drawer) -->
        <transition name="fade">
            <div v-if="mobileMenuOpen" class="fixed inset-0 z-50 bg-white dark:bg-gray-900 p-4 flex flex-col">
                <!-- Close Button -->
                <button
                    class="self-end mb-4 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                    @click="toggleMobileMenu">
                    <i class="pi pi-times text-xl"></i>
                </button>

                <!-- Mobile Navigation Links -->
                <nav class="space-y-4">
                    <router-link to="/jobSeeker/profile"
                        class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:underline transition-colors duration-200"
                        @click="toggleMobileMenu">
                        <i class="pi pi-user" style="font-size:13px"></i> Profile
                    </router-link>
                    <router-link to="/jobSeeker/job-search"
                        class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:underline transition-colors duration-200"
                        @click="toggleMobileMenu">
                        <i class="pi pi-search" style="font-size:13px"></i> Job Search
                    </router-link>
                    <router-link to="/jobSeeker/job-application"
                        class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:underline transition-colors duration-200"
                        @click="toggleMobileMenu">
                        <i class="pi pi-star" style="font-size:13px"></i> Job Application
                    </router-link>
                    <router-link to="/jobSeeker/settings"
                        class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:underline transition-colors duration-200"
                        @click="toggleMobileMenu">
                        <i class="pi pi-cog" style="font-size:13px"></i> Settings
                    </router-link>
                    <span
                        class="block text-gray-400 dark:text-gray-500 cursor-not-allowed transition-colors duration-200"
                        @click.prevent="toggleMobileMenu">
                        <i class="pi pi-eye" style="font-size:13px"></i> Profile Viewer
                        <i class="pi pi-crown text-yellow-500 ml-1" style="font-size:13px"></i>
                    </span>
                </nav>
            </div>
        </transition>

        <!-- Nested child route will appear here -->
        <router-view></router-view>

        <!-- Edit Profile Drawer -->
        <Drawer v-model:visible="visible" header="Edit Profile" position="right"
            class="!w-full md:!w-80 lg:!w-[30rem] dark:!bg-gray-800">
            <div class="p-4">
                <form @submit.prevent="updateProfile">
                    <div class="flex items-center space-x-4">
                        <div class="relative w-24 h-24">
                            <!-- Profile Image -->
                            <img :src="form.profile_image || 'https://placehold.co/100x100'" alt="Profile Picture"
                                class="rounded-full w-24 h-24 object-cover border" />

                            <!-- Hidden File Input Overlay -->
                            <input type="file" ref="fileInput"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                @change="uploadProfileImage" />

                            <!-- Edit Icon Overlay -->
                            <i class="pi pi-pencil absolute bottom-0 right-0 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full p-2 shadow cursor-pointer"
                                @click="triggerFileInput"></i>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                        <InputText v-model="form.name"
                            class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                            placeholder="Enter full name" :class="{ 'border-red-500': v$.name.$error }" />
                        <small v-if="v$.name.$error" class="text-red-500">Name must be at least 3 characters.</small>
                    </div>

                    <div class="mt-4">
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                        <AutoComplete v-model="selectedCountry" :suggestions="filteredCountries"
                            @complete="searchCountry" field="name" @item-select="onCountrySelect" class="w-full"
                            input-class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                            dropdown-class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 shadow-sm rounded-md overflow-hidden"
                            placeholder="Search country..." :class="{ 'border-red-500': v$.location.$error }">
                            <template #option="slotProps">
                                <div class="flex items-center">
                                    <div>{{ slotProps.option.name }}</div>
                                </div>
                            </template>
                        </AutoComplete>
                        <small v-if="v$.location.$error" class="text-red-500">Location is required.</small>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <Button label="Cancel" class="p-button-secondary mr-2" @click="visible = false" />
                        <Button label="Save Changes" type="submit" class="p-button-success" />
                    </div>
                </form>
            </div>
        </Drawer>
    </div>
</template>
<script setup>
/* ---------------------- Imports ---------------------- */
import { ref, onMounted } from 'vue'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Drawer from 'primevue/drawer'
import AutoComplete from 'primevue/autocomplete'
import { useVuelidate } from '@vuelidate/core'
import { required, minLength } from '@vuelidate/validators'
import { useToast } from 'primevue/usetoast'
import { useAuthStore } from '@/store'
import { useRouter } from 'vue-router'
import Skeleton from 'primevue/skeleton';
/* -------------------- Setup & Refs -------------------- */
const router = useRouter()
const toast = useToast()
const authStore = useAuthStore()
const loading = ref(false)

// Toggle for mobile menu overlay
const mobileMenuOpen = ref(false)
function toggleMobileMenu() {
    mobileMenuOpen.value = !mobileMenuOpen.value
}


// Profile data
const profile = ref({
    name: 'Unknown',
    profile_image: null,
    currentJob: '',
    location: ''
})

// Drawer visibility
const visible = ref(false)

// Form state for editing
const form = ref({
    name: '',
    location: '',
    profile_image: ''
})

// Country autocomplete
const selectedCountry = ref('')
const filteredCountries = ref([])
const selectedCountryId = ref(null)

// Vuelidate rules
const rules = {
    name: { required, minLength: minLength(3) },
    location: { required }
}
const v$ = useVuelidate(rules, form)


function getPresentJob(experienceList = []) {

    const current = experienceList.find(e => !e.end_date)
    return current ? current.job_title : 'Seeking Job'
}

// On mount, fetch the user’s profile
onMounted(async () => {

    loading.value = true
    try {
        await authStore.getJobSeekerProfile()
        const apiData = authStore.jobSeekerProfile.data

        // Populate local profile
        profile.value = {
            name: apiData?.user?.name || 'Unknown',
            profile_image: apiData?.profile_img || null,
            currentJob: getPresentJob(apiData?.careerHistories || []),
            location: apiData?.country?.name || 'Unknown Location'
        }

        // Pre-fill form fields
        form.value.name = profile.value.name
        form.value.profile_image = profile.value.profile_image
        form.value.location = profile.value.location

        // Also set autocomplete display
        selectedCountry.value = profile.value.location
        loading.value = false

    } catch (error) {
        console.error('Failed to fetch profile:', error)
    }
})

/* -------------- Methods -------------- */
function openEditProfile() {
    visible.value = true
}

// For the location autocomplete
async function searchCountry(event) {
    try {
        const response = await authStore.searchCountry(event.query)
        filteredCountries.value = response.map(country => ({
            id: country.id,
            name: country.name
        }))
    } catch (err) {
        console.error('Error fetching country data:', err.message)
    }
}

function onCountrySelect(event) {
    selectedCountry.value = event.value.name
    selectedCountryId.value = event.value.id
}

// Upload profile picture
const fileInput = ref(null)
function triggerFileInput() {
    fileInput.value.click()
}

function uploadProfileImage(e) {
    const file = e.target.files[0]
    if (file) {
        const reader = new FileReader()
        reader.onload = () => {
            form.value.profile_image = reader.result
        }
        reader.readAsDataURL(file)
    }
}

// Update the user profile
async function updateProfile() {
    const valid = await v$.value.$validate()
    if (!valid) {
        toast.add({
            severity: 'error',
            summary: 'Validation Error',
            detail: 'Please fill in required fields correctly.',
            life: 3000
        })
        return
    }
    try {
        // Attempt to update the profile in the backend
        await authStore.updateProfile({
            ...form.value,
            location: selectedCountryId.value
        })
        console.log('aa', form.value)
        // Update local data
        profile.value.name = form.value.name
        profile.value.profile_image = form.value.profile_image
        profile.value.location = selectedCountry.value

        // Close the drawer
        visible.value = false
        //update currentUser State
        authStore.$patch({
            currentUser: {
                ...authStore.currentUser,
                name: profile.value.name,
            },
        });
        toast.add({
            severity: 'success',
            summary: 'Profile Updated',
            detail: 'Your profile has been updated successfully!',
            life: 3000
        })
    } catch (error) {
        console.error('Failed to update profile:', error)
        toast.add({
            severity: 'error',
            summary: 'Update Failed',
            detail: 'Something went wrong updating your profile.',
            life: 3000
        })
    }
}
</script>

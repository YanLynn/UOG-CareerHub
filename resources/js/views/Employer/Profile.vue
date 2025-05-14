<template>
    <div class="container mx-auto p-6">

        <!-- Employer Profile Card -->
        <Card class="shadow-xl border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            <template #content>
                <div class="relative bg-gradient-to-br from-indigo-500 to-purple-700 text-white p-8 rounded-xl">

                    <!-- 🔹 Background Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-20 rounded-xl"></div>

                    <div
                        class="relative z-10 flex flex-col sm:flex-row sm:items-center space-y-6 sm:space-y-0 sm:space-x-6">

                        <!-- 🔹 Profile Image -->
                        <div class="relative w-36 h-36">
                            <Skeleton shape="circle" size="100%" v-if="loading" />
                            <Avatar v-else :image="employer.profile?.company_image || 'https://placehold.co/200x200'"
                                class="!w-full !h-full rounded-full border-4 border-white shadow-lg object-cover" />
                        </div>

                        <!-- 🔹 Company Info -->
                        <div class="flex-1">
                            <h2 class="text-3xl font-bold">
                                <Skeleton width="14rem" height="2rem" v-if="loading" />
                                <span v-else>{{ employer.profile?.company_name }}</span>
                            </h2>
                            <p class="text-lg text-gray-200">
                                <Skeleton width="10rem" height="1.5rem" v-if="loading" />
                                <span v-else>{{ employer.profile?.industry }}</span>
                            </p>
                            <p class="text-md text-gray-300 flex items-center mt-1">
                                <i class="pi pi-map-marker text-lg text-white mr-2"></i>

                                <Skeleton width="12rem" height="1.2rem" v-if="loading" />

                                <template v-else>
                                    <img :src="employer.profile?.country.flag" alt="flag"
                                        class="w-5 h-4 rounded-sm mr-2" v-if="employer.profile?.country.flag" />
                                    <span>{{ employer.profile?.country.country }}</span>
                                </template>
                            </p>
                            <Skeleton width="10rem" height="2rem" v-if="loading" class="mt-3" />
                            <Button label="Edit Profile" icon="pi pi-pencil" class="p-button-primary"
                                @click="editProfile.openEditProfile()" />
                            <EditProfile ref="editProfile" />
                        </div>

                        <!-- 🔹 Navigation Menu (Desktop) -->
                        <div class="hidden lg:flex space-x-5">
                            <router-link v-for="link in menuLinks" :key="link.label" :to="link.path"
                                class="text-white hover:text-yellow-300 text-lg font-medium transition">
                                <i :class="[link.icon, 'mr-2']"></i> {{ link.label }}
                            </router-link>
                        </div>

                        <!-- 🔹 Mobile Menu Button -->
                        <Button icon="pi pi-bars" class="lg:hidden p-button-text text-white"
                            @click="mobileMenuVisible = true" />
                    </div>
                </div>

                <!-- 🔹 Mobile Sidebar Menu -->
                <Sidebar v-model:visible="mobileMenuVisible" position="right"
                    class="lg:hidden w-72 shadow-lg rounded-r-xl bg-white dark:bg-gray-900">


                    <!-- User Profile Section -->
                    <div class="flex items-center space-x-4 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <Avatar :image="employer.profile?.company_image || 'https://placehold.co/100x100'" size="large"
                            class="border shadow-md" />
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ employer.profile?.company_name || "Company Name" }}
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ employer.profile?.industry ||
                                "Industry" }}</p>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-4 px-6">
                        <ul class="space-y-3">
                            <li v-for="link in menuLinks" :key="link.label">
                                <router-link :to="link.path"
                                    class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-300 p-3 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-indigo-100 dark:hover:bg-indigo-900">
                                    <i :class="[link.icon, 'text-lg']"></i>
                                    <span class="text-lg">{{ link.label }}</span>
                                </router-link>
                            </li>
                        </ul>
                    </div>

                    <!-- Logout Button -->
                    <div class="absolute bottom-4 left-6 right-6">
                        <Button label="Logout" icon="pi pi-sign-out"
                            class="p-button-danger w-full p-button-rounded text-lg" />
                    </div>
                </Sidebar>
            </template>
        </Card>
        <router-view></router-view>
    </div>
</template>

<script setup>
import { computed, ref, onMounted } from "vue";
import { useAuthStore } from '@/store'// Import Pinia store
import Card from "primevue/card";
import Avatar from "primevue/avatar";
import Button from "primevue/button";
import Sidebar from "primevue/sidebar";
import Skeleton from "primevue/skeleton";
import EditProfile from "./EditProfile.vue";
const editProfile = ref(null);
const mobileMenuVisible = ref(false);
const loading = ref(true);
const authStore = useAuthStore(); // Access Pinia store
const employer = ref({});

// Navigation Menu Links
const menuLinks = ref([
    { label: "Dashboard", icon: "pi pi-home", path: "/employer/profile" },
    { label: "Manage Jobs", icon: "pi pi-briefcase", path: "/employer/manage-jobs" },
    { label: "Applications", icon: "pi pi-users", path: "/employer/applications-list" },
    { label: "Settings", icon: "pi pi-cog", path: "/employer/settings" }
]);

// Fetch Employer Profile on Component Mount
onMounted(async () => {
    try {
        await authStore.getEmployerProfile();
        employer.value = authStore.EmployerProfile || {};
        loading.value = false;
    } catch (error) {
        console.error("Failed to fetch employer profile:", error);
        loading.value = false;
    }
});


</script>

<template>
    <div>
        <!-- Employer Metrics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <MetricCard title="Total Job Posts" :value="formattedTotalJobs" icon="pi pi-file" color="blue"
                :subtitle="`${employer.metrics?.total_jobs} jobs currently active`" :progress="jobProgress"
                :loading="loading" />
            <MetricCard title="Active Jobs" :value="formattedActiveJobs" icon="pi pi-briefcase" color="green"
                :subtitle="`${employer.metrics?.active_jobs} jobs running`" :progress="activeProgress"
                :loading="loading" />
            <MetricCard title="Total Applicants" :value="employer.metrics?.total_applicants" icon="pi pi-users"
                color="purple" :subtitle="`Total ${employer.metrics?.total_applicants} applicants`"
                :progress="applicantsProgress" :loading="loading" />
        </div>

        <!-- Profile Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <InfoCard title="Company Information" :info="companyInfo" :loading="loading" />
            <InfoCard title="Contact Information" :info="contactInfo" :loading="loading" />
            <SocialCard :socialLinks="socialLinks" />
        </div>

        <!-- Company Description -->
        <CompanyDescriptionCard :description="employer.profile?.company_description" class="mt-6" :loading="loading" />
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { useAuthStore } from '@/store'; // Import Pinia store
import { storeToRefs } from "pinia";
import MetricCard from "@/components/MetricCard.vue";
import InfoCard from "@/components/InfoCard.vue";
import CompanyDescriptionCard from "@/components/CompanyDescriptionCard.vue";
import SocialCard from "@/components/SocialCard.vue";

const loading = ref(true);
const authStore = useAuthStore();
const { EmployerProfile } = storeToRefs(authStore); // Get reactive data from Pinia

const employer = computed(() => EmployerProfile.value || {}); // Ensure a default object

// Compute Values for Metrics
const jobProgress = computed(() => (employer.value.metrics?.total_jobs / 50) * 100);
const activeProgress = computed(() => (employer.value.metrics?.active_jobs / 50) * 100);
const applicantsProgress = computed(() => (employer.value.metrics?.total_applicants / 200) * 100);

const formattedTotalJobs = computed(() => formatNumber(employer.value.metrics?.total_jobs));
const formattedActiveJobs = computed(() => formatNumber(employer.value.metrics?.active_jobs));

// Compute Company Information
const companyInfo = computed(() => [
    { icon: "pi pi-calendar", text: `Founded Year: ${employer.value.profile?.founded_year || "N/A"}` },
    { icon: "pi pi-building", text: `Company Size: ${employer.value.profile?.company_size || "N/A"}` },
    { icon: "pi pi-info-circle", text: `Industry: ${employer.value.profile?.industry || "N/A"}` }
]);

// Compute Contact Information
const contactInfo = computed(() => [
    { icon: "pi pi-envelope", text: employer.value.profile?.contact_email || "N/A" },
    { icon: "pi pi-phone", text: employer.value.profile?.contact_phone || "N/A" },
    { icon: "pi pi-globe", text: employer.value.profile?.company_website || "N/A", link: employer.value.profile?.company_website }
]);

// Compute Social Links
const socialLinks = computed(() => [
    { icon: "pi pi-linkedin", text: "LinkedIn", link: employer.value.profile?.linkedin_url },
    { icon: "pi pi-twitter", text: "Twitter", link: employer.value.profile?.twitter_url },
    { icon: "pi pi-facebook", text: "Facebook", link: employer.value.profile?.facebook_url }
]);

// Number Formatting Function
const formatNumber = (num) => (num >= 1000 ? (num / 1000).toFixed(1) + "K" : num);

// Watch for updates and log data changes
watch(EmployerProfile, (newVal) => {
    console.log("👀 EmployerProfile updated in ProfileContent.vue:", newVal);
    loading.value = false; // Update loading state when data arrives
});
</script>

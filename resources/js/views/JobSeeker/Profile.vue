<template>
    <div class="container mx-auto p-6">
        <!-- Profile Header -->
        <div class="bg-white shadow-lg rounded-lg p-6 flex items-center space-x-6">
            <!-- Profile Picture -->
            <div class="relative w-24 h-24">
                <img :src="profile.profile_image || 'https://placehold.co/600x400'" alt="Profile Picture"
                    class="rounded-full w-24 h-24 object-cover border">
            </div>

            <!-- Profile Info -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">{{ profile.name }}</h2>
                <p class="text-gray-600"><i class="pi pi-briefcase"></i> {{ profile.currentJob || 'Seeking Job' }}</p>
                <p class="text-gray-600"><i class="pi pi-map-marker"></i> {{ profile.location }}</p>
                <p class="text-blue-600 font-medium hover:underline cursor-pointer" @click="openEditProfile">
                    <i class="pi pi-pencil"></i> Edit Profile
                </p>
            </div>
        </div>

        <!-- Resume & Skills Section -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold">Resume</h3>
                <p v-if="profile.resume" class="mt-2 text-gray-600">{{ profile.resume.name }}</p>
                <Button v-if="profile.resume" icon="pi pi-download" label="Download Resume" class="mt-2"
                    @click="downloadResume" />
                <input type="file" class="mt-4 w-full border p-2 rounded-md" @change="uploadResume" />
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold">Skills</h3>
                <ul class="mt-2">
                    <li v-for="(skill, index) in profile.skills" :key="index"
                        class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm m-1">
                        {{ skill }}
                    </li>
                </ul>
                <input type="text" class="w-full border p-2 rounded-md mt-4" placeholder="Add new skill"
                    v-model="newSkill" @keyup.enter="addSkill" />
            </div>
        </div>

        <!-- Work Experience & Education -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold">Work Experience</h3>
                <div v-for="(job, index) in profile.experience" :key="index" class="mt-4 border-b pb-2">
                    <h4 class="font-medium">{{ job.position }} at {{ job.company }}</h4>
                    <p class="text-gray-500 text-sm">{{ job.startDate }} - {{ job.endDate || 'Present' }}</p>
                    <p class="text-gray-600 mt-1">{{ job.description }}</p>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold">Education</h3>
                <div v-for="(edu, index) in profile.education" :key="index" class="mt-4 border-b pb-2">
                    <h4 class="font-medium">{{ edu.degree }} - {{ edu.school }}</h4>
                    <p class="text-gray-500 text-sm">{{ edu.year }}</p>
                </div>
            </div>
        </div>

        <!-- Edit Profile Drawer -->
        <Drawer v-model:visible="visible" header="Edit Profile" position="right" class="!w-full md:!w-80 lg:!w-[30rem]">
            <div class="p-4">
                <form @submit.prevent="updateProfile">
                    <div class="flex items-center space-x-4">
                        <div class="relative w-24 h-24">
                            <!-- Profile Image -->
                            <img :src="profile.profile_image" alt="Profile Picture"
                                class="rounded-full w-24 h-24 object-cover border">

                            <!-- Hidden File Input Overlay -->
                            <input type="file" ref="fileInput"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                @change="uploadProfileImage" />

                            <!-- Edit Icon Overlay -->
                            <i class="pi pi-pencil absolute bottom-0 right-0 bg-white text-gray-600 rounded-full p-2 shadow cursor-pointer"
                                @click="triggerFileInput"></i>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block font-medium">Full Name</label>
                        <InputText v-model="form.name" class="w-full p-2 border rounded"
                            placeholder="Enter full name" />
                    </div>

                    <div class="mt-4">
                        <label class="block font-medium">Job Title</label>
                        <InputText v-model="form.currentJob" class="w-full p-2 border rounded"
                            placeholder="Enter job title" />
                    </div>

                    <div class="mt-4">
                        <label class="block font-medium">Location</label>
                        <InputText v-model="form.location" class="w-full p-2 border rounded"
                            placeholder="Enter location" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <Button label="Cancel" class="p-button-secondary mr-2" @click="closeModal" />
                        <Button label="Save Changes" type="submit" class="p-button-success" />
                    </div>
                </form>
            </div>
        </Drawer>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Drawer from 'primevue/drawer';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import { useAuthStore } from '@/store';

// State Variables
const visible = ref(false);
const newSkill = ref('');
const profileImage = ref('https://placehold.co/600x400');
const confirm = useConfirm();
const toast = useToast();
const authStore = useAuthStore();
const profile = ref({});
//get jobSeeker Profile by login userid
onMounted(async () => {
    try {
        await authStore.getJobSeekerProfile();
        const apiData = authStore.jobSeekerProfile.data;
        profile.value = {
            name: apiData?.user?.name || 'Unknown',
            profile_image: apiData?.profile_img,
            currentJob: apiData?.careerHistories?.length ? apiData.careerHistories[0].job_title : 'Seeking Job',
            location: apiData?.country?.name || 'Unknown Location',
            resume: apiData?.resume_url || null,
            skills: apiData?.skills?.map(skill => skill.name) || [],
            experience: apiData?.careerHistories?.map(job => ({
                position: job.job_title,
                company: job.company || 'Unknown Company',
                startDate: job.start_date,
                endDate: job.end_date || 'Present',
                description: job.description
            })) || [],
            education: apiData?.educations?.map(edu => ({
                degree: edu.qualification,
                school: edu.institution,
                year: edu.date
            })) || []
        };

        console.log('Updated Profile:', profile.value);

    } catch (error) {
        console.error('Failed to fetch profile:', error);
    }
})


// Form state
const form = ref({
    name: profile.value.name,
    currentJob: profile.value.currentJob,
    location: profile.value.location,
    profile_image: profile.value.profile_image,
});

// Open Edit Modal
const openEditProfile = () => {
    visible.value = true;
    form.value = { ...profile.value };
};

// Upload Profile Picture
const uploadProfileImage = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = () => (profileImage.value = reader.result);
        reader.readAsDataURL(file);
    }
};
//click pencil
const fileInput = ref(null);
const triggerFileInput = () => {
    fileInput.value.click();
};


// Upload Resume
const uploadResume = (event) => {
    const file = event.target.files[0];
    if (file) {
        profile.value.resume = { name: file.name };
    }
};

// Add New Skill
const addSkill = () => {
    if (newSkill.value.trim() !== '') {
        profile.value.skills.push(newSkill.value.trim());
        newSkill.value = '';
    }
};

// Update Profile
const updateProfile = () => {
    profile.value = { ...form.value };
    visible.value = false;
};

// Close Modal
const closeModal = () => {
    visible.value = false;
    toast.add({ severity: 'warn', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
};

const downloadResume = () => {
    toast.add({ severity: 'info', summary: 'download!', detail: 'Download resume', life: 3000 });
}
</script>

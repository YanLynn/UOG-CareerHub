<template>
    <div class="container mx-auto p-6">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 shadow-sm rounded-lg p-6 flex items-center space-x-6 hover:shadow-md relative">
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

            <!-- Horizontal Menu -->
            <Menubar :model="jobSeekerMenu"
                class="absolute bottom-0 right-0 m-0 p-0 flex space-x-4 text-sm !border-0 !bg-transparent !drop-shadow-sm " />
        </div>

        <!-- Resume & Skills Section -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-sm rounded-lg p-6 relative hover:shadow-md">
                <h3 class="text-xl font-semibold mb-2">Resume</h3>
                <div v-if="profile.resume">
                    <p class="text-gray-600 font-medium">{{ profile.resumeFileName }}</p>
                    <button @click="downloadResume" class="absolute right-8 top-5 text-gray-500 hover:text-gray-700"
                        title="Download Resume">
                        <i class="pi pi-download"></i>
                    </button>
                </div>
                <div v-else class="flex items-center justify-center h-24 text-gray-500">
                    No resume uploaded yet.
                </div>
                <div class="mt-4">

                    <label for="resumeUpload" class="inline-flex items-center py-2 px-3 border rounded-md
               text-gray-700 cursor-pointer hover:bg-gray-50">
                        <i class="pi pi-upload mr-2"></i>
                        Choose a File
                    </label>
                    <input id="resumeUpload" type="file" class="hidden" accept=".pdf" @change="handleFileSelection" />
                    <div v-if="selectedFile" class="inline-flex items-center ml-3 space-x-2">
                        <button @click="uploadNewResume()" class="p-2 text-green-500 hover:bg-green-100 rounded-full"
                            title="Upload">
                            <i class="pi pi-check"></i>
                        </button>
                        <button @click="cancelNewResume" class="p-2 text-red-500 hover:bg-red-100 rounded-full"
                            title="Cancel">
                            <i class="pi pi-times"></i>
                        </button>
                    </div>
                </div>



                <div v-if="uploadProgress > 0" class="mt-4">
                    <ProgressBar :value="uploadProgress" class="custom-progress-bar"></ProgressBar>
                    <!-- <p class="text-sm text-gray-500 mt-2">Uploading... {{ uploadProgress }}%</p> -->
                </div>
            </div>



            <!-- skill -->
            <div class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md">
                <h3 class="text-xl font-semibold">Skills <span class="text-blue-800 py-100 cursor-pointer ml-3"> <i
                            class="pi pi-pen-to-square" @click="openEditSkill"></i> </span> </h3>
                <ul class="mt-2">
                    <li v-for="(skill, index) in profile.skills" :key="index"
                        class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm m-1">
                        {{ skill }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Work Experience & Education -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md">
                <h3 class="text-xl font-semibold">Work Experience</h3>
                <div v-for="(job, index) in profile.experience" :key="index" class="mt-4 border-b pb-2">
                    <h4 class="font-medium">{{ job.position }} at {{ job.company }}</h4>
                    <p class="text-gray-500 text-sm">{{ job.startDate }} - {{ job.endDate || 'Present' }}</p>
                    <p class="text-gray-600 mt-1">{{ job.description }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md">
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
                            <img :src="form.profile_image || 'https://placehold.co/100x100'" alt="Profile Picture"
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
                        <InputText v-model="form.name" class="w-full p-2 border rounded" placeholder="Enter full name"
                            :class="{ 'border-red-500': v$.name.$error }" />
                        <small v-if="v$.name.$error" class="text-red-500">Name must be at least 3 characters.</small>
                    </div>

                    <div class="mt-4">
                        <label class="block font-medium text-gray-700 mb-2">Location</label>
                        <AutoComplete v-model="selectedCountry" :suggestions="filteredCountries"
                            @complete="searchCountry" field="name" @item-select="onCountrySelect" class="w-full"
                            input-class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            dropdown-class="absolute z-10 mt-1 w-full bg-white border border-gray-300 shadow-sm rounded-md overflow-hidden"
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
                        <Button label="Cancel" class="p-button-secondary mr-2" @click="$emit('close')" />
                        <Button label="Save Changes" type="submit" class="p-button-success" />
                    </div>
                </form>
            </div>
        </Drawer>

        <Drawer v-model:visible="visibleTow" header="Edit Skill" position="right"
            class="!w-full md:!w-80 lg:!w-[30rem]">
            <ul class="mt-2 flex flex-wrap gap-2">
                <li v-for="(skill, index) in profile.skills" :key="index" class="relative">

                    <button @click="removeSkill(index)"
                        class="absolute -top-1 -right-1 bg-gray-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs shadow-sm hover:bg-red-400">
                        <i class="pi pi-times-circle text-xs" style="font-size: 12px"></i>
                    </button>
                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        {{ skill }}
                    </span>
                </li>
            </ul>
            <input type="text" class="w-full border p-2 rounded-md mt-4" placeholder="Add new skill" v-model="newSkill"
                @keyup.enter="addSkill" />

            <div class="mt-6 flex justify-end">
                <Button label="Cancel" class="p-button-secondary mr-2" @click="ConfirmDialog()" severity="danger"
                    outlined />
                <Button label="Save Changes" type="button" @click.prevent="updateSkill" class="p-button-success" />
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
import AutoComplete from "primevue/autocomplete";
import { useVuelidate } from "@vuelidate/core";
import { required, minLength } from "@vuelidate/validators";
import ProgressBar from 'primevue/progressbar';


const jobSeekerMenu = ref([
    {
        label: 'Job Search',
        icon: 'pi pi-search'
    },
    {
        label: 'Job Apply List',
        icon: 'pi pi-star'
    },
    {
        label: 'Settings',
        icon: 'pi pi-cog'
    },
    {
        label: 'Profile Viewer',
        icon: 'pi pi-eye'
    },
])

// State Variables
const visible = ref(false);
const visibleTow = ref(false);
const newSkill = ref('');
const profileImage = ref('https://placehold.co/600x400');
const confirm = useConfirm();
const toast = useToast();
const authStore = useAuthStore();
const profile = ref({});
const selectedCountry = ref("");
const selectedCountryId = ref(null);
const filteredCountries = ref([]);
const selectedFile = ref(null);
const uploadProgress = ref(0);
const uploading = ref(false);




// Form state
const form = ref({
    name: profile.value.name,
    location: profile.value.location,
    profile_image: profile.value.profile_image,
});

// Validation rules
const rules = {
    name: { required, minLength: minLength(3) },
    location: { required }
};

// Vuelidate instance
const v$ = useVuelidate(rules, form);


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
            resumeFileName: apiData?.resume_file_name || null,
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

        selectedCountry.value = apiData?.country?.name


    } catch (error) {
        console.error('Failed to fetch profile:', error);
    }
})

// Fetch country suggestions from API
const searchCountry = async (event) => {
    try {
        const response = await authStore.searchCountry(event.query);
        filteredCountries.value = response.map(country => ({
            id: country.id,
            name: country.name
        }));
    } catch (err) {
        console.error("Error fetching country data:", err.message);
    }
};

const onCountrySelect = (event) => {
    selectedCountry.value = event.value.name;
    selectedCountryId.value = event.value.id;
};


const openEditProfile = async () => {

    visible.value = true;
    form.value.name = profile.value.name
    form.value.profile_image = profile.value.profile_image
    form.value.currentJob = profile.value.currentJob
    form.value.location = profile.value.location
};




// Upload Profile Picture
const uploadProfileImage = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = () => (
            form.value.profile_image = reader.result //convert to base 64bit image
        );
        reader.readAsDataURL(file);
    }
};
//click pencil
const fileInput = ref(null);
const triggerFileInput = () => {
    fileInput.value.click();
};




const cancelNewResume = () => {
    selectedFile.value = null;
    profile.value.resumeFileName = authStore.jobSeekerProfile.data.resume_file_name
    uploadProgress.value = 0;
}

const handleFileSelection = (e) => {
    const file = e.target.files[0];
    if (file.size > 10 * 1024 * 1024) {
        alert("File is too large! Maximum allowed size is 10MB.");
        return;
    }
    if (!file) {
        selectedFile.value = null;
        return;
    }
    selectedFile.value = file;
    profile.value.resumeFileName = file.name
};

// Upload Resume
const uploadNewResume = async () => {


    try {
        uploading.value = true;
        if (selectedFile.value) {
            const formData = new FormData();
            formData.append('resume', selectedFile.value);
            formData.append('file_name', selectedFile.value.name);
            const res = await authStore.uploadResumeFile(formData, (progressEvent) => {
                uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            });
            if (res.success) {
                //uploadProgress.value = 100; // Mark completion only after server responds
                setTimeout(() => {
                    uploadProgress.value = 0;
                    selectedFile.value = null;
                    uploading.value = false; // Reset uploading state
                }, 1000);
            }
        }
    } catch (error) {
        console.log('error', error)
    }


};

// Add New Skill
const addSkill = () => {
    const skill = newSkill.value.trim().toLowerCase();
    if (profile.value.skills.includes(skill)) {
        toast.add({ severity: 'warn', summary: 'Rejected', detail: 'skill is already exit!', life: 3000 });
        return;
    }
    if (skill !== '') {
        profile.value.skills.push(skill);
        newSkill.value = '';
    }
};

const updateSkill = async () => {

    try {
        await authStore.updateSkill(profile.value.skills)
        visibleTow.value = false
        toast.add({ severity: "success", summary: "Update Skill", detail: "Update Skill Successfully!", life: 3000 });

    } catch (error) {
        console.error("Failed to update profile:", error);
    }


}

// Update Profile
const updateProfile = async () => {
    const result = await v$.value.$validate();
    if (!result) {
        toast.add({ severity: "error", summary: "Validation Error", detail: "Please fill in all required fields correctly.", life: 3000 });
        return;
    }
    try {
        console.log("Updating Profile with:", {
            ...form.value,
            location: selectedCountryId.value
        });

        const res = await authStore.updateProfile({
            ...form.value,
            location: selectedCountryId.value
        });
        profile.value.name = form.value.name
        profile.value.location = selectedCountry.value
        visible.value = false;

    } catch (error) {
        console.error("Failed to update profile:", error);
    }

    visible.value = false;

};

const openEditSkill = () => {
    visibleTow.value = true;
}

const removeSkill = (index) => {
    profile.value.skills.splice(index, 1);
};


const ConfirmDialog = () => {
    confirm.require({
        message: 'Do you want to cancel this record?',
        header: 'Discard changes',
        icon: 'pi pi-info-circle',
        rejectLabel: 'Discard',
        rejectProps: {
            label: 'Discard',
            severity: 'danger',
            outlined: true
        },
        acceptProps: {
            label: 'Cancel',
            severity: 'secondary'
        },
        accept: () => {

        },
        reject: () => {
            profile.value.skills = authStore.jobSeekerProfile.data.skills.map(skill => skill.name) || [],
                visibleTow.value = false
        }
    });
}

// Close Modal
const closeModal = () => {
    visible.value = false;
    toast.add({ severity: 'warn', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
};

const downloadResume = async () => {
    if (!profile.value.resume) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'No resume available for download', life: 3000 });
        return;
    }

    const resumeUrl = profile.value.resume;
    const resumeFileName = profile.value.resumeFileName || "resume.pdf";

    try {

        const response = await fetch(resumeUrl);
        if (!response.ok) throw new Error("Failed to download file");

        const blob = await response.blob();
        const blobUrl = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = blobUrl;
        link.download = resumeFileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(blobUrl);


        toast.add({
            severity: "info",
            summary: "Download Started",
            detail: "Your resume is downloaded.",
            life: 3000,
        });

    } catch (error) {
        console.error("Download error:", error);
        toast.add({
            severity: "error",
            summary: "Download Failed",
            detail: "There was an issue downloading the resume.",
            life: 3000,
        });
    }
};

</script>

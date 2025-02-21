<template>
    <!-- Resume & Skills Section -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Resume Card -->
        <div class="bg-white shadow-sm rounded-lg p-6 relative hover:shadow-md">
            <h3 class="text-xl font-semibold mb-2">Resume</h3>
            <div v-if="profile.resume">
                <p class="text-gray-600 font-medium">{{ profile.resumeFileName }}</p>
                <button @click="downloadResume" class="absolute right-8 top-7 text-gray-500 hover:text-gray-700"
                    title="Download Resume">
                    <i class="pi pi-download"></i>
                </button>
            </div>
            <div v-else class="flex items-center justify-center h-24 text-gray-500">
                No resume uploaded yet.
            </div>

            <!-- File Upload -->
            <div class="mt-4">
                <label for="resumeUpload"
                    class="inline-flex items-center py-2 px-3 border rounded-md text-gray-700 cursor-pointer hover:bg-gray-50">
                    <i class="pi pi-upload mr-2"></i>
                    Choose a File
                </label>
                <input id="resumeUpload" type="file" class="hidden" accept=".pdf" @change="handleFileSelection" />
                <!-- If file selected, show Upload & Cancel buttons -->
                <div v-if="selectedFile" class="inline-flex items-center ml-3 space-x-2">
                    <button @click="uploadNewResume" class="p-2 text-green-500 hover:bg-green-100 rounded-full"
                        title="Upload">
                        <i class="pi pi-check"></i>
                    </button>
                    <button @click="cancelNewResume" class="p-2 text-red-500 hover:bg-red-100 rounded-full"
                        title="Cancel">
                        <i class="pi pi-times"></i>
                    </button>
                </div>
            </div>

            <!-- Upload Progress -->
            <div v-if="uploadProgress > 0" class="mt-4">
                <ProgressBar :value="uploadProgress" class="custom-progress-bar" />
            </div>
        </div>

        <!-- Skills Card -->
        <div class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md">
            <h3 class="text-xl font-semibold">
                Skills
                <span class="text-blue-800 py-100 cursor-pointer ml-3">
                    <i class="pi pi-pen-to-square" @click="openEditSkill"></i>
                </span>
            </h3>
            <ul class="mt-2">
                <li v-for="(skill, index) in profile.skills" :key="index"
                    class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full shadow-sm text-sm m-1">
                    {{ skill }}
                </li>
            </ul>
        </div>
    </div>

    <!-- Work Experience & Education -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Work Experience Card -->
        <div class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md">
            <h3 class="text-xl font-semibold">
                Work Experience
                <span class="text-blue-800 py-100 cursor-pointer ml-3">
                    <i class="pi pi-plus-circle" @click="openEditWorkExp"></i>
                </span>
            </h3>
            <div v-for="(job, index) in profile.experience" :key="index"
                class="mt-4 border-b pb-2 flex justify-between items-center">
                <!-- Left side: job info -->
                <div>
                    <h4 class="font-medium">
                        {{ job.job_title }}
                        <span class="text-sm text-gray-500">- {{ job.duration }}</span>
                    </h4>
                    <p class="text-gray-500 text-sm">
                        {{ job.startDate }} -
                        <span v-if="!job.endDate" class="text-green-500">Present</span>
                        <span v-else>{{ job.endDate }}</span>
                    </p>
                    <p class="text-gray-600 mt-1">{{ job.description }}</p>
                </div>

                <!-- Right side: icons -->
                <div class="flex items-center space-x-4">
                    <i class="pi pi-pencil cursor-pointer text-blue-800" @click="editWorkExp(job.id)"></i>
                    <i class="pi pi-trash cursor-pointer text-red-600" @click="deleteWorkExp(job.id)"></i>
                </div>
            </div>
        </div>

        <!-- Education Card -->
        <div class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md">
            <h3 class="text-xl font-semibold">
                Education
                <span class="text-blue-800 py-100 cursor-pointer ml-3">
                    <i class="pi pi-plus-circle" @click="openEducation"></i>
                </span>
            </h3>
            <div v-for="(edu, index) in profile.education" :key="index"
                class="mt-4 border-b pb-2 flex justify-between items-center">
                <div>
                    <h4 class="font-medium">
                        {{ edu.degree }} - {{ edu.school }}
                    </h4>
                    <p class="text-gray-500 text-sm">{{ edu.year }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <i class="pi pi-pencil cursor-pointer text-blue-800" @click="openEducation(edu)"></i>
                    <i class="pi pi-trash cursor-pointer text-red-600" @click="deleteEducation(edu.id)"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Drawer: Edit Skill -->
    <Drawer v-model:visible="visibleTow" header="Edit Skill" position="right" class="!w-full md:!w-80 lg:!w-[30rem]">
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

    <!-- Drawer: Work Experience -->
    <Drawer v-model:visible="visibleThree" header="Work Experience" position="right"
        class="!w-full md:!w-80 lg:!w-[30rem]">
        <form @submit.prevent="saveWorkExp">
            <!-- Job Title -->
            <div class="mb-4">
                <label for="position" class="block text-sm font-medium text-gray-700">Job Title</label>
                <input id="jobTitle" type="text" v-model="form.job_title"
                    class="mt-1 p-2 block w-full border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none"
                    placeholder="job title" required />
            </div>
            <!-- Start & End Dates -->
            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="mb-4 flex-1">
                    <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input id="startDate" type="date" v-model="form.startDate"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none"
                        required />
                </div>
                <div class="mb-4 flex-1">
                    <label for="endDate" class="block text-sm font-medium text-gray-700">End Date
                        <span class="text-xs">(leave blank if current)</span>
                    </label>
                    <input id="endDate" type="date" v-model="form.endDate"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none" />
                </div>
            </div>
            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" v-model="form.description"
                    class="mt-1 p-2 block w-full border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none"
                    rows="4" placeholder="Describe your role and responsibilities..."></textarea>
            </div>
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100"
                    @click="handleCancel">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>
    </Drawer>

    <!-- Drawer: Education (empty in this snippet) -->
    <Drawer v-model:visible="visibleFour" header="Education" position="right" class="!w-full md:!w-80 lg:!w-[30rem]">
        <form @submit.prevent="saveEducation">
            <!-- Degree Field -->
            <div class="mb-4">
                <label for="degree" class="block font-medium mb-1">
                    Qualification
                </label>
                <InputText id="degree" v-model="educationForm.degree" class="w-full p-2 border rounded"
                    placeholder="e.g., Bachelor of Science" />
            </div>

            <!-- School / Institution Field -->
            <div class="mb-4">
                <label for="school" class="block font-medium mb-1">
                    School / Institution
                </label>
                <InputText id="school" v-model="educationForm.school" class="w-full p-2 border rounded"
                    placeholder="e.g., University of XYZ" />
            </div>


            <!-- Year Calendar -->
            <div class="mb-4">
                <label for="yearCalendar" class="block font-medium mb-1">Graduation Year</label>

                <Calendar id="yearCalendar" v-model="educationForm.year" view="year" dateFormat="yy"
                    yearNavigator="true" yearRange="1950:2050" placeholder="choose year" class="w-full" />

            </div>
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <Button type="button" label="Cancel" class="p-button-secondary" @click="handleCancel" />
                <Button type="submit" label="Save" class="p-button-success" />
            </div>
        </form>
    </Drawer>
</template>

<script setup>

import { ref, computed } from 'vue'
import { useToast } from 'primevue/usetoast'
import { useConfirm } from 'primevue/useconfirm'
import ProgressBar from 'primevue/progressbar'
import Button from 'primevue/button'
import Drawer from 'primevue/drawer'
import { useAuthStore } from '@/store'
import { storeToRefs } from 'pinia'
import { getDurationString } from '../../composables/durationOfYear'
import Calendar from 'primevue/calendar'

const authStore = useAuthStore()
const { jobSeekerProfile } = storeToRefs(authStore)

const profile = computed(() => {
    const data = jobSeekerProfile.value?.data
    if (!data) {
        return {
            name: 'Unknown',
            profile_image: null,
            currentJob: '',
            location: 'Unknown Location',
            resume: null,
            resumeFileName: null,
            skills: [],
            experience: [],
            education: []
        }
    }

    return {
        name: data.user?.name || 'Unknown',
        profile_image: data.profile_img,
        currentJob: data.careerHistories?.[0]?.job_title || 'Seeking Job',
        location: data.country?.name || 'Unknown Location',
        resume: data.resume_url || null,
        resumeFileName: data.resume_file_name || null,
        skills: data.skills?.map(s => s.name) || [],
        experience:
            data.careerHistories?.map(job => ({
                id: job.id,
                job_title: job.job_title,
                startDate: job.start_date,
                endDate: job.end_date,
                description: job.description,
                duration: getDurationString(job.start_date, job.end_date)
            }))?.sort((a, b) => {
                if (!a.endDate && b.endDate) return -1;
                if (a.endDate && !b.endDate) return 1;
                return new Date(b.endDate || 0) - new Date(a.endDate || 0);
            }) || [],
        education:
            data.educations?.map(edu => ({
                id: edu.id,
                degree: edu.qualification,
                school: edu.institution,
                year: edu.date
            })) || []
    }
})

/* ----------------- State for drawers & file upload ----------------- */
const visibleTow = ref(false)     // Drawer: Edit skills
const visibleThree = ref(false)   // Drawer: Work experience
const visibleFour = ref(false)    // Drawer: Education
const newSkill = ref('')
const selectedFile = ref(null)
const uploadProgress = ref(0)
const uploading = ref(false)

/* -------- Form for adding/updating Work Experience -------- */
const form = ref({
    job_id: null,
    job_title: '',
    startDate: '',
    endDate: '',
    description: ''
})



const educationForm = ref({
    id: null,
    degree: '',
    school: '',
    year: null
})

// If editing, store an id:
const editingEduId = ref(null)



/* -------------- Toast & Confirm -------------- */
const toast = useToast()
const confirm = useConfirm()

/* =============== Methods =============== */

// ==================== Resume Upload ====================
function handleFileSelection(e) {
    const file = e.target.files[0]
    if (!file) return

    if (file.size > 10 * 1024 * 1024) {
        alert('File is too large! Maximum allowed size is 10MB.')
        return
    }
    selectedFile.value = file
    profile.value.resumeFileName = file.name
    // Update the displayed resume file name
}
function cancelNewResume() {
    selectedFile.value = null
    profile.value.resumeFileName = jobSeekerProfile.value?.data.resume_file_name
    uploadProgress.value = 0
}
async function uploadNewResume() {
    if (!selectedFile.value) return
    try {
        uploading.value = true
        const formData = new FormData()
        formData.append('resume', selectedFile.value)
        formData.append('file_name', selectedFile.value.name)

        const res = await authStore.uploadResumeFile(formData, progressEvent => {
            uploadProgress.value = Math.round(
                (progressEvent.loaded * 100) / progressEvent.total
            )
        })
        if (res.success) {
            toast.add({
                severity: 'success',
                summary: 'Upload Complete',
                detail: 'Resume uploaded successfully!',
                life: 3000
            })
        }
    } catch (error) {
        console.error(error)
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to upload resume.',
            life: 3000
        })
    } finally {
        // Reset
        uploading.value = false
        selectedFile.value = null
        uploadProgress.value = 0
    }
}
async function downloadResume() {
    if (!profile.value.resume) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'No resume available for download',
            life: 3000
        })
        return
    }
    try {
        const response = await fetch(profile.value.resume)
        if (!response.ok) throw new Error('Failed to download file')

        const blob = await response.blob()
        const blobUrl = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = blobUrl
        link.download = profile.value.resumeFileName || 'resume.pdf'
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
        window.URL.revokeObjectURL(blobUrl)

        toast.add({
            severity: 'info',
            summary: 'Download Started',
            detail: 'Your resume is downloaded.',
            life: 3000
        })
    } catch (error) {
        console.error('Download error:', error)
        toast.add({
            severity: 'error',
            summary: 'Download Failed',
            detail: 'There was an issue downloading the resume.',
            life: 3000
        })
    }
}

// ==================== Skills ====================
function openEditSkill() {
    visibleTow.value = true
}
function removeSkill(index) {
    // Remove from local array
    //profile.value.skills.splice(index, 1)
    jobSeekerProfile.value.data.skills.splice(index, 1)

}
async function updateSkill() {
    try {
        // Example: call an API to persist the updated array
        await authStore.updateSkill(profile.value.skills)
        toast.add({
            severity: 'success',
            summary: 'Update Skill',
            detail: 'Skills updated successfully!',
            life: 3000
        })
        visibleTow.value = false
    } catch (error) {
        console.error('Failed to update skill:', error)
    }
}
function addSkill() {
    const skill = newSkill.value.trim().toLowerCase()
    if (!skill) return

    // Check if skill already exists
    if (profile.value.skills.includes(skill)) {
        toast.add({
            severity: 'warn',
            summary: 'Rejected',
            detail: 'Skill already exists!',
            life: 3000
        })
        return
    }
    profile.value.skills.push(skill)
    newSkill.value = ''
}
function ConfirmDialog() {
    // Example of discarding changes
    confirm.require({
        message: 'Discard changes?',
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
            // Do nothing on "Cancel"
        },
        reject: () => {
            // On "Discard", revert the skill array
            profile.value.skills = jobSeekerProfile.value?.data?.skills?.map(s => s.name) || []
            visibleTow.value = false
        }
    })
}

// ==================== Work Experience ====================
function openEditWorkExp() {
    // Reset form
    form.value.job_id = null
    form.value.job_title = ''
    form.value.startDate = ''
    form.value.endDate = ''
    form.value.description = ''
    visibleThree.value = true
}
async function saveWorkExp() {
    try {
        const res = await authStore.addWorkExp(form.value)
        const backendItem = res.data
        // Transform to local shape
        const updatedExp = {
            ...backendItem,
            startDate: backendItem.start_date,
            endDate: backendItem.end_date,
            duration: getDurationString(backendItem.start_date, backendItem.end_date)
        }
        // Insert or replace in local array
        const idx = profile.value.experience.findIndex(e => e.id === updatedExp.id)
        if (idx !== -1) {
            profile.value.experience.splice(idx, 1, updatedExp)
        } else {
            profile.value.experience.push(updatedExp)
        }
        // Re-sort
        profile.value.experience.sort((a, b) => {
            if (!a.endDate && b.endDate) return -1
            if (a.endDate && !b.endDate) return 1
            return new Date(b.endDate || 0) - new Date(a.endDate || 0)
        })

        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Experience saved!',
            life: 3000
        })
    } catch (error) {
        console.error('Failed to save experience:', error)
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Something went wrong saving your work experience.',
            life: 3000
        })
    } finally {
        visibleThree.value = false
    }
}
function handleCancel() {
    visibleThree.value = false
    visibleFour.value = false
}
async function editWorkExp(jId) {
    try {
        const existing = profile.value.experience.find(e => e.id === jId)
        if (!existing) return

        // Populate form for editing
        form.value.job_id = jId
        form.value.job_title = existing.job_title
        form.value.startDate = existing.startDate
        form.value.endDate = existing.endDate || ''
        form.value.description = existing.description
        visibleThree.value = true
    } catch (error) {
        console.error(error)
    }
}
async function deleteWorkExp(jId) {
    confirm.require({
        message: 'Do you want to delete this record?',
        header: 'Delete Work Experience',
        icon: 'pi pi-trash',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Delete',
            severity: 'danger'
        },
        accept: async () => {
            try {
                await authStore.deleteHistory(jId)
                // Remove from local array
                jobSeekerProfile.value.data.careerHistories = jobSeekerProfile.value.data.careerHistories.filter(e => e.id !== jId)
                toast.add({
                    severity: 'success',
                    summary: 'Deleted',
                    detail: 'Record deleted!',
                    life: 3000
                })
            } catch (error) {
                console.error(error)
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to delete work experience.',
                    life: 3000
                })
            }
        }
    })
}

// ==================== Education ====================
function openEducation(edu = null) {

    visibleFour.value = true
    if (edu) {

        // Editing
        editingEduId.value = edu.id
        educationForm.value = {
            id: edu.id || null,
            degree: edu.degree || '',
            school: edu.school || '',
            year: edu.year || null
        }

    } else {
        // Adding new
        editingEduId.value = null
        educationForm.value = { degree: '', school: '', year: null }
    }

}


async function saveEducation() {

    try {
        if (!educationForm.value.degree || !educationForm.value.school) {
            toast.add({
                severity: 'error',
                summary: 'Validation Error',
                detail: 'Please fill in the required fields.',
                life: 3000
            })
            return
        }

        const res = await authStore.saveEdu(educationForm.value)
        if (jobSeekerProfile.value?.data.educations) {
            const index = jobSeekerProfile.value.data.educations.findIndex(edu => edu.id === res.data.id)
            if (index !== -1) {

                jobSeekerProfile.value.data.educations.splice(index, 1, res.data)
            } else {

                jobSeekerProfile.value.data.educations.push(res.data)
            }
        } else {

            jobSeekerProfile.value.data.educations = [res.data]
        }
        visibleFour.value = false
        toast.add({
            severity: 'success',
            summary: 'success',
            detail: 'Record save!',
            life: 3000
        })


    } catch (error) {
        console.error(error)
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to delete work experience.',
            life: 3000
        })
    }
}

async function deleteEducation(edu) {
    try {

        confirm.require({
            message: 'Do you want to delete this record?',
            header: 'Delete Education',
            icon: 'pi pi-trash',
            rejectProps: {
                label: 'Cancel',
                severity: 'secondary',
                outlined: true
            },
            acceptProps: {
                label: 'Delete',
                severity: 'danger'
            },
            accept: async () => {

                    if (edu) {
                        const res = await authStore.deleteEdu(edu);
                        jobSeekerProfile.value.data.educations = jobSeekerProfile.value.data.educations.filter(e => e.id !== edu)
                        console.log('res', res);

                    }

                    toast.add({
                        severity: 'success',
                        summary: 'Deleted',
                        detail: 'Record deleted!',
                        life: 3000
                    })
            }
        })

    } catch (error) {
        console.error(error)
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to delete work experience.',
            life: 3000
        })
    }
}



</script>

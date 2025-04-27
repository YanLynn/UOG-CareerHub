<template>
    <Dialog v-model:visible="showDialog" modal header="Edit Profile" :style="{ width: '50vw' }">
        <form @submit.prevent="submitProfile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Company Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                    <InputText v-model="profile.company_name" class="w-full" placeholder="Enter company name" required />
                </div>

                <!-- Industry (Dropdown) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Industry</label>
                    <Dropdown v-model="profile.industry" :options="industries" optionLabel="name" class="w-full" placeholder="Select industry" />
                </div>

                <!-- Founded Year -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Founded Year</label>
                    <Calendar v-model="profile.founded_year" view="year" class="w-full" placeholder="Select year" />
                </div>

                <!-- Company Size -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Company Size</label>
                    <Dropdown v-model="profile.company_size" :options="companySizes" class="w-full" placeholder="Select size" />
                </div>

                <!-- Country -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Country</label>
                    <Dropdown v-model="profile.country" :options="countries" class="w-full" placeholder="Select country" />
                </div>

                <!-- Website -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Company Website</label>
                    <InputText v-model="profile.company_website" class="w-full" placeholder="https://yourcompany.com" />
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Email</label>
                    <InputText v-model="profile.contact_email" class="w-full" placeholder="contact@company.com" />
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Phone</label>
                    <InputText v-model="profile.contact_phone" class="w-full" placeholder="Enter phone number" />
                </div>

                <!-- Social Media Links -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                    <InputText v-model="profile.linkedin_url" class="w-full" placeholder="LinkedIn profile link" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Twitter URL</label>
                    <InputText v-model="profile.twitter_url" class="w-full" placeholder="Twitter profile link" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Facebook URL</label>
                    <InputText v-model="profile.facebook_url" class="w-full" placeholder="Facebook profile link" />
                </div>

                <!-- Upload & Preview Logo -->
                <div class="col-span-2 flex flex-col items-center">
                    <label class="block text-sm font-medium text-gray-700">Company Logo</label>

                    <!-- Image Preview -->
                    <div v-if="profile.company_logo" class="my-3">
                        <img :src="profile.company_logo" class="w-28 h-28 object-cover rounded-lg shadow-md border" />
                    </div>

                    <FileUpload mode="basic" customUpload accept="image/*" :maxFileSize="1000000" @select="onLogoUpload" />
                </div>

                <!-- Company Description -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Company Description</label>
                    <Textarea v-model="profile.company_description" rows="4" class="w-full" placeholder="Brief company description" />
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end mt-4 space-x-3">
                <Button label="Cancel" class="p-button-text" @click="showDialog = false" />
                <Button type="submit" label="Save Changes" class="p-button-primary" />
            </div>
        </form>
    </Dialog>
</template>

<script setup>
import { ref, reactive } from "vue";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";
import FileUpload from "primevue/fileupload";
import Textarea from "primevue/textarea";
import Button from "primevue/button";

// Reactive profile data
const showDialog = ref(false);
const profile = reactive({
    company_name: "",
    industry: "",
    founded_year: null,
    company_size: "",
    country: "",
    company_website: "",
    contact_email: "",
    contact_phone: "",
    linkedin_url: "",
    twitter_url: "",
    facebook_url: "",
    company_description: "",
    company_logo: null
});

// Dropdown options
const industries = [
    { name: "Technology" },
    { name: "Finance" },
    { name: "Healthcare" },
    { name: "Education" },
    { name: "Retail" },
    { name: "Construction" },
    { name: "Other" }
];

const companySizes = ["Small", "Medium", "Large"];
const countries = ["United States", "United Kingdom", "Germany", "Canada", "Australia", "Other"];

// Handle logo upload and preview
const onLogoUpload = (event) => {
    const file = event.files[0];
    const reader = new FileReader();

    reader.onload = (e) => {
        profile.company_logo = e.target.result;
    };

    reader.readAsDataURL(file);
};

// Submit form
const submitProfile = () => {
    console.log("Profile updated:", profile);
    showDialog.value = false;
};

// Open the dialog (use this in parent component)
const openEditProfile = () => {
    showDialog.value = true;
};

defineExpose({ openEditProfile });
</script>

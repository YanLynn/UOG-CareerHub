<template>
    <div class="mt-4">
        <Card class="shadow-md hover:shadow-lg transition-shadow duration-200">
            <template #content>

                <!-- Status Buttons -->
                <div class="flex mb-4 gap-2 justify-end">
                    <Button rounded label="1" class="w-8 h-8 p-0" :outlined="value !== '0'"
                        @click="jobStatusAppend('pending'), value = '0'" />
                    <Button rounded label="1" class="w-8 h-8 p-0" :outlined="value !== '1'"
                        @click="jobStatusAppend('saved'), value = '1'" />
                    <Button rounded label="2" class="w-8 h-8 p-0" :outlined="value !== '2'"
                        @click="jobStatusAppend('approved'), value = '2'" />
                </div>

                <!-- Tabs -->
                <Tabs v-model:value="value">
                    <TabList>
                        <Tab value="0" @click="jobStatusAppend('pending')">Job Applied</Tab>
                        <Tab value="1" @click="jobStatusAppend('saved')">Saved Jobs</Tab>
                        <Tab value="2" @click="jobStatusAppend('approved')">Approved Jobs</Tab>
                    </TabList>

                    <TabPanels class="!border-0">
                        <TabPanel v-for="tabValue in ['0', '1', '2']" :key="tabValue" :value="tabValue" class="!border-0">

                            <!-- Split Layout -->
                            <Splitter class="h-[600px] min-h-[600px] !border-0">
                                <!-- Left Panel: Job List -->
                                <SplitterPanel :size="40" :minSize="10" class="!border-0">
                                    <ScrollPanel style="width: 100%; height: 100%;" class="!border-0">
                                        <div class="grid grid-cols-1 gap-4 p-4">
                                            <JobCard v-for="job in jobs" :key="job.id" :job="job"
                                                @click="openJobDetail(job)" :selectedJobId="selectedJobId"
                                                @select="handleSelect" />
                                        </div>
                                    </ScrollPanel>
                                </SplitterPanel>

                                <!-- Right Panel: Job Details -->
                                <SplitterPanel :size="60" class="h-full">
                                    <div class="p-4 h-full flex flex-col">
                                        <div v-if="selectedJobId !== null" class="h-full overflow-auto">
                                            <JobDetail :job="selectedJob" />
                                        </div>
                                        <div v-else class="text-center flex flex-col items-center justify-center h-full text-gray-500">
                                            <h2 class="mt-4 text-xl font-semibold text-gray-700">
                                                <i class="pi pi-arrow-left"></i> Select a job
                                            </h2>
                                            <p class="text-sm mt-1">Display details here</p>
                                        </div>
                                    </div>
                                </SplitterPanel>
                            </Splitter>

                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </template>
        </Card>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Tabs from 'primevue/tabs'
import TabList from 'primevue/tablist'
import Tab from 'primevue/tab'
import TabPanels from 'primevue/tabpanels'
import TabPanel from 'primevue/tabpanel'
import Splitter from 'primevue/splitter'
import SplitterPanel from 'primevue/splitterpanel'
import ScrollPanel from 'primevue/scrollpanel'
import JobCard from '../../components/JobCard.vue'
import JobDetail from '../../components/JobDetail.vue'
import { useAuthStore } from '@/store'
import { useToast } from 'primevue/usetoast'

// Selected tab
const value = ref('0')
const selectedJob = ref({})
const selectedJobId = ref(null)
const authStore = useAuthStore()
const toast = useToast()
const jobStatus = ref('pending')
const jobs = ref([])

onMounted(async () => {
    try {
        await fetchJobs()
    } catch (error) {
        console.error(error)
    }
})

async function fetchJobs() {
    try {
        const res = await authStore.jobSeekerJobList(jobStatus.value)
        jobs.value = res.data
        console.log('')
    } catch (error) {
        console.error('Error fetching jobs:', error)
    }
}

async function jobStatusAppend(status) {
    selectedJobId.value = null
    jobStatus.value = status
    await fetchJobs()
}

function openJobDetail(job) {
    selectedJob.value = job
    selectedJobId.value = job.id
}

function handleSelect(job) {
    selectedJobId.value = job.id
}
</script>

<template>

    <div class="mt-4">
        <Card class="shadow-md hover:shadow-lg transition-shadow duration-200">
            <template #content>

                <div class="flex mb-4 gap-2 justify-end">
                    <Button rounded label="1" class="w-8 h-8 p-0" :outlined="value !== '0'"
                        @click="jobStatusAppend('pending'), value = '0'" />
                    <Button rounded label="1" class="w-8 h-8 p-0" :outlined="value !== '1'"
                        @click="jobStatusAppend('saved'), value = '1'" />
                    <Button rounded label="2" class="w-8 h-8 p-0" :outlined="value !== '2'"
                        @click="jobStatusAppend('approved'), value = '2'" />


                </div>


                <Tabs v-model:value="value">
                    <TabList>
                        <Tab value="0" @click="jobStatusAppend('pending')">Job Applied</Tab>
                        <Tab value="1" @click="jobStatusAppend('saved')">Saved Jobs</Tab>
                        <Tab value="2" @click="jobStatusAppend('approved')">Approved Jobs</Tab>
                    </TabList>

                    <TabPanels class="!border-0">

                        <TabPanel value="0" class="!border-0">

                            <Splitter class="h-[600px] !border-0">
                                <SplitterPanel :size="40" :minSize="10" class="!border-0">

                                    <ScrollPanel style="width: 100%; height: 100%;" class="!border-0">
                                        <div class="grid grid-cols-1 gap-4 p-4">
                                            <JobCard v-for="job in jobs" :key="job.id" :job="job"
                                                @click="openJobDetail(job)" :selectedJobId="selectedJobId"
                                                @select="handleSelect" />
                                        </div>
                                    </ScrollPanel>
                                </SplitterPanel>

                                <SplitterPanel :size="60">
                                    <div class="p-4 text-gray-700">
                                        <div v-if="selectedJobId != null">
                                            <JobDetail :job="selectedJob" />
                                        </div>
                                        <div v-else class="text-center py-8">
                                            <div
                                                class="flex flex-col items-center justify-center h-full p-8 text-center text-gray-500">

                                                <h2 class="mt-4 text-xl font-semibold text-gray-700"><i
                                                        class="pi pi-arrow-left"></i> Select a job</h2>
                                                <p class="text-sm mt-1">Display details here</p>
                                            </div>
                                        </div>
                                    </div>
                                </SplitterPanel>
                            </Splitter>
                        </TabPanel>


                        <TabPanel value="1">

                            <Splitter class="h-[600px] !border-0">
                                <SplitterPanel :size="40" :minSize="10" class="!border-0">

                                    <ScrollPanel style="width: 100%; height: 100%;" class="!border-0">
                                        <div class="grid grid-cols-1 gap-4 p-4">
                                            <JobCard v-for="job in jobs" :key="job.id" :job="job"
                                                @click="openJobDetail(job)" :selectedJobId="selectedJobId"
                                                @select="handleSelect" />
                                        </div>
                                    </ScrollPanel>
                                </SplitterPanel>

                                <SplitterPanel :size="60">
                                    <div class="p-4 text-gray-700">
                                        <div v-if="selectedJobId != null">
                                            <JobDetail :job="selectedJob" />
                                        </div>
                                        <div v-else class="text-center py-8">
                                            <div
                                                class="flex flex-col items-center justify-center h-full p-8 text-center text-gray-500">

                                                <h2 class="mt-4 text-xl font-semibold text-gray-700"><i
                                                        class="pi pi-arrow-left"></i> Select a job</h2>
                                                <p class="text-sm mt-1">Display details here</p>
                                            </div>
                                        </div>
                                    </div>
                                </SplitterPanel>
                            </Splitter>
                        </TabPanel>


                        <TabPanel value="2">

                            <Splitter class="h-[600px] !border-0">
                                <SplitterPanel :size="40" :minSize="10" class="!border-0">

                                    <ScrollPanel style="width: 100%; height: 100%;" class="!border-0">
                                        <div class="grid grid-cols-1 gap-4 p-4">
                                            <JobCard v-for="job in jobs" :key="job.id" :job="job"
                                                @click="openJobDetail(job)" :selectedJobId="selectedJobId"
                                                @select="handleSelect" />
                                        </div>
                                    </ScrollPanel>
                                </SplitterPanel>

                                <SplitterPanel :size="60">
                                    <div class="p-4 text-gray-700">
                                        <div v-if="selectedJobId != null">
                                            <JobDetail :job="selectedJob" />
                                        </div>
                                        <div v-else class="text-center py-8">
                                            <div
                                                class="flex flex-col items-center justify-center h-full p-8 text-center text-gray-500">

                                                <h2 class="mt-4 text-xl font-semibold text-gray-700"><i
                                                        class="pi pi-arrow-left"></i> Select a job</h2>
                                                <p class="text-sm mt-1">Display details here</p>
                                            </div>
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
// Which tab is selected
const value = ref('0')
const jobDetailVisible = ref(false)
const selectedJob = ref({})
const selectedJobId = ref(null)
const authStore = useAuthStore()
const toast = useToast()
const jobStatus = ref('pending')
const jobs = ref()



onMounted(async () => {
    try {
        const res = await authStore.jobSeekerJobList(jobStatus.value)
        jobs.value = res.data
        console.log(res.data)
    } catch (error) {

    }
})


async function jobStatusAppend(status) {
    selectedJobId.value = null
    jobStatus.value = status
    const res = await authStore.jobSeekerJobList(jobStatus.value)
    jobs.value = res.data
}

function openJobDetail(job) {
    selectedJob.value = job
    jobDetailVisible.value = true
}

function handleSelect(job) {
    selectedJobId.value = job.id
}




</script>

<template>
    <div class="p-6 space-y-6">
        <!-- Admin Dashboard Header -->
        <h2 class="text-2xl font-bold text-gray-800">🚀 CareerHub Admin Dashboard</h2>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <Card><template #title>👥 Jobseekers</template><template #content>
                    <h3 class="text-3xl">{{ metrics.jobseekers }}</h3>
                </template></Card>
            <Card><template #title>🏢 Employers</template><template #content>
                    <h3 class="text-3xl">{{ metrics.employers }}</h3>
                </template></Card>
            <Card><template #title>📄 Jobs Posted</template><template #content>
                    <h3 class="text-3xl">{{ metrics.jobs }}</h3>
                </template></Card>
        </div>

        <!-- Platform Activity Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <Card>
                <template #title>📈 Job Post Growth (Last 5 Months)</template>
                <template #content>
                    <Chart type="line" :data="jobPostChartData" :options="chartOptions" style="max-height: 250px" />
                </template>
            </Card>

            <!-- Creative Chart Idea: Application Conversion Rate -->
            <Card>
                <template #title>📊 Application Conversion Rate</template>
                <template #content>
                    <Chart type="doughnut" :data="conversionChartData" style="max-height: 250px" />
                    <p class="mt-2 text-sm text-gray-500">Shows ratio of approved vs rejected applications.</p>
                </template>
            </Card>
        </div>

        <!-- Top Employers Table -->
        <Card>
            <template #title>🏆 Top 5 Employers</template>
            <template #content>
                <DataTable :value="topEmployers">
                    <Column field="name" header="Name" />
                    <Column field="jobs" header="Jobs Posted" />
                </DataTable>
            </template>
        </Card>

        <!-- Moderation Panel -->
        <Card>
            <template #title>🚨 Reported Job Posts</template>
            <template #content>
                <ul class="list-disc ml-5">
                    <li v-for="report in reportedJobs" :key="report.id">
                        {{ report.title }} ({{ report.flags }} flags)
                        <Button label="Review" icon="pi pi-eye" class="ml-2" size="small" />
                    </li>
                </ul>
            </template>
        </Card>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Card from 'primevue/card';
import Chart from 'primevue/chart';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

const metrics = ref({
    jobseekers: 1580,
    employers: 420,
    jobs: 930
});

const jobPostChartData = ref({
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
    datasets: [
        {
            label: 'Jobs Posted',
            data: [20, 45, 70, 50, 80],
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.2)',
            fill: true,
            tension: 0.4
        }
    ]
});

const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        y: {
            beginAtZero: true
        }
    }
});

const conversionChartData = ref({
    labels: ['Approved', 'Rejected'],
    datasets: [
        {
            data: [320, 180],
            backgroundColor: ['#22c55e', '#ef4444'],
            hoverOffset: 4
        }
    ]
});

const topEmployers = ref([
    { name: 'GlobalTech Ltd.', jobs: 45 },
    { name: 'DreamHire', jobs: 38 },
    { name: 'EduBridge Co.', jobs: 33 },
    { name: 'HealthLine', jobs: 27 },
    { name: 'InnoWorks', jobs: 22 }
]);

const reportedJobs = ref([
    { id: 1, title: 'Fake Remote Data Entry Job', flags: 4 },
    { id: 2, title: 'Unpaid Internship Scam', flags: 3 }
]);
</script>

<style scoped>
h2 {
    margin-bottom: 1rem;
}
</style>

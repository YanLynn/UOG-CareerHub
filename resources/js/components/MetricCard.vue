<template>
    <Card class="shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300">
        <template #content>
            <div class="relative p-6 rounded-lg" :class="bgClass">

                <!-- Floating Icon -->
                <div class="absolute top-3 right-3 bg-white bg-opacity-20 p-3 rounded-full shadow-md">
                    <i :class="[icon, 'text-3xl text-white']"></i>
                </div>

                <!-- Title & Count -->
                <h3 class="text-lg font-semibold text-white">{{ title }}</h3>
                <p class="text-4xl font-bold mt-3 text-white">
                    <Skeleton width="4rem" height="2rem" v-if="loading" />
                    <span v-else>{{ formattedValue }}</span>
                </p>

                <!-- Animated Progress Bar -->
                <div class="mt-4 h-2 bg-white bg-opacity-30 rounded-full overflow-hidden">
                    <div class="h-2 bg-white rounded-full transition-all duration-[1.5s] ease-in-out"
                        :style="{ width: progressWidth }"></div>
                </div>

                <!-- Small Badge -->
                <div class="mt-3 text-sm text-white">
                    <i class="pi pi-chart-line"></i> {{ subtitle }}
                </div>
            </div>
        </template>
    </Card>
</template>

<script setup>
import { ref, computed, watch, onMounted } from "vue";
import Skeleton from "primevue/skeleton";

const props = defineProps({
    title: String,
    value: [String, Number],
    icon: String,
    color: String,
    subtitle: String,
    progress: Number,
    loading: Boolean
});

// Dynamic Background Color
const bgClass = computed(() => {
    const colors = {
        blue: "bg-gradient-to-r from-blue-500 to-blue-700",
        green: "bg-gradient-to-r from-green-500 to-green-700",
        purple: "bg-gradient-to-r from-purple-500 to-purple-700"
    };
    return colors[props.color] || "bg-gray-500";
});

// **Fix NaN Issue: Ensure the value is always a number**
const formattedValue = computed(() => {
    const num = Number(props.value);
    return isNaN(num) || props.value === null || props.value === undefined ? 0 : num;
});

// Progress Bar Animation
const progressWidth = ref("0%");

onMounted(() => {
    setTimeout(() => {
        progressWidth.value = props.progress ? props.progress + "%" : "0%";
    }, 300);
});

// Watch for Updates
watch(() => props.progress, (newProgress) => {
    progressWidth.value = newProgress ? newProgress + "%" : "0%";
});
</script>

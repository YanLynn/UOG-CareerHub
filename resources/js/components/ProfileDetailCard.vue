<template>
    <Card class="shadow-lg border border-gray-200 dark:border-gray-700">
        <template #content>
            <h3 class="text-lg font-semibold">{{ title }}</h3>
            <div class="mt-3 space-y-2">
                <div v-for="item in info" :key="item.text" class="flex items-center space-x-3">
                    <i :class="[item.icon, 'text-blue-500']"></i>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <a v-if="item.link" :href="item.link" target="_blank" class="text-blue-500 hover:underline">{{ item.text }}</a>
                        <span v-else>{{ item.text }}</span>
                    </p>
                </div>
            </div>
        </template>
    </Card>
</template>

<script setup>
defineProps({
    title: String,
    info: Array // Array of { icon: "pi pi-icon", text: "Some text", link: "optional-url" }
});
</script>

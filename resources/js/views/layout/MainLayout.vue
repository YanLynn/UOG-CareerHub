<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <Toast />
        <Nav />


        <main>
            <router-view />
        </main>
    </div>
</template>

<script setup>
import Nav from '../../components/Navbar.vue';
import Toast from '../../components/Toast.vue';
</script>

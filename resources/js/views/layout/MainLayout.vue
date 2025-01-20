<template>
    <div
    class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100
           relative bg-cover bg-center
           bg-[url('https://readymadeui.com/bg-effect.svg')]
           dark:bg-gradient-to-b dark:from-gray-900 dark:via-gray-800 dark:to-gray-900
           transition-all duration-500 ease-in-out">
        <Toast />
        <Nav class="fixed top-0 left-0 w-full z-50"/>
        <main class="pt-10">
            <router-view />
        </main>
        <Footer/>

    </div>
</template>

<script setup>
import Nav from '@components/js/components/Navbar.vue';
import Toast from '@components/js/components/Toast.vue';
import Header from '@components/js/components/Header.vue';
import Footer from '@components/js/components/Footer.vue';



</script>

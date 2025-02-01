<template>
    <div>

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

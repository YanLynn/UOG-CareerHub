
<template>
    <div class="min-h-screen  dark:bg-gray-900 text-gray-900 dark:text-gray-100
           relative bg-cover bg-center
           bg-[url('https://readymadeui.com/bg-effect.svg')]
           dark:bg-gradient-to-b dark:from-gray-900 dark:via-gray-800 dark:to-gray-900
           transition-all duration-500 ease-in-out">
           <Toast />
           <ConfirmDialog></ConfirmDialog>

        <router-view />
    </div>
</template>




<style>
@import 'tailwindcss/tailwind.css';

</style>

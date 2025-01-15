<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
      <!-- Hero Section -->
      <section class="bg-cover bg-center h-96" style="background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d');">
      <div class="flex items-center justify-center h-full bg-gray-900 bg-opacity-50">
        <div class="text-center text-white px-6">
          <h2 class="text-4xl font-bold mb-4">Find the Right Job for You</h2>
          <p class="text-lg mb-6">Join thousands of professionals and discover your dream career.</p>
          <div class="flex flex-col md:flex-row justify-center items-center gap-4">
            <input
              type="text"
              placeholder="Job title or keyword"
              class="w-72 px-4 py-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
            />
            <input
              type="text"
              placeholder="Country"
              class="w-72 px-4 py-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
            />
            <button
              class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
            >
              Search
            </button>
          </div>
        </div>
      </div>
    </section>

      <!-- Job Categories Section -->
      <section class="py-16 bg-white dark:bg-gray-800">
        <div class="container mx-auto text-center">
          <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6">Popular Job Categories</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-6">
            <div class="p-6 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md hover:bg-blue-100 dark:hover:bg-blue-900 transition">
              <h4 class="text-xl font-semibold mb-2">Technology</h4>
              <p>Discover jobs in software development, AI, and IT support.</p>
            </div>
            <div class="p-6 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md hover:bg-blue-100 dark:hover:bg-blue-900 transition">
              <h4 class="text-xl font-semibold mb-2">Marketing</h4>
              <p>Explore roles in digital marketing, SEO, and content creation.</p>
            </div>
            <div class="p-6 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md hover:bg-blue-100 dark:hover:bg-blue-900 transition">
              <h4 class="text-xl font-semibold mb-2">Finance</h4>
              <p>Work with leading financial institutions and fintech startups.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Call to Action -->
      <section class="bg-blue-600 py-12">
        <div class="container mx-auto text-center text-white">
          <h3 class="text-3xl font-bold mb-4">Ready to Advance Your Career?</h3>
          <p class="mb-6">Create your profile and apply for jobs that fit your goals.</p>
          <router-link to="/" class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-md hover:bg-gray-100 transition">
            Sign Up Now
          </router-link>
        </div>
      </section>
    </div>
  </template>

  <script setup>

  </script>

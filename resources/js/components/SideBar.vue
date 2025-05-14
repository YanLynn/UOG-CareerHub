<template>
    <aside
        class="h-screen w-64 bg-white/80 shadow-xl backdrop-blur-md rounded-r-xl p-4 flex flex-col justify-between transition-all duration-300">
        <!-- Header -->
        <div>
            <div class="flex items-center mb-6 px-2">
                <router-link to="/" class="text-blue-600 font-bold text-2xl tracking-wide">
                    Career<span class="text-gray-800">Hub</span>
                </router-link>
            </div>

            <!-- Admin Info -->
            <div class="flex items-center space-x-3 px-2 mb-6">
                <Avatar image="https://i.pravatar.cc/40" shape="circle" />
                <div>
                    <div class="text-sm font-semibold text-gray-700">Admin</div>
                    <div class="text-xs text-gray-500">Administrator</div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <!-- <PanelMenu :model="items" class="custom-panelmenu text-sm" /> -->
            <PanelMenu :model="items" class="custom-panelmenu text-sm">
                <template #item="{ item }">
                    <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
                        <a v-ripple
                            class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
                            :href="href" @click="navigate">
                            <span :class="item.icon" />
                            <span class="ml-2">{{ item.label }}</span>
                        </a>
                    </router-link>
                    <a v-else v-ripple
                        class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
                        :href="item.url" :target="item.target">
                        <span :class="item.icon" />
                        <span class="ml-2">{{ item.label }}</span>
                        <span v-if="item.items" class="pi pi-angle-down text-primary ml-auto" />
                    </a>
                </template>
            </PanelMenu>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-400 mt-4">
            © {{ new Date().getFullYear() }} CareerHub Admin
        </div>
    </aside>
</template>

<script setup>
import PanelMenu from 'primevue/panelmenu'
import Avatar from 'primevue/avatar'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const logout = () => {
    localStorage.removeItem('user')
    router.push('/login')
}

const items = ref([
    {
        label: 'Dashboard',
        icon: 'pi pi-home',
        route: '/admin/dashboard'
    },
    {
        label: 'Information',
        icon: 'pi pi-info-circle',
        items: [
            { label: 'User Management', icon: 'pi pi-users', route: '/admin/user' },
            // { label: 'Suspended Users', icon: 'pi pi-ban', to: '/admin/user/suspended' },
            // { label: 'Activity Logs', icon: 'pi pi-clock', to: '/admin/user/activity' },
            // { label: 'Posts', icon: 'pi pi-file', to: '/admin/posts' },
            // { label: 'Schedules', icon: 'pi pi-calendar', to: '/admin/schedules' },
            // { label: 'Promote', icon: 'pi pi-bolt', to: '/admin/promote' }
        ]
    },
    // {
    //     label: 'Income',
    //     icon: 'pi pi-wallet',
    //     items: [
    //         { label: 'Earnings & Taxes', icon: 'pi pi-chart-line', to: '/admin/earnings' },
    //         { label: 'Refunds', icon: 'pi pi-refresh', to: '/admin/refunds' }
    //     ]
    // },
    {
        label: 'Actions',
        icon: 'pi pi-cog',
        items: [
            // { label: 'Profile', icon: 'pi pi-user', to: '/admin/profile' },
            { label: 'Logout', icon: 'pi pi-sign-out', command: logout }
        ]
    }
])
</script>

<style scoped>
/* Custom PanelMenu styling */
::v-deep(.p-panelmenu .p-menuitem-link) {
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    transition: background 0.2s ease;
    color: #1f2937;
}

::v-deep(.p-panelmenu .p-menuitem-link:hover) {
    background-color: #dbeafe;
    color: #1d4ed8;
}

::v-deep(.p-panelmenu .p-menuitem-icon) {
    margin-right: 0.6rem;
    color: #2563eb;
}

::v-deep(.p-panelmenu .p-menuitem-active > .p-menuitem-link) {
    background-color: #eff6ff;
    font-weight: 600;
}
</style>

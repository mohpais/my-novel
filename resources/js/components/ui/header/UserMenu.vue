<template>
    <div class="relative" ref="dropdownRef">
        <button
            class="flex items-center gap-2 text-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-700 dark:bg-brand-500 px-3 py-1 rounded-md"
            @click.prevent="toggleDropdown"
        >
            <div class="overflow-hidden rounded-full h-8 w-8 flex">
                <Image
                    :src="$auth.currentUser?.picture_url"
                    id="photo"
                    :alt="$auth.currentUser?.name"
                />
            </div>
            <div class="flex flex-col items-start text-gray-800 dark:text-gray-200 gap-0">
                <span class="font-semibold -mb-1">Moh Pais</span>
                <small>Procurement</small>
            </div>
            <ChevronDownIcon :class="{ 'rotate-180': dropdownOpen }" class="w-4 h-4 dark:text-gray-200" />
        </button>

        <!-- Dropdown Start -->
        <div
            v-if="dropdownOpen"
            class="absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark"
        >
            <div>
                <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">
                    {{ $auth.currentUser?.name }}
                </span>
                <span class="mt-0.5 block text-theme-xs text-gray-500 dark:text-gray-400">
                    {{ $auth.currentUser?.role.name }}
                </span>
            </div>

            <ul class="flex flex-col gap-1 pt-4 pb-3 border-b border-gray-200 dark:border-gray-800">
                <li v-for="item in menuItems" :key="item.href">
                    <router-link
                        :to="item.href"
                        class="flex items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                    >
                        <!-- SVG icon would go here -->
                        <component
                            :is="item.icon"
                            class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300"
                        />
                        {{ item.text }}
                    </router-link>
                </li>
            </ul>
            <div
                @click="modalOpen = !modalOpen"
                class="px-3 py-2 mt-3 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 cursor-pointer"
            >
                <div class="flex items-center gap-3" v-if="!authStore.isLoading">
                    <LogoutIcon
                        class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300"
                    />
                    Sign out
                </div>
                <Loading v-else />
            </div>
        </div>
        <!-- Dropdown End -->
    </div>

    <Modal
        :isVisible="modalOpen"
        :hideHeader="true"
        @close="closeModal"
        size="sm"
    >
        <template #body>
            <div class="flex flex-col items-center justify-center text-center mb-4">
                <span class="mb-4 inline-flex justify-center items-center size-15.5 rounded-full border-4 border-yellow-50 bg-yellow-100 text-yellow-500 dark:bg-yellow-700 dark:border-yellow-600 dark:text-yellow-100">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                    </svg>
                </span>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Sign out
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to sign out? You will need to log in again to access your account.
                </p>
                <div class="flex w-full justify-center gap-2 mt-4">
                    <button
                        @click="closeModal"
                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto"
                    >
                        Cancel
                    </button>
                    <button
                        @click="signOut"
                        class="flex w-full justify-center rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 sm:w-auto"
                    >
                        Yes, sign out
                    </button>
                </div>
            </div>
        </template>
    </Modal>
</template>

<script setup>
import { UserCircleIcon, ChevronDownIcon, LogoutIcon, SettingsIcon, InfoCircleIcon } from '../../icons'
import { RouterLink } from 'vue-router'
import { ref, onMounted, onUnmounted } from 'vue';
import { useAuthStore } from "@/stores/useAuthStore";

import User1 from '@/assets/images/user/user-01.png'

const authStore = useAuthStore();

const modalOpen = ref(false)
const dropdownOpen = ref(false)
const dropdownRef = ref(null)

const menuItems = [
    { href: '/user/profile', icon: UserCircleIcon, text: 'Edit profile' },
    // { href: '/chat', icon: SettingsIcon, text: 'Account settings' },
    // { href: '/profile', icon: InfoCircleIcon, text: 'Support' },
]

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value
}

const closeDropdown = () => {
    dropdownOpen.value = false
}

const signOut = async () => {
    // Implement sign out logic here
    closeModal();
    closeDropdown();
    await authStore.doLogout();
}

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        closeDropdown()
    }
}

const closeModal = () => {
    modalOpen.value = false;
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>

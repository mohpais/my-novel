<template>
    <RouterLink :to="appUrl" class="h-full" :class="show ? 'inline' : 'lg:hidden'">
        <img class="h-10 dark:hidden" :src="logoLight" alt="Logo" />
        <img class="h-10 hidden dark:block" :src="logoDark" alt="Logo" />
    </RouterLink>
</template>

<script setup>
    const appUrl = import.meta.env.VITE_APP_URL || '/home'
    const appName = import.meta.env.VITE_APP_NAME || 'App'

    import logoLight from "@/assets/logos/logo.svg";
    import logoDark from "@/assets/logos/logo-dark.svg";

    defineProps({
        show: {
            type: Boolean,
            default: false
        }
    })
</script>

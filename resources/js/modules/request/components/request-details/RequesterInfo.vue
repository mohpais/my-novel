<template>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <h2 class="mb-5 text-sm font-semibold text-gray-800 dark:text-white/90">Customer Details</h2>
        <ul class="divide-y divide-gray-100 dark:divide-gray-800">
            <li class="flex items-start gap-5 py-2.5">
                <span class="w-1/2 text-gray-500 sm:w-1/3 dark:text-gray-400">Employee ID</span>
                <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">{{ requester.employee_number || 'Not Set' }}</span>
            </li>
            <li class="flex items-start gap-5 py-2.5">
                <span class="w-1/2 text-gray-500 sm:w-1/3 dark:text-gray-400">Name</span>
                <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">{{ requester.name }}</span>
            </li>
            <li class="flex items-start gap-5 py-2.5">
                <span class="w-1/2 text-gray-500 sm:w-1/3 dark:text-gray-400">Email</span>
                <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">{{ requester.email }}</span>
            </li>
            <li class="flex items-start gap-5 py-2.5">
                <span class="w-1/2 text-gray-500 sm:w-1/3 dark:text-gray-400">Department</span>
                <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">ICT</span>
            </li>
            <li class="flex items-start gap-5 py-2.5">
                <span class="w-1/2 text-gray-500 sm:w-1/3 dark:text-gray-400">Position</span>
                <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">Spv Application Engineer</span>
            </li>
        </ul>
    </div>
</template>

<script setup>
    defineProps({
        requester: {
            type: Object,
            required: false,
            default: () => ({})
        }
    });
</script>

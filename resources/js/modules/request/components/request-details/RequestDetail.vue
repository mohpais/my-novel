<template>
    <h2 class="mb-5 text-sm font-semibold text-gray-800 dark:text-white/90">Request Detail</h2>
    <ul class="divide-y divide-gray-100 dark:divide-gray-800">
        <li class="flex items-start gap-5 py-2.5">
            <span class="w-2/6 font-semibold text-gray-500 dark:text-gray-400">Asset Name</span>
            <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">{{ asset_request.name }}</span>
        </li>
        <li class="flex items-start gap-5 py-2.5">
            <span class="w-2/6 font-semibold text-gray-500 dark:text-gray-400">Asset Description</span>
            <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">{{ asset_request.description || '-' }}</span>
        </li>
        <li class="flex items-start gap-5 py-2.5">
            <span class="w-2/6 font-semibold text-gray-500 dark:text-gray-400">Useful Life</span>
            <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">{{ asset_request.useful_life }} Years</span>
        </li>
        <li class="flex items-start gap-5 py-2.5">
            <span class="w-2/6 font-semibold text-gray-500 dark:text-gray-400">Reason</span>
            <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">{{ asset_request.reason }}</span>
        </li>
        <li class="flex items-start gap-5 py-2.5">
            <span class="w-2/6 font-semibold text-gray-500 dark:text-gray-400">Purchase Quantity</span>
            <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">{{ asset_request.purchase_quantity || '0' }} Item</span>
        </li>
        <li class="flex items-start gap-5 py-2.5">
            <span class="w-2/6 font-semibold text-gray-500 dark:text-gray-400">Currency</span>
            <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">{{ asset_request.initial_value || '-' }}</span>
        </li>
        <li class="flex items-start gap-5 py-2.5">
            <span class="w-2/6 font-semibold text-gray-500 dark:text-gray-400">Budget</span>
            <span class="w-1/2 text-gray-700 sm:w-2/3 dark:text-gray-400">{{ asset_request.budget.code }}</span>
        </li>
    </ul>
</template>

<script setup>
    defineProps({
        asset_request: {
            type: Object,
            required: true
        }
    });
</script>

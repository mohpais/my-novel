<template>
    <div class="rounded-xl border border-gray-200 p-6 dark:border-gray-800">
        <div class="border-b border-gray-200 dark:border-gray-800">
            <nav class="-mb-px flex space-x-2 overflow-x-auto [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-gray-600 dark:[&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar]:h-1.5">
                <button 
                    v-for="(item, index) in tabs" 
                    :key="index"
                    @click="$emit('change', item.id)"
                    class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out"
                    :class="activeTab === item.id 
                        ? 'text-brand-500 border-brand-500 dark:text-brand-400 dark:border-brand-400' 
                        : 'bg-transparent text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                >
                    <component :is="item.icon" class="size-5" />
                    {{ item.name }}
                </button>
            </nav>
        </div>
        <div class="pt-6">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
defineProps({
    tabs: { type: Array, default: () => [] },
    activeTab: String
});
defineEmits(['change']);
</script>
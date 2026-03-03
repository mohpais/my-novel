<template>
    <div
        v-if="visible"
        class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-90 backdrop-blur-sm"
    >
        <slot>
            <div class="flex flex-col items-center space-y-2">
                <svg
                    class="w-10 h-10 animate-spin text-gray-800"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                />
                </svg>
                <span class="text-sm text-gray-700 font-medium">Loading...</span>
            </div>
        </slot>
    </div>
</template>

<script setup>
    import { computed } from 'vue'
    import { useLoadingStore } from '@/stores/useLoadingStore'

    const props = defineProps({
        visible: {
            type: Boolean,
            default: undefined // bisa true/false/manual
        }
    })

    const store = useLoadingStore()

    // Prioritaskan prop, fallback ke store jika tidak ada
    const visible = computed(() => {
        return props.visible !== undefined ? props.visible : store.isLoading
    })
</script>

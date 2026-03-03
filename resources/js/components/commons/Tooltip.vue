<template>
    <div
        @mouseenter="showTooltip = true"
        @mouseleave="showTooltip = false"
        @focus="showTooltip = true"
        @blur="showTooltip = false"
    >
        <slot></slot>
    </div>

    <Transition
        enter-active-class="transition-all duration-200 ease-out"
        leave-active-class="transition-all duration-150 ease-in"
        enter-from-class="opacity-0 scale-95"
        leave-to-class="opacity-0 scale-95"
    >
    <div
        v-if="showTooltip"
        role="tooltip"
        :class="[
            'absolute z-50 p-2 text-xs text-white bg-gray-800 rounded-md shadow-lg whitespace-nowrap',
            tooltipClasses,
            // Optional: dynamic opacity if needed
            { 'opacity-0 invisible pointer-events-none': !showTooltip } // handled by Transition
        ]"
        aria-hidden="true"
    >
        <slot name="content">{{ content }}</slot> <div
        v-if="arrow"
        :class="[
            'absolute w-2 h-2 bg-gray-800 transform rotate-45 -top-2',
            arrowClasses
        ]"
        ></div>
    </div>
    </Transition>
</template>

<script setup>
    import { ref, computed } from 'vue';

    const props = defineProps({
    content: {
        type: String,
        default: ''
    },
    position: {
        type: String,
        default: 'top', // top, bottom, left, right
        validator: (value) => ['top', 'bottom', 'left', 'right'].includes(value)
    },
    arrow: {
        type: Boolean,
        default: true // Tampilkan panah secara default
    }
});

const showTooltip = ref(false);

const tooltipClasses = computed(() => {
    switch (props.position) {
        case 'top':
            return 'bottom-full left-1/2 -translate-x-1/2 mb-2';
        case 'bottom':
            return 'top-full left-1/2 -translate-x-1/2 mt-2';
        case 'left':
            return 'right-full top-1/2 -translate-y-1/2 mr-2';
        case 'right':
            return 'left-full top-1/2 -translate-y-1/2 ml-2';
        default:
            return 'bottom-full left-1/2 -translate-x-1/2 mb-2';
    }
});

const arrowClasses = computed(() => {
    switch (props.position) {
        case 'top':
            return 'top-full left-1/2 -translate-x-1/2 -mt-1';
        case 'bottom':
            return 'bottom-full left-1/2 -translate-x-1/2 mt-1';
        case 'left':
            return 'left-full top-1/2 -translate-y-1/2 -ml-1';
        case 'right':
            return 'right-full top-1/2 -translate-y-1/2 -mr-1';
        default:
            return 'top-full left-1/2 -translate-x-1/2 -mt-1';
    }
});
</script>

<style scoped>
    /*
    Tidak ada CSS tambahan yang ketat diperlukan di sini karena Tailwind sudah sangat kuat.
    Namun, jika ada styling yang tidak dapat ditangani oleh Tailwind (sangat jarang untuk kasus ini),
    atau untuk memastikan rendering yang sempurna pada panah (misalnya, jika ada anti-aliasing issues),
    Anda bisa menambahkan CSS di sini.
    */
</style>

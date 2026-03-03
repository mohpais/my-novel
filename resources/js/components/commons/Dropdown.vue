<template>
    <div class="relative" :class="dropdownClasses" ref="dropdownRef">
        <div @click="toggleDropdown" @keydown.space.enter.prevent="toggleDropdown" class="cursor-pointer" ref="triggerRef">
            <slot name="trigger"></slot>
        </div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                :class="[
                    'absolute overflow-hidden z-50 rounded-md shadow-lg list-none bg-white ring-1 ring-black ring-opacity-5 focus:outline-none',
                    computedDropdownStaticClasses,
                    contentClasses
                ]"
                :style="computedDynamicStyles"
                role="menu"
                aria-orientation="vertical"
                aria-labelledby="dropdown"
                tabindex="-1"
                @click="handleContentClick" ref="contentRef"
            >
                <slot name="content" :closeDropdown="closeDropdown"></slot> </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue';

const props = defineProps({
    position: {
        type: String,
        default: 'bottom-right',
        validator: (value) => [
            'top-left', 'top-center', 'top-right',
            'bottom-left', 'bottom-center', 'bottom-right'
        ].includes(value)
    },
    width: {
        type: String,
        default: '56',
        validator: (value) => {
            return ['full', '48', '56', '64', '80', '96'].includes(value) || value.startsWith('w-');
        }
    },
    dropdownClasses: {
        type: String,
        default: ''
    },
    contentClasses: {
        type: String,
        default: ''
    },
    // Prop baru untuk mengontrol penutupan saat konten diklik secara umum
    closeOnContentClick: {
        type: Boolean,
        default: true // Defaultnya, klik di dalam konten akan menutup dropdown
    }
});

const isOpen = ref(false);
const dropdownRef = ref(null);
const triggerRef = ref(null);
const contentRef = ref(null);

const dynamicLeft = ref(null);
const dynamicRight = ref(null);
const dynamicTransform = ref(null);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const closeDropdown = () => {
    isOpen.value = false;
};

// Handler baru untuk klik di dalam konten dropdown
const handleContentClick = (event) => {
    // Memeriksa apakah elemen yang diklik atau salah satu leluhurnya memiliki atribut data-prevent-dropdown-close="true"
    const clickedElement = event.target.closest('[data-prevent-dropdown-close="true"]');

    // Jika elemen yang diklik tidak memiliki data-prevent-dropdown-close="true"
    // DAN closeOnContentClick prop diizinkan untuk menutup, baru panggil closeDropdown
    if (!clickedElement && props.closeOnContentClick) {
        closeDropdown();
    }
    // Jika clickedElement ada, atau closeOnContentClick adalah false,
    // dropdown tidak akan ditutup secara otomatis oleh handler ini.
    // Penutupan harus diatur secara manual oleh komponen anak jika prosesnya sudah selesai.
};

const handleClickOutside = (event) => {
    // Pastikan klik di luar elemen trigger dan konten dropdown
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        closeDropdown();
    }
};

const computedDropdownStaticClasses = computed(() => {
    let classes = '';
    classes += ` w-${props.width}`;

    switch (props.position) {
        case 'top-left':
            classes += ' bottom-full origin-bottom-left mb-2';
            break;
        case 'top-center':
            classes += ' bottom-full origin-bottom mb-2';
            break;
        case 'top-right':
            classes += ' bottom-full origin-bottom-right mb-2';
            break;
        case 'bottom-left':
            classes += ' top-full origin-top-left mt-2';
            break;
        case 'bottom-center':
            classes += ' top-full origin-top mt-2';
            break;
        case 'bottom-right':
            classes += ' top-full origin-top-right mt-2';
            break;
    }
    return classes;
});

const computedDynamicStyles = computed(() => {
    const styles = {};
    if (dynamicLeft.value !== null) {
        styles.left = `${dynamicLeft.value}px`;
        delete styles.right;
    } else if (dynamicRight.value !== null) {
        styles.right = `${dynamicRight.value}px`;
        delete styles.left;
    } else {
        delete styles.left;
        delete styles.right;
    }

    if (dynamicTransform.value !== null) {
        styles.transform = dynamicTransform.value;
    } else {
        delete styles.transform;
    }
    return styles;
});

const adjustDropdownPosition = () => {
    if (!isOpen.value || !dropdownRef.value || !triggerRef.value || !contentRef.value) {
        dynamicLeft.value = null;
        dynamicRight.value = null;
        dynamicTransform.value = null;
        return;
    }

    const triggerRect = triggerRef.value.getBoundingClientRect();
    const contentRect = contentRef.value.getBoundingClientRect();
    const dropdownWrapperRect = dropdownRef.value.getBoundingClientRect();
    const viewportWidth = window.innerWidth;
    const padding = 16;

    dynamicLeft.value = null;
    dynamicRight.value = null;
    dynamicTransform.value = null;

    if (props.position.includes('center')) {
        let intendedLeftRelativeToWrapper = (triggerRect.left + (triggerRect.width / 2)) - dropdownWrapperRect.left - (contentRect.width / 2);

        const dropdownAbsLeft = intendedLeftRelativeToWrapper + dropdownWrapperRect.left;
        const dropdownAbsRight = dropdownAbsLeft + contentRect.width;

        if (dropdownAbsLeft < padding) {
            dynamicLeft.value = padding - dropdownWrapperRect.left;
            dynamicTransform.value = 'translateX(0)';
        } else if (dropdownAbsRight > viewportWidth - padding) {
            dynamicRight.value = dropdownWrapperRect.right - (viewportWidth - padding);
            dynamicTransform.value = 'translateX(0)';
        } else {
            dynamicLeft.value = intendedLeftRelativeToWrapper;
            dynamicTransform.value = 'translateX(0)';
        }
    } else if (props.position.includes('left')) {
        let potentialAbsLeft = triggerRect.left;
        let potentialAbsRight = potentialAbsLeft + contentRect.width;

        if (potentialAbsLeft < padding) {
            dynamicLeft.value = padding - dropdownWrapperRect.left;
        } else if (potentialAbsRight > viewportWidth - padding) {
            dynamicLeft.value = (viewportWidth - padding) - dropdownWrapperRect.left - contentRect.width;
        } else {
            dynamicLeft.value = 0;
        }
    } else if (props.position.includes('right')) {
        let potentialAbsRight = triggerRect.right;
        let potentialAbsLeft = potentialAbsRight - contentRect.width;

        if (potentialAbsRight > viewportWidth - padding) {
            dynamicRight.value = padding - (viewportWidth - dropdownWrapperRect.right);
        } else if (potentialAbsLeft < padding) {
            dynamicRight.value = dropdownWrapperRect.right - (padding + contentRect.width);
        } else {
            dynamicRight.value = 0;
        }
    }
};

watch(isOpen, async (newValue) => {
    if (newValue) {
        await nextTick();
        adjustDropdownPosition();
        window.addEventListener('resize', adjustDropdownPosition);
    } else {
        window.removeEventListener('resize', adjustDropdownPosition);
    }
});

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('touchstart', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('touchstart', handleClickOutside);
    window.removeEventListener('resize', adjustDropdownPosition);
});

// Mengekspos closeDropdown agar bisa dipanggil dari luar (penting untuk kasus logout)
defineExpose({ closeDropdown });
</script>

<style scoped>
/* Anda bisa menambahkan CSS kustom di sini jika diperlukan,
   tetapi sebagian besar styling sudah ditangani oleh Tailwind. */
</style>

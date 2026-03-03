<script setup>
    import { defineEmits, onMounted, onUnmounted, watch } from 'vue';

    const props = defineProps({
        isVisible: {
            type: Boolean,
            default: false,
        },
        // Ukuran modal, bisa 'sm', 'md', atau 'lg'
        size: {
            type: String,
            default: 'md',
            validator: (value) => ['sm', 'md', 'lg'].includes(value),
        },
        title: {
            type: String,
            default: 'Modal Title',
        },
        hideHeader: {
            type: Boolean,
            default: false,
        },
        // Opsi untuk menutup modal saat klik di luar
        closeOnClickOutside: {
            type: Boolean,
            default: true,
        },
        // Opsi untuk menutup modal saat tombol ESC ditekan
        closeOnEsc: {
            type: Boolean,
            default: true,
        },
    });

    const emit = defineEmits(['close']);

    // Fungsi untuk menutup modal
    const closeModal = () => {
        emit('close');
    };

    // Handle klik di luar modal
    const handleClickOutside = (event) => {
        if (props.isVisible && props.closeOnClickOutside && !event.target.closest('.modal-content')) {
            closeModal();
        }
    };

    // Handle tombol ESC
    const handleKeydown = (event) => {
        // if (!modalReady) return;
        if (props.isVisible && props.closeOnClickOutside && !event.target.closest('.modal-content')) {
            closeModal();
        }
    };

    // Tambahkan/hapus event listener saat modal terlihat
    watch(() => props.isVisible, (newVal) => {
        if (newVal) {
            // Delay sedikit supaya klik pembuka tidak dianggap klik luar
            setTimeout(() => {
                document.addEventListener('click', handleClickOutside);
                document.addEventListener('keydown', handleKeydown);
            }, 100);

            // Tambahkan class 'overflow-hidden' ke body saat modal terbuka
            document.body.classList.add('overflow-hidden');

            if (!document.getElementById('drawer-backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.setAttribute('drawer-backdrop', '');
                backdrop.setAttribute('id', 'drawer-backdrop');
                backdrop.className = 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-[55]';
                document.body.appendChild(backdrop);
            }
        } else {
            document.removeEventListener('click', handleClickOutside);
            document.removeEventListener('keydown', handleKeydown);
            // Hapus class 'overflow-hidden' saat modal tertutup
            document.body.classList.remove('overflow-hidden');
            const backdrop = document.getElementById('drawer-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    }, { immediate: true }); // Jalankan watcher segera saat komponen dimuat

    // Pastikan event listener dihapus saat komponen di-unmount
    onUnmounted(() => {
        document.removeEventListener('click', handleClickOutside);
        document.removeEventListener('keydown', handleKeydown);
        document.body.classList.remove('overflow-hidden'); // Pastikan dihapus
    });
</script>

<template>
    <Transition name="modal-fade">
        <div
            v-if="isVisible"
            class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 bg-gray-400/50 backdrop-blur-[32px] z-999999 justify-center items-center w-full md:inset-0 h-full max-h-full flex"
            @click.self="handleClickOutside"
        >
            <div
                class="relative p-4 w-full max-w-2xl max-h-full"
                :class="{
                    'max-w-lg': size === 'sm',
                }"
            >
                <!-- Modal Content -->
                <div
                    class="relative bg-gray-100 rounded-lg shadow-sm dark:bg-gray-700"
                    @click.stop
                >
                    <!-- Modal Header -->
                    <button
                        v-if="hideHeader"
                        @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white absolute top-4 right-4"
                        aria-label="Close modal"
                    >
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div
                        v-if="!hideHeader"
                        class="flex items-center justify-between py-4 px-4 md:px-5 border-b rounded-t dark:border-gray-600 border-gray-200"
                    >
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">
                            <slot name="header">
                                {{ title }}
                            </slot>
                        </h3>
                        <button
                            @click="closeModal"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            aria-label="Close modal"
                        >
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div
                        class="p-4 md:p-5 space-y-4"
                        :class="{
                            '!py-12': !$slots.header,
                        }"
                    >
                        <slot name="body">
                            <p>Ini adalah isi default modal.</p>
                            <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                With less than a month to go before the European Union enacts new consumer privacy laws for its citizens, companies around the world are updating their terms of service agreements to comply.
                            </p>
                        </slot>
                    </div>

                    <!-- Modal Footer -->
                    <div
                        v-if="$slots.footer"
                        class="flex items-center py-3 px-4 md:px-5 border-t border-gray-200 rounded-b dark:border-gray-600"
                    >
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
    /* Transisi untuk modal */
    .modal-fade-enter-active,
    .modal-fade-leave-active {
        transition: opacity 0.3s ease;
    }

    .modal-fade-enter-from,
    .modal-fade-leave-to {
        opacity: 0;
    }

    /* Transisi untuk konten modal (optional, untuk efek zoom/scale) */
    .modal-fade-enter-active .modal-content,
    .modal-fade-leave-active .modal-content {
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
    }

    .modal-fade-enter-from .modal-content,
    .modal-fade-leave-to .modal-content {
        transform: scale(0.9);
        opacity: 0;
    }
</style>

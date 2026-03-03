<template>
    <Modal
        :isVisible="isOpen"
        :hideHeader="true"
        size="sm"
        :closeOnClickOutside="false"
    >
        <template #body>
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity duration-300">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-lg mx-4" @click.stop>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Crop Profile Picture</h3>
                    
                    <div class="w-full h-80 mb-6">
                        <Cropper
                            ref="cropper"
                            :src="imageUrl"
                            :stencil-props="{ aspectRatio: 1 / 1 }"
                            :auto-zoom="true"
                            class="cropper"
                        />
                    </div>

                    <div class="flex justify-end gap-3">
                        <button 
                            @click="$emit('close')" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                        >
                            Cancel
                        </button>
                        <button 
                            @click="cropAndComplete" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                        >
                            Change Profile
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </Modal>
</template>

<script setup>
import { ref } from 'vue';
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css'; // Import style cropper

const props = defineProps({
    imageUrl: String,
    isOpen: Boolean
});

const emit = defineEmits(['close', 'cropComplete']);

const cropper = ref(null);

const cropAndComplete = () => {
    if (cropper.value) {
        // Mendapatkan hasil crop dalam format canvas
        const { canvas } = cropper.value.getResult();
        
        if (canvas) {
            // Mengubah canvas menjadi Blob
            canvas.toBlob((blob) => {
                emit('cropComplete', blob);
                emit('close');
            }, 'image/jpeg'); // Anda dapat memilih format lain seperti 'image/png'
        }
    }
};
</script>

<style scoped>
/* Anda mungkin perlu menyesuaikan style agar cropper terlihat baik di Tailwind */
.cropper {
    height: 100%;
    width: 100%;
    background: #DDD; /* Background untuk cropper area */
}
</style>
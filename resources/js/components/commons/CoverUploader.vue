<script setup>
import { computed, ref } from 'vue';
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

const defaultImage = '/images/defaults/no-cover.png';

const props = defineProps({
    aspectRatio: {
        type: Number,
        default: 2 / 3,
    },
});

const model = defineModel({ type: [String, File] });

const showCropper = ref(false);
const imageSrc = ref(null);
const cropperRef = ref(null);

// Preview gambar
const previewSrc = computed(() => {
    if (!model.value) return defaultImage;

    if (typeof model.value === 'string') return model.value;

    if (model.value instanceof File) {
        return URL.createObjectURL(model.value);
    }

    return defaultImage;
});

// Validasi file foto
function validateFile(file) {
    return file && file.type.startsWith('image/');
}

// Saat pilih file
function onFileSelect(e) {
    const file = e.target.files[0];
    if (!validateFile(file)) return alert('Hanya file foto yang diperbolehkan.');

    loadFile(file);
}

// Load file untuk cropper
function loadFile(file) {
    imageSrc.value = URL.createObjectURL(file);
    showCropper.value = true;
}

// Apply crop
async function applyCrop() {
    const cropper = cropperRef.value;
    if (!cropper) return;

    const result = cropper.getResult();
    if (result?.canvas) {
        const base64 = result.canvas.toDataURL('image/jpeg', 0.9);

        const blob = await (await fetch(base64)).blob();
        const file = new File([blob], 'cover.jpg', { type: blob.type });

        model.value = file;
        showCropper.value = false;
    }
}

// Cancel crop
function cancelCrop() {
    showCropper.value = false;
}

// Remove cover
function removeCover() {
    model.value = null;
}
</script>

<template>
    <div class="space-y-4">
        <!-- PREVIEW -->
        <div class="preview flex justify-center shadow-theme-md rounded-md overflow-hidden w-full h-[300px]">
            <img
                :src="previewSrc"
                class="rounded-md w-full object-cover shadow"
            />
        </div>

        <!-- BUTTON BROWSE / CHANGE / REMOVE -->
        <div class="flex gap-3 justify-center">
            <!-- Jika belum ada foto -->
            <!-- <button
                v-if="!model"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700"
                @click="$refs.fileInput.click()"
            >
                Browse Cover
            </button> -->
            <Button
                v-if="!model"
                type="button"
                size="xs"
                class="rounded-md"
                @click="$refs.fileInput.click()"
            >
                Browse Cover
            </Button>

            <!-- Jika sudah ada foto -->
            <template v-else>
                <Button
                    type="button"
                    size="xs"
                    class="rounded-md"
                    @click="$refs.fileInput.click()"
                >
                    Change
                </Button>

                <Button
                    type="button"
                    size="xs"
                    class="rounded-md bg-red-500 text-white hover:bg-red-600"
                    @click="removeCover"
                >
                    Remove
                </Button>
            </template>
        </div>

        <!-- HIDDEN INPUT FILE -->
        <input
            type="file"
            ref="fileInput"
            accept="image/*"
            class="hidden"
            @change="onFileSelect"
        />

        <!-- MODAL CROPPER -->
        <div
            v-if="showCropper"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 px-4"
        >
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-lg p-4 w-full max-w-3xl h-[90vh] flex flex-col">
                <h3 class="text-md font-semibold mb-3 dark:text-white">
                    Crop Cover
                </h3>

                <div class="flex-1 overflow-hidden rounded-md border dark:border-zinc-700">
                    <cropper
                        ref="cropperRef"
                        :src="imageSrc"
                        class="w-full h-full"
                        :stencil-props="{ aspectRatio: props.aspectRatio }"
                    />
                </div>

                <div class="flex justify-end mt-4 gap-2">
                    <Button
                        type="button"
                        size="xs"
                        class="bg-zinc-300 dark:bg-zinc-600 rounded-md"
                        @click="cancelCrop"
                    >
                        Cancel
                    </Button>

                    <Button
                        type="button"
                        size="xs"
                        class="text-white rounded-md"
                        @click="applyCrop"
                    >
                        Apply Crop
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.preview img {
    border-radius: 8px;
    object-fit: cover;
}
</style>

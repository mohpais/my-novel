<template>
    <div>
        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex flex-col items-center w-full gap-6 xl:flex-row">
                    <!-- File Input Tersembunyi -->
                    <input
                        type="file"
                        ref="fileInput"
                        @change="handleFileChange"
                        class="hidden"
                        accept="image/*"
                    />

                    <div class="flex flex-col items-center gap-2">

                        <!-- Foto dengan Hover Overlay -->
                        <div class="w-20 h-20 relative group">
                            <!-- Foto. Menggunakan previewImageUrl jika ada, atau default dari $auth -->
                            <div class="w-full h-full overflow-hidden border border-gray-200 rounded-full dark:border-gray-800">
                                <Image
                                    :src="previewImageUrl || $auth.currentUser?.picture_url"
                                    id="photo"
                                    :alt="$auth.currentUser?.name"
                                />
                            </div>

                            <!-- Overlay (Trigger File Input) -->
                            <div 
                                class="absolute inset-0 flex flex-col items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                                @click="triggerFileInput"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                                <small class="text-gray-200 text-[.5rem]"> Change profile </small>
                            </div>
                        </div>
                    </div>
                    <div class="order-3 xl:order-2">
                        <h4 class="mb-0 text-xl font-bold text-center text-gray-800 dark:text-white/90 xl:text-left"> {{ $auth.currentUser?.name }} </h4>
                        <p class="text-base text-center text-gray-500 dark:text-gray-400">{{ $auth.currentUser?.role.name }}</p>
                    </div>
                </div>

                <!-- Tombol Save Changes dan Cancel (Muncul setelah Crop) -->
                <div v-if="isPictureChanged" class="mt-6 pt-4 xl:p-0 xl:m-0 xl:border-0 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2">
                    <Button 
                        @click="cancelChanges" 
                        :disabled="isLoading.changeProfilePicture"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-400  rounded-lg hover:bg-gray-500 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                        size="sm"
                    >
                        Cancel
                    </Button>
                    <!-- <button 
                        @click="saveChanges" 
                        :disabled="isLoading.changeProfilePicture"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50 transition-colors"
                    >
                        {{ isSaving ? 'Saving...' : 'Save Changes' }}
                    </button> -->
                    <Button
                        @click="saveChanges" 
                        :loading="isLoading.changeProfilePicture"
                        class="bg-green-600 hover:bg-green-700 text-xs whitespace-nowrap"
                        size="sm"
                    >
                        Save Changes
                    </Button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cropper Modal Component -->
    <CropperModal 
        :image-url="imageToCrop"
        :is-open="isModalOpen"
        @close="isModalOpen = false"
        @crop-complete="handleCropComplete"
    />
</template>

<script setup>
    import { ref } from 'vue';
    import CropperModal from './modals/CropperModal.vue'; // Asumsikan Anda membuat file ini
    import { useUserStore } from '../store';

    const store = useUserStore();
    const { isLoading, changeProfilePicture } = store;

    // State untuk Modal dan Gambar
    const isModalOpen = ref(false);
    const imageToCrop = ref(null); // URL gambar yang akan dicrop
    const fileInput = ref(null); // Ref untuk input file

    // State untuk Preview dan Save Changes
    const previewImageUrl = ref(null); // URL Blob untuk pratinjau hasil crop
    const imageBlob = ref(null); // Blob hasil crop (untuk diupload)
    const isPictureChanged = ref(false); // Mengontrol visibilitas tombol Save/Cancel
    const isSaving = ref(false); // Loading state saat upload

    // 1. Memicu Input File (Sama seperti sebelumnya)
    const triggerFileInput = () => {
        fileInput.value.click();
    };

    // 2. Menangani Pilihan File (Sama seperti sebelumnya)
    const handleFileChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            // Membuat URL untuk file yang dipilih
            const reader = new FileReader();
            reader.onload = (e) => {
                imageToCrop.value = e.target.result;
                isModalOpen.value = true;
            };
            reader.readAsDataURL(file);
            
            // Mengatur ulang input file agar event @change dapat dipicu lagi jika file yang sama dipilih
            event.target.value = ''; 
        }
    };

    // 3. Menangani Hasil Crop (Diperbarui)
    const handleCropComplete = (blob) => {
        imageBlob.value = blob; // Simpan blob untuk diupload
        
        // Buat URL sementara untuk pratinjau di UI
        if (previewImageUrl.value) {
            URL.revokeObjectURL(previewImageUrl.value); // Hapus URL lama
        }
        previewImageUrl.value = URL.createObjectURL(blob);
        
        isModalOpen.value = false;
        isPictureChanged.value = true; // Tampilkan tombol Save/Cancel
    };

    // 4. Membatalkan Perubahan
    const cancelChanges = () => {
        // Hapus pratinjau gambar dan reset state
        if (previewImageUrl.value) {
            URL.revokeObjectURL(previewImageUrl.value);
        }
        previewImageUrl.value = null;
        imageBlob.value = null;
        isPictureChanged.value = false;
    };

    // 5. Menyimpan Perubahan (Mengupload Gambar)
    const saveChanges = async () => {
        if (!imageBlob.value) return;
        
        // Buat FormData untuk mengirim file sebagai 'picture'
        const formData = new FormData();
        formData.append('picture', imageBlob.value, 'profile.jpg'); 
        
        try {
            await changeProfilePicture(formData);
        } catch (error) {
            console.error('Fetch Error:', error);
        } finally {
            cancelChanges();
        }
    };
</script>
<template>
    <div class="space-y-6">
        <div v-if="isLoading.fetchChapters" class="flex justify-center py-12">
            <div class="animate-spin size-8 border-4 border-brand-500 border-t-transparent rounded-full"></div>
        </div>
        <template v-else>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Daftar Isi
                    </h3>
                    <p class="text-sm text-gray-500">Total {{ filteredChapters.length }} Bab tersedia</p>
                </div>
    
                <div class="relative max-w-xs w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Cari judul bab..." 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-sm focus:ring-brand-500 focus:border-brand-500 transition-all"
                    />
                </div>
            </div>
    
            <div v-if="filteredChapters.length > 0" class="grid gap-2">
                <div 
                    v-for="(chapter, index) in filteredChapters" 
                    :key="chapter.id"
                    class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all cursor-pointer"
                >
                    <div class="flex items-center gap-4">
                    <span class="flex items-center justify-center size-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-500 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                        {{ index + 1 }}
                    </span>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-brand-500">
                        {{ chapter.title }}
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">Dipublikasi: {{ formatDate(chapter.created_at) }}</p>
                    </div>
                    </div>
    
                    <div class="flex items-center gap-3">
                        <span v-if="chapter.is_premium" class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 rounded-full">
                            Premium
                        </span>
                        <svg class="size-5 text-gray-300 group-hover:text-brand-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </div>
    
            <div v-else class="py-12 text-center">
                <div class="inline-flex items-center justify-center size-12 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 mb-3">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="text-gray-500 text-sm">Tidak ada bab yang ditemukan.</p>
                <Button size="xs" class="mt-2">Tambah Chapter Baru</Button>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useNovelStore } from '../../store';
import { storeToRefs } from 'pinia';

const store = useNovelStore();
const { data, isLoading } = storeToRefs(store);

const searchQuery = ref('');

// Logic untuk filter bab berdasarkan input pencarian
const filteredChapters = computed(() => {
  const list = data.value.chapters || [];
  if (!searchQuery.value) return list;
  
  return list.filter(chapter => 
    chapter.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

// Helper untuk format tanggal
const formatDate = (dateString) => {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleDateString('id-ID', { 
    year: 'numeric', month: 'short', day: 'numeric' 
  });
};
</script>
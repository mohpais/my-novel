<template>
    <div class="space-y-6">
        <div v-if="isLoading.fetchChapters" class="flex justify-center py-12">
            <div class="animate-spin size-8 border-4 border-brand-500 border-t-transparent rounded-full"></div>
        </div>

        <template v-else>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Isi</h3>
                    <p class="text-sm text-gray-500">Total {{ filteredChapters.length }} Bab tersedia</p>
                </div>
    
                <div class="flex gap-2 items-center">
                    <button 
                        @click="toggleSort"
                        class="p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors group"
                        title="Ganti Urutan"
                    >
                        <svg 
                            class="size-5 text-gray-500 group-hover:text-brand-500 transition-transform duration-300"
                            :class="sortOrder === 'asc' ? 'rotate-180' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                    </button>

                    <div class="relative max-w-xs w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Cari judul..." 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-sm focus:ring-brand-500 focus:border-brand-500 transition-all"
                        />
                    </div>

                    <Button @click="$router.push({
                        name: 'ChapterCreatePage',
                        params: { slug: $route.params.slug }
                    })" size="xs">+</Button>
                </div>
            </div>
    
            <div v-if="filteredChapters.length > 0" class="grid gap-2">
                <div v-for="(chapter, index) in filteredChapters" :key="chapter.id" class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col items-center justify-center min-w-[3rem] h-12 rounded-lg bg-gray-100 dark:bg-gray-800 border border-transparent group-hover:border-brand-500/30 group-hover:bg-brand-50 transition-all">
                            <span class="text-[10px] uppercase tracking-tighter text-gray-400 group-hover:text-brand-500 font-bold">Bab</span>
                            <span class="text-sm font-black text-gray-700 dark:text-gray-200 group-hover:text-brand-600">
                                {{ chapter.chapter_number || (filteredChapters.length - index) }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-brand-500">
                                {{ chapter.title }}
                            </h4>
                            <div class="flex items-center gap-2 mt-0.5">
                                <p class="text-[11px] text-gray-500">{{ formatDate(chapter.created_at) }}</p>
                                <span class="size-1 rounded-full bg-gray-300"></span>
                                <p class="text-[11px] font-medium" :class="chapter.status === 'published' ? 'text-green-600' : 'text-amber-600'">
                                    {{ chapter.status || 'Draft' }}
                                </p>
                            </div>
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
const sortOrder = ref('desc'); // 'desc' untuk terbaru, 'asc' untuk terlama

// Logic untuk filter dan sort bab
const filteredChapters = computed(() => {
  // 1. Buat salinan array agar tidak mengubah data asli di store
  let list = [...(data.value.chapters || [])];

  // 2. Jalankan pengurutan berdasarkan sortOrder
  list.sort((a, b) => {
    const dateA = new Date(a.created_at);
    const dateB = new Date(b.created_at);
    return sortOrder.value === 'desc' ? dateB - dateA : dateA - dateB;
  });

  // 3. Filter berdasarkan pencarian
  if (!searchQuery.value) return list;
  
  return list.filter(chapter => 
    chapter.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const toggleSort = () => {
  sortOrder.value = sortOrder.value === 'desc' ? 'asc' : 'desc';
};

const formatDate = (dateString) => {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleDateString('id-ID', { 
    year: 'numeric', month: 'short', day: 'numeric',
    hour: '2-digit', minute: '2-digit'
  });
};
</script>
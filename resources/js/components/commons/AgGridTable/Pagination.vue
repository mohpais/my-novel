<template>
  <!-- <div
    v-if="props.paginationConfig.totalRecord > 0"
    class="flex flex-row rounded-b-xl justify-between w-full text-gray-700 items-center gap-8 px-4 bg-white -mt-[2px] z-0 py-1 dark:border-gray-800 dark:bg-white/[0.03]"
    :class="{
      'text-xs border-t border-t-gray-200 dark:border-t-gray-800 border-b border-b-gray-200 dark:border-b-gray-800': props.theme === 'quartz',
      'text-xs': props.theme === 'balham',
    }"> -->
  <div
    class="flex flex-col lg:flex-row rounded-b-xl justify-between w-full text-gray-700 items-center gap-3 lg:gap-8 px-4 bg-white z-0 pt-4 pb-2 lg:py-1 dark:border-gray-800 dark:bg-white/[0.03]"
    :class="{
      'text-xs border-t border-t-gray-200 dark:border-t-gray-800 border-b border-b-gray-200 dark:border-b-gray-800': props.theme === 'quartz',
      'text-xs': props.theme === 'balham',
    }">
    <div class="flex flex-row items-center gap-2 divide-x lg:divide-x-0 h-4 lg:h-auto dark:text-gray-200">
      <div class="flex-none h-full flex items-center px-1 lg:px-0 justify-center lg:hidden dark:text-white">
        <span class="font-bold me-1">Total:</span> {{ props.paginationConfig.totalRecord }} item
      </div>
      <div class="flex h-full items-center pl-2.5 lg:px-0">
        <span class="flex-none lg:pr-2">{{ $t("Commons.Page_Size") }}: </span>
        <div class="flex-none w-12 lg:w-[70px]">
          <select
            v-model="props.paginationConfig.pageSize"
            :disabled="props.paginationConfig.totalRecord === 0"
            @change="handlePageSizeChange"
            class="bg-white border border-gray-200 text-gray-700 focus:ring-green-500 focus:border-green-500 block w-full dark:bg-gray-700 dark:border-gray-800 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
            :class="{
              'cursor-not-allowed': props.paginationConfig.totalRecord === 0,
              'py-1.5 px-1 text-xs lg:px-2.5 lg:text-sm rounded-lg': props.theme === 'quartz',
              'py-1 px-2 text-xs rounded-0': props.theme === 'balham',
            }">
            <option
              v-for="(item, index) in props.paginationConfig.rowItems"
              :key="index"
              :value="item">
              {{ item }}
            </option>
          </select>
        </div>
      </div>

      <div class="flex-none dark:text-white hidden lg:flex">
        {{ props.paginationConfig.totalRecord === 0 ? 0 : 1 }}
        -
        {{
          props.paginationConfig.totalRecord > props.paginationConfig.pageSize
            ? props.paginationConfig.pageSize
            : props.paginationConfig.totalRecord
        }}
        {{ $t("Commons.of") }} {{ props.paginationConfig.totalRecord }}
        {{ $t("Commons.items") }}
      </div>
    </div>
    <div class="flex flex-row items-center gap-1 dark:text-white">
      <!-- First page button -->
      <div
        :class="[
          'hover:bg-zinc-200 group rounded-full cursor-pointer h-6 w-6 text-zinc-600 dark:text-zinc-400 dark:hover:text-gray-700 flex items-center justify-center',
          {
            '!text-zinc-300 dark:!text-zinc-700': !canGoBack,
            '!cursor-not-allowed hover:!bg-transparent': !canGoBack,
          },
        ]"
        @click="firstPage(canGoBack)"
        :disabled="!canGoBack">
        <i class="fa-solid fa-backward-step"></i>
      </div>
      <!-- Previous page button -->
      <div
        :class="[
          'hover:bg-zinc-200 group rounded-full cursor-pointer h-6 w-6 text-zinc-600 dark:text-zinc-400 dark:hover:text-gray-700 flex items-center justify-center',
          {
            '!text-zinc-300 dark:!text-zinc-700': !canGoBack,
            '!cursor-not-allowed hover:!bg-transparent': !canGoBack,
          },
        ]"
        @click="prevPage(canGoBack)"
        :disabled="!canGoBack">
        <i class="fa-solid fa-caret-left"></i>
      </div>
      <div class="p-2">
        {{ $t("Commons.Page") }} {{ props.paginationConfig.totalRecord > 0 ? props.paginationConfig.currentPage : 0 }}
        {{ $t("Commons.of") }}
        {{ props.paginationConfig.totalPages }}
      </div>
      <!-- Next page button -->
      <div
        :class="[
          'hover:bg-zinc-200 group rounded-full cursor-pointer h-6 w-6 text-zinc-600 dark:text-zinc-400 dark:hover:text-gray-700 flex items-center justify-center',
          {
            '!text-zinc-300 dark:!text-zinc-700': !canGoForward,
            '!cursor-not-allowed hover:!bg-transparent': !canGoForward,
          },
        ]"
        @click="nextPage(canGoForward)"
        :disabled="!canGoForward">
        <i class="fa-solid fa-caret-right"></i>
      </div>
      <!-- Last page button -->
      <div
        :class="[
          'hover:bg-zinc-200 group rounded-full cursor-pointer h-6 w-6 text-zinc-600 dark:text-zinc-400 dark:hover:text-gray-700 flex items-center justify-center',
          {
            '!text-zinc-300 dark:!text-zinc-700': !canGoForward,
            '!cursor-not-allowed hover:!bg-transparent': !canGoForward,
          },
        ]"
        @click="lastPage(canGoForward)"
        :disabled="!canGoForward">
        <i class="fa-solid fa-forward-step"></i>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive } from "vue";

const emit = defineEmits(["page-changed"]);

let props = defineProps({
  paginationConfig: {
    type: Object,
    default: {},
  },
  theme: {
    type: String,
    default: "quartz", // quartz, balham
  },
});

// Check if user can go to the previous or next pages
const canGoBack = computed(() => props.paginationConfig.currentPage > 1);

const canGoForward = computed(
  () => props.paginationConfig.currentPage < props.paginationConfig.totalPages,
);

const handlePageSizeChange = () => {
  props.paginationConfig.currentPage = 1; // Reset to first page on size change
  emit("page-changed", props.paginationConfig);
};

const nextPage = (isEnable) => {
  if (isEnable) {
    if (
      props.paginationConfig.currentPage < props.paginationConfig.totalPages
    ) {
      props.paginationConfig.currentPage =
        props.paginationConfig.currentPage + 1;
      emit("page-changed", props.paginationConfig);
    }
  }
};

const prevPage = (isEnable) => {
  if (isEnable) {
    props.paginationConfig.currentPage = props.paginationConfig.currentPage - 1;
    emit("page-changed", props.paginationConfig);
  }
};

const firstPage = (isEnable) => {
  if (isEnable) {
    props.paginationConfig.currentPage = 1;
    emit("page-changed", props.paginationConfig);
  }
};

const lastPage = (isEnable) => {
  if (isEnable) {
    props.paginationConfig.currentPage = props.paginationConfig.totalPages;
    emit("page-changed", props.paginationConfig);
  }
};
</script>

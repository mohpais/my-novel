<template>
  <Button
    size="lg"
    @click="router.go(-1)"
    class="flex-none h-9 w-9 !p-0 !rounded-full bg-brand hover:bg-gray-400 text-gray-600 flex flex-row items-center justify-center dark:text-gray-100 dark:bg-gray-800 hover:dark:bg-gray-900">
    <i class="fa-solid fa-chevron-left"></i>
  </Button>
</template>

<script setup>
import { useRouter } from "vue-router";
const router = useRouter();
</script>

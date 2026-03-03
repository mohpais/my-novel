<template>
    <div class="h-screen w-full flex items-center">
        <div class="mx-auto text-center">
            <div class="p-8 py-24">
                <div class="w-64 h-64 mx-auto rounded-full bg-tertiary relative drop-shadow-2xl">
                <img :src="NotFound" class="absolute mx-auto left-0 right-0 h-72" alt="NotFound" />
                </div>
                <div class="text-zinc-800 fs-title font-bold mt-6 dark:text-white">
                    Sorry, the page you are looking for could not be found :(
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import NotFound from "@/assets/illustrations/404-2.svg";
</script>

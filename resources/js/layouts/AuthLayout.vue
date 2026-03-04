<template>
    <div class="min-h-screen">
        <main>
            <div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0">
                <div 
                    class="relative flex flex-col justify-center w-full h-screen lg:flex-row dark:bg-gray-900"
                >
                    <router-view></router-view>
                    <div 
                        class="relative items-center hidden w-full h-full lg:w-1/2 lg:grid"
                        :style="{ backgroundImage: `url(${Background})`, backgroundSize: 'cover', backgroundPosition: 'center' }"
                    >
                        <div class="absolute inset-0 bg-gray-900/50 dark:bg-gray-900/70"></div>
                        <div class="flex items-center justify-center z-1">
                            <div>
                                <div class="absolute right-0 top-0 -z-1 w-full max-w-[250px] xl:max-w-[450px]">
                                    <img :src="Grid1" alt="grid" />
                                </div>
                                <div class="absolute bottom-0 left-0 -z-1 w-full max-w-[250px] rotate-180 xl:max-w-[450px]">
                                    <img :src="Grid1" alt="grid" />
                                </div>
                            </div>
                            <div class="flex flex-col items-center max-w-sm">
                                <router-link to="/" class="block mb-4">
                                    <img :src="AuthLogo" alt="Logo" />
                                </router-link>
                                <!-- <p class="text-center text-gray-400 dark:text-white/60">
                                    Website untuk kemudahan pengelolaan aset Anda
                                </p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
    import AuthLogo from "@/assets/logos/logo-auth.svg";
    import Grid1 from "@/assets/images/shape/grid-01.svg";
    import KoneBackground from "@/assets/backgrounds/Kone Background.jpg";
    import Background from "@/assets/backgrounds/background-1.avif";
</script>
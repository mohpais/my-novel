<template>
    <Breadcrumb pageTitle="Profile Page" />
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <ProfileHeader />
        <div>
            <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6"> Personal Information </h4>
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Full Name</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $auth.currentUser?.name }}</p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Employee Number</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $auth.currentUser?.employee_number }}</p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400"> Department </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90"> {{ $auth.currentUser?.department?.name }} </p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Position</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $auth.currentUser?.position.name }}</p></div>
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Location</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $auth.currentUser?.location.name }}</p>
                            </div>
                        </div>
                    </div>
                    <button class="edit-button">
                        <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z" fill=""></path>
                        </svg> 
                        Edit 
                    </button>
                </div>
            </div><!---->
        </div>
        <div>
            <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
                <div class="space-y-6">
                    <!-- <div class="file-uploader"><form id="dropzone-gt589b52h" action="/upload" class="border-gray-300 border-dashed dropzone rounded-xl bg-gray-50 p-7 hover:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-brand-500 lg:p-10 dz-clickable"><div class="dz-message m-0!"><div class="mb-[22px] flex justify-center"><div class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-400"><svg class="fill-current" width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z" fill=""></path></svg></div></div><h4 class="mb-3 font-semibold text-gray-800 text-theme-xl dark:text-white/90"> Drag &amp; Drop File Here </h4><span class="mx-auto mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400"> Drag and drop your PNG, JPG, WebP, SVG images here or browse </span><span class="font-medium underline cursor-pointer text-theme-sm text-brand-500"> Browse File </span></div></form></div> -->
                    <Dropzone uploadUrl="/api/upload" />
                </div>
            </div><!---->
        </div>
    </div>
</template>

<script setup>
    import { ref, defineAsyncComponent } from 'vue';

    const ProfileHeader = defineAsyncComponent(() => import('../components/ProfileHeader.vue'));
</script>

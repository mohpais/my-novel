<template>
    <FormProvider
        :initial-values="form"
        @on-submit="btnCreateChapter"
    >
        <template #touch-form>
            <div class="space-y-6">
                <div class="flex flex-col gap-6 md:flex-row">
                    <div class="flex flex-col gap-6 w-full">
                        <div class="">
                            <FormField
                                id="number"
                                type="number"
                                name="number"
                                labelName="Number"
                                v-model="form.number"
                                rules="required"
                            />
                        </div>
                        <div class="">
                            <FormField
                                id="title"
                                name="title"
                                labelName="Title"
                                placeholder="Enter title ..."
                                v-model="form.title"
                                rules="required"
                            />
                        </div>
                        <div class="">
                            <FormField
                                as="htmleditor"
                                name="content"
                                id="content"
                                uploadFolder="chapters"
                                v-model="form.content"
                                rules="required"
                                placeholder="Tuliskan cerita Anda di sini..."
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-4 sm:pt-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <Button type="button" class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-3 py-2 text-xs font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M13.333 16.6666V12.9166C13.333 12.2262 12.7734 11.6666 12.083 11.6666L7.91634 11.6666C7.22599 11.6666 6.66634 12.2262 6.66634 12.9166L6.66635 16.6666M9.99967 5.83325H6.66634M15.4163 16.6666H4.58301C3.89265 16.6666 3.33301 16.1069 3.33301 15.4166V4.58325C3.33301 3.8929 3.89265 3.33325 4.58301 3.33325H12.8171C13.1483 3.33325 13.4659 3.46468 13.7003 3.69869L16.2995 6.29384C16.5343 6.52832 16.6662 6.84655 16.6662 7.17841L16.6663 15.4166C16.6663 16.1069 16.1066 16.6666 15.4163 16.6666Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        Save as Draft
                    </Button>
                    <Button type="submit" :loading="isLoading.createNovel"   class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-white transition">
                        <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20"  
                            fill="currentColor" viewBox="0 0 20 20" >
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path d="M20.56 3.17c-.29-.2-.67-.23-.99-.08l-17 8.01a.999.999 0 0 0 .03 1.82L8 15.28V22l5.84-4.17 4.76 2.08c.13.06.26.08.4.08.18 0 .36-.05.52-.15a.99.99 0 0 0 .48-.79l1-15c.02-.35-.14-.69-.43-.89Zm-2.47 14.34-5.21-2.28L16 9l-7.65 4.25-2.93-1.28 13.47-6.34-.79 11.89Z"></path>
                        </svg>
                        Submit
                    </Button>
                </div>
            </div>
        </template>
    </FormProvider>

</template>

<script setup>
    import { storeToRefs } from 'pinia';
    
    import { useChapterStore } from '../../store';


    // Access the request store
    const store = useChapterStore();
    const { form, isLoading } = storeToRefs(store);

    const { btnCreateChapter } = store;

</script>
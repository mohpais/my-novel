<template>
    <Breadcrumb pageTitle="Create Request Asset" :withBackButton="true" />
    <FormProvider
        className="rounded-2xl w-full border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
        :initial-values="form"
        @on-submit="btnCreateRequest"
    >
        <template #touch-form>
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h2 class="text-base font-medium text-gray-800 dark:text-white">Request Form</h2>
            </div>
            <div class="border-b border-gray-200 p-4 sm:p-8 dark:border-gray-800">
                <AssetDetailForm />
            </div>
            <div class="border-b border-gray-200 p-4 sm:p-8 dark:border-gray-800">
                <div class="">
                    <QuotationTable :quotations="form.quotations" />
                    <QuotationForm />
                </div>
            </div>
            <div class="border-b border-gray-200 p-4 sm:p-8 dark:border-gray-800">
                <JustificationTable  />
            </div>
            <div class="p-4 sm:p-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <Button type="button" class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M13.333 16.6666V12.9166C13.333 12.2262 12.7734 11.6666 12.083 11.6666L7.91634 11.6666C7.22599 11.6666 6.66634 12.2262 6.66634 12.9166L6.66635 16.6666M9.99967 5.83325H6.66634M15.4163 16.6666H4.58301C3.89265 16.6666 3.33301 16.1069 3.33301 15.4166V4.58325C3.33301 3.8929 3.89265 3.33325 4.58301 3.33325H12.8171C13.1483 3.33325 13.4659 3.46468 13.7003 3.69869L16.2995 6.29384C16.5343 6.52832 16.6662 6.84655 16.6662 7.17841L16.6663 15.4166C16.6663 16.1069 16.1066 16.6666 15.4163 16.6666Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        Save as Draft
                    </Button>
                    <Button type="submit" :loading="isLoading.createRequest"   class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M13.333 16.6666V12.9166C13.333 12.2262 12.7734 11.6666 12.083 11.6666L7.91634 11.6666C7.22599 11.6666 6.66634 12.2262 6.66634 12.9166L6.66635 16.6666M9.99967 5.83325H6.66634M15.4163 16.6666H4.58301C3.89265 16.6666 3.33301 16.1069 3.33301 15.4166V4.58325C3.33301 3.8929 3.89265 3.33325 4.58301 3.33325H12.8171C13.1483 3.33325 13.4659 3.46468 13.7003 3.69869L16.2995 6.29384C16.5343 6.52832 16.6662 6.84655 16.6662 7.17841L16.6663 15.4166C16.6663 16.1069 16.1066 16.6666 15.4163 16.6666Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        Submit Request
                    </Button>
                </div>
            </div>
        </template>
    </FormProvider>
</template>

<script setup>
    import { defineAsyncComponent } from 'vue';
    import { useRequestStore } from '../../store';
    import { storeToRefs } from 'pinia'

    // Lazy load AssetDetailForm component
    const AssetDetailForm = defineAsyncComponent(() =>
        import("./AssetDetailForm.vue"),
    );

    // Lazy load QuotationForm component
    const QuotationForm = defineAsyncComponent(() =>
        import("./QuotationForm.vue"),
    );

    // Lazy load QuotationTable component
    const QuotationTable = defineAsyncComponent(() =>
        import("../QuotationTable.vue"),
    );

    // Lazy load QuotationTable component
    const JustificationTable = defineAsyncComponent(() =>
        import("../JustificationTable.vue"),
    );

    // Access the request store
    const store = useRequestStore();
    const { form, isLoading } = storeToRefs(store);

    const { btnCreateRequest } = store;

    const removeItem = (index) => {
        form.quotations.splice(index, 1);
    };
</script>

<template>
    <FormProvider
        :initial-values="form.asset"
        @on-submit="store.btnCreateAsset"
    >
        <template #touch-form>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="">
                    <FormField
                        id="name"
                        name="name"
                        labelName="Asset Name"
                        placeholder="Enter asset name..."
                        v-model="form.asset.name"
                        rules="required"
                    />
                </div>
                <div class="">
                    <FormField
                        as="select"
                        id="company_code"
                        name="company_code"
                        v-model="form.asset.company_code"
                        :optionsValue="[
                            {
                                id: 244,
                                text: '244'
                            }
                        ]"
                        labelName="Company Code"
                        rules="required"
                    />
                </div>
                <div class="">
                    <FormField
                        id="description"
                        name="description"
                        labelName="Asset Description (Additional)"
                        placeholder="Enter Additional description"
                    />
                </div>
                <div class="">
                    <FormField
                        as="select"
                        id="depreciation_key"
                        name="depreciation_key"
                        v-model="form.asset.depreciation_key"
                        :optionsValue="[
                            {
                                id: 'ZSLO',
                                text: 'ZSLO'
                            }
                        ]"
                        labelName="Depreciation Key"
                        rules="required"
                    />
                </div>
                <div class="">
                    <FormField
                        as="select"
                        id="budget_id"
                        name="budget_id"
                        v-model="form.asset.budget_id"
                        :optionsValue="store.data.budgets.map((budget) => ({ id: budget.id, text: budget.code }))"
                        labelName="Budget Code"
                        rules="required"
                    />
                </div>
                <div class="">
                    <FormField
                        id="asset_class"
                        name="asset_class"
                        labelName="Asset Class"
                        placeholder="Enter Asset Class"
                        v-model="form.asset.asset_class"
                        :readonly="true"
                        rules="required"
                    />
                </div>
                <!-- <div class="">
                    <FormField
                        type="number"
                        id="purchase_quantity"
                        name="purchase_quantity"
                        labelName="Purchase Quantity"
                        placeholder="Enter Purchase Quantity"
                        rules="required"
                    />
                </div> -->
                <div class="">
                    <FormField
                        id="brief_reason"
                        name="brief_reason"
                        labelName="Brief Reason for Requirement"
                        placeholder="Enter Reason for Requirement"
                        rules="required"
                    />
                </div>
                <div class="col-span-2">
                    <FormField
                        as="checkbox"
                        id="replacement"
                        name="replacement"
                        checkboxName="Replace Purchase"
                    />
                </div>
                <div class="">
                    <FormField
                        as="select"
                        id="useful_life"
                        name="useful_life"
                        v-model="form.asset.useful_life"
                        :optionsValue="[
                            {
                                id: '4',
                                text: '4'
                            },
                            {
                                id: '8',
                                text: '8'
                            },
                            {
                                id: '16',
                                text: '16'
                            },
                            {
                                id: '20',
                                text: '20'
                            },
                        ]"
                        labelName="Useful Life"
                        rules="required"
                    />
                </div>
                <div class="">
                    <FormField
                        type="file"
                        id="signature"
                        name="signature"
                        labelName="Upload Signature (PNG)"
                    />
                </div>
                <div class="col-span-2 pt-3">
                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <button type="button" class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">Draft</button>
                        <button type="submit" class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">Create Request</button>
                    </div>
                </div>
                <!-- <div class="col-span-2 mt-8 p-4 bg-gray-100 rounded">
                    <h3 class="text-lg font-semibold mb-2">Data Form Saat Ini:</h3>
                    <pre class="whitespace-pre-wrap text-sm p-2 bg-gray-50 rounded">{{ JSON.stringify(form.asset, null, 2) }}</pre>
                </div> -->
                </div>
        </template>
    </FormProvider>
</template>

<script setup>
    import { onMounted } from 'vue';
    import { useAssetStore } from '../store';
    import { storeToRefs } from 'pinia'

    const store = useAssetStore();
    const { form } = storeToRefs(store);

    onMounted(async () => {
        await store.fetchBudget();
    })
</script>

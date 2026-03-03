<template>
    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div class="">
                <FormField
                    id="name"
                    name="name"
                    labelName="Asset Name"
                    placeholder="Enter asset name..."
                    v-model="form.name"
                    rules="required"
                />
            </div>
            <div class="">
                <FormField
                    as="select"
                    id="company_code"
                    name="company_code"
                    v-model="form.company_code"
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
                    v-model="form.depreciation_key"
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
                    :searchable="true"
                    v-model="form.budget_id"
                    :optionsValue="store.data.budgets.map((budget) => ({ id: budget.id, text: budget.code }))"
                    :isLoading="isLoading.fetchBudget"
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
                    v-model="form.asset_class"
                    :readonly="true"
                    rules="required"
                />
            </div>
            <div class="">
                <FormField
                    as="select"
                    id="useful_life"
                    name="useful_life"
                    v-model="form.useful_life"
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
                    :isNumber="true"
                    id="purchase_quantity"
                    v-model="form.purchase_quantity"
                    name="purchase_quantity"
                    labelName="Purchase Quantity"
                    placeholder="Enter Purchase Quantity"
                    rules="required"
                />
            </div>
            <div class="">
                <FormField
                    id="brief_reason"
                    name="brief_reason"
                    v-model="form.brief_reason"
                    labelName="Brief Reason for Requirement"
                    placeholder="Enter Reason for Requirement"
                    rules="required"
                />
            </div>
            <div class="col-span-2">
                <FormField
                    as="checkbox"
                    v-model="form.replacement"
                    id="replacement"
                    name="replacement"
                    checkboxName="Replace Purchase"
                />
            </div>
            <!-- <div class="">
                <FormField
                    type="file"
                    id="signature"
                    name="signature"
                    labelName="Upload Signature (PNG)"
                />
            </div> -->
        </div>
    </div>
</template>

<script setup>
    import { useRequestStore } from '../../store';
    import { storeToRefs } from 'pinia'

    const store = useRequestStore();
    const { form, isLoading } = storeToRefs(store);

</script>

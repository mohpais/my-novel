<template>
    <FormProvider
        className="mt-5 rounded-xl border border-gray-100 bg-gray-50 p-4 sm:p-6 dark:border-gray-800 dark:bg-gray-900"
        :initial-values="quotationItem"
        @on-submit="addItem"
    >
        <template #touch-form>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-12">
                <div class="w-full lg:col-span-2">
                    <FormField
                        labelName="Vendor Name"
                        v-model="quotationItem.vendor_name"
                        name="vendor_name"
                        id="vendor_name"
                        placeholder="Isi nama vendor..."
                        rules="required"
                    />
                </div>
                <div class="w-full lg:col-span-2">
                    <FormField
                        labelName="Currency"
                        as="select"
                        id="currency"
                        name="currency"
                        :optionsValue="[
                            { id: 'IDR', text: 'IDR' },
                            { id: 'USD', text: 'USD' }
                        ]"
                        rules="required"
                    />
                </div>
                <div class="w-full lg:col-span-2">
                    <FormField
                        type="number"
                        labelName="Quantity"
                        name="purchase_quantity"
                        id="purchase_quantity"
                        rules="required|min_value:1"
                    />
                </div>
                <div class="w-full lg:col-span-2">
                    <FormField
                        :isNumber="true"
                        labelName="Price"
                        v-model="quotationItem.base_rate"
                        name="base_rate"
                        id="base_rate"
                        rules="required"
                    />
                </div>
                <div class="w-full lg:col-span-2">
                    <FormField
                        type="number"
                        labelName="Tax (%)"
                        v-model="quotationItem.tax_rate"
                        :isNumber="true"
                        name="tax_rate"
                        id="tax_rate"
                        rules="required"
                    />
                </div>
                <div class="flex w-full items-end lg:col-span-2">
                    <Button type="submit" class="hover:bg-brand-600 bg-brand-500 h-11 w-full rounded-lg px-4 py-3 !text-xs font-medium text-white transition"> Save Vendor </Button>
                </div>
            </div>
            <div class="mt-5 flex max-w-2xl items-center gap-2">
                <svg class="text-gray-500 dark:text-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 7.22485H10.0007" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path><path d="M10.0004 9.34575V12.8661M17.7087 10.0001C17.7087 14.2573 14.2575 17.7084 10.0003 17.7084C5.74313 17.7084 2.29199 14.2573 2.29199 10.0001C2.29199 5.74289 5.74313 2.29175 10.0003 2.29175C14.2575 2.29175 17.7087 5.74289 17.7087 10.0001Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                <p class="text-xs text-gray-500 dark:text-gray-400"> After filling in the vendor details, press Enter/Return or click 'Save Vendor' to add it to the list. </p>
            </div>
        </template>
    </FormProvider>
</template>

<script setup>
    import { useRequestStore } from "../../store";

    const quotationItem = {
        vendor_name: '',
        currency: 'IDR',
        purchase_quantity: 0,
        base_rate: 0,
        tax_rate: 0,
        luxury_goods: false,
        amount: 0,
        vat_amount: 0,
        total: 0,
    };

    // Store
    const requestStore = useRequestStore();
    const { form } = requestStore;

    const addItem = ({ values }) => {
        if (values) {
            const qty = Number(values.purchase_quantity) || 0
            const rate = Number(values.base_rate) || 0
            const tax = Number(values.tax_rate) || 0

            const subtotal = qty * rate
            const vat = (subtotal * tax) / 100
            const total = subtotal + vat

            values.base_rate = rate
            values.amount = subtotal
            values.vat_amount = vat
            values.total = total

            form.quotations.push(values);
            // Reset the form fields after adding
            Object.keys(quotationItem).forEach(key => {
                quotationItem[key] = key === 'currency' ? 'IDR' : (typeof quotationItem[key] === 'number' ? 0 : '');
            });
        }
    };
</script>

<style scoped></style>

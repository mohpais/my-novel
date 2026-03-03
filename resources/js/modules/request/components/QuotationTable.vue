<template>
    <h5 class="text-sm font-medium text-gray-800 dark:text-white/90 mb-5">Quotation Data</h5>
    <div class="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800">
        <div class="custom-scrollbar overflow-x-auto">
            <table class="min-w-full text-left  text-gray-700 dark:border-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="border-b border-gray-100 whitespace-nowrap dark:border-gray-800">
                        <th scope="col" class="py-4 font-medium text-gray-700 dark:text-gray-400"></th>
                        <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $t("App.Vendor_Name") }}</th>
                        <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $t("App.Currency") }}</th>
                        <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Qty</th>
                        <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $t("App.Unit_Price") }}</th>
                        <!-- <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $t("App.Subtotal") }}</th> -->
                        <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $t("App.Tax") }} (%)</th>
                        <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $t("App.Subtotal_Tax") }}</th>
                        <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Total</th>
                        <th v-if="isCreatePage" class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-white/[0.03]">
                    <tr v-if="quotations.length === 0">
                        <td :colspan="isCreatePage ? 10 : 9" class="text-center px-5 py-4  font-medium whitespace-nowrap text-gray-800 dark:text-white/90">
                            No data available
                        </td>
                    </tr>
                    <tr v-else v-for="(item, index) in quotations" :key="index">
                        <td
                            class="px-5 py-4 w-[1%] whitespace-nowrap text-gray-500 dark:text-gray-400"
                        >
                            {{ index + 1 }}
                        </td>
                        <td class="px-5 py-4  font-medium whitespace-nowrap text-gray-800 dark:text-white/90">
                            {{ item.vendor_name }}
                        </td>
                        <td class="px-5 py-4 text-center text-gray-800 dark:text-white/90">
                            {{ item.currency }}
                        </td>
                        <td class="px-5 py-4  whitespace-nowrap text-gray-500 dark:text-gray-400">
                            {{ item.purchase_quantity }}
                        </td>
                        <td class="px-5 py-4  whitespace-nowrap text-gray-500 dark:text-gray-400">
                            {{ formatNumber(item.base_rate) }}
                        </td>

                        <!-- <td class="px-5 py-4  whitespace-nowrap text-gray-500 dark:text-gray-400">
                            {{ formatNumber(item.amount) }}
                        </td> -->

                        <td class="px-5 py-4  whitespace-nowrap text-gray-500 dark:text-gray-400">
                            {{ item.tax_rate }}
                        </td>

                        <td class="px-5 py-4  whitespace-nowrap text-gray-500 dark:text-gray-400">
                            {{ formatNumber(item.vat_amount) }}
                        </td>

                        <td class="px-5 py-4  whitespace-nowrap text-gray-500 dark:text-gray-400">
                            {{ formatNumber(item.total) }}
                        </td>

                        <td v-if="isCreatePage" class="px-5 py-4  whitespace-nowrap text-gray-500 dark:text-gray-400">
                            <div class="flex items-center justify-center">
                                <Button @click="$emit('removeItem', index)" type="button" size="xs" class="hover:fill-error-500 dark:hover:fill-error-500 cursor-pointer fill-gray-700 dark:fill-gray-400">
                                    <i class="fa-solid fa-trash"></i>
                                </Button>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <!-- <tfoot v-if="!isCreatePage"  class="bg-gray-50 dark:bg-gray-900">
                    <tr class="border-t border-gray-100 whitespace-nowrap dark:border-gray-800">
                        <th colspan="7" scope="col" class="px-5 py-4 font-medium whitespace-nowrap text-gray-700 dark:text-gray-400">Grand Total</th>
                        <th class="px-5 py-4 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">{{ formatNumber(grandTotal) }}</th>
                    </tr>
                </tfoot> -->
            </table>
        </div>
    </div>
</template>

<script setup>
    import { formatNumber } from "@/utils";
    import { useRoute } from "vue-router"
    import { computed } from "vue"

    const props = defineProps({
        quotations: {
            type: Array,
            required: true
        }
    });

    const route = useRoute()
    const isCreatePage = computed(() => route.name === 'RequestCreatePage')

    const grandTotal = computed(() => {
        return props.quotations.reduce((akumulator, item) => {
            return akumulator + (item.total || 0)
        }, 0)
    })
</script>

<style scoped></style>

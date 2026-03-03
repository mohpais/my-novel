import { reactive, ref, watch } from "vue";
import service from './service';
import { defineStore } from 'pinia';
import { flushToastQueue } from '@/composables/useToastAfterLayout'

export const useAssetStore = defineStore('asset', () => {
    const data = reactive({
        request: null,
        budgets: []
    });

    const currentStep = ref('request')

    const form = reactive({
        asset: {
            name: "",
            description: "",
            brief_reason: "",
            purchase_quantity: "",
            budget_id: null,
            company_code: 244,
            depreciation_key: "ZSLO",
            asset_class: "",
            replacement: false,
            useful_life: "",
            signature: ""
        },
        justificationDetail: null
    });

    const isLoading = reactive({
        createAsset: false,
    });

    // ✅ contoh watch langsung di dalam store
    watch(
        () => form.asset,
        (newVal) => {
            if (newVal) {
                let newBudgetId = newVal.budget_id;
                if (!newBudgetId) {
                    form.asset.asset_class = ""; // reset jika budget_id kosong
                    return;
                }

                const selectedBudget = data.budgets.find(b => b.id === newBudgetId);
                if (selectedBudget) {
                    form.asset.asset_class = selectedBudget.category; // isi dari kategori budget
                }
            }
        },
        {
            deep: true
        }
    );

    async function btnCreateAsset({ values }) {
        console.log(values);
        if (values) {
            try {
                const result = await service.create(values);
                let { success, message, data: requestData } = result;

                if (success) {
                    data.request = requestData; // ✅ jangan pakai this
                    flushToastQueue(message, { type: 'success' });
                }
            } catch (error) {
                console.error("❌ Error saat memuat count data:", error);
                flushToastQueue(error, { type: 'error' });
            }
        }
    }

    async function fetchBudget() {
        try {
            const result = await service.getBudgets();
            let { success, data: budgetsData } = result;

            if (success) {
                data.budgets = budgetsData; // ✅ jangan pakai this
            }
        } catch (error) {
            console.error("❌ Error saat memuat count data:", error);
            flushToastQueue(error, { type: 'error' });
        }
    }

    async function checkActiveRequest() {
        try {
            const result = await service.getCurrentRequest();
            let { success, data: currentRequest } = result;

            if (success && currentRequest) {
                console.log(currentRequest);

                data.request = currentRequest; // ✅ jangan pakai this
            }
        } catch (error) {
            console.error("❌ Error saat memuat count data:", error);
            flushToastQueue(error, { type: 'error' });
        }
    }

    return {
        data,
        currentStep,
        form,
        isLoading,
        btnCreateAsset,
        fetchBudget,
        checkActiveRequest
    }
});

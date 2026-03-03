import { computed, reactive, ref, watch } from "vue";
import service from './service';
import { defineStore } from 'pinia';
import { queueToastAfterLayout, flushToastQueue } from '@/composables/useToastAfterLayout'
import { listRequestsColumnDefs, workListRequestsColumnDefs } from "./useColumnDefsTable";
import { useRouter } from "vue-router";

export const useRequestStore = defineStore('request', () => {
    const data = reactive({
        request: null,
        asset: null,
        budgets: [],
        questions: []
    });

    const currentListType = ref("listRequests");
    const currentTabId = ref("pending");

    const form = reactive({
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
        signature: "",
        // quotations: [
        //     {
        //         vendor_name: '',
        //         currency: 'IDR',
        //         purchase_quantity: 0,
        //         base_rate: 0,
        //         tax_rate: 0,
        //         luxury_goods: false,
        //         amount: 0,
        //         vat_amount: 0,
        //         total: 0,
        //     }
        // ],
        quotations: [],
        justifications: []
    });

    let tabList = ref([
        {
            id: "pending",
            title: "Pending Requests",
            description: "Daftar permintaan asset yang sudah anda proses.",
            icon: "fa-solid fa-spinner",
            path: `pending`,
            count: 0,
        },
        {
            id: "history",
            title: "History",
            description: "Daftar permintaan asset yang sudah anda proses.",
            icon: "fa-solid fa-clock-rotate-left",
            path: `history`,
            count: 0,
        },
    ]);

    const router = useRouter();

    const isLoading = reactive({
        createRequest: false,
        approveRequest: false,
        rejectRequest: false,
        fetchBudget: false,
        fetchQuestion: false,
        fetchListRequests: false,
        fetchPendingListRequests: false,
        fetchHistoryListRequests: false,
        fetchRequest: false,
        checkActiveRequest: false,
    });

    const dataTable = reactive({
        paginationConfig: {
            currentPage: 1,
            pageSize: 25,
            totalPages: 0,
            totalRecord: 0,
            rowItems: [10, 25, 50, 100],
        },
        filterParams: [],
        sortParams: [],
        column: computed(() => {
            return currentListType.value === "listRequests"
                ? listRequestsColumnDefs
                : workListRequestsColumnDefs;
        }),
        data: [],
    });

    const showModal = reactive({
        approve: false,
        reject: false,
        cancel: false,
        selectedItem: null,
        payload: {},
    })

    // ✅ contoh watch langsung di dalam store
    watch(
        () => form,
        (newVal) => {
            if (newVal) {
                let newBudgetId = newVal.budget_id;
                if (!newBudgetId) {
                    form.request_class = ""; // reset jika budget_id kosong
                    return;
                }

                const selectedBudget = data.budgets.find(b => b.id === newBudgetId);
                if (selectedBudget) {
                    form.asset_class = selectedBudget.category; // isi dari kategori budget
                }
            }
        },
        {
            deep: true
        }
    );

    async function fetchListRequests() {
        isLoading.fetchListRequests = true;
        currentListType.value = "listRequests";
        
        const result = await service.getList(
            dataTable.paginationConfig.currentPage, dataTable.paginationConfig.pageSize,
            {
                filterParams: dataTable.filterParams,
                sortParams: dataTable.sortParams,
            }
        );

        let { success, records, pagination } = result;

        if (success) {
            dataTable.data = records;
            dataTable.paginationConfig.currentPage = pagination.current_page;
            dataTable.paginationConfig.totalPages = pagination.total_pages;
            dataTable.paginationConfig.totalRecord = pagination.total_records;
        }
        isLoading.fetchListRequests = false;
    }

    async function fetchPendingListRequests() {
        isLoading.fetchPendingListRequests = true;
        currentListType.value = "pendingListRequests";
        currentTabId.value = "pending";

        const result = await service.getWorkList(
            currentTabId.value,
            dataTable.paginationConfig.currentPage, 
            dataTable.paginationConfig.pageSize,
            {
                filterParams: dataTable.filterParams,
                sortParams: dataTable.sortParams,
            }
        );

        let { success, records, pagination } = result;

        if (success) {
            dataTable.data = records;
            dataTable.paginationConfig.currentPage = pagination.current_page;
            dataTable.paginationConfig.totalPages = pagination.total_pages;
            dataTable.paginationConfig.totalRecord = pagination.total_records;
        }
        isLoading.fetchPendingListRequests = false;
    }

    async function fetchHistoryListRequests() {
        isLoading.fetchHistoryListRequests = true;
        currentListType.value = "historyListRequests";
        currentTabId.value = "history";

        const result = await service.getWorkList(
            currentTabId.value,
            dataTable.paginationConfig.currentPage, 
            dataTable.paginationConfig.pageSize,
            {
                filterParams: dataTable.filterParams,
                sortParams: dataTable.sortParams,
            }
        );

        let { success, records, pagination } = result;

        if (success) {
            dataTable.data = records;
            dataTable.paginationConfig.currentPage = pagination.current_page;
            dataTable.paginationConfig.totalPages = pagination.total_pages;
            dataTable.paginationConfig.totalRecord = pagination.total_records;
        }
        isLoading.fetchHistoryListRequests = false;
    }

    async function fetchRequest(request_code) {
        isLoading.fetchRequest = true;
        const result = await service.get(request_code);

        let { success, data: asset_request } = result;

        if (success) {
            data.request = asset_request;
        }
        isLoading.fetchRequest = false;
    }

    async function btnCreateRequest({ values }) {
        if (values) {
            isLoading.createRequest = true;
            try {
                const result = await service.create(form);
                let { success, message, data: responseData } = result;

                if (success) {
                    queueToastAfterLayout(message, { type: 'success' });
                    router.push({ name: 'RequestListPage' });
                }
            } catch (error) {
                console.error("❌ Error saat memuat count data:", error);
                flushToastQueue(error, { type: 'error' });
            } finally {
                isLoading.createRequest = false;
            }
        }
    }

    async function fetchBudget() {
        isLoading.fetchBudget = true;
        try {
            const result = await service.getBudgets();
            let { success, data: budgetsData } = result;

            if (success) {
                data.budgets = budgetsData; // ✅ jangan pakai this
            }
        } catch (error) {
            console.error("❌ Error saat memuat count data:", error);
            flushToastQueue(error, { type: 'error' });
        } finally {
            isLoading.fetchBudget = false;
        }
    }

    async function fetchQuestion() {
        isLoading.fetchQuestion = true;
        try {
            const result = await service.getQuestions();
            let { success, data: questionsData } = result;

            if (success) {
                form.justifications = questionsData.map((q) => ({
                    id: q.id,
                    question: q.question_text,
                    answer: "", // default kosong
                }));
            }
        } catch (error) {
            console.error("❌ Error saat memuat count data:", error);
            flushToastQueue(error, { type: 'error' });
        } finally {
            isLoading.fetchQuestion = false;
        }
    }

    async function btnApproveRequest() {
        isLoading.approveRequest = true;
        try {
            const result = await service.approve(showModal.payload);
            let { success, message } = result;

            if (success) {
                queueToastAfterLayout(message, { type: 'success' });
                showModal.approve = false;
                showModal.selectedItem = null;
                showModal.payload = null;
                router.push({ name: 'RequestApprovalPage' });
            }
        } catch (error) {
            console.error("❌ Error saat memuat count data:", error);
            flushToastQueue(error, { type: 'error' });
        } finally {
            isLoading.approveRequest = false;
        }
    }

    async function btnRejectRequest() {
        isLoading.rejectRequest = true;
        try {
            const result = await service.reject(showModal.payload);
            let { success, message } = result;

            if (success) {
                queueToastAfterLayout(message, { type: 'success' });
                showModal.reject = false;
                showModal.selectedItem = null;
                showModal.payload = null;
                router.push({ name: 'RequestApprovalPage' });
            }
        } catch (error) {
            console.error("❌ Error saat memuat count data:", error);
            flushToastQueue(error, { type: 'error' });
        } finally {
            isLoading.rejectRequest = false;
        }
    }

    async function checkActiveRequest() {
        isLoading.checkActiveRequest = true;
        try {
            const result = await service.getDraftRequest();
            let { success, data: responseData } = result;

            if (success && responseData) {
                data.request = responseData.request; // ✅ jangan pakai this
                data.asset = responseData.asset; // ✅ jangan pakai this
            }
        } catch (error) {
            console.error("❌ Error saat memuat count data:", error);
            flushToastQueue(error, { type: 'error' });
        } finally {
            isLoading.checkActiveRequest = false;
        }
    }

    function btn_OpenModal(type, item = null) {
        showModal.selectedItem = item;
        switch (type) {
            case "approve":
                showModal.approve = true;
                showModal.payload = {
                    asset_request_id: item.id,
                    remarks: null,
                }
                break;
            case "reject":
                showModal.reject = true;
                showModal.payload = {
                    asset_request_id: item.id,
                    remarks: null,
                }
                break;
            default:
                break;
        }
    }

    return {
        data,
        dataTable,
        form,
        tabList,
        currentTabId,
        isLoading,
        showModal,
        fetchListRequests,
        fetchPendingListRequests,
        fetchHistoryListRequests,
        fetchRequest,
        btnCreateRequest,
        btnApproveRequest,
        btnRejectRequest,
        fetchBudget,
        fetchQuestion,
        checkActiveRequest,
        btn_OpenModal
    }
});

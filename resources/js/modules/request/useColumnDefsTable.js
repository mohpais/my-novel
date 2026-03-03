// useColumnDefsTable.js
import { dateFormatter } from "@/utils";
import { computed, defineAsyncComponent } from "vue";
import { useTranslation } from "@/composables/useTranslation";
const { i18n } = useTranslation();
const { t } = i18n.global;

const CellAction = defineAsyncComponent(() =>
    import("./components/cell-actions/Index.vue")
);
const CellStatus = defineAsyncComponent(() =>
    import("./components/cell-status/Index.vue")
);

const listRequestsColumnDefs = [
    {
        field: "request_code",
        headerName: t("App.Request_Code"),
        flex: 1 / 3,
        minWidth: 150,
        valueGetter: (params) => params.data.request_code,
    },
    {
        field: "asset_name",
        headerName: t("App.Asset_Name"),
        flex: 1,
        minWidth: 150,
        valueGetter: (params) => params.data.asset_name,
    },
    {
        field: "purchase_quantity",
        headerName: "Quantity",
        flex: 1 / 3,
        minWidth: 120,
        valueGetter: (params) =>
            params.data.purchase_quantity ? `${params.data.purchase_quantity} item` : "-",
    },
    {
        field: "request_status_id",
        flex: 1,
        headerName: t("Commons.Status"),
        valueGetter: (params) => {
            console.log(params.data.request_status_id);
            
            switch (params.data.request_status_id) {
                case 1:
                    return t("App.Draft");
                case 2:
                    return t("App.Submitted");
                case 3:
                    return `${params.data.request_status_name} - ${params.data.current_stage_role}`;
                case 4:
                    return t("App.Approved");
                case 5:
                    return t("App.Rejected");
                case 6:
                    return t("App.Cancelled");
                case 8:
                    return "Completed";
                default:
                    return "-";
            }
        },
        cellClass: (params) => {
            switch (params.data.request_status_id) {
                case 1:
                    return "text-purple-500";
                case 8:
                case 2:
                    return "text-blue-500";
                case 3:
                    return "text-yellow-500";
                case 4:
                    return "text-green-500";
                case 5:
                    return "text-red-500";
                case 6:
                    return "text-gray-500";
                default:
                    return "";
            }
        },
    },
    {
        field: "created_at",
        headerName: "Created At",
        flex: 1,
        valueGetter: (params) =>
            params.data.created_at ? dateFormatter(params.data.created_at) : "-",
    },
    {
        field: "action",
        headerName: "",
        cellRenderer: CellAction,
        cellClass: "text-center",
        pinned: "right",
        sortable: false,
        flex: 0,
        width: 120,
    },
];

const workListRequestsColumnDefs = [
    {
        field: "request_code",
        headerName: "Kode Permintaan", // Contoh custom header
        flex: 1 / 3,
        minWidth: 150,
        valueGetter: (params) => params.data.request_code,
    },
    {
        field: "purchase_quantity",
        headerName: "Qty",
        flex: 1/4,
        minWidth: 120,
        valueGetter: (params) => params.data.purchase_quantity ? `${params.data.purchase_quantity} Item` : '-',
    },
    {
        field: "budget_code",
        headerName: "Anggaran",
        flex: 1,
        minWidth: 220,
        valueGetter: (params) => params.data.budget_code ? `${params.data.budget_code} (${params.data.budget_item})` : '-',
    },
    {
        field: "request_status_id",
        flex: 1 / 3,
        headerName: t("Commons.Status"),
        cellRenderer: CellStatus,
    },
    // Tambahkan kolom lain yang relevan untuk halaman "Awaiting List"
    {
        field: "action",
        headerName: "Aksi",
        cellRenderer: CellAction,
        cellClass: "text-center",
        pinned: "right",
        sortable: false,
        flex: 0,
        width: 120,
    },
];

export { listRequestsColumnDefs, workListRequestsColumnDefs };
// useColumnDefsTable.js
import { dateFormatter } from "@/utils";
import { computed, defineAsyncComponent } from "vue";
import { useTranslation } from "@/composables/useTranslation";
const { i18n } = useTranslation();
const { t } = i18n.global;

const CellStatus = defineAsyncComponent(() =>
    import("./components/cell-status/Index.vue")
);

const CellAction = defineAsyncComponent(() =>
    import("./components/cell-actions/Index.vue")
);

const listNovelsColumnDefs = [
    {
        field: "title",
        headerName: "Title",
        flex: 1 / 3,
        minWidth: 150,
        valueGetter: (params) => params.data.title,
    },
    {
        field: "synopsis",
        headerName: "Synopsis",
        flex: 1,
        minWidth: 150,
        valueGetter: (params) => params.data.synopsis,
    },
    {
        field: "total_views",
        headerName: "Total Views",
        flex: 1,
        minWidth: 120,
        valueGetter: (params) => `${params.data.total_views} views`,
    },
    {
        field: "status",
        headerName: "Status",
        flex: 1,
        cellRenderer: CellStatus,
        width: 120
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

export default listNovelsColumnDefs;
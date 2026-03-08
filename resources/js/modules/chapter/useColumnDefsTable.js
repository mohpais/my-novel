// useColumnDefsTable.js
import { dateFormatter } from "@/utils";
import { computed, defineAsyncComponent } from "vue";
import { useTranslation } from "@/composables/useTranslation";
const { i18n } = useTranslation();
const { t } = i18n.global;

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
        field: "sinopsis",
        headerName: "Synopsis",
        flex: 1,
        minWidth: 150,
        valueGetter: (params) => params.data.sinopsis,
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
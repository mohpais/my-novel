import { computed, defineAsyncComponent } from "vue";
const { dateFormatter }  = "@/utils";
import { useTranslation } from '@/composables/useTranslation';
const { i18n } = useTranslation();
const { t } = i18n.global;

const CellAction = defineAsyncComponent(() =>
  import("./components/CellAction.vue")
);

let columnDefs = [
    {
        field: "id",
        headerName: "ID",
        flex: 1,
        maxWidth: 100,
        valueGetter: (params) => params.data.id,
    },
    {
        field: "email",
        headerName: computed(() => t("Email")),
        flex: 1,
        minWidth: 280,
        valueGetter: (params) =>
        params.data.email ? params.data.email : "-",
    },
    {
        field: "birthOfDate",
        headerName: computed(() => t("App.BirthOfDate")),
        flex: 1,
        minWidth: 240,
        valueGetter: (params) =>
        params.data.birthOfDate ? dateFormatter(params.data.birthOfDate, 'dateShort') : "-",
    },
    {
        field: "action",
        headerName: computed(() => t("Commons.Action")),
        cellRenderer: CellAction,
        cellClass: "text-center",
        pinned: "right",
        sortable: false,
        flex: 0,
        width: 120,
    },
];

export { columnDefs }

// useColumnDefsTable.js
import { computed, defineAsyncComponent } from "vue";
import { useTranslation } from '@/composables/useTranslation';
const { i18n } = useTranslation();
const { t } = i18n.global;

const CellAction = defineAsyncComponent(() =>
    import("./components/CellAction.vue")
);

export function getColumnDefs(currentType) {
    let baseColumns = [
        // {
        //     field: "id",
        //     headerName: "ID",
        //     flex: 1,
        //     maxWidth: 120,
        //     valueGetter: (params) => params.data.id,
        // },
        {
            field: "code",
            headerName: computed(() => t("Commons.Code")),
            flex: 0.15,
            minWidth: 100,
            valueGetter: (params) => params.data.code,
        },
        {
            field: "description",
            headerName: computed(() => t("Commons.Description")),
            flex: 1,
            minWidth: 280,
            valueGetter: (params) => params.data.description ? params.data.description : "-",
        },
    ];

    // Kalau type cost-center → tambahkan kolom profit_center dan business_unit
    if (currentType === 'cost_center') {
        baseColumns.push(
            {
                field: "name",
                headerName: computed(() => t("Commons.Name")),
                flex: 1,
                minWidth: 180,
                valueGetter: (params) => params.data.name,
            },
            {
                field: "profit_center",
                // headerName: computed(() => t("Commons.ProfitCenter")),
                headerName: "Profit Center",
                flex: 1,
                minWidth: 150,
                valueGetter: (params) => params.data.profit_center || "-",
            },
            {
                field: "business_unit",
                // headerName: computed(() => t("Commons.BusinessUnit")),
                headerName: "Business Unit",
                flex: 1,
                minWidth: 150,
                valueGetter: (params) => params.data.business_unit || "-",
            }
        );
    }

    // baseColumns.push({
    //     field: "action",
    //     headerName: computed(() => t("Commons.Action")),
    //     cellRenderer: CellAction,
    //     cellClass: "text-center",
    //     pinned: "right",
    //     sortable: false,
    //     flex: 0,
    //     width: 120,
    // });

    return baseColumns;
}

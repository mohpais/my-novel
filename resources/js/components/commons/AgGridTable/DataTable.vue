<template>
    <div class="flex flex-col w-full">
        <div
        v-if="isHaveFilter && filterList.length > 0"
        class="flex flex-row item-center justify-between px-4 py-1 gap-2 border-b border-b-gray-200 dark:border-b-gray-700">
        <div class="flex-1 flex flex-row gap-0.5 items-center">
            <div class="font-semibold me-2 flex-none dark:text-white">Filter:</div>
            <div
            v-for="(item, index) in columnDefs"
            :key="index"
            class="flex flex-row overflow-auto gap-2 scroll-thin max-w-full">
            <div
                v-if="isFieldFiltered(item.field)"
                class="flex-none max-w-full overflow-auto flex flex-row items-center gap-1 text-xs bg-zinc-200 py-0.5 rounded-full pr-0.5 pl-3 dark:bg-zinc-600 dark:text-zinc-100">
                <span class="flex-none font-semibold">{{ item.headerName }} :</span>
                <span class="flex-none">
                {{ getFilterByField(item) }}
                </span>
                <Button
                @click="clearFilter(item.field)"
                class="w-[20px] h-[20px] flex-none !p-0 !rounded-full bg-zinc-400 text-zinc-200 text-xs ms-2 dark:text-zinc-800">
                <i class="fa-solid fa-xmark"></i>
                </Button>
            </div>
            </div>
            <div class="flex-none">
            <Button @click="clearFilter(null)" class="text-primary !text-xs">{{
                $t("Commons.Clear_All")
            }}</Button>
            </div>
        </div>

        <div class="flex flex-row gap-2 flex-none">
            <slot />
        </div>
        </div>
        <ag-grid-vue
          v-bind="$attrs"
          class="w-full h-full"
          :class="[themeClass]"
          :rowData="rowData"
          :columnDefs="columnDefs"
          :pagination="props.pagination && !props.isServerSide"
          :loading="loading"
          @grid-ready="onGridReady"
          :gridOptions="gridOptions"></ag-grid-vue>
        <Pagination
            v-model:paginationConfig="props.paginationConfig"
            :theme="props.theme"
            @page-changed="handlePagination"
        />
        <!-- <Pagination
            v-if="props.isServerSide && props.pagination"
            v-model:paginationConfig="props.paginationConfig"
            :theme="props.theme"
            @page-changed="handlePagination"
        /> -->
    </div>
</template>

<script setup>
import { computed, ref, defineAsyncComponent } from "vue";
import { AgGridVue } from "ag-grid-vue3";
import { handleTime, dateFormatter } from "@/utils";
import { useTheme } from '@/components/ui/ThemeProvider.vue'

const Pagination = defineAsyncComponent(() =>
  import("./Pagination.vue"),
);

// Store

// State
const { isDarkMode } = useTheme()

// Emits
const emit = defineEmits([
  "onCellValueChanged",
  "onRowSelected",
  "onRowEditingStarted",
  "onRowEditingStopped",
  "onFilterChanged",
  "onSortChanged",
  "getRowClass",
  "onRowClicked",
  "onCellClicked",
  "onFirstDataRendered",
  "onPaginationChanged",
  "page-changed",
  "grid-ready",
]);

// Props
const props = defineProps({
  // Basic Props
  theme: {
    type: String,
    default: "quartz", // Grid theme (options: "quartz", "balham")
  },
  rowData: {
    type: Array,
    required: true, // Data array for table rows (required)
  },
  columnDefs: {
    type: Array,
    required: true, // Column definitions for the table (required)
  },
  loading: {
    type: Boolean,
    required: true,
    default: false, // Loading state to show a loading overlay when true
  },
  isServerSide: {
    type: Boolean,
    default: false, // If true, enables server-side operations (pagination, sorting, filtering)
  },

  editable: {
    type: Boolean,
    default: false, // Ubah ke true atau pastikan Anda mengaktifkannya dari parent
  },

  // Pagination and Sorting
  paginationConfig: {
    type: Object,
    default: () => ({
      pageSize: 25,
      currentPage: 1,
      totalPages: 0,
      totalRecord: 0,
      rowItems: [10, 25, 50, 100],
    }), // Configuration object for pagination settings
  },
  pagination: {
    type: Boolean,
    default: true, // Enables pagination if true
  },
  paginationPageSize: {
    type: Number,
    default: 10, // Number of rows per page
  },
  paginationPageSizeSelector: {
    type: Array,
    default: () => [10, 20, 50, 100], // Options for rows per page selection
  },
  sortingOrder: {
    type: Array,
    default: () => ["asc", "desc"], // Sorting options (e.g., "asc", "desc")
  },

  // Row Configuration
  rowSelection: {
    type: String,
    default: null, // Row selection mode (options: 'single', 'multiple')
  },
  rowMultiSelectWithClick: {
    type: Boolean,
    default: false, // Enables multi-select with single click if true
  },
  suppressRowClickSelection: {
    type: Boolean,
    default: false, // Disables row selection on row click if true
  },
  animateRows: {
    type: Boolean,
    default: true, // Enables row animations on changes if true
  },
  suppressDragLeaveHidesColumns: {
    type: Boolean,
    default: true, // Prevents hiding columns when rows are dragged out
  },
  suppressHorizontalScroll: {
    type: Boolean,
    default: false, // Disables horizontal scrolling if true
  },
  rowHeight: {
    type: Number,
    default: 25, // Height of each row in pixels
  },

  // Column Configuration
  resizable: {
    type: Boolean,
    default: true, // Allows columns to be resizable
  },
  sortable: {
    type: Boolean,
    default: true, // Allows columns to be sortable
  },
  filter: {
    type: Boolean,
    default: false, // Enables basic filtering for columns
  },
  floatingFilter: {
    type: Boolean,
    default: false, // Enables floating filter row for quick column filtering
  },
  editable: {
    type: Boolean,
    default: false, // Makes cells editable if true
  },
  flex: {
    type: Number,
    default: 1, // Flex value for columns to adjust their width dynamically
  },

  // Grouping and Pinned Rows
  rowGroup: {
    type: Boolean,
    default: false, // Enables row grouping if true
  },
  enableRowGroup: {
    type: Boolean,
    default: false, // Allows grouping rows based on columns
  },
  groupDefaultExpanded: {
    type: Number,
    default: 0, // Sets default expanded state of row groups (-1 to expand all)
  },
  enablePivot: {
    type: Boolean,
    default: false, // Enables pivoting functionality if true
  },
  enableValue: {
    type: Boolean,
    default: false, // Allows value columns for aggregations if true
  },
  pinnedTopRowData: {
    type: Array,
    default: () => [], // Data for rows pinned at the top
  },
  pinnedBottomRowData: {
    type: Array,
    default: () => [], // Data for rows pinned at the bottom
  },

  // Row Dragging and Custom Renderers
  rowDrag: {
    type: Boolean,
    default: true, // Enables dragging of rows if true
  },
  enableCellTextSelection: {
    type: Boolean,
    default: false, // Allows text selection in cells if true
  },
  frameworkComponents: {
    type: Object,
    default: () => ({}), // Object for custom cell renderers
  },
  cellRendererParams: {
    type: Object,
    default: () => ({}), // Parameters for custom cell renderers
  },

  // Miscellaneous
  headerHeight: {
    type: Number,
    default: 25, // Height of the header row in pixels
  },
  suppressColumnVirtualisation: {
    type: Boolean,
    default: false, // Disables column virtualization if true
  },
  suppressContextMenu: {
    type: Boolean,
    default: false, // Disables the context menu if true
  },
  suppressClipboardPaste: {
    type: Boolean,
    default: false, // Disables clipboard paste functionality if true
  },
  suppressAggFuncInHeader: {
    type: Boolean,
    default: false, // Disables aggregation function tooltips in headers if true
  },
  domLayout: {
    type: String,
    default: "autoHeight", // DOM layout setting ('normal', 'autoHeight', or 'print')
  },
  tooltipShowDelay: {
    type: Number,
    default: 200, // Delay in ms before showing tooltips
  },
  suppressCellFocus: {
    type: Boolean,
    default: false, // Disables cell focus behavior if true
  },
  enterMovesDownAfterEdit: {
    type: Boolean,
    default: false, // Moves focus down after editing when pressing Enter
  },
  enableRangeSelection: {
    type: Boolean,
    default: false, // Enables range selection of cells if true
  },
  enableFillHandle: {
    type: Boolean,
    default: false, // Enables Excel-like fill handle for drag-and-fill operations
  },
});

const handlePagination = (params) => {
  emit("page-changed", params);
};

const allFilters = ref(null);
const isHaveFilter = ref(false);
const filterList = ref([]);

// Computed function to check if a field exists in filterList
const isFieldFiltered = (field) => {
  return filterList.value.some((filter) => filter.field === field);
};

// Function to get the filter object by field
const getFilterByField = (item) => {
  // Log the filter list for debugging

  // Find the filter object for the given field
  const filter = filterList.value.find((filter) => filter.field === item.field);

  // Check if the filter exists
  if (!filter) {
    return null; // or any default value you want to return when no filter is found
  }

  // Handle case where the filter is a date
  if (filter.isDate) {
    return `${dateFormatter(filter.value, "dateShort")} - ${dateFormatter(
      filter.value2,
      "dateShort",
    )}`; // Return date range
  } else {
    // Handle non-date filter
    return filter.value; // Return the single value
  }
};

// Grid Options
const gridOptions = ref({
  defaultColDef: {
    sortable: props.sortable, // Enable sorting by default on all columns
    filter: props.filter, // Enable filtering by default on all columns
    resizable: props.resizable, // Enable column resizing by default
    flex: props.flex, // Flex columns to fill the grid width
    editable: props.editable,
    floatingFilter: props.floatingFilter,
    unSortIcon: true,
    filterParams: {
      filterOptions: ["contains", "equals", "notEqual"], // Set default filter option, "inRange" -> between
      maxNumConditions: 1, // Set global filter parameters
      // suppressAndOrCondition: false, // Disallow AND/OR conditions
    },
    // minWidth: 200,
  },

  // pagination: props.pagination,
  pagination: props.isServerSide ? false : true,
  paginationPageSize: props.paginationPageSize,
  paginationPageSizeSelector: props.paginationPageSizeSelector,

  // selection: {
  //   mode: props.rowSelection, // or 'multiRow'
  //   checkboxes: false,
  //   enableMultiSelectWithClick: props.rowMultiSelectWithClick,
  //   enableClickSelection: props.suppressRowClickSelection,
  // },

  animateRows: props.animateRows, // Enables row animation when sorting/filtering
  suppressDragLeaveHidesColumns: props.suppressDragLeaveHidesColumns, // Prevents hiding columns when dragging outside the grid area
  suppressHorizontalScroll: props.suppressHorizontalScroll, // If true, disables horizontal scrolling
  // domLayout: "autoHeight", // Adjusts grid height automatically based on the content
  sortingOrder: props.sortingOrder,
  // getRowStyle: (params) => {
  //   return { background: params.node.rowIndex % 2 === 0 ? "#f9f9f9" : "#fff" };
  // },
  // rowClassRules: {
  //   "high-price-row": (params) => params.data.price > 1000,
  // },

  onRowEditingStarted: (params) => {
    emit("onRowEditingStarted", params);
  },
  onRowEditingStopped: (params) => {
    emit("onRowEditingStopped", params);
  },
  onCellValueChanged: (params) => {
    emit("onCellValueChanged", params);
  },
  onRowSelected: (params) => {
    emit("onRowSelected", params);
  },
  onFilterChanged: (params) => {
    allFilters.value = params.api.getFilterModel(); // Get the filter model from AG Grid
    const result = [];

    const getOperator = (type) => {
      switch (type) {
        case "contains":
          return "like";
        case "equals":
          return "=";
        case "notEqual":
          return "!=";
        case "lessThan":
          return "<";
        case "lessThanOrEqual":
          return "<=";
        case "greaterThan":
          return ">";
        case "greaterThanOrEqual":
          return ">=";
        case "inRange":
          return "between";
        default:
          return type;
      }
    };

    const handleCondition = (field, condition, filterData) => {
      const isDate = condition.filterType === "date";
      const isNumber = condition.filterType === "number";
      const isInRange = condition.type === "inRange";

      if (isInRange && isDate) {
        condition.dateTo = `${condition.dateTo.split(" ")[0]} 23:59:00`;
      }

      result.push({
        field,
        operator: isInRange ? "between" : getOperator(condition.type),
        comparator: filterData.operator || "AND",
        isNumber: isNumber,
        isDate: isDate,
        filterType: condition.filterType,
        value: isDate
          ? handleTime(condition.dateFrom)
          : condition.filter.toString(),
        ...(isInRange && {
          value2: isDate
            ? handleTime(condition.dateTo)
            : condition.filterTo.toString(),
        }),
      });
    };

    for (const field in allFilters.value) {
      const filterData = allFilters.value[field];
      const isDate = filterData.filterType === "date";
      const isNumber = filterData.filterType === "number";
      const isInRange = filterData.type === "inRange";

      if (isInRange) {
        if (isDate) {
          filterData.dateTo = `${filterData.dateTo.split(" ")[0]} 23:59:00`;
        }

        result.push({
          field,
          operator: "between",
          comparator: filterData.operator || "AND",
          isNumber: isNumber,
          isDate: isDate,
          filterType: filterData.filterType,
          value: isDate
            ? handleTime(filterData.dateFrom)
            : filterData.filter.toString(),
          value2: isDate
            ? handleTime(filterData.dateTo)
            : filterData.filterTo.toString(),
        });
      } else if (filterData.conditions && filterData.conditions.length > 0) {
        filterData.conditions.forEach((condition) => {
          handleCondition(field, condition, filterData);
        });
      } else {
        result.push({
          field,
          operator: getOperator(filterData.type),
          comparator: filterData.operator || "AND",
          isNumber: isNumber,
          isDate: isDate,
          filterType: filterData.filterType,
          value: isDate
            ? handleTime(filterData.dateFrom)
            : filterData.filter.toString(),
          ...(isInRange && {
            value2: isDate
              ? handleTime(filterData.dateTo)
              : filterData.filterTo.toString(),
          }),
        });
      }
    }

    if (result.length > 0) {
      isHaveFilter.value = true;
      filterList.value = result;
      result[0].comparator = null; // Remove comparator for the first element
    } else {
      isHaveFilter.value = false;
    }

    emit("onFilterChanged", result);
  },

  onSortChanged: (params) => {
    let newParams = params.columns
      .map((column) => {
        const sortBy = column.getColId();
        const sortType = column.getSort();
        return sortType ? { sortBy, sortType } : { sortBy };
      })
      .filter((param) => param.sortType !== undefined);
    emit("onSortChanged", newParams);
  },
  getRowClass: (params) => {
    emit("getRowClass", params);
  },
  onRowClicked: (params) => {
    emit("onRowClicked", params);
  },
  onCellClicked: (params) => {
    emit("onCellClicked", params);
  },
  onFirstDataRendered: (params) => {
    emit("onFirstDataRendered", params);
  },
});

const clearFilter = (field) => {
  if (filterList.value.length > 0) {
    if (!field) {
      // No specific field provided, clear all filters
      isHaveFilter.value = false;
      gridApi.value.setFilterModel(null); // Clear all filters
      filterList.value = []; // Clear the filter list
    } else {
      // Clear the filter for the specific field
      const currentFilterModel = gridApi.value.getFilterModel(); // Get the current filters

      if (currentFilterModel[field]) {
        // If filter exists for the field, remove it
        delete currentFilterModel[field];

        // Set the updated filter model back to the grid
        gridApi.value.setFilterModel(currentFilterModel);

        // Remove the field from the filterList
        filterList.value = filterList.value.filter((x) => x.field !== field);
      }
    }
  }
};

// Define the checkbox selection column
const checkboxSelectionColumn = {
  headerCheckboxSelection: true, // Show checkbox in header to select/deselect all rows
  checkboxSelection: true, // Enable checkbox for each row
  width: 50, // Set the width of the checkbox column
  filter: false,
  flex: 0,
};

// Example of defining a groupable column
const groupableColumn = {
  headerName: "Category",
  field: "category",
  rowGroup: true, // Enable row grouping by this column
  hide: true, // Hide the original column as it will be displayed in the group
};

// Add the checkbox selection column to the beginning of columnDefs
// props.columnDefs.unshift(checkboxSelectionColumn);

const gridApi = ref(null);

const onGridReady = (params) => {
  gridApi.value = params.api;
  // gridApi.value.sizeColumnsToFit();
  emit('grid-ready', params);
};

// onBeforeMount(() => {
//   isRowSelectable.value = (params) => {
//     return !!params.data && params.data.year === 2012;
//   };
// });

// Setup themes class by currentTheme
const themeClass = computed(() =>
  // quartz, balham
  isDarkMode.value
    ? `ag-theme-${props.theme}-dark`
    : `ag-theme-${props.theme}`,
);
</script>

<style>
/* Custom styles can go here */

.ag-header-cell-comp-wrapper {
  justify-content: center;
}

.ag-root-wrapper {
  @apply border-0 rounded-none;
}

.ag-theme-quartz {
  /*
    --ag-foreground-color: rgb(126, 46, 132);
    --ag-background-color: rgb(249, 245, 227);
    --ag-header-foreground-color: rgb(204, 245, 172);
    --ag-header-background-color: rgb(209, 64, 129);
    --ag-odd-row-background-color: rgb(0, 0, 0, 0.03);
    --ag-header-column-resize-handle-color: rgb(126, 46, 132);

    --ag-font-family: "League Spartan", sans-serif;
    */
  --ag-font-family: Poppins, sans;
  --ag-foreground-color: #1d2939;
  --ag-header-foreground-color: #1d2939;
  --ag-background-color: white;
  --ag-header-background-color: white;
  --ag-selected-row-background-color: rgb(0, 255, 0, 0.1);
  --ag-font-size: 11px;
}

.ag-theme-quartz-dark {
  --ag-foreground-color: #e4e7ec;
  --ag-header-foreground-color: #e4e7ec;
  --ag-background-color: #171f2e;
  --ag-header-background-color: #171f2e;
  --ag-border-color: #1d2939;
  --ag-row-border-color: #1d2939;
  --ag-column-border-color: #1d2939;
  --ag-font-family: Poppins, sans;
  --ag-font-size: 11px;
}

.ag-theme-balham {
}

.ag-theme-balham-dark {
  --ag-foreground-color: rgb(126, 46, 132);
  --ag-background-color: rgb(249, 245, 227);
  --ag-header-foreground-color: rgb(204, 245, 172);
  --ag-header-background-color: rgb(209, 64, 129);
  --ag-odd-row-background-color: rgb(0, 0, 0, 0.1);
  --ag-header-column-resize-handle-color: rgb(126, 46, 132);

  --ag-active-color: #2196f3;
  --ag-background-color: color-mix(in srgb, #fff, #182230 97%);
  --ag-foreground-color: #fff;
  /* --ag-border-color: rgba(255, 255, 255, 0.16); */
  --ag-border-color: #1d2939;
  --ag-secondary-border-color: color-mix(
    in srgb,
    transparent,
    var(--ag-foreground-color) 10%
  );
  --ag-header-background-color: color-mix(in srgb, #fff, #182230 93%);
  --ag-tooltip-background-color: color-mix(in srgb, #fff, #182230 96%);
  --ag-control-panel-background-color: color-mix(in srgb, #fff, #182230 93%);
  --ag-input-disabled-background-color: #68686e12;
  --ag-card-shadow: 0 1px 20px 1px black;
  --ag-input-border-color: var(--ag-border-color);
  --ag-input-disabled-border-color: rgba(255, 255, 255, 0.07);
  --ag-checkbox-unchecked-color: color-mix(
    in srgb,
    var(--ag-background-color),
    var(--ag-foreground-color) 40%
  );
  --ag-row-hover-color: color-mix(
    in srgb,
    transparent,
    var(--ag-active-color) 20%
  );
  --ag-selected-row-background-color: var(--ag-row-hover-color);
  --ag-panel-background-color: color-mix(
    in srgb,
    var(--ag-background-color),
    var(--ag-foreground-color) 10%
  );
  --ag-panel-border-color: color-mix(
    in srgb,
    transparent,
    var(--ag-foreground-color) 10%
  );
  --ag-menu-background-color: color-mix(
    in srgb,
    var(--ag-background-color),
    var(--ag-foreground-color) 10%
  );
  --ag-menu-border-color: color-mix(
    in srgb,
    transparent,
    var(--ag-foreground-color) 10%
  );
  --ag-advanced-filter-join-pill-color: #7a3a37;
  --ag-advanced-filter-column-pill-color: #355f2d;
  --ag-advanced-filter-option-pill-color: #5a3168;
  --ag-advanced-filter-value-pill-color: #374c86;
  --ag-popup-shadow: 0 0px 20px rgba(0, 0, 0, 0.3);
  --ag-row-loading-skeleton-effect-color: rgba(202, 203, 204, 0.4);
  color-scheme: dark;
}
</style>

<!-- const columnDefs = [
  {
    headerName: "Name",
    field: "name",
    filter: "agTextColumnFilter",
  },
  {
    headerName: "Age",
    field: "age",
    filter: "agNumberColumnFilter",
  },
  {
    headerName: "Date of Birth",
    field: "dob",
    filter: "agDateColumnFilter",
    filterParams: {
      filterOptions: [
        'equals',
        'notEqual',
        'lessThan',
        'greaterThan',
        'inRange'
      ],
      suppressAndOrCondition: true,
    },
  },
  {
    headerName: "Country",
    field: "country",
    filter: "agSetColumnFilter",
  },
]; -->

<!--
1. agTextColumnFilter
Filters for text-based values. It provides options such as:
contains: Checks if the value contains the specified text.
notContains: Checks if the value does not contain the specified text.
equals: Checks if the value equals the specified text.
notEqual: Checks if the value does not equal the specified text.
startsWith: Checks if the value starts with the specified text.
endsWith: Checks if the value ends with the specified text.
2. agNumberColumnFilter
Filters for numeric values. It provides options such as:
equals: Checks if the value equals the specified number.
notEqual: Checks if the value does not equal the specified number.
lessThan: Checks if the value is less than the specified number.
lessThanOrEqual: Checks if the value is less than or equal to the specified number.
greaterThan: Checks if the value is greater than the specified number.
greaterThanOrEqual: Checks if the value is greater than or equal to the specified number.
inRange: Checks if the value is within a specified range (between two numbers).
3. agDateColumnFilter
Filters for date-based values. It provides options such as:
equals: Checks if the date equals the specified date.
notEqual: Checks if the date does not equal the specified date.
lessThan: Checks if the date is before the specified date.
lessThanOrEqual: Checks if the date is on or before the specified date.
greaterThan: Checks if the date is after the specified date.
greaterThanOrEqual: Checks if the date is on or after the specified date.
inRange: Checks if the date falls within a specified range (between two dates).
4. agSetColumnFilter
Provides a set filter, which shows a list of all unique values in the column and allows the user to select one or multiple values to filter. It is suitable for filtering categories or discrete values.
5. agMultiColumnFilter
Allows combining multiple filters for a single column, such as a text filter combined with a date filter. This filter type enables more complex filtering scenarios.
6. agCustomColumnFilter
Enables the use of a custom filter component that you can define. This is for cases where you want to implement your own filtering logic beyond the standard filters.
-->

<!-- Extended Props List
1. Column Management & Layout
columnHoverHighlight: Boolean - Highlights columns when hovering over them.
suppressColumnMoveAnimation: Boolean - Disables column movement animations.
autoSizeColumns: Boolean - Automatically adjusts column widths based on content.
domLayout: String - 'normal', 'autoHeight', 'print'.
suppressAutoSize: Boolean - Prevents automatic column sizing.
2. Filtering
quickFilterText: String - Text used for filtering the grid quickly.
defaultColDef: Object - Default configuration for all columns (e.g., sortable: true, filter: true).
externalFilterPresent: Boolean - Indicates if an external filter is active.
isExternalFilterPresent: Function - A function to check if an external filter should be applied.
doesExternalFilterPass: Function - A function to check if a row passes the external filter.
3. Row and Cell Editing
singleClickEdit: Boolean - Enables editing with a single click.
editType: String - 'fullRow' or 'singleCell'.
stopEditingWhenCellsLoseFocus: Boolean - Stops editing when focus moves to another cell.
enableCellEditing: Boolean - Enables inline cell editing.
cellEditorFramework: Object - Allows specifying a Vue component for cell editing.
4. Row Drag and Drop
rowDragManaged: Boolean - Allows for managed row dragging.
suppressMoveWhenRowDragging: Boolean - Prevents moving rows when dragging.
enableRowDrag: Boolean - Enables drag-and-drop for rows.
5. Row Styling and Classes
rowStyle: Object - Sets style properties for each row.
getRowStyle: Function - A function returning a style object for each row.
rowClass: String/Object/Array - Static class or classes for rows.
getRowClass: Function - A function returning a CSS class for rows.
rowClassRules: Object - Define rules for dynamically setting classes based on conditions.
6. Selection & Clipboard
suppressCellSelection: Boolean - Disables cell selection.
clipboardCopyDelimiter: String - Delimiter used when copying cell content (e.g., '\t' for tabs).
enableCharts: Boolean - Enables charting in the grid.
suppressRowDeselection: Boolean - Prevents row deselection when clicking on an already selected row.
7. Aggregation & Grouping
autoGroupColumnDef: Object - Defines default settings for the auto group column.
groupIncludeFooter: Boolean - Adds a footer for each group with a summary row.
groupSelectsChildren: Boolean - Selecting a group row selects all its children.
groupSelectsFiltered: Boolean - Only filtered rows are selected when a group is selected.
groupSuppressAutoColumn: Boolean - Prevents automatic group column creation.
8. Grid Events
onRowClicked: Function - Called when a row is clicked.
onCellClicked: Function - Called when a cell is clicked.
onCellValueChanged: Function - Called when a cell value is changed.
onRowSelected: Function - Called when a row is selected.
onRowDoubleClicked: Function - Called when a row is double-clicked.
onFirstDataRendered: Function - Called after the grid has finished loading data for the first time.
9. Server-Side & Infinite Scrolling
rowModelType: String - 'clientSide', 'serverSide', 'infinite', etc.
cacheBlockSize: Number - The number of rows in each block for infinite scrolling.
maxBlocksInCache: Number - Maximum number of blocks to keep in cache.
paginationAutoPageSize: Boolean - Automatically adjusts the page size based on the available rows.
infiniteInitialRowCount: Number - Initial row count for infinite scrolling mode.
10. Data Export
enableExcelExport: Boolean - Enables exporting the grid data to Excel.
enableCsvExport: Boolean - Enables exporting the grid data to CSV.
exportHeaders: Boolean - Option to include headers in exports.
getRowId: Function - Function to provide unique row IDs when exporting.
11. Performance & Optimization
suppressScrollOnNewData: Boolean - Prevents the grid from scrolling to the top when new data is set.
suppressAnimationFrame: Boolean - Disables animation frames for rendering, improving performance.
suppressAsyncEvents: Boolean - Suppresses asynchronous event processing for better control.
batchUpdateWaitMillis: Number - Controls the wait time in milliseconds for batched updates. -->

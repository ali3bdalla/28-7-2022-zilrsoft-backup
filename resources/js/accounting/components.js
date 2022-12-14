import Vue from 'vue';
import AccountingTableFiltersSearchComponent from './components/layout/tableFiltersSearch'
import AccountingTreeViewRawLayoutComponent from './components/layout/treeViewRawLayoutComponent'
import AccountingHeaderLayoutComponent from './components/layout/headerLayoutComponent'
import accountingAppendGatewaysLayoutComponent from './components/layout/appendGatewaysLayoutComponent'
import AccountingTablePaginationHelperLayoutComponent from './components/layout/tablePaginationHelperLayoutComponent'
import AccountingTablePaginationHelperLayoutV2Component
  from './components/layout/tablePaginationHelperLayoutV2Component'
import AccountingSelectWithSearchComponent from './components/layout/selectWithSearchComponent'
import AccountingMultiSelectWithSearchComponent from './components/layout/multiSelectWithSearchLayoutComponent'
import accountingFilterSelectWithSearchComponent from './components/layout/filterSelectWithSearchComponent'
import AccountingBranchesDatatableComponent from './components/branches/branchesDatatableComponent'
import AccountingDepartmentsDatatableComponent from './components/branches/departmentsDatatableComponent'
import AccountingItemsDatatableComponent from './components/items/itemsDatatableComponent'
import AccountingItemsCreateComponent from './components/items/itemsCreateComponent'
import accountingCategoryFiltersListComponent from './components/categories/categoryFiltersListComponent'
import AccountingFiltersDatatableComponent from './components/filters/filtersDatatableComponent'
import AccountingEditFilterAndValuesComponent from './components/filters/editFilterAndValuesComponent'
import AccountingIdentitiesDatatableComponent from './components/users/identitiesDatatableComponent'
import AccountingManagersDatatableComponent from './components/users/managersDatatableComponent'
import AccountingIdentitiesCreateComponent from './components/users/identitiesCreateComponent'
import AccountingManagersCreateComponent from './components/users/managersCreateComponent'
import AccountingManagersGatewaysComponent from './components/users/managersGatewaysComponent'
import AccountingManagerPermissionAndRoleComponent from './components/users/managerPermissionAndRoleComponent'
import AccountingBeginningDatatableComponent from './components/inventories/beginningDatatableComponent'
import AccountingBeginningCreateComponent from './components/inventories/beginningCreateComponent'
import AccountingBeginningEditComponent from './components/inventories/beginningEditComponent'
import AccountingInvoiceItemSerialsListLayoutComponent from './components/layout/invoiceItemserialsListLayoutComponent'
import AccountingReturnItemSerialsListLayoutComponent from './components/layout/returnItemserialsListLayoutComponent'
import AccountingPurchasesDatatableComponent from './components/purchases/purchasesDatatableComponent'
import AccountingPurchasesCreateComponent from './components/purchases/purchaseseCreateComponent'
import AccountingInvoiceEmbeddedPaymentsGatewayLayout from './components/layout/invoiceEmbeddedPaymentsGatewayLayout'
import AccountingInvoiceEmbeddedPurchaseExpensesLayoutComponent
  from './components/layout/invoiceEmbeddedPurchaseExpensesLayoutComponent'
import AccountingSalesDatatableComponent from './components/sales/salesDatatableComponent'
import AccountingInvoicesReportComponent from './components/sales/invoicesReportComponent'
import AccountingSalesCreateComponent from './components/sales/salesCreateComponent'
import AccountingSalesReturnComponent from './components/sales/salesReturnComponent'
import AccountingWarrantyTracingComponent from './components/sales/SaleWarrantyTracing'
import AccountingWarrantyTracingsDatatableComponent from './components/sales/WarrantyTracingsDatatable'
import AccountingKitsDatatableComponent from './components/items/kitsDatatableComponent'
import AccountingKitsCreateComponent from './components/items/kitsCreateComponent'
import AccountingPrintReceiptLayoutComponent from './components/layout/printerReceiptLayoutComponent'
import AccountingPrintersSettingLayoutComponent from './components/layout/printersSettingsLayoutComponent'
import AccountingItemTransactionsComponent from './components/items/itemTransactionsComponent'
import AccountingBarcodePrinterLayoutComponent from './components/layout/barcodePrinterLayoutComponent'
import AccountingBarcodeBulkPrinterLayoutComponent from './components/layout/barcodeBulkPrinterLayoutComponent'
import layoutPrintSingleBarcodeLayoutComponent from './components/layout/layoutPrintSingleBarcodeLayoutComponent'
import AccountingKitItemsLayoutComponent from './components/layout/kitItemsLayoutComponent'
import AccountingKitReturnItemsLayoutComponent from './components/layout/kitReturnItemsLayoutComponent'
import AccountingPurchasesReturnComponent from './components/purchases/purchasesReturnComponent'
import AccountingShowBarcodeLayoutComponent from './components/layout/showBarcodeLayoutComponent'
import AccountingAccountsChartComponent from './components/charts/accountsChartComponent'
import AccountingDeleteButtonLayoutComponent from './components/layout/deleteButtonLayoutComponent'
import AccountingTransactionsCreateComponent from './components/transactions/transactionsCreateComponent'
import AccountingVouchersDatatableComponent from './components/vouchers/vouchersDatatableComponet'
import AccountingVouchersCreateComponent from './components/vouchers/vouchersCreateComponet'
import AccountingQuotationsCreateComponent from './components/quotations/quotationsCreateComponet'
import AccountingQuotationsServicesCreateComponent from './components/quotations/quotationsSerivcesCreateComponent'
import AccountingPeriodAccountCloseComponent from './components/charts/periodAccountCloseComponent'
import AccountingResellerDailyTransferAmountsComponent from './components/reseller_daily/transferAmountsComponent'
import AccountingHeaderNotificationsLayoutComponent from './components/layout/headerLayoutComponent'
import AccountingSingleBarcodeLayoutComponent from './components/layout/singleBarcodeComponent'
import AccountingAdjustStockDatatableComponent from './components/inventories/adjustStockDatatableComponent'
import AccountingAdjustStockCreateComponent from './components/inventories/adjustStockCreateComponent'
import AccountingChartOfAccountsListComponent from './components/charts/chartOfAccountsListComponent'
import AccountingAccountReportComponent from './components/charts/accountReportComponent'
import AccountingGlobalTransactionsListComponent from './components/transactions/globalTransactionsListComponent'
import AccountingAttachmentsPreviewComponent from './components/attachments/previewComponent'
import AccountingFinancialStatementsTrailBalanceComponent from './components/financial_statements/trailBalanceComponent'
import AccountingOrdersDatatableComponent from './components/orders/ordersDatatableComponent'
Vue.component(
  'accounting-attachments-preview-component',
  AccountingAttachmentsPreviewComponent
)
Vue.component(
  'accounting-search-category-filters-components',
  require('./components/categories/categorySearchFiltersComponent').default
)
Vue.component(
  'accounting-table-filter-search-component',
  AccountingTableFiltersSearchComponent
)
Vue.component(
  'accounting-header-layout-component',
  AccountingHeaderLayoutComponent
)
Vue.component(
  'accounting-append-gateways-layout-component',
  accountingAppendGatewaysLayoutComponent
)
Vue.component(
  'accounting-treeview-raw-layout-component',
  AccountingTreeViewRawLayoutComponent
)
Vue.component(
  'accounting-table-pagination-helper-layout-component',
  AccountingTablePaginationHelperLayoutComponent
)
Vue.component(
  'accounting-table-pagination-helper-layout-v2-component',
  AccountingTablePaginationHelperLayoutV2Component
)
Vue.component(
  'accounting-select-with-search-layout-component',
  AccountingSelectWithSearchComponent
)
Vue.component(
  'accounting-multi-select-with-search-layout-component',
  AccountingMultiSelectWithSearchComponent
)
Vue.component(
  'accounting-filter-select-with-search-component',
  accountingFilterSelectWithSearchComponent
)
Vue.component(
  'accounting-items-datatable-component',
  AccountingItemsDatatableComponent
)
Vue.component(
  'accounting-items-create-component',
  AccountingItemsCreateComponent
)
Vue.component(
  'accounting-category-filters-list-component',
  accountingCategoryFiltersListComponent
)
Vue.component(
  'accounting-filters-datatable-component',
  AccountingFiltersDatatableComponent
)
Vue.component(
  'accounting-edit-filter-and-values-component',
  AccountingEditFilterAndValuesComponent
)
Vue.component(
  'accounting-identities-datatable-component',
  AccountingIdentitiesDatatableComponent
)
Vue.component(
  'accounting-managers-datatable-component',
  AccountingManagersDatatableComponent
)

Vue.component(
  'accounting-financial-statements-trial-balance-component',
  AccountingFinancialStatementsTrailBalanceComponent
)

Vue.component(
  'accounting-managers-gateways-component',
  AccountingManagersGatewaysComponent
)
Vue.component(
  'accounting-identities-create-component',
  AccountingIdentitiesCreateComponent
)
Vue.component(
  'accounting-managers-create-component',
  AccountingManagersCreateComponent
)
Vue.component(
  'accounting-managers-permissions-and-roles-component',
  AccountingManagerPermissionAndRoleComponent
)
// Vue.component(
//   'accounting-dashboard-items-chart-component',
//   AccountingDashboardItemsChartComponent
// )
Vue.component(
  'accounting-branches-datatable-component',
  AccountingBranchesDatatableComponent
)
Vue.component(
  'accounting-departments-datatable-component',
  AccountingDepartmentsDatatableComponent
)
Vue.component(
  'accounting-beginning-datatable-component',
  AccountingBeginningDatatableComponent
)
Vue.component(
  'accounting-adjust-stock-datatable-component',
  AccountingAdjustStockDatatableComponent
)
Vue.component(
  'accounting-beginning-create-component',
  AccountingBeginningCreateComponent
)
Vue.component(
  'accounting-beginning-edit-component',
  AccountingBeginningEditComponent
)
Vue.component(
  'accounting-invoice-item-serials-list-layout-component',
  AccountingInvoiceItemSerialsListLayoutComponent
)
Vue.component(
  'accounting-return-item-serials-list-layout-component',
  AccountingReturnItemSerialsListLayoutComponent
)
Vue.component(
  'accounting-purchases-datatable-component',
  AccountingPurchasesDatatableComponent
)
Vue.component(
  'accounting-purchases-create-component',
  AccountingPurchasesCreateComponent
)
Vue.component(
  'accounting-invoice-embedded-payments-gateway-layout',
  AccountingInvoiceEmbeddedPaymentsGatewayLayout
)
Vue.component(
  'accounting-invoice-embedded-purchase-expenses-layout',
  AccountingInvoiceEmbeddedPurchaseExpensesLayoutComponent
)
Vue.component(
  'accounting-sales-datatable-component',
  AccountingSalesDatatableComponent
)
Vue.component(
  'accounting-invoices-report-component',
  AccountingInvoicesReportComponent
)
Vue.component(
  'accounting-sales-create-component',
  AccountingSalesCreateComponent
)
Vue.component(
  'accounting-sales-return-component',
  AccountingSalesReturnComponent
)
Vue.component(
  'accounting-warranty-tracing-component',
    AccountingWarrantyTracingComponent
)
Vue.component(
    'accounting-warranty-tracings-datatable-component',
    AccountingWarrantyTracingsDatatableComponent
)
Vue.component(
  'accounting-kits-datatable-component',
  AccountingKitsDatatableComponent
)
Vue.component('accounting-kits-create-component', AccountingKitsCreateComponent)
Vue.component(
  'accounting-print-receipt-layout-component',
  AccountingPrintReceiptLayoutComponent
)
Vue.component(
  'accounting-printers-setting-layout-component',
  AccountingPrintersSettingLayoutComponent
)
Vue.component(
  'accounting-item-transactions-component',
  AccountingItemTransactionsComponent
)
Vue.component(
  'accounting-barcode-printer-layout-component',
  AccountingBarcodePrinterLayoutComponent
)
Vue.component(
  'accounting-barcode-bulk-printer-layout-component',
  AccountingBarcodeBulkPrinterLayoutComponent
)
Vue.component(
  'accounting-print-single-barcode-layout-component',
  layoutPrintSingleBarcodeLayoutComponent
)
Vue.component(
  'accounting-kit-items-layout-component',
  AccountingKitItemsLayoutComponent
)
Vue.component(
  'accounting-kit-return-items-layout-component',
  AccountingKitReturnItemsLayoutComponent
)
Vue.component(
  'accounting-purchases-return-component',
  AccountingPurchasesReturnComponent
)
// Vue.component(
//   'accounting-dashboard-active-items-layout-component',
//   AccountingdashboardActiveItemsLayoutComponet
// )
Vue.component(
  'accounting-show-barcode-layout-component',
  AccountingShowBarcodeLayoutComponent
)
Vue.component(
  'accounting-accounts-chart-component',
  AccountingAccountsChartComponent
)
Vue.component(
  'accounting-delete-button-layout-component',
  AccountingDeleteButtonLayoutComponent
)
Vue.component(
  'accounting-transactions-create-component',
  AccountingTransactionsCreateComponent
)
Vue.component(
  'accounting-vouchers-datatable-component',
  AccountingVouchersDatatableComponent
)
Vue.component(
  'accounting-vouchers-create-component',
  AccountingVouchersCreateComponent
)
Vue.component(
  'accounting-quotations-create-component',
  AccountingQuotationsCreateComponent
)
Vue.component(
  'accounting-quotations-services-create-component',
  AccountingQuotationsServicesCreateComponent
)
Vue.component(
  'accounting-period-account-close-component',
  AccountingPeriodAccountCloseComponent
)
Vue.component(
  'accounting-reseller-daily-transfer-amounts-component',
  AccountingResellerDailyTransferAmountsComponent
)
Vue.component(
  'accounting-header-notifications-layout-component',
  AccountingHeaderNotificationsLayoutComponent
)
Vue.component(
  'accounting-single-barcode-layout-component',
  AccountingSingleBarcodeLayoutComponent
)
Vue.component(
  'accounting-adjust-stock-create-component',
  AccountingAdjustStockCreateComponent
)
Vue.component(
  'accounting-chart-of-accounts-list-component',
  AccountingChartOfAccountsListComponent
)
Vue.component(
  'accounting-global-transactions-list-component',
  AccountingGlobalTransactionsListComponent
)
Vue.component(
  'accounting-account-report-component',
  AccountingAccountReportComponent
)
Vue.component(
  'accounting-orders-datatable-component',
  AccountingOrdersDatatableComponent
)

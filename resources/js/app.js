import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import VuejsDialog from 'vuejs-dialog';
import ToggleButton from 'vue-js-toggle-button'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import 'vuejs-dialog/dist/vuejs-dialog.min.css';
import draggable from 'vuedraggable'
import VModal from 'vue-js-modal'
import CxltToastr from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'

// imp
// components including
import categoriesList from './components/categories/ListComponent';
import chartOfAccountsComponent from './components/accounts/chartOfAccountsComponent';

import ItemAccountingCostHistoryComponent from './components/items/accountingCostHistory';

import filterList from './components/filters/ListComponent';
import InputTextArea from './components/InputTextAreaComponent';
import InputText from './components/InputTextComponent';
import SelectList from './components/SelectListComponent';
import sidebarItem from './components/sidebar/ItemComponent';
import viewItemComponent from './components/items/viewItemComponent';
import newItemFormComponent from './components/items/newItemFormComponent';
import itemTableListComponent from './components/items/itemTableListComponent.vue';
import createSaleFormComponent from './components/sales/createFormComponent';
import createQuotationFormComponent from './components/sales/createQuotationFormComponent';
import createPurchaseFormComponent from './components/purchases/createFormComponent';
import createPurchaseFormComponent2 from './components/purchases/createFormComponent2';
import editPurchaseFormComponent from './components/purchases/editFormComponent';
import filtersValuesListComponent from './components/filters/filtesValuesListComponent';
import categoryfiltersListComponent from './components/categories/filterListComponent.vue';
import CreateKitComponent from './components/kits/CreateKitComponent.vue';
import kitItemAndDataComponent from './components/items/kitItemAndDataComponent.vue';
import createUserFormComponent from './components/users/createUserFormComponent.vue';
import deleteButtonComponent from './components/deleteButtonComponent.vue';
import FilterValueSelectWithSearchComponent from './components/filters/FilterValueSelectWithSearchComponent';
import popBillingComponent from './components/billing/popBillingComponent';
import uploadDocComponent from './components/uploadDocComponent.vue';
import printInvoiceComponent from './components/printInvoiceComponent.vue';
import itemSerialPurchaseReturnListComponent from './components/invoice/itemSerialPurchaseReturnListComponent.vue';
import itemSerialSaleReturnListComponent from './components/invoice/itemSerialSaleReturnListComponent.vue';
import editSaleFormComponent from './components/sales/editFormComponent.vue';
import createPaymentFormComponent from './components/billing/createPaymentFormComponent.vue';
import createReceiptFormComponent from './components/billing/createReceiptFormComponent.vue';
import createReceiptFormComponent1 from './components/billing/createReceiptFormComponent1.vue';
import createPayWayFormComponent from './components/settings/createPayWayFormComponent.vue';
import customDateFieldComponent from './components/customDateFieldComponent';
import showBarcodeComponent from './components/showBarcodeComponent';
import ExpensesListComponent from './components/billing/expensesListComponent';
import liveServerDateAndTime from './components/liveServerDateAndTime.vue';
import UserGatewaysComponent from './components/users/userGatewaysComponent'


import ItemSerialComponent from './components/invoice/itemSerialListComponent';
import invoicePrintPageComponent from './components/invoice/printTemplateComponent';
import createBeginningInventoryComponent from './components/inventories/createBeginningInventoryComponent';
import updateBeginningInventoryComponent from './components/inventories/updateBeginningInventoryComponent';
import itemMovementHistoryComponent from './components/items/itemMovementHistoryComponent';
import mulitSelectComponent from './components/mulitSelectComponent.vue';
import addNewPaymentAccountComponent from './components/settings/addNewPaymentAccountComponent';
import customSelectComponent from './components/customSelectComponent';
import customBillingInvoicesComponent from './components/billing/customBillingInvoicesComponent.vue';


import saleTableComponent from './components/sales/tableComponent';
import inventoryTableComponent from './components/inventories/tableComponent';
import purchaseTableComponent from './components/purchases/tableComponent';


import invoiceA4Component from './components/print/invoiceA4Component.vue';
import localPrintersComponent from "./components/print/localPrintersComponent.vue";
import receiptPrinterComponent from "./components/print/receiptPrinterComponent";
import barcodePrinterComponent from "./components/print/barcodePrinterComponent";
import createVoucherFormComponent from "./components/vouchers/createFormComponent.vue";


import saleDataTable from './components/datatables/saleTable';
import ManagerGatewaysComponent from "./components/billing/ManagerGatewaysComponent";

import createTransactionFromComponent from './components/transactions/createTransaction';
import Toasted from 'vue-toasted';


require('./bootstrap');


var config = require('../js/config');


window.Vue = Vue;
require('./accounting/load');

// import VueTable from './components/enso/vuedatatable/VueTable.vue';
Vue.use(Toasted);
Vue.use(Vuetify);
Vue.use(VModal);
Vue.use(CxltToastr,
    {
        position: 'top right',
        showDuration: 2000
    });
Vue.use(draggable);
Vue.use(VuejsDialog);
// Vue.use(AgGridVue);
Vue.use(Loading);
Vue.use(ToggleButton);


// component
Vue.component('item-serials-list-component', ItemSerialComponent);
Vue.component('live-server-date-and-time', liveServerDateAndTime);
Vue.component('expenses-list-component', ExpensesListComponent);
Vue.component('user-accounts-component', UserGatewaysComponent);
Vue.component('create-voucher-form-component', createVoucherFormComponent);


Vue.component('item-accounting-cost-history-component', ItemAccountingCostHistoryComponent);
Vue.component('create-transaction-form-component', createTransactionFromComponent);

Vue.component('input-text-component', InputText);
Vue.component('print-invoice-component', printInvoiceComponent);
Vue.component('filter-select-with-search-component', FilterValueSelectWithSearchComponent);
Vue.component('select-list-component', SelectList);
Vue.component('input-textarea-component', InputTextArea);
Vue.component('sidebar-item-component', sidebarItem);
Vue.component('categories-list-component', categoriesList);
Vue.component('filters-list-component', filterList);
Vue.component('new-item-form-component', newItemFormComponent);
Vue.component('view-item-component', viewItemComponent);
// Vue.component('items-table-component', itemTableCompoent);
Vue.component('create-sale-form-component', createSaleFormComponent);
Vue.component('create-quotation-form-component', createQuotationFormComponent);
Vue.component('create-purchase-form-component', createPurchaseFormComponent);
Vue.component('create-purchase-form-component2', createPurchaseFormComponent2);
Vue.component('edit-purchase-form-component', editPurchaseFormComponent);
Vue.component('edit-sale-form-component', editSaleFormComponent);
// Vue.component('show-sale-component', showSaleComponent);
Vue.component('filter-values-list-component', filtersValuesListComponent);
Vue.component('category-filters-component', categoryfiltersListComponent);
Vue.component('create-user-form-component', createUserFormComponent);
Vue.component('item-list-table-component', itemTableListComponent);
Vue.component('create-kit-component', CreateKitComponent);
Vue.component('delete-button-component', deleteButtonComponent);
Vue.component('pop-billing-component', popBillingComponent);
Vue.component('invoice-print-page-component', invoicePrintPageComponent);
Vue.component('upload-doc-component', uploadDocComponent);
Vue.component('create-beginning-inventory-form-component', createBeginningInventoryComponent);
Vue.component('update-beginning-inventory-form-component', updateBeginningInventoryComponent);
Vue.component('item-serial-for-purchase-return-component', itemSerialPurchaseReturnListComponent);
Vue.component('item-serial-for-sale-return-component', itemSerialSaleReturnListComponent);
Vue.component('create-receipt-form-component', createReceiptFormComponent);
Vue.component('create-receipt-form-component1', createReceiptFormComponent1);
Vue.component('create-payment-form-component', createPaymentFormComponent);
Vue.component('item-movement-history-component', itemMovementHistoryComponent);
Vue.component('multi-select-component', mulitSelectComponent);
Vue.component('add-new-payment-account-component', addNewPaymentAccountComponent);
Vue.component('custom-select-component', customSelectComponent);
Vue.component('custom-billing-invoices-component', customBillingInvoicesComponent);
Vue.component('custom-pay-way-form-component', createPayWayFormComponent);
Vue.component('custom-date-field-component', customDateFieldComponent);
Vue.component('kit-items-and-data-component', kitItemAndDataComponent);


Vue.component('chart-of-accounts-component', chartOfAccountsComponent);
Vue.component('manager-gateways-component', ManagerGatewaysComponent);


Vue.component('purchase-table-component', purchaseTableComponent);
Vue.component('sale-table-component', saleTableComponent);
Vue.component('inventory-table-component', inventoryTableComponent);


Vue.component('invoice-a4-component', invoiceA4Component);
Vue.component('receipt-printer-component', receiptPrinterComponent);
Vue.component('barcode-printer-component', barcodePrinterComponent);
Vue.component('local-printers-component', localPrintersComponent);
Vue.component('show-barcode-component', showBarcodeComponent);


Vue.component('sale-datatable', saleDataTable);


/*

	forms compnents
*/
// Vue.component('user-create-form-component', require('./components/forms/UserCreateFormComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



const app = new Vue({
    el: '#app',
});

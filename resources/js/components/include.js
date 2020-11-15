import Vue from 'vue'
const OnlineOrdersTable = require("./BackEnd/Orders/OnlineOrdersTable");
const SupplierVoucher = require("./BackEnd/Vouchers/SupplierVoucherComponent");

Vue.component('online-orders-table', OnlineOrdersTable.default);
Vue.component('supplier-voucher', SupplierVoucher.default);


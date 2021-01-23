import Vue from 'vue'
import DeliveryMenTable from './BackEnd/DeliveryMen/DeliveryMenTable'

const OrderPaymentOptions = require('./BackEnd/Orders/OrderPaymentOptions')
const OnlineOrdersTable = require('./BackEnd/Orders/OnlineOrdersTable')
const ShippingMethodTransctionsTable = require('./BackEnd/ShippingMethod/ShippingMethodTransctions')
const DeliveryMenAsign = require('./BackEnd/ShippingMethod/DeliveryMenAsign')
const OrderShippingOptions = require('./BackEnd/Orders/OrderShippingOptions')
const SupplierVoucher = require('./BackEnd/Vouchers/SupplierVoucherComponent')

Vue.component('online-orders-table', OnlineOrdersTable.default)
Vue.component('delivery-men-table', DeliveryMenTable.default)
Vue.component('supplier-voucher', SupplierVoucher.default)
Vue.component('order-payment-options', OrderPaymentOptions.default)
Vue.component('order-shipping-options', OrderShippingOptions.default)
Vue.component('shipping-transactions-asgin', DeliveryMenAsign.default)
Vue.component('shipping-method-transactions-table', ShippingMethodTransctionsTable.default)

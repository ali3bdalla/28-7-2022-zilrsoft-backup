<template>
    <!-- startup box -->
    <div class="message">

        <div class="row">
            <div class="col-md-6">
                <button :disabled="!everythingFineToSave" @click="pushDataToServer" class="btn btn-custom-primary"><i
                        class="fa fa-save"></i> {{ app.trans.save }}
                </button>

                <button :disabled="!everythingFineToSave" @click="pushDataToServer" class="btn btn-custom-primary"><i
                        class="fa fa-save"></i> {{ app.trans.save_and_print4 }}
                </button>
            </div>
            <div class="col-md-6">
                <a :href="app.BaseApiUrl + 'purchases'" class="btn btn-default "><i
                        class="fa fa-redo"></i> {{ app.trans.cancel }}</a>
            </div>

        </div>


        <div class="row">
            <div class="col-md-6">
                <accounting-select-with-search-layout-component
                        :default-index="clients[0].id"
                        :no_all_option="true"
                        :options="clients"
                        :placeholder="app.trans.client"
                        :title="app.trans.client"
                        @valueUpdated="clientListChanged"
                        identity="001"
                        index="001"
                        label_text="name"
                >

                </accounting-select-with-search-layout-component>

            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon" id="time-field">{{ app.trans.date }}</span>
                    <input aria-describedby="time-field" class="form-control" disabled name="" readonly style=" direction: ltr
                            !important;"
                           type="text" v-model="LiveTimer">

                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <accounting-select-with-search-layout-component
                        :default-index="creator.id"
                        :no_all_option="true"
                        :options="salesmen"
                        :placeholder="app.trans.salesman"
                        :title="app.trans.salesman"
                        @valueUpdated="salesmanListChanged"
                        identity="002"
                        index="002"
                        label_text="name"
                >

                </accounting-select-with-search-layout-component>

            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">{{ app.trans.department }}</span>
                    <input aria-describedby="time-field" class="form-control" disabled
                           type="text" v-model="creator.department.locale_title">

                </div>
            </div>
        </div>


        <!-- start search field -->
        <div class="row">
            <div class="col-md-8">
                <div class="product_search" id="seach_area">
                    <div class="">
                        <input :placeholder="app.trans.search_barcode"
                               @keyup.enter="sendQueryRequestToFindItems"
                               class="form-control" ref="barcodeNameAndSerialField"
                               type="text"
                               v-model="barcodeNameAndSerialField"/>
                    </div>
                    <div class="live-vue-search panel" v-show="searchResultList.length >=1">
                        <div :key="item.id" @click="validateAndPrepareItem(item)"
                             class="panel-footer" href="#" v-for="item in searchResultList">
                            <h4 class="title has-text-white">{{ item.locale_name }}
                                <small class="has-text-white">{{ item.barcode}} - {{ item.price }}</small>
                            </h4>

                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-2" v-if="canViewItems==1">
                <a :href="app.BaseApiUrl +
                    'items?selectable=true'" class="btn btn-custom-primary btn-lg"
                   target="_blank">{{ app.trans.view_products}}</a>

            </div>
            <div class="col-md-2" v-if="canCreateItem==1">
                <a :href="app.BaseApiUrl + 'items/create'" class="btn btn-custom-primary btn-lg"
                   target="_blank">{{app.trans.create_product}}</a>
            </div>


        </div>


        <div class="panel panel-primary">
            <table class="table table-bordered text-center  table-striped">
                <thead class="panel-heading">
                <tr>
                    <th></th>
                    <!-- <th class="has-text-white"></th> -->
                    <th>{{ app.trans.barcode }}</th>
                    <th>{{ app.trans.item_name }}</th>
                    <th>{{ app.trans.item_available_qty }}</th>
                    <th>{{ app.trans.qty }}</th>
                    <th>{{app.trans.sales_price}}</th>
                    <th>{{ app.trans.total }}</th>
                    <th>{{ app.trans.discount }}</th>
                    <th>{{ app.trans.subtotal }}</th>
                    <th>{{ app.trans.tax }}</th>
                    <th>{{ app.trans.net }}</th>

                </tr>


                </thead>

                <tbody>


                <tr :key="item.id" v-for="(item,index) in invoiceData.items">
                    <td>

                        <button @click="deleteItemFromList(item)" class="btn btn-danger btn-xs"><i
                                class="fa fa-trash"></i></button>
                        <button @click="openItemSerialsModal(index,item)"
                                class="btn btn-success btn-xs"
                                v-if="item.is_need_serial"><i class="fa fa-pen"></i> &nbsp;
                        </button>

                    </td>
                    <td v-text="item.barcode"></td>
                    <td v-text="item.locale_name"></td>
                    <td v-text="item.available_qty"></td>
                    <td>
                        <input
                                :placeholder="app.trans.qty"
                                :ref="'itemQty_' + item.id + 'Ref'"
                                @focus="$event.target.select()"
                                @keyup="itemQtyUpdated(item)"
                                class="form-control input-xs amount-input"
                                type="text"
                                v-if="!item.is_need_serial"
                                v-model="item.qty"
                        >
                        <p v-else>{{item.qty}}</p>
                    </td>


                    <td>
                        <input
                                :disabled="item.is_fixed_price"
                                :ref="'itemPrice_' + item.id + 'Ref'"
                                @focus="$event.target.select()"
                                @keyup="itemPriceUpdated(item)"
                                class="form-control input-xs amount-input"
                                type="text"
                                v-model="item.price">

                    </td>


                    <td>
                        <input @focus="$event.target.select()" class="form-control input-xs amount-input" disabled
                               type="text"
                               v-model="item.total">
                    </td>
                    <td>
                        <input :disabled="item.is_kit"
                               :ref="'itemDiscount_' + item.id + 'Ref'"
                               @focus="$event.target.select()"
                               @keyup="itemDiscountUpdated(item)"
                               class="form-control input-xs amount-input" placeholder="discount" type="text"
                               v-model="item.discount">
                    </td>
                    <td>
                        <input @focus="$event.target.select()" class="form-control input-xs amount-input" disabled=""
                               placeholder="subtotal"

                               type="text" v-model="item.subtotal">
                    </td>

                    <td>
                        <input @focus="$event.target.select()" class="form-control input-xs amount-input" disabled=""
                               placeholder="tax"
                               type="text" v-model="item.tax">
                    </td>
                    <td>
                        <input @focus="$event.target.select()"
                               class="form-control input-xs amount-input"
                               disabled
                               placeholder="net" type="text" v-model="item.net">
                    </td>


                </tr>

                </tbody>
            </table>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            {{ app.trans.invoice_data }}
                        </div>
                        <div class="panel-body text-center">
                            <div class="row">
                                <div class="col-md-6"><label>{{ app.trans.total }}</label></div>
                                <div class="col-md-6">
                                    <input :placeholder="app.trans.total"
                                           class="form-control  input-xs amount-input"
                                           disabled type="text"
                                           v-model="invoiceData.total">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><label>{{ app.trans.discount }}</label></div>
                                <div class="col-md-6">
                                    <input :placeholder="app.trans.discount"
                                           class="form-control  input-xs amount-input"
                                           disabled type="text"
                                           v-model="invoiceData.discount">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6"><label>{{ app.trans.subtotal }}</label></div>
                                <div class="col-md-6">
                                    <input :placeholder="app.trans.subtotal"
                                           class="form-control  input-xs amount-input"
                                           disabled type="text"
                                           v-model="invoiceData.total">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6"><label>{{ app.trans.tax }}</label></div>
                                <div class="col-md-6">
                                    <input :placeholder="app.trans.tax"
                                           class="form-control  input-xs amount-input"
                                           disabled type="text"
                                           v-model="invoiceData.tax">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><label>{{ app.trans.net }}</label></div>
                                <div class="col-md-6">
                                    <input :placeholder="app.trans.net"
                                           class="form-control  input-xs amount-input"
                                           disabled type="text"
                                           v-model="invoiceData.net">
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--                    <accounting-invoice-embedded-purchase-expenses-layout-->
                    <!--                            :expenses="expensesList"-->
                    <!--                            @expenseDeIncludeInNet="expenseDeIncludeInNet"-->
                    <!--                            @expenseIncludeInNet="expenseIncludeInNet"-->
                    <!--                            @expensesUpdated="expensesListUpdated"-->
                    <!--                    >-->

                    <!--                    </accounting-invoice-embedded-purchase-expenses-layout>-->

                </div>
                <div class="col-md-9">
                    <accounting-invoice-embedded-payments-gateway-layout
                            :gateways="gateways"
                            :net-amount="invoiceData.net"
                            @updateGatewaysAmounts="updateGatewaysAmounts"
                            invoice-type="purchase"
                    >
                    </accounting-invoice-embedded-payments-gateway-layout>
                </div>

            </div>
        </div>


        <accounting-invoice-item-serials-list-layout-component
                :item="selectedItem"
                :item-index="selectedItemIndex"
                @panelClosed="handleItemSerialsClosed"
                @publishUpdated="handleItemSerialsUpdated"
                invoice-type="sale"
        >

        </accounting-invoice-item-serials-list-layout-component>

    </div>


</template>

<script>

    import {
        accounting as ItemAccounting,
        math as ItemMath,
        query as ItemQuery,
        validator as ItemValidator
    } from '../../item';

    export default {
        props: ['creator', 'clients', 'salesmen', 'gateways', 'expenses', 'canViewItems', 'canCreateItem'],
        data: function () {
            return {
                everythingFineToSave: false,
                selectedItem: null,
                selectedItemIndex: null,
                invoiceData: {
                    remaining: 0,
                    vendorIncCumber: "",
                    clientId: 0,
                    salesmanId: 0,
                    methods: [],
                    items: [],
                    total: 0,
                    net: 0,
                    tax: 0,
                    discount: 0,
                    subtotal: 0,
                    status: "credit"
                },
                searchResultList: [],
                expensesList: [],
                barcodeNameAndSerialField: "",
                bc: new BroadcastChannel('item_barcode_copy_to_invoice'),
                app: {
                    primaryColor: metaHelper.getContent('primary-color'),
                    secondColor: metaHelper.getContent('second-color'),
                    appLocate: metaHelper.getContent('app-locate'),
                    trans: trans('invoices-page'),
                    messages: trans('messages'),
                    dateTimeTrans: trans('datetime'),
                    validation: trans('validation'),
                    datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
                    BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
                    defaultVatSaleValue: 5,
                    defaultVatPurchaseValue: 5,
                },
                LiveTimer: new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate() +
                    ' ' +
                    new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds()


            };
        },
        created: function () {
            // this.InvoiceData.salesmanId = this.creator.id;
            // this.InvoiceData.clientId = this.clients[0].id;
            this.initExpensesList();
            this.initLiveTimer();

        },

        mounted: function () {
            this.itemsTabsPusherHandler();
            this.$refs.barcodeNameAndSerialField.focus();
        },


        methods: {
            initLiveTimer() {
                let appVm = this;
                setInterval(function () {

                    appVm.LiveTimer = helpers.getFullDateAndTime();
                }, 1000);
            },

            clientListChanged(event) {
                this.invoiceData.clientId = event.value.id;
            },
            salesmanListChanged(event) {
                this.invoiceData.salesmanId = event.value.id;
            },


            itemsTabsPusherHandler() {
                let appVm = this;
                this.bc.onmessage = function (ev) {
                    if (ev.isTrusted) {
                        let item = JSON.parse(ev.data);
                        appVm.validateAndPrepareItem(item);
                    }
                }
            },
            sendQueryRequestToFindItems() {
                let appVm = this;
                ItemQuery.sendQueryRequestToFindItems(this.barcodeNameAndSerialField, 'sale').then(response => {
                    if (response.data.length === 1) {
                        appVm.validateAndPrepareItem(response.data[0]);
                        appVm.barcodeNameAndSerialField = "";
                        appVm.searchResultList = [];
                    } else if (response.data.length === 0) {
                        appVm.$refs.barcodeNameAndSerialField.select();
                        appVm.searchResultList = [];
                    } else {
                        appVm.searchResultList = response.data;
                    }

                }).catch(error => {
                    console.log(error);
                })
            },
            validateAndPrepareItem(item) {
                if (db.model.contain(this.invoiceData.items, item.id)) {
                    let parent = db.model.find(this.invoiceData.items, item.id);
                    if (!parent.is_need_serial) {
                        parent.qty = parseInt(parent.qty) + 1;
                        this.itemQtyUpdated(parent);
                    } else if (item.has_init_serial) {
                        this.itemWithSerialProccess(item, parent);
                    }

                } else if (item.has_init_serial) {
                    this.itemWithSerialProccess(item);
                } else {
                    let preparedItem = this.prepareDataInFirstUse(item);
                    this.appendItemToInvoiceItemsList(preparedItem);
                }

                this.clearAndFocusOnBarcodeField();
            },

            itemWithSerialProccess(item, parent = null) {
                let serial = item.init_serial.serial;
                if (parent == null) {

                    item.serials = [serial];
                    item.qty = 1;
                    item.discount = 0;
                    this.invoiceData.items.push(item);
                    var newItem = db.model.find(this.invoiceData.items, item.id);
                    this.itemUpdater(newItem);
                } else {
                    if (!db.model.contain(parent.serials, serial)) {
                        parent.serials = db.model.createUnique(parent.serials, serial);
                        parent.qty = parseInt(parent.qty) + 1;
                        let element = {
                            index: db.model.index(this.invoiceData.items, parent.id),
                            serials: parent.serials
                        };
                        this.handleItemSerialsUpdated(element);
                    }
                }


                this.$refs.barcodeNameAndSerialField.focus();
                this.$refs.barcodeNameAndSerialField.select();


            },
            prepareDataInFirstUse(item) {
                item.isOpen = false;
                item.qty = 1;
                if (item.is_need_serial) {
                    item.qty = 0;
                    item.serials = [];
                }

                item.discount = 0;
                let newItem = this.itemUpdater(item);
                return newItem;

            },
            appendItemToInvoiceItemsList(item, index = null) {
                if (index != null) {
                    this.invoiceData.items.splice(index, 1, item);
                } else {
                    this.invoiceData.items.push(item);
                }

                this.updateInvoiceData();
            },
            updateInvoiceData() {
                this.invoiceData.total = db.model.sum(this.invoiceData.items, 'total');
                this.invoiceData.discount = db.model.sum(this.invoiceData.items, 'discount');
                this.invoiceData.subtotal = db.model.sum(this.invoiceData.items, 'subtotal');
                this.invoiceData.tax = db.model.sum(this.invoiceData.items, 'tax');
                this.invoiceData.net = db.model.sum(this.invoiceData.items, 'net');
                this.validateInvoiceData();
            },

            validateInvoiceData() {
                let everythingFineToSave = true;


                let validating = db.model.validateAmounts(this.invoiceData.items, [
                    'purchase',
                    'tax',
                    'total',
                    'discount',
                    'net',
                ]);

                validating = validating && ItemValidator.validateAmount(this.invoiceData.total);
                validating = validating && ItemValidator.validateAmount(this.invoiceData.subtotal);
                validating = validating && ItemValidator.validateAmount(this.invoiceData.discount);
                validating = validating && ItemValidator.validateAmount(this.invoiceData.net);

                this.everythingFineToSave = validating;
            },
            clearAndFocusOnBarcodeField() {
                this.barcodeNameAndSerialField = "";
                this.searchResultList = [];
                this.$refs.barcodeNameAndSerialField.focus();
            },
            itemQtyUpdated(item, bySerial = false) {
                if (bySerial === false) {
                    let el = this.$refs['itemQty_' + item.id + 'Ref'][0];
                    if (!inputHelper.validateQty(item.qty, el, item.available_qty, 0)) {
                        item.qty = item.available_qty
                    }
                }

                item = this.itemUpdater(item);
                this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
            },

            itemPriceUpdated(item) {
                let el = this.$refs['itemPrice_' + item.id + 'Ref'][0];
                if (!inputHelper.validatePrice(item.price, el)) {
                    return false;
                }
                item = this.itemUpdater(item);
                this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
            },


            itemDiscountUpdated(item) {
                let el = this.$refs['itemDiscount_' + item.id + 'Ref'][0];
                if (!inputHelper.validateDiscount(item.discount, el)) {
                    return false;
                }
                item = this.itemUpdater(item);
                this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
            },


            itemUpdater(item) {
                if (!item.is_kit) {
                    if (!ItemValidator.validateQty(item.qty, item.available_qty)) {
                        item.qty = ItemMath.sub(item.qty, 1);
                        return this.itemUpdater(item);
                    }
                }


                item.total = ItemAccounting.getTotal(item.price, item.qty);
                item.subtotal = ItemAccounting.getSubtotal(item.total, item.discount);
                item.tax = ItemAccounting.getTax(item.subtotal, item.vts, true);// this for vat purchase => vtp
                item.net = ItemAccounting.getNet(item.subtotal, item.tax, true);
                return item;
            },


            deleteItemFromList(item) {
                this.invoiceData.items.items = db.model.delete(this.invoiceData.items, item.id);
                this.updateInvoiceData();
            },

            openItemSerialsModal(index, item) {
                this.selectedItem = item;
                this.selectedItemIndex = index;
            },

            handleItemSerialsUpdated(e) {
                let index = e.index;
                let item = db.model.findByIndex(this.invoiceData.items, index);
                item.serials = e.serials;
                item.qty = e.serials.length;
                this.itemQtyUpdated(item, true);
            },
            handleItemSerialsClosed(e) {
                this.selectedItem = null;
                this.selectedItemIndex = null;
            },


            updateListItemsWidgets() {
                for (let i = 0; i < this.invoiceData.items.length; i++) {
                    let item = this.invoiceData.items[i];
                    let itemWidget = ItemMath.dev(item.total, this.invoiceData.total);
                    item.widget = itemWidget;
                    this.invoiceData.items = db.model.replace(this.invoiceData.items, i, item);
                }
            },

            initExpensesList() {
                for (let i = 0; i < this.expenses.length; i++) {
                    let expense = this.expenses[i];
                    expense.is_open = false;
                    expense.is_apended_to_net = false;
                    expense.amount = 0;
                    this.expensesList.push(expense);
                }
            },


            updateGatewaysAmounts(e) {
                this.invoiceData.status = e.status;
                this.invoiceData.methods = [];
                for (let i = 0; i < e.methods.length; i++) {
                    let method = e.methods[i];

                    if (parseFloat(method.amount) > 0) {
                        this.invoiceData.methods.push(method);
                    }
                }
            },


            expensesListUpdated(e) {
                if (parseFloat(e.expense.amount) > 0) {
                    this.expensesList.splice(e.index, 1, e.expense);
                    this.updateNetAfterExpenses();
                }

            },


            expenseIncludeInNet(e) {
                this.invoiceData.net = ItemMath.sum(this.invoiceData.net, e.expense.amount);
                this.expensesList.splice(e.index, 1, e.expense);
                this.updateListItemsWidgets();
            },


            expenseDeIncludeInNet(e) {
                this.invoiceData.net = ItemMath.sub(this.invoiceData.net, e.expense.amount);
                this.expensesList.splice(e.index, 1, e.expense);
                this.updateListItemsWidgets();
            },

            updateNetAfterExpenses() {

                let total = 0;
                for (let i = 0; i < this.expensesList.length; i++) {
                    let expense = this.expensesList[i];
                    if (expense.is_apended_to_net && parseFloat(expense.amount) > 0) {
                        total = ItemMath.sum(total, expense.amount);
                    }
                    this.updateListItemsWidgets();
                }


                if (total > 0) {
                    this.invoiceData.net = ItemMath.sum(total, db.model.sum(this.invoiceData.items, 'net'));
                } else {
                    this.invoiceData.net = db.model.sum(this.invoiceData.items, 'net');
                }

            },


            pushDataToServer() {
                let data = {
                    items: this.invoiceData.items,
                    salesman_id: this.invoiceData.salesmanId,
                    client_id: this.invoiceData.clientId,
                    total: this.invoiceData.total,
                    tax: this.invoiceData.tax,
                    discount_value: this.invoiceData.discount,
                    discount_percent: this.invoiceData.discount,
                    net: this.invoiceData.net,
                    subtotal: this.invoiceData.subtotal,
                    methods: this.invoiceData.methods,
                    expenses: this.invoiceData.expenses,
                    current_status: this.invoiceData.status,
                    issued_status: this.invoiceData.status,
                    remaining: this.invoiceData.remaining,
                    department_id: this.creator.department_id,
                    invoice_type: 'sale',
                    branch_id: this.creator.branch_id,
                    creator_id: this.creator.id,
                };
                let appVm = this;
                axios.post(this.app.BaseApiUrl + 'sales', data)
                    .then(function (response) {

                        window.location.reload();
                    })
                    .catch(function (error) {
                        alert(error.response)
                    });

            },

        },

    }
</script>


<style scoped>
    input {
        text-align: center
    }

    .product_search {
        position: relative;;
    }

    .live-vue-search {
        position: absolute;
        width: 100%;
        /* bottom: -46px; */
        z-index: 10000;
    }

    .live-vue-search a {
        text-decoration: none !important;
    }

    .live-vue-search div {
        background-color: black;
        border-radius: 0px;
        border-bottom: 1px solid #eeeeee;
        color: #eee;
        cursor: pointer;
    }

    live-vue-search div:hover {

        border-radius: 0px;

        border-bottom: 1px solid #eeeeee;
        cursor: pointer;
    }


</style>
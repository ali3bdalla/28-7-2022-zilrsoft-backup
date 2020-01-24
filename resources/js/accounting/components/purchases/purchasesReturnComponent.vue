<template>
    <!-- startup box -->
    <div class="message">

        <div class="row">
            <div class="col-md-6">
                <button :disabled="!everythingFineToSave" @click="pushDataToServer"
                        class="btn btn-custom-primary"><i
                        class="fa fa-save"></i> {{ app.trans.save }}
                </button>

                <button :disabled="!everythingFineToSave" @click="pushDataToServer('open')"
                        class="btn btn-custom-primary"><i
                        class="fa fa-save"></i> {{ app.trans.save_and_open }}
                </button>
            </div>
            <div class="col-md-6">
                <a :href="app.BaseApiUrl + 'purchases'" class="btn btn-default "><i
                        class="fa fa-redo"></i> {{ app.trans.cancel }}</a>
            </div>

        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">{{ app.trans.vendor }}</span>
                    <input aria-describedby="time-field" class="form-control" disabled
                           type="text" v-model="purchase.vendor.locale_name">

                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">{{ app.trans.date }}</span>
                    <input aria-describedby="time-field" class="form-control" disabled
                           type="text" v-model="invoice.created_at">

                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">{{ app.trans.receiver }}</span>
                    <input aria-describedby="time-field" class="form-control" disabled
                           type="text" v-model="purchase.receiver.locale_name">

                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">{{ app.trans.department }}</span>
                    <input aria-describedby="time-field" class="form-control" disabled
                           type="text" v-model="invoice.department.locale_title">

                </div>
            </div>
        </div>

        <div class="panel panel-primary">
            <table class="table table-bordered text-center  table-striped">
                <thead class="panel-heading">
                <tr>
                    <th></th>
                    <th>{{ app.trans.barcode }}</th>
                    <th>{{ app.trans.item_name }}</th>
                    <th>{{ app.trans.item_available_qty }}</th>
                    <th>{{ app.trans.qty }}</th>
                    <th>{{app.trans.purchase_price}}</th>
                    <th>{{ app.trans.total }}</th>
                    <th>{{ app.trans.discount }}</th>
                    <th>{{ app.trans.subtotal }}</th>
                    <th>{{ app.trans.tax }}</th>
                    <th>{{ app.trans.net }}</th>

                </tr>


                </thead>

                <tbody>

                <tr :key="item.id" v-for="(item,index) in invoiceData.items" v-if="item.belong_to_kit!=1">

                    <td>
                        <button @click="openItemSerialsModal(index,item)"
                                class="btn btn-success btn-xs"
                                v-if="item.is_need_serial"><i class="fa fa-bars"></i> &nbsp;
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
                                v-model="item.returned_qty"
                        >
                        <p v-else>{{item.returned_qty}}</p>
                    </td>


                    <td>
                        <input
                                :ref="'itemPrice_' + item.id + 'Ref'"
                                @focus="$event.target.select()"
                                class="form-control input-xs amount-input"

                                disabled
                                readonly
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
                                           v-model="invoiceData.subtotal">
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

                </div>
                <div class="col-md-9">
                    <accounting-invoice-embedded-payments-gateway-layout
                            :gateways="gateways"
                            v-if="invoice.invoice_type!=='beginning_inventory'"
                            :net-amount="invoiceData.net"
                            @updateGatewaysAmounts="updateGatewaysAmounts"
                            invoice-type="return_sale"
                    >
                    </accounting-invoice-embedded-payments-gateway-layout>
                </div>

            </div>
        </div>


        <accounting-return-item-serials-list-layout-component
                v-if="invoice.invoice_type!=='beginning_inventory'"
                :item="selectedItem"
                :item-index="selectedItemIndex"
                @panelClosed="handleItemSerialsClosed"
                @publishUpdated="handleItemSerialsUpdated"
                invoice-type="r_purchase"
        >

        </accounting-return-item-serials-list-layout-component>

    </div>


</template>

<script>

    import {
        accounting as ItemAccounting,
        math as ItemMath,
        query as ItemQuery,
        validator as ItemValidator
    } from '../../item';
    import {sendGetKitAmountsRequest} from '../../api/kits';

    export default {
        props: ['creator', 'items', 'invoice', 'purchase', 'gateways', 'expenses'],
        data: function () {
            return {
                everythingFineToSave: false,
                selectedItem: null,
                selectedItemIndex: null,
                invoiceData: {
                    remaining: 0,
                    vendorIncCumber: "",
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
                },
                LiveTimer: new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate() +
                    ' ' +
                    new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds()


            };
        },
        created: function () {
            //


        },

        mounted: function () {
            this.initItems();
        },


        methods: {

            initItems() {
                let len = this.items.length;
                for (let i = 0; i < len; i++) {
                    let item = this.items[i];

                    item.is_expense = item.item.is_expense;
                    item.is_need_serial = item.item.is_need_serial;
                    item.locale_name = item.item.locale_name;
                    item.barcode = item.item.barcode;
                    item.vts = item.item.vts;
                    item.available_qty = item.qty - item.r_qty;
                    item.init_discount = item.discount;
                    item.returned_qty = 0;
                    item.error = '';
                    item.total = 0;
                    item.subtotal = 0;
                    item.net = 0;
                    item.tax = 0;
                    if(this.invoice.invoice_type==='beginning_inventory')
                    {
                        item.vtp = 0;
                        item.vts = 0;
                    }
                    this.invoiceData.items.push(item);
                }
            },


            sendQueryRequestToFindItems() {
                let appVm = this;
                ItemQuery.sendQueryRequestToFindItems(this.barcodeNameAndSerialField, 'purchase').then(response => {
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
                    let newItem = db.model.find(this.invoiceData.items, item.id);
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

                this.updateInvoiceData();

            },
            prepareDataInFirstUse(item) {
                item.isOpen = false;
                item.qty = 1;
                if (item.is_need_serial) {
                    item.qty = 0;
                    item.serials = [];
                }


                if (item.is_kit) {

                    item = ItemAccounting.getKitInformation(item);
                } else {
                    item.discount = 0;
                }

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

            kitItemsDataUpdated(e) {
                let kit = e.kit;
                this.invoiceData.items.splice(e.index, 1, kit);
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
            kitQtyUpdated(kit) {

                let appVm = this;
                sendGetKitAmountsRequest(kit.item.id, kit.returned_qty).then(response => {
                    kit.total = response.data.total;
                    kit.discount = response.data.discount;
                    kit.subtotal = response.data.subtotal;
                    kit.net = response.data.net;
                    appVm.invoiceData.items.splice(db.model.index(appVm.invoiceData.items, kit.id), 1, kit);
                    appVm.updateInvoiceData();
                }).catch(error => {
                    console.log(error.response);
                })

            },


            itemQtyUpdated(item, bySerial = false) {
                if (item.item.is_kit) {
                    return this.kitQtyUpdated(item);
                }
                if (bySerial === false) {
                    let el = this.$refs['itemQty_' + item.id + 'Ref'][0];
                    if (!inputHelper.validateQty(item.returned_qty, el, item.qty, 0)) {
                        item.returned_qty = 0;
                        return this.itemUpdater(item);
                    }

                    if (!inputHelper.validateQty(item.returned_qty, el, item.available_qty, 0)) {
                        item.returned_qty = 0;
                        return this.itemUpdater(item);
                    }
                }

                item = this.itemUpdater(item);
                this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
            },

            itemNetUpdated(item) {
                let tax = ItemAccounting.convertVatPercentValueIntoFloatValue(item.vts); //  1.05
                item.subtotal = parseFloat(ItemMath.dev(item.net, tax)).toFixed(2);
                item.tax = parseFloat(ItemMath.dev(ItemMath.mult(item.subtotal, item.vts), 100)).toFixed(3);
                item.discount = parseFloat(ItemMath.sub(item.total, item.subtotal)).toFixed(2);
                // item.tax = ItemMath.sub(ItemMath.mult(item.subtotal, tax / 100), item.subtotal);
                // this.items.splice(db.model.index(this.invoiceData.items), 1, item);
                this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));

            },

            itemUpdater(item) {
                if (!item.is_kit) {
                    if (!ItemValidator.validateQty(item.returned_qty, item.available_qty)) {
                        item.returned_qty = ItemMath.sub(item.returned_qty, 1);
                        return this.itemUpdater(item);
                    }
                }


                item.total = ItemAccounting.getTotal(item.price, item.returned_qty);
                item.subtotal = ItemAccounting.getSubtotal(item.total, item.discount);
                item.tax = ItemAccounting.getTax(item.subtotal, item.vts, true);// this for vat purchase => vtp
                item.net = ItemAccounting.getNet(item.subtotal, item.tax, true);
                return item;
            },


            openItemSerialsModal(index, item) {
                this.selectedItem = item;
                this.selectedItemIndex = index;
            },

            handleItemSerialsUpdated(e) {
                let index = e.index;
                let item = db.model.findByIndex(this.invoiceData.items, index);
                item.serials = e.serials;
                item.returned_qty = e.serials.length;
                this.itemQtyUpdated(item, true);
            },
            handleItemSerialsClosed(e) {
                this.selectedItem = null;
                this.selectedItemIndex = null;
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


            pushDataToServer(doWork = null) {

                let data = {
                    items: this.invoiceData.items,
                    methods: this.invoiceData.methods,
                    expenses: this.invoiceData.expenses,
                };
                let appVm = this;

                let invoice = this.invoice;
                //


                // console.log(this.invoiceData.items);

                let request_url = this.app.BaseApiUrl + 'purchases/' + invoice.id;
                if(this.invoice.invoice_type==='beginning_inventory')
                {
                    request_url = this.app.BaseApiUrl + 'inventories/beginning/' + invoice.id;
                }
                axios.put(request_url, data)
                    .then(function (response) {
                        console.log(response.data);

                        if (doWork == 'open') {
                            window.location.href = appVm.app.BaseApiUrl + 'purchases/' + response.data.id;
                        } else {
                            window.location.reload();
                        }

                    })
                    .catch(function (error) {
                        console.log(error.response.data)
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
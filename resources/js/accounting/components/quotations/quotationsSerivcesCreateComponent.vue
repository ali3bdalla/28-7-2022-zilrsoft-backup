<template>
    <!-- startup box -->
    <div class="message">

        <div class="row">
            <div class="col-sm-6">


                <button :disabled="!everythingFineToSave" @click="pushDataToServer('open')"
                        class="btn btn-custom-primary"><i
                        class="fa fa-save"></i> {{ app.trans.save_and_open }}
                </button>
            </div>
            <div class="col-sm-6">
                <a :href="app.BaseApiUrl + 'purchases'" class="btn btn-default "><i
                        class="fa fa-redo"></i> {{ app.trans.cancel }}</a>
            </div>

        </div>


        <div class="row">

            <div class="col-sm-6 ">
                <div class="panel panel-primary">
                    <table class="table table-bordered text-center  table-striped">
                        <thead class="panel-heading">
                        <tr>
                            <th></th>
                            <th>{{ app.trans.item_name }}</th>
                            <th>{{app.trans.sales_price}}</th>
                            <th>{{ app.trans.tax }}</th>
                            <th>{{ app.trans.net }}</th>

                        </tr>


                        </thead>

                        <tbody>


                        <tr :key="item.id" v-for="(item,index) in invoiceData.items" v-if="item.is_expense!=1">
                            <td>
                                <button @click="deleteItemFromList(item)" class="btn btn-danger btn-xs"><i
                                        class="fa fa-trash"></i></button>
                            </td>

                            <td v-text="item.locale_name"></td>


                            <td>
                                <input
                                        :disabled="item.is_fixed_price || item.is_service"
                                        :ref="'itemPrice_' + item.id + 'Ref'"
                                        @change="itemPriceUpdated(item)"
                                        @focus="$event.target.select()"
                                        @keyup.enter="clearAndFocusOnBarcodeField"
                                        class="form-control input-xs amount-input"
                                        type="text"
                                        v-model="item.price">

                            </td>


                            <td>
                                <input @focus="$event.target.select()" class="form-control input-xs amount-input"
                                       disabled=""
                                       placeholder="tax"
                                       type="text" v-model="item.tax">
                            </td>
                            <td>
                                <input :disabled="item.is_fixed_price"
                                       @change="itemNetUpdated(item)"
                                       @focus="$event.target.select()"
                                       class="form-control input-xs amount-input"
                                       placeholder="net" type="text" v-model="item.net">
                            </td>


                        </tr>

                        </tbody>
                    </table>

                    <div class="panel-footer">
                        <div class="form-group">

                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    {{ app.trans.invoice_data }}
                                </div>
                                <div class="panel-body text-center">
                                    <!--                            <div class="row">-->
                                    <!--                                <div class="col-sm-6"><label>{{ app.trans.total }}</label></div>-->
                                    <!--                                <div class="col-sm-6">-->
                                    <!--                                    <input :placeholder="app.trans.total"-->
                                    <!--                                           class="form-control  input-xs amount-input"-->
                                    <!--                                           disabled type="text"-->
                                    <!--                                           v-model="invoiceData.total">-->
                                    <!--                                </div>-->
                                    <!--                            </div>-->
                                    <!--                            <div class="row">-->
                                    <!--                                <div class="col-sm-6"><label>{{ app.trans.discount }}</label></div>-->
                                    <!--                                <div class="col-sm-6">-->
                                    <!--                                    <input :placeholder="app.trans.discount"-->
                                    <!--                                           class="form-control  input-xs amount-input"-->
                                    <!--                                           disabled type="text"-->
                                    <!--                                           v-model="invoiceData.discount">-->
                                    <!--                                </div>-->
                                    <!--                            </div>-->

                                    <div class="row">
                                        <div class="col-sm-6"><label>{{ app.trans.subtotal }}</label></div>
                                        <div class="col-sm-6">
                                            <input :placeholder="app.trans.subtotal"
                                                   class="form-control  input-xs amount-input"
                                                   disabled type="text"
                                                   v-model="invoiceData.subtotal">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6"><label>{{ app.trans.tax }}</label></div>
                                        <div class="col-sm-6">
                                            <input :placeholder="app.trans.tax"
                                                   class="form-control  input-xs amount-input"
                                                   disabled type="text"
                                                   v-model="invoiceData.tax">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6"><label>{{ app.trans.net }}</label></div>
                                        <div class="col-sm-6">
                                            <input :placeholder="app.trans.net"
                                                   class="form-control  input-xs amount-input"
                                                   disabled type="text"
                                                   v-model="invoiceData.net">
                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="">
                    <div class="row">
                        <div class="col-sm-4" v-for="ser in services">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    {{ ser.locale_name}}
                                    <span class="pull-left">{{ parseFloat(ser.price_with_tax).toFixed(2)}}</span>
                                </div>
                                <div class="panel-footer">
                                    <button @click="validateAndPrepareItem(ser)" class="btn btn-primary">
                                        اضافة للفاتورة
                                    </button>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


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
        components: {},
        props: ['creator', 'clients', 'salesmen', 'gateways', 'services', 'canViewItems', 'canCreateItem'],
        data: function () {
            return {
                clientModal: {
                    clientName: "",
                    clientArName: "",
                    isIndividual: true,
                    isMsr: true,
                    canMakeCredit: true,
                },
                selectedExpense: null,
                modalsInfo: {
                    showCreateClientModal: false,
                    showNoteModal: false,
                    showAliceNameModal: false,
                },
                clientList: [],
                createdInvoiceId: 0,
                everythingFineToSave: false,
                selectedItem: null,
                selectedItemIndex: null,
                invoiceData: {
                    aliceName: "",
                    remaining: 0,
                    notes: "",
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

            this.invoiceData.clientId = this.clients[0].id;
            this.invoiceData.salesmanId = this.creator.id;

        },


        methods: {

            updatedItemsList(event) {
                this.invoiceData.items = event.items;
            },

            clientListChanged(event) {
                this.invoiceData.clientId = event.value.id;
            },
            salesmanListChanged(event) {
                this.invoiceData.salesmanId = event.value.id;
            },

            validateAndPrepareItem(item) {
                this.searchResultList = [];
                if (!db.model.contain(this.invoiceData.items, item.id)) {
                    let preparedItem = this.prepareDataInFirstUse(item);
                    this.appendItemToInvoiceItemsList(preparedItem);
                }

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
                // this.$refs.barcodeNameAndSerialField.focus();
            },
            itemQtyUpdated(item, bySerial = false) {

                if (item.is_kit) {
                    return this.kitQtyUpdated(item);
                }
                if (bySerial === false && !item.is_service) {
                    let el = this.$refs['itemQty_' + item.id + 'Ref'][0];
                    if (!inputHelper.validateQty(item.qty, el, item.qty, 0)) {
                        item.qty = 0
                    }
                }

                item = this.itemUpdater(item);
                this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
            },

            kitQtyUpdated(kit) {

                let appVm = this;
                sendGetKitAmountsRequest(kit.id, kit.qty).then(response => {
                    kit.total = response.data.total;
                    kit.discount = response.data.discount;
                    kit.subtotal = response.data.subtotal;
                    kit.net = response.data.net;
                    appVm.invoiceData.items.splice(db.model.index(appVm.invoiceData.items, kit.id), 1, kit);
                    appVm.updateInvoiceData();
                }).catch(error => {
                    alert(error);
                })

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


            itemNetUpdated(item) {
                if (item.is_service || item.is_expense) {
                    // let tax = ItemAccounting.convertVatPercentValueIntoFloatValue(item.vts); //  1.05
                    item.price = ItemAccounting.getSalesPriceFromSalesPriceWithTaxAndVat(item.net, item.vts);
                    // item.subtotal = parseFloat(ItemMath.dev(item.net, tax)).toFixed(2);
                    // item.price = item.subtotal;
                    item.total = item.price;
                    item.subtotal = item.price;
                    item.tax = ItemAccounting.getTax(item.subtotal, item.vts, true);
                    item.discount = 0;
                } else {
                    let tax = ItemAccounting.convertVatPercentValueIntoFloatValue(item.vts); //  1.05
                    item.subtotal = parseFloat(ItemMath.dev(item.net, tax)).toFixed(2);
                    item.tax = parseFloat(ItemMath.dev(ItemMath.mult(item.subtotal, item.vts), 100)).toFixed(3);
                    item.discount = parseFloat(ItemMath.sub(item.total, item.subtotal)).toFixed(2);
                }

                // item.tax = ItemMath.sub(ItemMath.mult(item.subtotal, tax / 100), item.subtotal);
                // this.items.splice(db.model.index(this.invoiceData.items), 1, item);
                this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));

            },

            itemUpdater(item) {
                // if (!item.is_kit && !item.is_service) {
                //     if (!ItemValidator.validateQty(item.qty, item.qty)) {
                //         item.qty = ItemMath.sub(item.qty, 1);
                //         return this.itemUpdater(item);
                //     }
                // }


                item.total = ItemAccounting.getTotal(item.price, item.qty);
                item.subtotal = ItemAccounting.getSubtotal(item.total, item.discount);
                item.tax = ItemAccounting.getTax(item.subtotal, item.vts, true);// this for vat purchase => vtp
                item.net = ItemAccounting.getNet(item.subtotal, item.tax, true);
                return item;
            },


            deleteItemFromList(item) {
                this.invoiceData.items = db.model.delete(this.invoiceData.items, item.id);
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


            pushDataToServer(doWork = null) {
                this.everythingFineToSave = false;


                let data = {
                    items: this.invoiceData.items,
                    salesman_id: this.invoiceData.salesmanId,
                    alice_name: this.invoiceData.aliceName,
                    client_id: this.invoiceData.clientId,
                    notes: this.invoiceData.notes,
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
                    invoice_type: 'quotation',
                    branch_id: this.creator.branch_id,
                    creator_id: this.creator.id,
                };
                let appVm = this;

                axios.post(this.app.BaseApiUrl + 'quotations', data)
                    .then(function (response) {
                        window.location.href = appVm.app.BaseApiUrl + 'sales/' + response.data.id;
                    })
                    .catch(function (error) {
                        alert(error.response.data.message);
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

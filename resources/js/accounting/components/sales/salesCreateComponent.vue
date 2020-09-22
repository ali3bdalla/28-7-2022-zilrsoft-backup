<template>
    <!-- startup box -->
    <div class="message">

        <div class="row">
            <div class="col-md-6">
                <button :disabled="!everythingFineToSave" @click="pushDataToServer"
                        class="btn btn-custom-primary"><i
                        class="fa fa-save"></i> {{ app.trans.save }}
                </button>
                <button :disabled="!everythingFineToSave" @click="pushDataToServer('print')"
                        class="btn btn-custom-primary"
                        ref="saveAndPrintReceiptBarcode"><i
                        class="fa fa-print"></i> {{ app.trans.save_and_print_receipt }}
                </button>

                <accounting-print-receipt-layout-component
                        :direct="true"
                        :invoice-id="createdInvoiceId"></accounting-print-receipt-layout-component>

                <button :disabled="!everythingFineToSave" @click="pushDataToServer('open')"
                        class="btn btn-custom-primary"><i
                        class="fa fa-save"></i> {{ app.trans.save_and_open }}
                </button>
            </div>
            <div class="col-md-6">
                <a class="btn btn-default " href="/sales"><i
                        class="fa fa-redo"></i> {{ app.trans.cancel }}</a>
            </div>

        </div>


        <div class="row">
            <div class="col-md-4">
                <accounting-select-with-search-layout-component
                        :default-index="clientList[0].id"
                        :no_all_option="true"
                        :options="clientList"
                        :placeholder="app.trans.client"
                        :title="app.trans.client"
                        @valueUpdated="clientListChanged"
                        identity="001"
                        index="001"
                        label_text="locale_name"
                >

                </accounting-select-with-search-layout-component>

            </div>
            <div class="col-md-4">
                <a @click="modalsInfo.showAliceNameModal=true" class="btn btn-custom-primary btn-sm">{{app.trans
                    .make_alice_name}}</a>

                <a @click="modalsInfo.showCreateClientModal=true" class="btn btn-custom-primary btn-sm">{{app.trans
                    .create_identity}}</a>

            </div>
            <div class="col-md-4">
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
                        label_text="locale_name"
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
            <div class="col-md-6">
                <div class="product_search" id="seach_area">
                    <div class="">
                        <input
                                :placeholder="app.trans.search_barcode"
                                @keyup.enter="sendQueryRequestToFindItems"
                                class="form-control"
                                ref="barcodeNameAndSerialField" tabindex="1"
                                type="text"
                                v-model="barcodeNameAndSerialField"/>
                    </div>
                    <div class="live-vue-search panel" v-show="searchResultList.length >=1">
                        <div :key="item.id" @click="validateAndPrepareItem(item)"
                             class="panel-footer" href="#" v-for="item in searchResultList">
                            <h4 class="title has-text-white">{{ item.locale_name }}
                                <small class="has-text-white">{{ item.barcode}} - {{ item.price_with_tax }}</small>
                            </h4>

                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-2  text-center" v-if="canViewItems==1">
                <a class="btn btn-custom-primary" href="/items?selectable=true"
                   target="_blank">{{ app.trans.view_products}}</a>

            </div>
            <div class="col-md-2  text-center" v-if="canCreateItem==1">
                <a class="btn btn-custom-primary" href="/items/create"
                   target="_blank">{{app.trans.create_product}}</a>
            </div>

            <div class="col-md-2 text-center">
                <a @click="modalsInfo.showNoteModal=true" class="btn btn-custom-primary">{{app.trans.make_note}}</a>
            </div>


        </div>


        <div class="panel panel-primary">
            <table class="table table-bordered text-center  table-striped">
                <thead class="panel-heading">
                <tr>
                    <th></th>
                    <th></th>
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


                <tr :key="item.id" v-for="(item,index) in invoiceData.items" v-if="item.is_expense!=1">
                    <td>
                        <button @click="deleteItemFromList(item)" class="btn btn-danger btn-xs"><i
                                class="fa fa-trash"></i></button>
                    </td>
                    <td>
                        <button

                                @click="openItemSerialsModal(index,item)"
                                class="btn btn-success btn-xs"
                                v-if="item.is_need_serial"><i class="fa fa-bars"></i> &nbsp;
                        </button>
                        <accounting-kit-items-layout-component
                                :index="index"
                                :kit="item"
                                :qty="item.qty"
                                @kitUpdated="kitItemsDataUpdated"
                                v-if="item.is_kit===true">

                        </accounting-kit-items-layout-component>


                    </td>
                    <td>
                        <button

                                @click="updateItemPrintable(false,item)"
                                class="btn btn-success btn-xs"
                                v-show="item.printable"><i class="fa fa-eye"></i> &nbsp;
                        </button>
                        <button

                                @click="updateItemPrintable(true,item)"
                                class="btn btn-danger btn-xs"
                                v-show="!item.printable"><i class="fa fa-eye-slash"></i> &nbsp;
                        </button>
                    </td>
                    <td>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <span v-on="on">{{ item.barcode}}</span>

                            </template>
                            <span>{{ parseFloat(item.cost * (1 + (item.vtp/100))).toFixed(2) }}</span>
                        </v-tooltip>
                    </td>
                    <td>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <span v-on="on">{{ item.locale_name}}</span>

                            </template>
                            <span>{{ item.warranty_title }}</span>
                        </v-tooltip>
                    </td>
                    <td v-text="item.available_qty"></td>
                    <td>

                        <input

                                :disabled="item.is_service"
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
                        <input @focus="$event.target.select()" class="form-control input-xs amount-input" disabled
                               type="text"
                               v-model="item.total">
                    </td>
                    <td>
                        <input :disabled="item.is_kit || item.is_service"
                               :ref="'itemDiscount_' + item.id + 'Ref'"
                               @change="itemDiscountUpdated(item)"
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
                        <input :disabled="item.is_fixed_price"
                               @change="itemNetUpdated(item)"
                               @focus="$event.target.select()"
                               class="form-control input-xs amount-input"
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
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-8">

                                    <select class="form-control" v-model="selectedExpense">
                                        <option :value="expense" v-for="expense in expenses">{{ expense.locale_name
                                            }}
                                        </option>
                                    </select>

                                </div>
                                <div class="col-md-4">
                                    <button @click="addExpenseToInvoice"
                                            class="btn btn-custom-primary"><i class="fa fa-plus-circle"></i></button>
                                </div>
                            </div>

                            <div class="">
                                <div class="panel panel-primary" v-for="expense in invoiceData.items"
                                     v-show="expense.is_expense">
                                    <div class="panel-heading">{{ expense.locale_name}}</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon">السعر الضريبة</span>
                                                    <input :ref="'itemNet_' + expense.id+ 'Ref'"
                                                           @focus="$event.target.select()"
                                                           @keyup="itemNetUpdated(expense)" class="form-control"
                                                           name=""
                                                           placeholder="القيمة بالضريبة"
                                                           style=" direction: ltr!important;"
                                                           type="text" v-model="expense.net"
                                                    />

                                                </div>

                                            </div>
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon">سعر الشراء</span>
                                                    <input class="form-control" placeholder="سعر الشراء" type="text"
                                                           v-model="expense.purchase_price"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
                <div class="col-md-9">
                    <accounting-invoice-embedded-payments-gateway-layout
                            :gateways="gateways"
                            :net-amount="invoiceData.net"
                            @updateGatewaysAmounts="updateGatewaysAmounts"
                            invoice-type="sale"
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


        <!--invoice Note Modal-->
        <div v-if="modalsInfo.showNoteModal===true">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header ">
                                    <button @click="modalsInfo.showNoteModal = false"
                                            class="pull-left btn btn-custom-primary"
                                            type="button">
                                        اغلاق
                                    </button>
                                    <h4 class="modal-title">{{app.trans.make_note}}</h4>
                                </div>
                                <div class="modal-body">
                                    <textarea :placeholder="app.trans.type_here" class="form-control"
                                              v-model="invoiceData.notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
        <!--invoice Note Modal-->

        <!--        invoice other client name  Modal-->
        <div v-if="modalsInfo.showAliceNameModal===true">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header ">
                                    <button @click="modalsInfo.showAliceNameModal = false"
                                            class="pull-left btn btn-custom-primary"
                                            type="button">
                                        اغلاق
                                    </button>
                                    <h4 class="modal-title">{{app.trans.make_alice_name}}</h4>
                                </div>
                                <div class="modal-body">
                                    <input :placeholder="app.trans.type_here"
                                           class="form-control"
                                           type="text"
                                           v-model="invoiceData.aliceName"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
        <!--        invoice other client name  Modal-->


        <!--invoice Note Modal-->
        <div v-show="modalsInfo.showCreateClientModal===true">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header ">
                                    <button @click="modalsInfo.showCreateClientModal = false"
                                            class="pull-left btn btn-custom-primary"
                                            type="button">
                                        اغلاق
                                    </button>
                                    <h4 class="modal-title">انشاء هوية</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <toggle-button :font-size="19" :height='30' :labels="{checked:'فرد',
                                   unchecked: 'منشأة'}" :sync="true" :width='150'
                                                           v-model="clientModal.isIndividual"/>
                                        </div>
                                        <div class="col-md-4">
                                            <toggle-button :font-size="19" :height='30'
                                                           :labels="{checked:'السيد',
                                   unchecked: 'السيد'}"
                                                           :sync="true" :width='150' v-model="clientModal.isMsr"
                                                           v-show="clientModal.isIndividual"/>
                                        </div>
                                        <div class="col-md-4">
                                            <toggle-button :font-size="19" :height='30'
                                                           :labels="{checked:'فواتير اجله',
                                   unchecked: 'سداد فقط'}"
                                                           :sync="true" :width='150'
                                                           v-model="clientModal.canMakeCredit"
                                            />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input class="form-control" placeholder="اسم العميل"
                                                   v-model="clientModal.clientArName"/>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" placeholder="اسم العميل (انجليزي)"
                                                   v-model="clientModal.clientName"/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div @click="pushClientData" class="btn btn-custom-primary">
                                                حفظ
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
        <!--invoice create Modal-->


        <textarea class="form-control" v-if="activateTestMode" v-model="testRequestData"></textarea>
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
        props: ['creator', 'clients', 'cloning', 'quotation', 'salesmen', 'gateways', 'expenses', 'canViewItems',
            'canCreateItem'],
        data: function () {
            return {
                activateTestMode: false,
                testRequestData: "",
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
                    quotationId: 0,
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
                    trans: trans('invoices-page'),
                    messages: trans('messages'),
                    defaultVatSaleValue: 15,
                    defaultVatPurchaseValue: 15,
                },
                LiveTimer: new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate() +
                    ' ' +
                    new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds()


            };
        },
        created: function () {


            this.clientList = this.clients;
            this.initExpensesList();
            this.initLiveTimer();
            if (this.cloning === true) {
                this.handleCloningEvent();
            }

        },

        mounted: function () {
            this.clientList = this.clients;
            this.itemsTabsPusherHandler();
            this.$refs.barcodeNameAndSerialField.focus();
        },


        methods: {

            toCheckMakePrintableOption(item) {
                let canMakeItPrintable = false;
                let len = this.invoiceData.items.length;

                let itemIndex = db.model.index(this.invoiceData.items, item.id);
                if (len >= 2) {
                    for (let i = 0; i < len; i++) {
                        let checkerItem = db.model.findByIndex(this.invoiceData.items, i);
                        if (checkerItem.printable && itemIndex !== i) {
                            canMakeItPrintable = true;
                        }
                    }
                }

                return canMakeItPrintable;

            },
            updateItemPrintable(printable, item) {

                if (!printable) {
                    if (this.toCheckMakePrintableOption(item))
                        this.toRealChangePrintableStatus(printable, item);
                } else {
                    this.toRealChangePrintableStatus(printable, item);
                }


            },
            toRealChangePrintableStatus(printable, item) {
                item.printable = printable;
                this.invoiceData.items = db.model.replace(this.invoiceData.items,
                    db.model.index(this.invoiceData.items, item.id), item);
            },
            handleCloningEvent() {
                let items = this.quotation.items;
                this.quotationId = this.quotation.id;
                let appVm = this;
                items.forEach((item) => {
                    item.barcode = item.item.barcode;
                    item.available_qty = item.item.available_qty;
                    item.vts = item.item.vts;
                    item.is_service = item.item.is_service;
                    item.is_fixed_price = item.item.is_fixed_price;
                    item.is_expense = item.item.is_expense;
                    item.is_need_serial = item.item.is_need_serial;
                    item.is_kit = item.item.is_kit;
                    item.items = item.item.items;
                    item.data = item.item.data;
                    item.vts = item.item.vts;
                    item.id = item.item.id;
                    item.locale_name = item.item.locale_name;
                    if (item.is_need_serial) {
                        item.qty = 0;
                    }
                    appVm.invoiceData.items.push(item);
                });

                this.invoiceData.salesmanId = this.quotation.sale.salesman_id;
                this.invoiceData.clientId = this.quotation.sale.client_id;
                this.updateInvoiceData();
            },
            pushClientData() {
                this.modalsInfo.showCreateclientModal = false;
                //
                let user_type;
                if (this.clientModal.isIndividual) {
                    user_type = 'individual';
                } else {
                    user_type = 'company';
                }

                let user_title;

                if (this.clientModal.isMr) {
                    user_title = 'mr';
                } else {
                    user_title = 'mis';
                }

                if (user_type === 'company')
                    user_title = 'company';


                var data = {
                    user_gateways: [],
                    user_title: user_title,
                    is_client: true,
                    is_supplier: false,
                    is_vendor: false,
                    user_type: user_type,
                    ar_name: this.clientModal.clientArName,
                    name: this.clientModal.clientName,
                    phone_number: "00000000",
                    email: "",
                    can_make_credit: this.clientModal.canMakeCredit,
                    user_detail_vat: "",
                    user_detail_email: "",
                    user_detail_cr: "",

                    user_detail_address: "",
                    user_detail_responser: "",
                    user_detail_responser_phone: "000000"


                };
                let appVm = this;
                axios.post('/accounting/identities', data).then(response => {
                    alert('تم التسجيل بنجاح');
                    appVm.clientList.push(response.data);
                    appVm.modalsInfo.showCreateclientModal = false;
                    appVm.invoiceData.clientId = response.data.id;
                    appVm.clientModal.clientArName = "";
                    appVm.clientModal.clientName = "";
                    appVm.clientListChanged({
                        value: {
                            id: response.data.id
                        }
                    })
                }).catch(error => {
                    console.log(error.response);
                    console.log(error.response.messages);
                    console.log(error.message);
                });
            },
            addExpenseToInvoice() {
                if (this.selectedExpense != null) {
                    let new_expense = this.selectedExpense;
                    new_expense.available_qty = 1;
                    new_expense.qty = 1;
                    new_expense.price = 0;
                    new_expense.purchase_price = 0;
                    new_expense.total = 0;
                    new_expense.net = 0;
                    new_expense.tax = 0;
                    new_expense.subtotal = 0;
                    new_expense.discount = 0;
                    this.invoiceData.items.push(new_expense);
                    this.updateInvoiceData();
                }
            },

            updatedItemsList(event) {
                this.invoiceData.items = event.items;
            },
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
                // when we activate this code one press will make multi invoice
                if (this.barcodeNameAndSerialField === "") {
                    if (this.invoiceData.items.length >= 1) {
                        this.$refs.saveAndPrintReceiptBarcode.focus();
                        return;
                    }
                }
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
                item.price = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.price);
                item.printable = true;
                this.searchResultList = [];
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
                    if (preparedItem.is_kit)
                        return 0;
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
                this.validateInvoiceData(focus);
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

                if (item.is_kit) {
                    return this.kitQtyUpdated(item);
                }
                if (bySerial === false && !item.is_service) {
                    let el = this.$refs['itemQty_' + item.id + 'Ref'][0];
                    if (!inputHelper.validateQty(item.qty, el, item.available_qty, 0)) {
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
                    kit.tax = response.data.tax;
                    kit.net = response.data.net;
                    appVm.invoiceData.items.splice(db.model.index(appVm.invoiceData.items, kit.id), 1, kit);
                    appVm.updateInvoiceData();
                }).catch(error => {
                    alert(error);
                })

            },

            itemPriceUpdated(item) {
                item.price = parseFloat(item.price).toFixed(2);
                let el = this.$refs['itemPrice_' + item.id + 'Ref'][0];
                if (!inputHelper.validatePrice(item.price, el)) {
                    return false;
                }
                item = this.itemUpdater(item);
                this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
            },


            itemDiscountUpdated(item) {
                item.discount = parseFloat(item.discount).toFixed(2);
                let el = this.$refs['itemDiscount_' + item.id + 'Ref'][0];
                if (!inputHelper.validateDiscount(item.discount, el)) {
                    return false;
                }
                item = this.itemUpdater(item);
                this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
            },


            itemNetUpdated(item) {
                if (item.is_service || item.is_expense) {
                    item.price = ItemAccounting.getSalesPriceFromSalesPriceWithTaxAndVat(item.net, item.vts);
                    item.total = item.price;
                    item.subtotal = item.price;
                    item.tax = ItemAccounting.getTax(item.subtotal, item.vts, true);
                    item.discount = 0;
                } else {
                    let tax = ItemAccounting.convertVatPercentValueIntoFloatValue(item.vts); //  1.05
                    item.subtotal = parseFloat(ItemMath.dev(item.net, tax)).toFixed(2);
                    item.tax = parseFloat(ItemMath.dev(ItemMath.mult(item.subtotal, item.vts), 100)).toFixed(3);
                    item.discount = ItemMath.sub(item.total, item.subtotal);
                }


                if (parseFloat(item.discount) < 0) {
                    item = this.itemNetToUpdatePriceWithoutTax(item);
                }
                this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));

            },

            itemNetToUpdatePriceWithoutTax(item) {
                item.price = ItemAccounting.getSalesPriceFromSalesPriceWithTaxAndVat(item.net, item.vtp);
                item.total = parseFloat(item.price) * parseInt(item.qty);
                item.subtotal = item.total;
                item.tax = ItemAccounting.getTax(item.subtotal, item.vtp, true);
                item.discount = 0;

                return item;
            },

            itemUpdater(item) {
                if (!item.is_kit && !item.is_service) {
                    if (!ItemValidator.validateQty(item.qty, item.available_qty)) {
                        item.qty = ItemMath.sub(item.qty, 1);
                        return this.itemUpdater(item);
                    }
                }


                item.total = ItemAccounting.getTotal(item.price, item.qty);
                item.subtotal = ItemAccounting.getSubtotal(item.total, item.discount);
                //
                item.tax = item.is_kit ? item.data.tax : ItemAccounting.getTax(item.subtotal, item.vts, true);// this for vat purchase => vtp
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
                let client = db.model.find(this.clientList, this.invoiceData.clientId);
                if (client.can_make_credit === false) {
                    let amount = db.model.sum(this.invoiceData.methods, 'amount');
                    if (ItemMath.isBiggerThan(this.invoiceData.net, amount)) {
                        alert('هذا العميل لا يمكنه القيام بفواتير آجله الرجاء التحقق من وسائل الدفع واكمال المبلغ ');

                        return;
                    }
                }

                let data = {
                    quotation_id: this.quotationId,
                    items: this.invoiceData.items,
                    salesman_id: this.invoiceData.salesmanId,
                    alice_name: this.invoiceData.aliceName,
                    client_id: this.invoiceData.clientId,
                    notes: this.invoiceData.notes,
                    total: this.invoiceData.total,
                    tax: this.invoiceData.tax,
                    discount: this.invoiceData.discount,
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

                // $("")
                let appVm = this;

                if (this.activateTestMode) {
                    this.testRequestData = JSON.stringify(data)
                } else {
                    axios.post('/api/sales', data)
                        .then(function (response) {
                            console.log(response.data);
                            if (doWork === 'open') {
                                window.location.href = '/sales/' + response.data.id;
                            } else if (doWork === 'print') {
                                appVm.everythingFineToSave = false;
                                appVm.createdInvoiceId = response.data.id;
                                let salesman = appVm.invoiceData.salesmanId,
                                    clientId = appVm.invoiceData.clientId;

                                appVm.invoiceData = {
                                    remaining: 0,
                                    vendorIncCumber: "",
                                    clientId: clientId,
                                    salesmanId: salesman,
                                    methods: [],
                                    items: [],
                                    total: 0,
                                    net: 0,
                                    tax: 0,
                                    discount: 0,
                                    subtotal: 0,
                                    status: "credit"
                                };

                                setInterval(function () {
                                    if (appVm.cloning) {
                                        window.location.href = '/sales/' + response.data.id;
                                    } else {
                                        window.location.reload();
                                    }
                                }, 1000);

                            } else {
                                if (appVm.cloning) {
                                    window.location.href = '/sales/' + response.data.id;
                                } else {
                                    window.location.reload();
                                }
                            }

                        })
                        .catch(function (error) {
                            alert(error.response.data.message);
                            console.log(error.response.data)
                        });
                }
                // this.stringifyRequestData = JSON.stringify(data);


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

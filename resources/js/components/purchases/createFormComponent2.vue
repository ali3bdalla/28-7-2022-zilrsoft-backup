<template>
    <!-- startup box -->
    <div class="message">

        <print-invoice-component :invoice_id="invoice_id" :print="false"
                                 :print_counter="print_a4_counter" title='hidden'></print-invoice-component>

        <div class="panel-heading has-background-dark">
            <div class="columns">
                <div class="column">
                    <a class="button " href="/management/sales"><i
                            class="fa fa-redo"></i>&nbsp;{{ translator.cancel }}</a>
                </div>

                <div class="column pull-right text-left">
                    <button :disabled="disableSaveButton" @click="saveInvoiceButtonClickedOnly"
                            class="button is-primary "><i
                            class="fa fa-save"></i>&nbsp; {{ translator.save }}
                    </button>
                </div>

            </div>


        </div>
        <div class="message-body">
            <div class="has-background-light">
                <div class="columns">
                    <div class="column">
                        <!-- <label>vendor name</label> -->
                        <div class="" v-bind:class="{'is-danger':error=='vendor_id'}">
                            <div :class="{'has-error':errors.hasOwnProperty('vendor_id') }" class="input-group">
                                <span class="input-group-addon" id="vendors-list">{{ translator.vendor }}</span>
                                <select aria-describedby="vendors-list" class="form-control has-error" v-model='user'>
                                    <option :checked="true"
                                            :key="user.id"
                                            :value="user"
                                            v-for="( user,index  ) in vendors"
                                    >{{ user.name }}
                                    </option>
                                </select>


                            </div>
                            <p class="help is-danger is-center"
                               v-show="errors.hasOwnProperty('vendor_id')">مطلوب</p>


                        </div>
                    </div>


                    <div class="column">
                        <!-- <label>date</label> -->
                        <div :class="{'has-error':errors.hasOwnProperty('vendor_inc_number') }" class="input-group">
                            <span class="input-group-addon" id="vendor-in-number">رقم فاتورة المورد </span>
                            <input aria-describedby="vendor-in-number" class="form-control" name="" type="text"
                                   v-model="vendor_inc_number">

                        </div>
                        <p class="help is-danger is-center"
                           v-show="errors.hasOwnProperty('vendor_inc_number')">مطلوب </p>

                    </div>


                    <div class="column">
                        <!-- <label>date</label> -->
                        <div class="input-group">
                            <span class="input-group-addon" id="time-field">{{ reusable_translator.date }}</span>
                            <input aria-describedby="time-field" class="form-control" name="" style="    direction: ltr
                            !important;"
                                   type="text"
                                   v-model="time">

                        </div>


                    </div>


                </div>


                <div>
                    <div class="columns">
                        <div class="column">
                            <!-- <label>vendor name</label> -->
                            <div class="">
                                <div :class="{'has-error':errors.hasOwnProperty('receiver_id') }" class="input-group">
                                    <span class="input-group-addon" id="receivers-list">{{ translator.receiver }}</span>
                                    <select aria-describedby="receivers-list" class="form-control" v-model='receiver'>
                                        <option :checked="true"
                                                :key="user.id"
                                                :value="user.id"
                                                v-for="( user,index  ) in receivers"
                                        >{{ user.name }}
                                        </option>
                                    </select>

                                </div>
                                <p class="help is-danger is-center"
                                   v-show="errors.hasOwnProperty('receiver_id')" v-text="errors.receiver_id"></p>
                            </div>
                        </div>

                        <div class="column">
                            <div class="input-group">
                                <span class="input-group-addon" id="department-field">{{ translator.department }}</span>
                                <input aria-describedby="department-field" class="form-control" disabled name=""
                                       type="text"
                                       v-model="department">

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- start search field -->
            <div class="columns">
                <div class="column is-two-thirds">
                    <div class="product_search" id="seach_area">
                        <div class="">
                            <input :placeholder="translator.search_barcode" @keyup.enter="findItems" class="input"
                                   ref="search_input_ref" tabindex="0" type="text"
                                   v-bind:class="{'is-danger':error=='items'}"
                                   v-model="search_field"/>
                            <p class="help is-danger is-center" v-show="error=='items'" v-text="errorMessage"></p>
                        </div>
                        <div class="live-vue-search">
                            <a :key="item.id" @click="addItemToList(item)"
                               class="message-header has-background-primary"
                               href="#" v-for="item in itemsSearchList">
                                <h3 class="title">{{ item.ar_name }} <small class="has-text-white">{{ item.barcode
                                    }}</small></h3>
                                <!--                                ,{{ item.price }},-->
                                , {{ reusable_translator.available }} {{ item.available_qty }}
                            </a>
                        </div>
                    </div>
                </div>


                <div class="column text-left">
                    <a class="button is-info" href="/management/items?selectable=true&&is_purchase=true" tabindex="100"
                       target="_blank">{{
                        translator.view_products }}
                    </a>

                </div>
                <div class="column text-center">
                    <a class="button is-primary" href="/management/items/create" tabindex="101" target="_blank"> {{
                        translator.create_product }} </a>
                </div>

                <div class="column text-center">
                    <upload-doc-component @uploaded="setDocFile"></upload-doc-component>
                </div>


            </div>


            <hr>
            <div class="table-container">
                <table class="table is-bordered text-center is-fullwidth is-narrow is-hoverable is-striped">
                    <thead class="has-background-dark ">
                    <tr>
                        <th class="has-text-white"></th>
                        <!-- <th class="has-text-white"></th> -->
                        <th class="has-text-white">{{ translator.barcode }}</th>
                        <th class="has-text-white" width="20%">{{ translator.item_name }}</th>
                        <th class="has-text-white" width="3%">{{ translator.qty }}</th>
                        <th class="has-text-white" width="8%">{{translator.sales_price}}</th>
                        <th class="has-text-white" width="8%">{{ translator.purchase_price }}</th>
                        <th class="has-text-white">{{ translator.total }}</th>
                        <th class="has-text-white">{{ translator.discount }}</th>
                        <th class="has-text-white">{{ translator.subtotal }}</th>
                        <th class="has-text-white">{{ translator.tax }}</th>
                        <th class="has-text-white">{{ translator.net }}</th>
                        <th class="has-text-white">- / +</th>

                    </tr>


                    </thead>

                    <tbody>


                    <tr :key="item.id" v-for="(item,itemindex) in items">
                        <th class="has-text-white">

                            <button @click="deleteItemFromList(item)" class="button is-danger is-small"><i
                                    class="fa fa-trash"></i></button>
                            <button @click="show(itemindex,item)" class="button is-primary is-small"
                                    style="margin:10px"
                                    v-if="item.is_need_serial">
                                <i class="fa fa-plus"></i> &nbsp;
                            </button>


                            <item-serials-list-component
                                    :init_item_serial_list="item.serials"
                                    :isOpen="item.isOpen"
                                    :item="item"
                                    :item_index="itemindex" :key="item.id"
                                    :updatehock="updatehock"
                                    @changed="updatedItemSerials"></item-serials-list-component>

                        </th>
                        <th class="barcode_field" v-text="item.barcode"></th>
                        <th style="text-align: right !important;" v-text="item.locale_name">item name</th>
                        <th width="6%">
                            <input :tabindex="{1:itemindex==0}" @focus="$event.target.select()"
                                   @keyup="onChangeQtyField(item)"
                                   class="input"
                                   type="text" v-if="!item.is_need_serial"
                                   v-model="item.qty">
                            <p v-else>{{item.qty}}</p>
                        </th>


                        <th class="has-text-white">
                            <input
                                    @dblclick="updateItemSalePriceVisisbility(item)"
                                    @focus="$event.target.select()"
                                    class="form-control onlyhidden"
                                    disabled
                                    type="text"
                                    v-if="!item.openSalePrice" v-model="item.price_with_tax">


                            <input
                                    @dblclick="updateItemSalePriceVisisbility(item)"

                                    @focus="$event.target.select()"
                                    class="form-control"
                                    type="text"
                                    v-else v-model="item.price_with_tax">


                        </th>

                        <th class="has-text-white">
                            <input @focus="$event.target.select()"
                                   @keyup="onChangePriceField(item)"
                                   class="input"
                                   type="text" v-model="item.purchase_price">

                        </th>


                        <th class="has-text-white">
                            <input @focus="$event.target.select()" class="input" disabled type="text"
                                   v-model="item.total">
                        </th>
                        <th class="has-text-white">
                            <input @focus="$event.target.select()"
                                   @keyup="onChangeDiscountField(item)"
                                   class="input" placeholder="discount" type="text"
                                   v-model="item.discount">
                        </th>
                        <th class="has-text-white">
                            <input @focus="$event.target.select()" class="input" disabled="" placeholder="subtotal"
                                   type="text" v-model="item.subtotal">
                        </th>

                        <th class="has-text-white">
                            <input @focus="$event.target.select()" class="input" disabled="" placeholder="tax"
                                   type="text" v-model="item.tax">
                        </th>
                        <th class="has-text-white">
                            <input @focus="$event.target.select()"
                                   @keyup="onChangeNetField(item)"
                                   class="input"
                                   disabled
                                   placeholder="net" type="text" v-model="item.net">
                        </th>

                        <th class="has-text-white">
                            <input :class="{'is-danger':item.variation>0,'is-primary':item.variation<=0}"
                                   @focus="$event.target.select()"
                                   class="input"
                                   disabled
                                   placeholder="net"
                                   readonly=""
                                   type="text"
                                   v-model="item.variation">
                        </th>

                    </tr>


                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <div class="columns">
                    <div class="column is-three-quarters">
                        <pop-billing-component
                                :button_counter="button_counter"
                                :current_items_net="current_items_net"
                                :disable_button_counter="disable_button_counter2"
                                :gateways="gateways"
                                :net-amount="net"
                                :user="user"
                                @billingsUpdate="billingsUpdate"
                                @errorInPayment="errorInPayment"
                                @saveInvoice="saveInvoiceButtonClicked"></pop-billing-component>
                    </div>
                    <div class="column">
                        <div class="card">

                            <div class="message-body text-center">
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><b>{{ translator.total }}</b></div>
                                        <div class="column">
                                            <input class="input" disabled type="text" v-model="total">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><b>{{ translator.discount }}</b></div>
                                        <div class="column">
                                            <input @focus="$event.target.select()" @keyup="onInvoiceDiscountUpdated"
                                                   class="input" type="text"
                                                   v-model="discount">
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><b>{{ translator.subtotal }}</b></div>
                                        <div class="column">
                                            <input class="input" disabled="" disabled type="text" v-model="subtotal">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><b>{{ translator.tax }}</b></div>
                                        <div class="column">
                                            <input class="input" disabled type="text" v-model="tax">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><b>{{ translator.net }}</b></div>
                                        <div class="column">
                                            <input @focus="$event.target.select()" class="input"

                                                   disabled
                                                   type="text"
                                                   v-model="net">
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <expenses-list-component
                                    :expenses="updated_expenses"
                                    @expenseDeIncludeInNet="expenseDeIncludeInNet"
                                    @expenseIncludeInNet="expenseIncludeInNet" @expensesUpdated="expensesUpdated">
                            </expenses-list-component>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
    <!-- end box -->

</template>

<script>


    export default {


        props: ['creator', 'vendors', 'receivers', 'gateways', 'expenses'],
        data: function () {
            return {
                new_net: 0,
                updated_expenses: [],
                updatehock: 0,
                user: null,
                disable_button_counter: 0,
                disable_button_counter2: true,
                current_items_net: 0,
                disableSaveButton: true,
                print_counter: 1,
                print_a4_counter: 1,
                invoice_id: 0,
                button_counter: 0,
                vendor_inc_number: "",
                bc: new BroadcastChannel('item_barcode_copy_to_invoice'),
                reusable_translator: null,
                messages: null,
                translator: null,
                errors: new Map,
                document: '',
                pdfLink: '',
                department: '',
                errorMessage: "",
                error: "",
                vendor: "",
                vendor_data: "",
                receiver: "",
                status: 'credit',
                search_field: "",
                itemsSearchList: [],
                methods: [],
                items: [],
                total: 0,
                discount: 0,
                tax: 0,
                net: 0,
                subtotal: 0,
                remaining: 0,
                time: new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate() + ' ' + new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds()
            };
        },
        created: function () {

            this.receiver = this.creator.id;
            // if(this.vendors.length>=1)
            // {
            //
            // }
            // this.vendor = this.vendors[0].id;
            // this.vendor_data = this.vendors[0];
            this.receiver = this.creator.id;
            this.initExpenses();
            //  console.log(this.vendor);
            this.translator = JSON.parse(window.translator);
            this.messages = JSON.parse(window.messages);
            this.reusable_translator = JSON.parse(window.reusable_translator);
            this.department = this.creator.department.title;
            this.timerLoop();
            // console.log(this.$refs.)
        },

        mounted: function () {


            this.watchCopiedItems();
            this.$refs.search_input_ref.focus();
        },


        methods: {


            initExpenses() {

                for (var i = 0; i < this.expenses.length; i++) {

                    var expense = this.expenses[i];

                    expense.is_open = false;
                    expense.is_apended_to_net = false;
                    expense.amount = 0;
                    this.updated_expenses.push(expense);

                }
            },


            expensesUpdated(e) {
                if (parseFloat(e.expense.amount) > 0) {
                    this.updated_expenses.splice(e.index, 1, e.expense);
                    this.updateNetAfterExpenses();
                }
                this.handleWidgets();
                // console.log(e.index);
            },


            expenseIncludeInNet(e) {
                if (parseFloat(e.expense.amount) > 0) {

                    this.net = parseFloat(this.net) + parseFloat(e.expense.amount);
                    this.updated_expenses.splice(e.index, 1, e.expense);
                }


                this.handleWidgets();
            },


            expenseDeIncludeInNet(e) {
                this.net = parseFloat(this.net) - parseFloat(e.expense.amount);
                this.updated_expenses.splice(e.index, 1, e.expense);
                this.handleWidgets();
            },

            updateNetAfterExpenses() {

                var total = 0;
                for (var i = 0; i < this.updated_expenses.length; i++) {
                    var expense = this.updated_expenses[i];

                    if (expense.is_open && helpers.isNumber(expense.amount) && expense.is_apended_to_net &&
                        parseFloat(expense.amount) != 'NaN') {

                        total = parseFloat(total) + parseFloat(expense.amount);
                    }


                    this.handleWidgets();
                    // this.onInvoiceNetUpdated();

                }


                if (parseFloat(total) > 0) {

                    this.net = parseFloat(total) +
                        parseFloat(helpers.getColumnSumationFromArrayOfObjects(this.items, 'net'));
                } else {
                    this.net = parseFloat(helpers.getColumnSumationFromArrayOfObjects(this.items, 'net'));
                }


            },


            setDocFile(e) {
                this.document = e.file;
                // console.log(e);
            },


            errorInPayment(e) {
                this.disableSaveButton = e.error;
                //   console.log(e);
            },

            watchCopiedItems() {

                var vm = this;
                this.bc.onmessage = function (ev) {
                    if (ev.isTrusted) {
                        var item = JSON.parse(ev.data);
                        vm.addItemToList(item);
                    }
                }

            },
            setDocFile(e) {
                this.document = e.file;
            },

            show(index, item) {
                //
                item.isOpen = !item.isOpen;
                //
                this.$modal.show('serialList_' + index);

                this.items.splice(this.items.indexOf(item), 1, item);
            },


            updatedItemSerials(e) {
                // console.log(e);
                var item = this.items[e.index];
                item.qty = e.serials.length;
                item.serials = e.serials;
                this.onChangeQtyField(item);
            },
            timerLoop() {
                var vm = this;
                setInterval(function () {

                    vm.time = helpers.getFullDateAndTime();

                }, 1000);
            },
            findItems() {
                var vm = this;
                if (this.search_field != "") {
                    axios.get('/management/items/search/barcode/' + this.search_field)
                        .then(function (response) {
                            if (response.data.length == 0) {
                                vm.$refs.search_input_ref.select();
                            } else if (response.data.length == 1) {
                                var item = response.data[0];
                                vm.search_field = '';
                                vm.addItemToList(item);
                            } else if (response.data.length == 0) {
                                vm.$refs.search_input_ref.select();
                                vm.itemsSearchList = [];

                            } else {
                                vm.itemsSearchList = response.data;
                            }
                        })
                        .catch(function (error) {
                            console.log(error.response);
                        })
                        .then(function () {
                            // always executed
                        });
                } else {
                    this.itemsSearchList = [];
                    if (this.items.length >= 1) {
                        //

                        this.button_counter++;
                        //   this.$refs.save_invoice_button.el.focus();
                        // this.$refs.save
                    }
                }
            },


            updateItemSalePriceVisisbility(item) {

                item.openSalePrice = true;

                var index = this.items.indexOf(item);

                this.updateItemInListBYindex(index, item);
                //  alert('he')

            },

            addItemToList(item) {
                item.isOpen = false;


                if (helpers.checkIfObjectExistsOnArrayBYIdentifer(this.items, item.id)) {

                    var old_item = helpers.getDataFromArrayById(this.items, item.id);

                    if (!item.is_need_serial) {
                        var new_qty = parseInt(old_item.qty) + 1;
                        if (old_item.available_qty >= new_qty) {
                            old_item.qty = new_qty;
                        }

                        this.onChangeQtyField(old_item);

                    }


                } else {

                    this.items.push(this.callInitValuesForItem(item)); // add item after add  new props to the objecs like total,subtotal
                    this.updateInvoiceDetails();

                }

                this.itemsSearchList = []; // clear the search items list
                this.search_field = ""; /// clear the text on the search field
                this.$refs.search_input_ref.focus();// focus on the search field after make nice search

                this.net = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(Math.round(this.net));
                // alert( this.net);

                this.handleWidgets();
                // this.onInvoiceNetUpdated();

                this.checkData();
            },


            handleWidgets() {
                for (var i = 0; i < this.items.length; i++) {
                    var item = this.items[i];

                    var widget = parseFloat(item.subtotal) / parseFloat(this.subtotal);

                    item.widget = widget;

                    this.items.splice(this.items.indexOf(item), 1,item);

                }
            },

            callInitValuesForItem(item) {

                item.qty = 1;

                item.widget = 1;

                if (item.is_need_serial) {
                    item.qty = 0;
                    item.serials = [];


                }


                item.purchase_price = item.last_p_price;
                item.total = item.qty * item.purchase_price;
                item.discount = 0;
                item.openSalePrice = false;
                item.subtotal = item.total;
                item.tax = this.updateTaxForOneItem(item);
                item.net = this.updateNetForOneItem(item);
                item.variation = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(this.purchase_price >= 0 ? this.purchase_price : 0);
                item.temp_p_price = item.purchase_price;
                item.temp_r_qty = item.r_qty;


                return item;
            },


            /*

                delete the button handler
            */
            deleteItemFromList(item) {
                this.items.splice(this.items.indexOf(item), 1);
                this.updateInvoiceDetails();
            },

            /*

                update qty
            */


            onInvoiceDiscountUpdated() {


                if (parseFloat(this.discount) > parseFloat(this.total) || this.discount == ' ') {
                    this.discount = 0;
                }

                var len = this.items.length;


                for (var i = 0; i < len; i++) {
                    var item = this.items[i];
                    var index = this.items.indexOf(item);
                    var item_widget = item.total / this.total; //
                    item.discount = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(Math.round(item_widget *
                        (this.discount)));
                    item.subtotal = this.updateSubtotalForOneItem(item);
                    item.tax = this.updateTaxForOneItem(item);
                    item.net = this.updateNetForOneItem(item);
                    item.variation = this.updateVariationForOneItem(item);
                    this.updateItemInListBYindex(index, item);

                }


                this.tax = helpers.getColumnSumationFromArrayOfObjects(this.items, 'tax');
                this.net = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(Math.round(parseFloat(this.tax) +
                    parseFloat(this.subtotal)));

                this.checkData();
            },

            onChangeNetField(item) {
                var new_vat = counting.convertVatToValue(item.vtp); //  1.05
                item.subtotal = helpers.roundTheFloatValueTo2DigitOnlyAfterComma((item.net / new_vat));
                item.discount = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.total - item.subtotal);
                item.tax = helpers.showOnlyTwoAfterComma(item.subtotal * (item.vtp / 100));
                this.items.splice(this.items.indexOf(item), 1, item);
                this.updateInvoiceDetails()
            },

            onChangeNetField2(item) {

                item.discount = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.total - item.subtotal);
                item.tax = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(this.subtotal * (item.vtp / 100));


                this.items.splice(this.items.indexOf(item), 1, item);

                this.checkData();
            },


            /// events
            onChangeQtyField(item) {

                this.runUpdater(item);


            },
            onChangePriceField(item) {

                this.runUpdater(item);
            },

            onChangeDiscountField(item) {


                this.runUpdater(item);


            },


            runUpdaderWithoutUpdateInvoice(item) {
                var index = this.items.indexOf(item);
                // validate the value
                item.total = this.updateTotalForOneItem(item);
                item.subtotal = this.updateSubtotalForOneItem(item);
                item.tax = this.updateTaxForOneItem(item);
                item.net = this.updateNetForOneItem(item);
                item.variation = this.updateVariationForOneItem(item);
                this.updateItemInListBYindex(index, item);
                this.checkData();
            },
            runUpdater(item) {
                // console.log(item);
                var index = this.items.indexOf(item);
                // validate the value
                item.total = this.updateTotalForOneItem(item);
                item.subtotal = this.updateSubtotalForOneItem(item);
                item.tax = this.updateTaxForOneItem(item);
                item.net = this.updateNetForOneItem(item);
                item.variation = this.updateVariationForOneItem(item);
                this.updateItemInListBYindex(index, item);
                this.updateInvoiceDetails();
                this.checkData();
                this.handleWidgets();

            },


            // helpers
            updateTotalForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.purchase_price * item.qty);
            },


            updateVariationForOneItem(item) {
                var vig = parseFloat(item.purchase_price) - parseFloat(item.temp_p_price);
                console.log(item.temp_p_price);
                console.log(vig);


                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(vig);
            },
            updateSubtotalForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.total - item.discount);
            },
            updateTaxForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(helpers.showOnlyTwoAfterComma(item.vtp * item.subtotal / 100));
            },
            updateNetForOneItem(item) {
                var net = parseFloat(item.tax) + parseFloat(item.subtotal);
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(net);
            },
            updateItemInListBYindex(index, newItem) {
                this.items.splice(index, 1, newItem);
            },

            updateInvoiceDetails() {
                this.total = helpers.getColumnSumationFromArrayOfObjects(this.items, 'total');
                this.discount = helpers.getColumnSumationFromArrayOfObjects(this.items, 'discount');
                this.subtotal = helpers.getColumnSumationFromArrayOfObjects(this.items, 'subtotal');
                this.tax = helpers.getColumnSumationFromArrayOfObjects(this.items, 'tax');
                this.net = helpers.getColumnSumationFromArrayOfObjects(this.items, 'net');
            //    this.remaining = this.net;
            },


            saveAndPrintInvoiceButtonClicked(e) {

            },


            checkData() {
                var len = this.items.length;
                var any_error = false;
                for (var i = 0; i < len; i++) {
                    var item = this.items[i];

                    if (parseFloat(item.net) < parseFloat(0))
                        any_error = true;

                    if (parseFloat(item.subtotal) < parseFloat(0))
                        any_error = true;


                    if (parseFloat(item.discount) < parseFloat(0))
                        any_error = true;


                    if (parseFloat(item.tax) < parseFloat(0))
                        any_error = true;


                    if (!helpers.isNumber(parseFloat(item.net)))
                        any_error = true;

                    if (!helpers.isNumber(parseFloat(item.discount)))
                        any_error = true;


                    if (!helpers.isNumber(parseFloat(item.net)))
                        any_error = true;


                }


                if (parseFloat(this.tax) < parseFloat(0))
                    any_error = true;

                if (parseFloat(this.subtotal) < parseFloat(0))
                    any_error = true;

                if (parseFloat(this.net) < parseFloat(0))
                    any_error = true;

                if (parseFloat(this.discount) < parseFloat(0))
                    any_error = true;


                if (!helpers.isNumber(parseFloat(this.net)))
                    any_error = true;

                if (!helpers.isNumber(parseFloat(this.discount)))
                    any_error = true;


                if (!helpers.isNumber(parseFloat(this.net)))
                    any_error = true;


                this.disable_button_counter2 = any_error;

            },
            validateInvoiceData() {
                return true;
            },

            saveInvoiceButtonClicked(e) {

                if (this.validateInvoiceData()) {
                    this.sendDataToServer(e.event);
                }

            },

            saveInvoiceButtonClickedOnly() {

                // console.log(e);
                if (this.validateInvoiceData()) {
                    this.sendDataToServer('only');
                }

                // console.log('clicked..');
            },

            cancelInvoiceButtonClicked(e) {
                // window.print();
            },
            sendDataToServer(event) {
                // console.log(this.creator);
                var data_to = {
                    document: this.document,
                    vendor_id: this.vendor,
                    receiver_id: this.receiver,
                    items: this.items,
                    expenses:this.updated_expenses,
                    total: this.total,
                    subtotal: this.subtotal,
                    net: this.net,
                    creator_id: this.creator.id,
                    branch_id: this.creator.branch_id,
                    department_id: this.creator.department_id,
                    tax: this.tax,
                    invoice_type: 'purchase',
                    discount_value: this.discount,
                    discount_percent: this.discount,
                    remaining: this.remaining,
                    current_status: this.status,
                    issued_status: this.status,
                    methods: this.methods,
                    vendor_inc_number: this.vendor_inc_number,
                };
                var vm = this;
                axios.post('/management/purchases', data_to)
                    .then(function (response) {
                       vm.showFinishTableMessage(event, response.data.invoice_id);



                        console.log(response.data)
                    })
                    .catch(function (error) {
                        console.log(error.response.data.errors);
                        vm.errors = error.response.data.errors;
                        vm.showerror();
                        // console.log(vm.errors);
                    });

            },


            showerror() {

                this.$toast.error({
                    type: 'error',
                    showMethod: 'lightSpeedIn',
                    closeButton: false,
                    timeOut: 2000,
                    icon: '',
                    title: this.messages.process_title,
                    message: this.messages.process_error,
                    progressBar: true,
                    hideDuration: 1000
                });


                $('body,html').scrollTop(0);

            },
            showFinishTableMessage(event, id) {

                this.invoice_id = id;

                this.items = [];
                this.updateInvoiceDetails();

                this.$toast.success({
                    type: 'success',
                    showMethod: 'lightSpeedIn',
                    closeButton: false,
                    timeOut: 2000,
                    icon: '',
                    title: this.messages.process_title,
                    message: this.messages.process_done,
                    progressBar: true,
                    hideDuration: 1000
                });


                if (event == "receipt") {


                    this.print_counter++;

                } else if (event == "a4") {

                    this.print_a4_counter++;
                }



                setTimeout(function () {
                    location.reload();
                },3000);
                // this.$ref.printFrameRef.el.print();
            },
            billingsUpdate(e) {


                // this.remaining = e.remaining;


                this.remaining = e.remaining;
                if (parseFloat(e.remaining) > 0) {

                    this.status = 'credit';
                } else {
                    // this.remaining = 0;
                    this.status = 'paid';
                }


                this.methods = e.methods;
                // alert('hellow')
            }
        },

        watch: {

            user: function (value) {
                // console.log(value);
                this.vendor = value.id;
            },


            total: function (newTotal) {
                this.subtotal = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(newTotal) - parseFloat(this.discount));
            },
            subtotal: function (newSubtotal) {
            },
            tax: function (newTax) {
            },
            discount: function (newDiscount) {
                this.subtotal = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(this.total) - parseFloat(newDiscount));

            },
            net: function (value) {

                // this.net = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(value);

                var sum = 0;
                var arr = this.items;
                for (var i = arr.length - 1; i >= 0; i--) {
                    sum = parseFloat(sum) + parseFloat(arr[i]['net']);
                }


                this.current_items_net = sum;

                //    console.log(this.current_items_net);
            }
        }


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

    .live-vue-search .message-header {

        border-radius: 0px;
        border-bottom: 1px solid #eeeeee;
        cursor: pointer;
    }

    live-vue-search .message-header:hover {

        border-radius: 0px;
        border-bottom: 1px solid #eeeeee;
        cursor: pointer;
    }

    th {
        text-align: center !important
    }


    input {
        font-weight: bolder !important;
        direction: ltr !important;
    }

    .barcode_field {
        text-align: left !important;
    }


    .onlyhidden {
        font-weight: bolder;
        background: #d6e4d6 !important;
    }
</style>



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
                    <receipt-printer-component :invoice_id='invoice_id' :print_counter="print_counter"
                                               v-show="false"></receipt-printer-component>

                    <button @click="saveInvoiceButtonClickedOnly"
                            class="button is-primary "><i
                            class="fa fa-save"></i>&nbsp; {{ translator.save }}
                    </button>
                    <!--                    :disabled="disableSaveButton"-->
                </div>

            </div>


        </div>
        <div class="message-body">
            <div class="has-background-light">
                <div class="columns">
                    <div class="column">
                        <!-- <label>client name</label> -->
                        <div class="" v-bind:class="{'is-danger':error=='client_id'}">
                            <div :class="{'has-error':errors.hasOwnProperty('client_id') }" class="input-group">
                                <span class="input-group-addon" id="clients-list">{{ translator.client }}</span>
                                <select aria-describedby="clients-list" class="form-control has-error" v-model='user'>
                                    <option :checked="true"
                                            :key="user.id"
                                            :value="user"
                                            v-for="( user,index  ) in clients"
                                    >{{ user.name }}
                                    </option>
                                </select>


                            </div>
                            <p class="help is-danger is-center"
                               v-show="errors.hasOwnProperty('client_id')" v-text="errors.client_id"></p>


                        </div>
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
                            <!-- <label>client name</label> -->
                            <div class="">
                                <div :class="{'has-error':errors.hasOwnProperty('salesman_id') }" class="input-group">
                                    <span class="input-group-addon" id="salesmans-list">{{ translator.salesman }}</span>
                                    <select aria-describedby="salesmans-list" class="form-control" v-model='salesman'>
                                        <option :checked="true"
                                                :key="user.id"
                                                :value="user.id"
                                                v-for="( user,index  ) in salesmen"
                                        >{{ user.name }}
                                        </option>
                                    </select>

                                </div>
                                <p class="help is-danger is-center"
                                   v-show="errors.hasOwnProperty('salesman_id')" v-text="errors.salesman_id"></p>
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
                            <input :placeholder="translator.search_barcode_sale" @keyup.enter="findItems" class="input"
                                   ref="search_input_ref" tabindex="0" type="text"
                                   v-bind:class="{'is-danger':error=='items'}"
                                   v-model="search_field"/>
                            <p class="help is-danger is-center" v-show="error=='items'" v-text="errorMessage"></p>
                        </div>
                        <div :class="{'has-background-primary':itemsSearchList.length>=1}" class="live-vue-search ">

                            <div :key="item.id" @click="addItemToList(item)"
                                 class="has-background-primary has-text-white item"
                                 href="#" v-for="item in itemsSearchList">
                                <h6 class="">{{ item.ar_name }} </h6>


                                <div class="row">
                                    <div :class="{'has-text-info': item.is_need_serial}" class="col-xs-6">{{ item
                                        .barcode}}
                                    </div>
                                    <div class="col-xs-6">{{ reusable_translator.available }} : {{item.available_qty }}
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>


                <div class="column text-left">
                    <a class="button is-info" href="/management/items?selectable=true" tabindex="100"
                       target="_blank">{{
                        translator.view_products }}
                    </a>

                </div>
                <div class="column text-center">
                    <a class="button is-primary" href="/management/items/create" tabindex="101" target="_blank"> {{
                        translator.create_product }} </a>
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
                        <th class="has-text-white" width="3%">{{ translator.item_available_qty }}</th>
                        <th class="has-text-white" width="3%">{{ translator.qty }}</th>
                        <th class="has-text-white">{{ translator.price }}</th>
                        <th class="has-text-white">{{ translator.total }}</th>
                        <th class="has-text-white">{{ translator.discount }}</th>
                        <th class="has-text-white">{{ translator.subtotal }}</th>
                        <th class="has-text-white">{{ translator.vat }}</th>
                        <th class="has-text-white">{{ translator.tax }}</th>
                        <th class="has-text-white">{{ translator.net }}</th>
                    </tr>
                    </thead>

                    <tbody>


                    <tr :key="item.id" v-for="(item,itemindex) in items" v-show="!item.is_expense">
                        <th class="has-text-white">

                            <button @click="deleteItemFromList(item)" class="button is-danger is-small"><i
                                    class="fa fa-trash"></i></button>
                            <button @click="show(itemindex,item)" class="button is-primary is-small"
                                    style="margin:10px"
                                    v-if="item.is_need_serial">
                                <i class="fa fa-plus"></i> &nbsp;
                            </button>


                            <kit-items-and-data-component :index="itemindex" :kit="item"
                                                          :qty="item.qty"
                                                          @kitUpdated="kitHasBeenUpdated"
                                                          v-if="item.is_kit==true"></kit-items-and-data-component>


                            <item-serials-list-component
                                    :init_item_serial_list="item.serials"
                                    :isOpen="item.isOpen"
                                    :item="item"
                                    :item_index="itemindex" :key="item.id"
                                    @changed="updatedItemSerials"
                                    invoicetype="sale"
                                    v-if="item.is_need_serial"></item-serials-list-component>
                        </th>
                        <!-- <th class="has-text-white"></th> -->
                        <th v-text="item.barcode"></th>
                        <th style="text-align: right !important;" v-if="item.is_kit"><a
                                :href="'/management/kits/'+item.id+'/edit'"
                                target="_blank">{{item
                            .locale_name}}</a></th>
                        <th style="text-align: right !important;" v-else v-text="item.locale_name">item name</th>
                        <th v-if="!item.is_kit"><input :value="item.available_qty" class="input" disabled/></th>
                        <th v-else><input class="input" disabled value="-"/></th>
                        <th width="6%">
                            <input :tabindex="{1:itemindex==0}"
                                   @focus="$event.target.select()"
                                   @keyup="onChangeQtyField(item)"
                                   class="input"
                                   type="text" v-if="!item.is_need_serial"
                                   v-model="item.qty">
                            <p v-else>{{item.qty}}</p>
                        </th>
                        <th class="has-text-white">
                            <input @focus="$event.target.select()" @keyup="onChangePriceField(item)"
                                   class="input" disabled
                                   type="text" v-model="item.price">

                        </th>
                        <th class="has-text-white">
                            <input v-if="!item.is_kit" @focus="$event.target.select()" class="input" disabled
                                   type="text"
                                   v-model="item.total">
                            <input v-else class="input" disabled
                                   type="text"
                                   :value="parseFloat(0)">
                        </th>
                        <th class="has-text-white">
                            <input v-if="!item.is_kit" :disabled="item.is_fixed_price || item.is_kit"
                                   @focus="$event.target.select()"
                                   @keyup="onChangeDiscountField(item)"
                                   class="input" placeholder="discount" type="text"
                                   v-model="item.discount">
                            <input v-else class="input" disabled
                                   type="text"
                                   :value="parseFloat(0)">
                        </th>
                        <th class="has-text-white">
                            <input @focus="$event.target.select()" class="input" disabled="" placeholder="subtotal"
                                   type="text" v-model="item.subtotal">
                        </th>
                        <th class="has-text-white">
                            <input @focus="$event.target.select()" class="input" disabled="" placeholder="vat sale"
                                   type="text"
                                   v-model="item.vts + '%'">
                        </th>
                        <th class="has-text-white">
                            <input @focus="$event.target.select()" class="input" disabled="" placeholder="tax"
                                   type="text" v-model="item.tax">
                        </th>
                        <th class="has-text-white">
                            <input :disabled="item.is_fixed_price" @focus="$event.target.select()"
                                   @keyup="onChangeNetField(item)"
                                   class="input"
                                   placeholder="net" type="text" v-model="item.net">
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
                                            <input class="input" disabled type="text" v-model="discount">
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
                                            <input :disabled="items.length==0 || disabled_net_field"
                                                   @focus="$event.target.select()"
                                                   @keyup="onInvoiceNetUpdated"
                                                   class="input"
                                                   type="text"
                                                   v-model="net">
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>


                        <div class="box">
                            <div class="columns">
                                <div class="column is-two-thirds">
                                    <div class="select is-fullwidth">
                                        <select v-model="active_expense">
                                            <option :value="expense" v-for="expense in expenses">{{
                                                expense.locale_name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column">
                                    <button @click="addNewExpenseOption"
                                            class="button is-primary"><i class="fa fa-plus-circle"></i></button>
                                </div>
                            </div>


                            <div class="" v-show="items.length>=1">
                                <div class="panel" v-for="expense in items" v-show="expense.is_expense">
                                    <p>{{ expense.locale_name}}</p>
                                    <div class="columns">
                                        <div class="column is-two-thirds">
                                            <input @focus="$event.target.select()" @keyup="onChangePriceField(expense)"
                                                   class="input" placeholder="القيمة"
                                                   type="text" v-model="expense.price"/>
                                        </div>
                                        <div class="column">
                                            <input class="input" placeholder="التكلفة" type="text"
                                                   v-model="expense.purchase_price"/>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>


                        <div class="box">
                            <textarea class="form-control" v-model="test_request_textarea"></textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- end box -->

</template>

<script>
    import printJS from 'print-js'


    export default {
        components: {
            printJS
        },

        props: ['creator', 'clients', 'salesmen', 'gateways', 'expenses'],
        data: function () {
            return {
                active_expense: null,
                new_net: 0,
                expenses_list: [],
                user: null,
                test_request_textarea: '',
                disable_button_counter: 0,
                disable_button_counter2: true,
                current_items_net: 0,
                disableSaveButton: true,
                print_counter: 1,
                print_a4_counter: 1,
                invoice_id: 0,
                button_counter: 0,
                bc: new BroadcastChannel('item_barcode_copy_to_invoice'),
                reusable_translator: null,
                messages: null,
                translator: null,
                errors: new Map,
                document: '',
                client_inc_number: '',
                pdfLink: '',
                department: '',
                disabled_net_field: true,
                errorMessage: "",
                error: "",
                client: "",
                client_data: "",
                salesman: "",
                status: 'credit',
                search_field: "",
                itemsSearchList: [],
                methods: [],
                pay_ways: [],
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
            this.client = this.clients[0].id;
            this.user = this.clients[0];
            this.client_data = this.clients[0];
            this.salesman = this.creator.id;
            //  console.log(this.client);

            this.translator = JSON.parse(window.translator);
            this.messages = JSON.parse(window.messages);
            this.reusable_translator = JSON.parse(window.reusable_translator);
            this.department = this.creator.department.title;
            this.timerLoop();


            // this.checkData();
            // console.log(this.$refs.)
        },

        mounted: function () {


            this.watchCopiedItems();
            this.$refs.search_input_ref.focus();
        },


        methods: {


            disable_field_or_not() {

                var is_open = true;
                for (var i = 0; i < this.items.length; i++) {
                    var item = this.items[i];

                    if (!item.is_kit && !item.is_fixed_price)
                        is_open = false;
                }


                this.disabled_net_field = is_open;
            },

            addNewExpenseOption() {

                if (this.active_expense != null) {
                    var new_expense = this.active_expense;
                    new_expense.available_qty = 1;
                    new_expense.qty = 1;
                    new_expense.price = 0;
                    new_expense.purchase_price = 0;
                    // this.expenses_list.push(new_expense);
                    this.addItemToList(new_expense);


                }

            },
            handleWidgets() {

                for (var i = 0; i < this.items.length; i++) {
                    var item = this.items[i];

                    var widget = parseFloat(item.subtotal) / parseFloat(this.subtotal);

                    item.widget = widget;


                    this.items.splice(this.items.indexOf(item), 1, item);

                }


            },


            kitHasBeenUpdated(e) {

                var kit = e.kit;
                console.log(kit.items);
                this.items.splice(e.index, 1, kit);


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
                        vm.onInvoiceNetUpdated();
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
                    axios.get('/management/items/search/barcode/' + this.search_field + '?search_for=sale')
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


            addItemToList(item) {
                item.isOpen = false;

                if (helpers.checkIfObjectExistsOnArrayBYIdentifer(this.items, item.id)) {

                    var old_item = helpers.getDataFromArrayById(this.items, item.id);

                    if (!item.is_need_serial) {
                        var new_qty = parseInt(old_item.qty) + 1;


                        if (!old_item.is_kit) {
                            if (old_item.available_qty >= new_qty) {
                                old_item.qty = new_qty;
                            }
                        } else {
                            old_item.qty = new_qty;
                        }


                        this.onChangeQtyField(old_item);

                    } else if (item.has_init_serial) {
                        //   console.log(old_item.serials);
                        if (!old_item.serials.includes(item.init_serial.serial)) {
                            old_item.serials.push(
                                item.init_serial.serial
                            );
                            old_item.qty = parseInt(old_item.qty) + 1;
                            this.onChangeQtyField(old_item);
                            this.$refs.search_input_ref.focus();
                            this.$refs.search_input_ref.select();
                        }

                    }


                    // console.log(item);
                    // update the product after increse the value of qty by one

                } else {

                    this.items.push(this.callInitValuesForItem(item)); // add item after add  new props to the objecs like total,subtotal
                    this.updateInvoiceDetails();

                }

                // }
                this.itemsSearchList = []; // clear the search items list
                this.search_field = ""; /// clear the text on the search field
                // focus on the search field after make nice search

                if (!item.is_kit)
                    this.net = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(Math.round(this.net));
                // alert( this.net);


                if (!item.is_kit) {
                    this.$refs.search_input_ref.focus();
                }
                if (!item.is_kit)
                    this.onInvoiceNetUpdated();

                this.checkData();

                this.handleWidgets();

                this.disable_field_or_not();
            },


            callInitValuesForItem(item) {


                item.qty = 1;
                // if (item.available_qty >= 1) {
                //     item.qty = 1;
                // } else {
                //     item.qty = 1;
                // }

                item.widget = 1;
                if (item.is_need_serial) {
                    item.qty = 0;
                    item.serials = [];

                    if (item.has_init_serial) {
                        item.serials.push(item.init_serial.serial);
                        item.qty = 1;
                    }


                }


                if (item.is_kit) {
                    item.available_qty = 100;
                    item.is_fixed_price = true;
                    item.price = item.data.total;
                    item.total = item.data.total;
                    item.subtotal = item.data.subtotal;
                    item.discount = item.data.discount;
                    item.price_with_tax = counting.calcPriceWithTaxFromPrice(item.price, item.vts);
                    item.net = item.data.net;
                    item.tax = item.data.tax;


                    item.base_total = item.data.total;
                    item.base_subtotal = item.data.subtotal;
                    item.base_discount = item.data.discount;
                    item.base_price_with_tax = counting.calcPriceWithTaxFromPrice(item.price, item.vts);
                    item.base_net = item.data.net;
                    item.base_tax = item.data.tax;


                    item.is_fixed_price = true;
                } else {
                    item.total = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.qty * item.price);
                    item.discount = 0;
                    item.subtotal = item.total;
                    item.tax = this.updateTaxForOneItem(item);
                    item.net = this.updateNetForOneItem(item);
                }


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


            onInvoiceNetUpdated() {


                var new_vat = counting.convertVatToValue(config.vtp); //  5 = fixed vts
                this.subtotal = helpers.roundTheFloatValueTo2DigitOnlyAfterComma((this.net / new_vat));
                this.discount = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(this.total - this.subtotal);


                var len = this.items.length;


                var none_editable_net = 0;
                var total_for_editable = 0;
                for (var i = 0; i < len; i++) {

                    var item = this.items[i];
                    if (!item.is_fixed_price) {
                        total_for_editable = parseFloat(total_for_editable) + parseFloat(item.total);

                    } else {
                        none_editable_net = parseFloat(none_editable_net) + parseFloat(item.net);
                    }
                }


                for (var i = 0; i < len; i++) {
                    var item = this.items[i];
                    if (!item.is_fixed_price) {


                        if (parseFloat(total_for_editable) > 0) {
                            var item_widget = parseFloat(item.total) / parseFloat(total_for_editable);
                        } else {
                            var item_widget = 0;
                        }
                        //

                        // console.log(parseFloat(total_for_editable));

                        var new_item_net = parseFloat(item_widget) * (parseFloat(this.net) - parseFloat(none_editable_net));// Math.round(helpers.roundTheFloatValueTo2DigitOnlyAfterComma(


                        // Math.round(helpers.roundTheFloatValueTo2DigitOnlyAfterComma(
                        var new_vat = counting.convertVatToValue(item.vts); //  1.05


                        // var new_tax_value = new_item_net
                        item.subtotal = (parseFloat(new_item_net) /
                            parseFloat(new_vat));// helpers.roundTheFloatValueTo2DigitOnlyAfterComma( //helpers.showOnlyTwoAfterComma(
                        item.discount = parseFloat(item.total) -
                            parseFloat(item.subtotal);//helpers.showOnlyTwoAfterComma(
                        console.log(item.discount);
                        item.tax = item.subtotal * (item.vts / 100);// helpers.showOnlyTwoAfterComma(
                        item.net = new_item_net;// helpers.roundTheFloatValueTo2DigitOnlyAfterComma(
                        this.items.splice(this.items.indexOf(item), 1, item);


                    }


                }


                this.tax = helpers.getColumnSumationFromArrayOfObjects(this.items, 'tax');
                this.total = helpers.getColumnSumationFromArrayOfObjects(this.items, 'total');
                this.discount = helpers.getColumnSumationFromArrayOfObjects(this.items, 'discount');
                this.subtotal = helpers.getColumnSumationFromArrayOfObjects(this.items, 'subtotal');
                // this.tax = helpers.showOnlyTwoAfterComma(this.subtotal * 5 / 100);
                // this.updateInvoiceDetails()

                this.checkData();
                this.handleWidgets();

            },

            onChangeNetField(item) {
                var new_vat = counting.convertVatToValue(item.vts); //  1.05
                item.subtotal = helpers.roundTheFloatValueTo2DigitOnlyAfterComma((item.net / new_vat));
                item.discount = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.total - item.subtotal);
                item.tax = helpers.showOnlyTwoAfterComma(item.subtotal * (item.vts / 100));
                this.items.splice(this.items.indexOf(item), 1, item);
                this.updateInvoiceDetails()

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


            onChangeNetField2(item) {

                item.discount = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.total - item.subtotal);
                item.tax = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(this.subtotal * (item.vts / 100));


                this.items.splice(this.items.indexOf(item), 1, item);

                this.checkData();

            },


            /// events
            onChangeQtyField(item) {
                console.log(item);
                // if(item.qty > item)
                if (item.is_kit) {
                    item.total = parseFloat(item.base_total) * parseFloat(item.qty);
                    item.subtotal = parseFloat(item.base_subtotal) * parseFloat(item.qty);
                    item.tax = parseFloat(item.base_tax) * parseFloat(item.qty);
                    item.net = parseFloat(item.base_net) * parseFloat(item.qty);
                    item.total = parseFloat(item.base_total) * parseFloat(item.qty);

                    var index = this.items.indexOf(item);

                    this.updateItemInListBYindex(index, item);
                    this.updateInvoiceDetails();

                    this.checkData();
                    this.handleWidgets();


                } else if (item.available_qty >= item.qty) {
                    this.runUpdater(item);
                } else {
                    this.$toast.error({
                        type: 'error',
                        showMethod: 'lightSpeedIn',
                        closeButton: false,
                        timeOut: 4000,
                        icon: '',
                        title: 'خطأ',
                        message: 'الكمية المطلوبة غير متوفرة',
                        progressBar: true,
                        hideDuration: 1000
                    })
                }


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
                var index = this.items.indexOf(item);


                item.discount = helpers.showOnlyTwoAfterComma(item.discount);
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
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.price * item.qty);
            },

            updateVariationForOneItem(item) {
                var vig = parseFloat(item.price) - parseFloat(item.temp_p_price);
                // console.log(item.temp_p_price);
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(vig);
            },
            updateSubtotalForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.total - item.discount);
            },
            updateTaxForOneItem(item) {
                return helpers.showOnlyTwoAfterComma(item.vts * item.subtotal / 100);
            },
            updateNetForOneItem(item) {
                if (item.is_fixed_price && !item.is_kit) {
                    return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.qty * item.price_with_tax);
                }
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
                //      this.remaining = this.net;
                this.checkData();
                this.disable_field_or_not();
            },


            saveAndPrintInvoiceButtonClicked(e) {

            },

            validateInvoiceData() {
                return true;
            },

            saveInvoiceButtonClicked(e) {

                // console.log(e);
                if (this.validateInvoiceData()) {
                    this.sendDataToServer(e.event);
                }

                // console.log('clicked..');
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
                var data_to = {
                    client_id: this.client,
                    salesman_id: this.salesman,
                    items: this.items,
                    total: this.total,
                    expenses: this.expenses_list,
                    subtotal: this.subtotal,
                    net: this.net,
                    creator_id: this.creator.id,
                    branch_id: this.creator.branch_id,
                    department_id: this.creator.department_id,
                    tax: this.tax,
                    invoice_type: 'sale',
                    discount_value: this.discount,
                    discount_percent: this.discount,
                    remaining: this.remaining,
                    current_status: this.status,
                    issued_status: this.status,
                    methods: this.pay_ways,
                };
                var vm = this;
                // console.log(data_to);
                this.test_request_textarea = JSON.stringify(data_to);
                axios.post('/management/sales', data_to)
                    .then(function (response) {
                        console.log(response);
                        console.log(response.data);
                        vm.showFinishTableMessage(event, response.data.invoice_id);

                    })
                    .catch(function (error) {
                        console.log(error.response.data.errors);
                        console.log(error.response.data);
                        console.log(error.response);
                        console.log(error);
                        //  vm.errors = error.response.data.errors;
                        // console.log(vm.errors);
                    });

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


                    this.print_counter = id;

                } else if (event == "a4") {

                    this.print_a4_counter = id;
                }

                //
                setTimeout(function () {
                    location.reload();
                }, 4000);
                // this.$ref.printFrameRef.el.print();
            },
            billingsUpdate(e) {
                var gats = e.methods;
                this.remaining = e.remaining;

                if (parseFloat(e.remaining) > parseFloat(0)) {

                    this.status = 'credit';
                } else {
                    //    gats[0].amount = parseFloat(gats[0].amount) + parseFloat(e.remaining);
                    //     this.remaining = 0;
                    this.status = 'paid';
                }

                this.pay_ways = gats;


            }
        },

        watch: {

            user: function (value) {
                // console.log(value);
                this.client = value.id;
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

            }
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

    .live-vue-search {
        text-decoration: none !important;
        padding: 10px;
        border-bottom: 2px solid white;
    }

    .live-vue-search .item {

        padding-bottom: 5px;
        border-radius: 0px;
        border-bottom: 1px solid black;
        cursor: pointer;
    }

    live-vue-search .item:hover {

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

</style>



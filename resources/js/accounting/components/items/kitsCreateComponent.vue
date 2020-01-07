<template>
    <!-- startup box -->
    <div class="">

        <div class="row">
            <div class="col-md-6">
                <button @click="saveInvoiceButtonClicked" class="btn btn-custom-primary"><i
                        class="fa fa-save"></i> {{ app.trans.save_kit }}
                </button>

            </div>
            <div class="col-md-6">
                <a :href="app.BaseApiUrl + 'kits'" class="btn btn-default "><i
                        class="fa fa-redo"></i> {{ app.trans.cancel }}</a>
            </div>

        </div>


        <div class="">


            <div class="row">
                <div class="col-md-6">
                    <input  :placeholder="translator.ar_name" class="form-control" type="text"
                           v-model="ar_name">
                    <p class="help is-danger is-center" v-show="error=='client_id'" v-text="errorMessage"></p>
                </div>

                <div class="col-md-6">
                    <div v-bind:class="{'is-danger':error=='client_id'}">
                        <input  :placeholder="translator.name" class="form-control"
                               type="text"
                               v-model="name">
                        <p class="help is-danger is-center" v-show="error=='client_id'" v-text="errorMessage"></p>
                    </div>
                </div>


            </div>

            <div class="form-group">
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <div class="control">
                                <input :class="{'is-danger':error=='barcode'}" :disabled="editingKit"
                                       :placeholder="translator.barcode"
                                       @blur="checkBarcodeIfItAlreadyUsed" class="form-control" type='text'
                                       v-model="barcode" v-on:keyup.13="barcodeItemInterClicked">
                            </div>
                            <p class="help is-danger is-center" v-show="error=='barcode'" v-text="errorMessage"></p>
                        </div>
                    </div>
                    <div class="column is-one-fifth" v-if="!editingKit">
                        <button @click="generateBarcode" class="button is-info is-fullwidth">{{translator
                            .generate_barcode}}
                        </button>
                    </div>


                </div>
            </div>


            <!-- start search field -->
            <div class="columns">
                <div class="column is-four-fifths">
                    <div class="product_search" id="seach_area">
                        <div class="">
                            <input :placeholder="translator.search_barcode" @keyup.enter="findItems"
                                   class="input" ref="search_input_ref" type="text"

                                   v-bind:class="{'is-danger':error=='items'}" v-model="search_field"/>
                            <p class="help is-danger is-center" v-show="error=='items'" v-text="errorMessage"></p>
                        </div>
                        <div class="live-vue-search">
                            <a :key="item.id" @click="addItemToList(item)" class="message-header has-background-primary"
                               href="#" v-for="item in itemsSearchList">
                                <h3 class="title">{{ item.locale_name }} <small class="has-text-white">{{ item.barcode
                                    }}</small></h3>
                                ,{{ item.price }},
                            </a>
                        </div>
                    </div>

                </div>

                <div class="column text-center">
                    <a class="button is-info" href="/accounting/items?selectable=true&&is_purchase=true" tabindex="100"
                       target="_blank">{{
                        translator.view_products }}
                    </a>

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
                        <th class="has-text-white" width="20%">{{ translator.name }}</th>
                        <th class="has-text-white" width="3%">{{ translator.available_qty }}</th>
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


                    <tr :key="item.id" v-for="(item,itemindex) in items">
                        <th class="has-text-white">

                            <button @click="deleteItemFromList(item)" class="button is-danger is-small"><i
                                    class="fa fa-trash"></i></button>

                        </th>
                        <!-- <th class="has-text-white"></th> -->
                        <th v-text="item.barcode"></th>
                        <th style="text-align: right !important;" v-text="item.locale_name"></th>
                        <th>
                            <input :value="item.available_qty" class="input" disabled/>
                        </th>
                        <th width="6%">
                            <input :tabindex="{1:itemindex==0}"
                                   @focus="$event.target.select()"
                                   @keyup="onChangeQtyField(item)"
                                   class="input"
                                   type="text"
                                   v-model="item.qty">
                        </th>
                        <th class="has-text-white">
                            <input @focus="$event.target.select()" @keyup="onChangePriceField(item)"
                                   class="input"
                                   type="text" v-model="item.price">

                        </th>
                        <th class="has-text-white">
                            <input @focus="$event.target.select()" class="input" disabled type="text"
                                   v-model="item.total">
                        </th>
                        <th class="has-text-white">
                            <input :disabled="item.is_fixed_price" @focus="$event.target.select()"
                                   @keyup="onChangeDiscountField(item)"
                                   class="input" placeholder="discount" type="text"
                                   v-model="item.discount">
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
                                            <input :disabled="items.length==0"
                                                   @focus="$event.target.select()"
                                                   @keyup="onInvoiceNetUpdated"
                                                   class="input"
                                                   disabled
                                                   type="text"
                                                   v-model="net">
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
    <!-- end box -->

</template>

<script>

    import {
        accounting as ItemAccounting,
        math as ItemMath,
        query as ItemQuery,
        validator as ItemValidator
    } from '../../item';

    export default {
        props: ['creator', 'kit', 'data', 'initItems', 'editingKit'],
        data: function () {
            return {
                app: {
                    primaryColor: metaHelper.getContent('primary-color'),
                    secondColor: metaHelper.getContent('second-color'),
                    appLocate: metaHelper.getContent('app-locate'),
                    trans: trans('items-page'),
                    messages: trans('messages'),
                    dateTimeTrans: trans('datetime'),
                    validation: trans('validation'),
                    datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
                    BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
                    defaultVatSaleValue: 5,
                    defaultVatPurchaseValue: 5,
                },


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
                errorMessage: "",
                error: "",
                search_field: "",
                itemsSearchList: [],
                items: [],
                total: 0,
                discount: 0,
                tax: 0,
                net: 0,
                subtotal: 0,
                barcode: '',
                name: '',
                ar_name: '',
            };
        },
        created: function () {

            //  console.log(this.client);
            this.translator = JSON.parse(window.translator);
            this.messages = JSON.parse(window.messages);
            this.reusable_translator = JSON.parse(window.reusable_translator);
            if (this.editingKit) {
                this.loadInitData();
            }

        },
        mounted: function () {


            this.$refs.search_input_ref.focus();
            this.watchCopiedItems();


        },
        methods: {


            /*
            * s
            *
            * */

            checkBarcodeIfItAlreadyUsed(e) {
                var vm = this;
                axios.post('/accounting/items/helper/validate_barcode', {
                    barcode: e.target.value,
                })
                    .then(function (response) {
                        if (vm.error == 'barcode')
                            vm.error = '';
                    })
                    .catch(function (error) {
                        vm.error = 'barcode';
                        vm.errorMessage = error.response.data.message;
                        vm.barcode = '';
                    });


            },


            generateBarcode() {
                var barcode = helpers.generateRandomNumberWithSize();
                var vm = this;


                if (this.error == 'barcode') {

                    this.error = '';
                }
                axios.get('/accounting/items/helper/validate_barcode?barcode=' + barcode)
                    .then(function (response) {
                        if (vm.error == 'barcode') {
                            vm.error = '';
                        }
                    })
                    .catch(function (error) {
                        if (error.response.status == 403) {
                            alert('you have no permession to create item');
                        } else {
                            vm.generateBarcode();
                        }
                        //
                    });

                vm.barcode = barcode;
                if (this.error == 'barcode') {
                    this.error = '';
                }
            },

            loadInitData() {
                this.barcode = this.kit.barcode;
                this.name = this.kit.name;
                this.ar_name = this.kit.ar_name;

                this.total = this.data.total;
                this.discount = this.data.discount;
                this.subtotal = this.data.subtotal;
                this.tax = this.data.tax;
                this.net = this.data.net;

                for (let index = 0; index < this.initItems.length; index++) {
                    let item = this.initItems[index];
                    item.barcode = item.item.barcode;
                    item.ar_name = item.item.ar_name;
                    item.name = item.item.name;
                    item.locale_name = item.item.locale_name;
                    item.id = item.item.id;
                    item.available_qty = item.item.available_qty;
                    item.vts = item.item.vts;
                    this.items.push(item);
                }

            },

            /**
             *
             *
             *
             *
             *
             * */

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


            findItems() {
                var vm = this;
                if (this.search_field != "") {
                    ItemQuery.sendQueryRequestToFindItems(this.search_field)
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


                if (helpers.checkIfObjectExistsOnArrayBYIdentifer(this.items, item.id)) {

                    var old_item = helpers.getDataFromArrayById(this.items, item.id);


                    var new_qty = parseInt(old_item.qty) + 1;
                    // if (old_item.available_qty >= new_qty) {
                    old_item.qty = new_qty;
                    // }

                    this.onChangeQtyField(old_item);


                } else {

                    this.items.push(this.callInitValuesForItem(item)); // add item after add  new props to the objecs like total,subtotal
                    this.updateInvoiceDetails();

                }

                this.itemsSearchList = []; // clear the search items list
                this.search_field = ""; /// clear the text on the search field
                this.$refs.search_input_ref.focus();// focus on the search field after make nice search

                this.onInvoiceNetUpdated();

                this.checkData();
            },


            callInitValuesForItem(item) {


                item.qty = 1;
                item.total = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.qty * item.price);
                item.discount = 0;
                item.subtotal = item.total;
                item.tax = this.updateTaxForOneItem(item);
                item.net = this.updateNetForOneItem(item);


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

                        var new_item_net = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(Math.round(item_widget * (this.net - none_editable_net)));


                        var new_vat = counting.convertVatToValue(item.vts); //  1.05
                        item.subtotal = helpers.roundTheFloatValueTo2DigitOnlyAfterComma((parseFloat(new_item_net) /
                            parseFloat(new_vat)));
                        item.discount = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(item.total) -
                            parseFloat(item.subtotal));

                        item.tax = helpers.showOnlyTwoAfterComma(item.subtotal * (item.vts / 100));
                        item.net = new_item_net;
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

            },
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
                if (item.is_fixed_price) {
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
                this.remaining = this.net;
                this.checkData();
            },


            saveInvoiceButtonClicked(e) {
                if (this.editingKit) {
                    this.sendUpdateRequest();
                } else {
                    this.sendDataToServer(e.event);
                }

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


            },


            sendCreationRequest() {

                var data_to = {
                    name: this.name,
                    ar_name: this.ar_name,
                    barcode: this.barcode,
                    items: this.items,
                    total: this.total,
                    subtotal: this.subtotal,
                    net: this.net,
                    creator_id: this.creator.id,
                    branch_id: this.creator.branch_id,
                    department_id: this.creator.department_id,
                    tax: this.tax,
                    invoice_type: 'sale',
                    discount_value: this.discount,
                    discount_percent: this.discount,
                    remaining: 0,
                    current_status: 'paid',
                    issued_status: 'paid',
                };


                axios.post('/accounting/kits', data_to)
                    .then(function (response) {

                        console.log(response.data);
                        console.log(response);
                        location.href = '/accounting/kits';
                    })
                    .catch(function (error) {
                        console.log(error.response.data);


                    });
            },

            sendUpdateRequest() {

                var data_to = {
                    name: this.name,
                    ar_name: this.ar_name,
                    barcode: this.barcode,
                    items: this.items,
                    total: this.total,
                    subtotal: this.subtotal,
                    net: this.net,
                    creator_id: this.creator.id,
                    branch_id: this.creator.branch_id,
                    department_id: this.creator.department_id,
                    tax: this.tax,
                    invoice_type: 'sale',
                    discount_value: this.discount,
                    discount_percent: this.discount,
                    remaining: 0,
                    current_status: 'paid',
                    issued_status: 'paid',
                };


                axios.patch('/accounting/kits/' + this.kit.id, data_to)
                    .then(function (response) {
                        location.href = '/accounting/kits';
                    })
                    .catch(function (error) {
                        console.log(error.response.data);
                    });
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


<style scoped src='bulma/css/bulma.css'>

</style>

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
</style>



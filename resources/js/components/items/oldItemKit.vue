<template>
    <!-- startup box -->
    <div class="message">

        <div class="panel-body has-background-dark">
            <div class="form-group">
                <div class="columns">
                    <div class="column text-right">

                        <button @click="cancelInvoiceButtonClicked" class="button is-medium"><i
                                class="fa fa-lock"></i>&nbsp;{{ translator.cancel}}
                        </button>
                    </div>
                    <div class="column text-left">
                        <button @click="saveInvoiceButtonClicked" class="button is-primary is-medium"><i
                                class="fa fa-save"></i>&nbsp; {{ translator.save_kit}}
                        </button>

                    </div>

                </div>
            </div>
        </div>
        <div class="message-body">

            <div class="has-background-light">

                <div class="columns">
                    <div class="column">
                        <div v-bind:class="{'is-danger':error=='client_id'}">
                            <input :placeholder="translator.name" class="input" type="text" v-model="name">
                            <p class="help is-danger is-center" v-show="error=='client_id'" v-text="errorMessage"></p>
                        </div>
                    </div>

                    <div class="column">
                        <input :placeholder="translator.ar_name" class="input" type="text" v-model="ar_name">
                        <p class="help is-danger is-center" v-show="error=='client_id'" v-text="errorMessage"></p>
                    </div>

                </div>


            </div>

            <hr>
            <div class="form-group">
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <div class="control">
                                <input :class="{'is-danger':error=='barcode'}" :placeholder="translator.barcode"
                                       :readonly="isEditMode"
                                       @blur="checkBarcodeIfItAlreadyUsed" class="input" type='text'
                                       v-model="barcode" v-on:keyup.13="barcodeItemInterClicked">
                            </div>
                            <p class="help is-danger is-center" v-show="error=='barcode'" v-text="errorMessage"></p>
                        </div>
                    </div>
                    <div class="column is-one-fifth" v-if="!isEditMode">
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
                                <h3 class="title">{{ item.name }} <small class="has-text-white">{{ item.barcode
                                    }}</small></h3>
                                ,{{ item.price }},
                            </a>
                        </div>
                    </div>

                </div>

                <div class="column text-center">
                    <a class="button is-info" href="/management/items?selectable=true" tabindex="100"
                       target="_blank">{{
                        translator.view_products }}
                    </a>

                </div>
            </div>


            <hr>
            <div class="table-container">
                <table class="table is-bordered text-center is-fullwidth is-narrow is-hoverable is-striped">
                    <thead class="has-background-info has-text-white">
                    <tr>
                        <th class=" has-text-white">#</th>
                        <!-- <th></th> -->
                        <th  class=" has-text-white">{{ translator.barcode}}</th>
                        <th  class=" has-text-white"width="20%">{{ translator.name}}</th>
                        <th class=" has-text-white" width="3%">{{ translator.qty}}</th>
                        <th  class=" has-text-white">{{ translator.movement.price}}</th>
                        <th  class=" has-text-white">{{ translator.total}}</th>
                        <th  class=" has-text-white">{{ translator.discount}}</th>
                        <th  class=" has-text-white">{{ translator.subtotal}}</th>
                        <th  class=" has-text-white">{{ translator.vat }}</th>
                        <th  class=" has-text-white">{{ translator.tax}}</th>
                        <th  class=" has-text-white">{{ translator.net}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr :key="item.id" v-for="item in items">
                        <th>
                            <button @click="deleteItemFromList(item)" class="button is-danger is-small"><i
                                    class="fa fa-trash"></i></button>
                        </th>
                        <!-- <th></th> -->
                        <th style="text-align: left  !important;" v-text="item.barcode"></th>
                        <th style="text-align: right  !important;" v-text="item.locale_name">item name</th>
                        <th width="6%">
                            <input @keyup="onChangeQtyField(item)" class="input" type="text" v-model="item.qty">
                        </th>
                        <th>
                            <input @keyup="onChangePriceField(item)" class="input" type="text"
                                   :disabled="!item.is_fixed_price"
                                   v-model="item.price">
                            <!--                            <span v-else>{{item.price}}</span>-->

                        </th>
                        <th>
                            <input class="input" type="text" v-model="item.total">
                        </th>
                        <th>
                            <input @keyup="onChangeDiscountField(item)" class="input" placeholder="discount" type="text"
                                   v-model="item.discount">
                        </th>
                        <th>
                            <input class="input" placeholder="subtotal" readonly="" type="text" v-model="item.subtotal">
                        </th>
                        <th>
                            <input class="input" placeholder="vat sale" readonly="" type="text"
                                   v-model="item.vts + '%'">
                        </th>
                        <th>
                            <input class="input" placeholder="tax" readonly="" type="text" v-model="item.tax">

                        </th>
                        <th>
                            <input class="input" placeholder="net" readonly="" type="text" v-model="item.net">
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <div class="columns">
                    <div class="column is-three-quarters"></div>
                    <div class="column">
                        <div class="card">
                            <div class="message-header">
                            </div>
                            <div class="message-body text-center">
                                <div class="list-item-group">
                                    <div class="columns">
                                        <div class="column">{{ translator.total}}</div>
                                        <div class="column">
                                            <input class="input" type="text" v-model="total">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-item-group">
                                    <div class="columns">
                                        <div class="column">{{ translator.discount}}</div>
                                        <div class="column">
                                            <input class="input" type="text" v-model="discount">
                                        </div>
                                    </div>
                                </div>

                                <div class="list-item-group">
                                    <div class="columns">
                                        <div class="column">{{ translator.subtotal}}</div>
                                        <div class="column">
                                            <input class="input" readonly="" type="text" v-model="subtotal">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-item-group">
                                    <div class="columns">
                                        <div class="column">{{ translator.tax}}</div>
                                        <div class="column">
                                            <input class="input" type="text" v-model="tax">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-item-group">
                                    <div class="columns">
                                        <div class="column">{{ translator.net}}</div>
                                        <div class="column">
                                            <input class="input" type="text" v-model="net">
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
    export default {
        props: ['creator', 'update', 'kit'],
        data: function () {
            return {
                bc: new BroadcastChannel('item_barcode_copy_to_invoice'),
                translator: null,
                barcode: '',
                name: '',
                ar_name: '',
                errorMessage: "",
                error: "",
                selected_client: "",
                search_field: "",
                itemsSearchList: [],
                items: [],
                total: 0,
                discount: 0,
                isEditMode: false,
                tax: 0,
                net: 0,
                subtotal: 0,
                time: new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate() + ' ' + new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds()
            };
        },
        created: function () {
            this.translator = JSON.parse(window.translator);
            if (this.update == 'edit') {
                this.isEditMode = true;
                this.editKit();
            }

        },
        mounted: function () {


            this.$refs.search_input_ref.focus();
            this.watchCopiedItems();

        },


        methods: {

            watchCopiedItems() {
                var vm = this;
                this.bc.onmessage = function (ev) {
                    if (ev.isTrusted) {
                        var item = JSON.parse(ev.data);
                        vm.addItemToList(item);
                        //vm.onInvoiceNetUpdated();
                    }
                }

            },



            editKit() {
                var len = this.kit.items.length;

                for (var i = 0; i < len; i++) {
                    var item = this.kit.items[i];
                    item['name'] = item.item.name;
                    item['barcode'] = item.item.barcode;
                    item['vts'] = item.item.vts;
                    this.items.push(item);
                }


                this.barcode = this.kit.barcode;
                this.name = this.kit.name;
                this.ar_name = this.kit.ar_name;
                // load all data
                this.total = this.kit.data.total;
                this.subtotal = this.kit.data.subtotal;
                this.tax = this.kit.data.tax;
                this.net = this.kit.data.net;

            },

            checkBarcodeIfItAlreadyUsed(e) {
                var vm = this;
                axios.post('/management/items/check_barcode_if_exists', {
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
                axios.post('/management/items/check_barcode_if_exists', {
                    barcode: barcode,
                })
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
                        // // console.log(error.response.data.message);
                    });

                vm.barcode = barcode;
                if (this.error == 'barcode') {
                    this.error = '';
                }
            },


            findItems() {
                var vm = this;
                if (this.search_field != "") {

                    axios.get('/management/items/search/barcode/' + this.search_field)
                        .then(function (response) {
                            if (response.data.length == 1) {

                                // console.log(response.data[0]);
                                var item = response.data[0];
                                // console.log(item);
                                // vm.items.push(item)
                                vm.search_field = '';
                                vm.addItemToList(item);
                                // console.log('full barcode');
                            } else {
                                vm.itemsSearchList = response.data;
                            }
                        })
                        .catch(function (error) {

                        })
                        .then(function () {
                            // always executed
                        });
                } else {
                    this.itemsSearchList = [];
                }
            },


            addItemToList(item) {

                if (helpers.checkIfObjectExistsOnArrayBYIdentifer(this.items, item.id)) {
                    var old_item = helpers.getDataFromArrayById(this.items, item.id);
                    old_item.qty = parseInt(old_item.qty) + 1;
                    this.onChangeQtyField(old_item); // update the product after increse the value of qty by one

                } else {
                    this.items.push(this.callInitValuesForItem(item)); // add item after add  new props to the objecs like total,subtotal
                    this.updateInvoiceDetails();

                }

                this.itemsSearchList = []; // clear the search items list
                this.search_field = ""; /// clear the text on the search field
                this.$refs.search_input_ref.focus();// focus on the search field after make nice search
            },


            callInitValuesForItem(item) {
                item.qty = 1;
                item.total = item.qty * item.price;
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
                var index = this.items.indexOf(item);
                // validate the value
                item.total = this.updateTotalForOneItem(item);
                item.subtotal = this.updateSubtotalForOneItem(item);
                item.tax = this.updateTaxForOneItem(item);
                item.net = this.updateNetForOneItem(item);
                this.updateItemInListBYindex(index, item);
                this.updateInvoiceDetails();
            },

            // helpers
            updateTotalForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.price * item.qty);
            },
            updateSubtotalForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.total - item.discount);
            },
            updateTaxForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.vts * item.subtotal / 100);
            },
            updateNetForOneItem(item) {
                // return counting.convertVatToValue(taxRate) *
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
            },


            saveAndPrintInvoiceButtonClicked(e) {

            },


            saveInvoiceButtonClicked(e) {
                this.sendDataToServer();
                // console.log('clicked..');
            },

            cancelInvoiceButtonClicked(e) {

            },
            sendDataToServer() {

                if (this.update == 'edit') {
                    // console.log(this.creator);
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

                    var vm = this;

                    axios.patch('/management/kits/update/' + this.kit.id, data_to)
                        .then(function (response) {
                            location.href = '/management/kits';
                            console.log(response.data);
                        })
                        .catch(function (error) {


                        });


                } else {
                    // console.log(this.creator);
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


                    axios.post('/management/kits', data_to)
                        .then(function (response) {
                            location.href = '/management/kits';
                        })
                        .catch(function (error) {
                            console.log(error.response.data);


                        });
                }


            }

        },

        watch: {
            total: function (newTotal) {
                this.subtotal = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(newTotal) - parseFloat(this.discount));
            },
            subtotal: function (newSubtotal) {
                // this.net = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(this.tax) + parseFloat(newSubtotal));
                // this.discount = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(this.total) - parseFloat(newSubtotal));
            },
            tax: function (newTax) {
                this.net = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(this.subtotal) + parseFloat(newTax));
            },
            discount: function (newDiscount) {
                this.subtotal = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(this.total) - parseFloat(newDiscount));
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
</style>



<template>
    <!-- startup box -->
    <div class="message">


        <div class="panel-heading has-background-dark">
            <div class="columns">
                <div class="column">
                    <a class="button " href="/management/inventories/beginning"><i
                            class="fa fa-redo"></i>&nbsp;{{ translator.cancel }}</a>
                </div>

                <div class="column text-left">
                    <button @click="saveInvoiceButtonClicked" class="button is-primary "><i
                            class="fa fa-save"></i>&nbsp; {{ translator.save }}
                    </button>
                </div>

            </div>
        </div>
        <div class="message-body">
            <div class="has-background-light">
                <div class="columns">
                    <div class="column">
                        <!-- <label>client name</label> -->
                        <div class="" v-bind:class="{'is-danger':error=='client_id'}">
                            <div :class="{'has-error':errors.hasOwnProperty('vendor_id') }" class="input-group">
                                <span class="input-group-addon" id="vendors-list">{{ translator.vendor }}</span>
                                <input aria-describedby="time-field" class="form-control" disabled name="" readonly
                                       type="text" v-model="user.locale_name">


                            </div>
                            <p class="help is-danger is-center"
                               v-show="errors.hasOwnProperty('vendor_id')" v-text="errors.vendor_id"></p>


                        </div>
                    </div>

                    <div class="column">
                        <!-- <label>date</label> -->
                        <div class="input-group">
                            <span class="input-group-addon" id="time-field">{{ translator.date }}</span>
                            <input aria-describedby="time-field" class="form-control" disabled name="" readonly style=" direction: ltr
                            !important;"
                                   type="text" v-model="time">

                        </div>


                    </div>


                </div>


                <div>
                    <div class="columns">
                        <div class="column">
                            <!-- <label>client name</label> -->
                            <div class="">
                                <div :class="{'has-error':errors.hasOwnProperty('receiver_id') }" class="input-group">
                                    <span class="input-group-addon" id="receivers-list">{{ translator.receiver }}</span>
                                    <input aria-describedby="time-field" class="form-control" disabled name=""
                                           type="text" v-model="creator.locale_name">

                                </div>
                                <p class="help is-danger is-center"
                                   v-show="errors.hasOwnProperty('receiver_id')" v-text="errors.receiver_id"></p>
                            </div>
                        </div>

                        <div class="column">
                            <div class="input-group">
                                <span class="input-group-addon" id="department-field">{{ translator.department }}</span>
                                <input aria-describedby="department-field" class="form-control" disabled name=""
                                       readonly
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
                            <input :placeholder="translator.search_barcode"
                                   @keyup="clearError"
                                   @keyup.enter="findItems"
                                   class="input  " ref="search_input_ref" type="text"
                                   v-bind:class="{'is-danger':error=='items'}" v-model="search_field"/>
                            <p class="help is-danger is-center" v-show="error=='items'" v-text="errorMessage"></p>
                        </div>
                        <div class="live-vue-search">
                            <a :key="item.id" @click="addItemToList(item)" class="message-header has-background-primary"
                               href="#" v-for="item in itemsSearchList">
                                <h3 class="title">{{ item.locale_name }} <small class="has-text-white">{{ item.barcode
                                    }}</small></h3>
                                {{ item.price }}
                            </a>
                        </div>
                    </div>
                </div>


                <div class="column">
                    <a class="button is-info" href="/management/items?selectable=true&&is_purchase=true"
                       target="_blank">{{ translator.view_products
                        }}</a>

                </div>
                <div class="column">
                    <a class="button is-primary" href="/management/items/create" target="_blank">{{translator.create_product}}</a>
                </div>


            </div>


            <hr>
            <div class="table-container">
                <table class="table is-bordered text-center is-fullwidth is-narrow is-hoverable is-striped">
                    <thead class="has-background-dark ">
                    <tr>
                        <th class="has-text-white"></th>
                        <!-- <th class="has-text-white"></th> -->
                        <th class="has-text-white">{{ translator.barcode}}</th>
                        <th class="has-text-white" width="20%">{{ translator.item_name}}</th>
                        <th class="has-text-white" width="3%">{{ translator.qty}}</th>
                        <th class="has-text-white">{{ translator.price}}</th>
                        <th class="has-text-white">{{ translator.total}}</th>

                    </tr>
                    </thead>

                    <tbody>


                    <tr :key="item.id" v-for="(item,itemindex) in items">
                        <th class="has-text-white">

                            <button @click="deleteItemFromList(item)" class="button is-danger is-small"><i
                                    class="fa fa-trash"></i></button>
                            <button @click="show(itemindex,item)" class="button is-primary is-small"
                                    style="margin-left:10px"
                                    v-if="item.is_need_serial">
                                <i class="fa fa-plus"></i> &nbsp;
                            </button>

                            <item-serials-list-component
                                    :init_item_serial_list="item.serials"
                                    :isOpen="item.isOpen"
                                    :item="item" :item_index="itemindex"
                                    :key="item.id"
                                    @changed="updatedItemSerials"
                                    v-if="item.is_need_serial"></item-serials-list-component>
                        </th>
                        <!-- <th class="has-text-white"></th> -->
                        <th v-text="item.barcode"></th>
                        <th v-text="item.locale_name">item name</th>
                        <th width="6%">
                            <input
                                    @focus="$event.target.select()"
                                    @keyup="onChangeQtyField(item)" class="input" type="text"
                                    v-if="!item.is_need_serial"
                                    v-model="item.qty">
                            <p v-else>{{item.qty}}</p>
                        </th>
                        <th class="has-text-white">
                            <input
                                    @focus="$event.target.select()"
                                    @keyup="onChangePriceField(item)" class="input" type="text"
                                    v-model="item.purchase_price">

                        </th>
                        <th class="has-text-white">
                            <input @focus.native="$event.target.select()" class="input"
                                   disabled
                                   type="text"
                                   v-model="item.total">
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
                                {{ translator.invoice_data }}
                            </div>
                            <div class="message-body text-center">
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column">{{ translator.total }}</div>
                                        <div class="column">
                                            <input :placeholder="total" class="input" type="text" v-model="total">
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
    import printJS from 'print-js'


    export default {
        components: {
            printJS
        },

        props: ['creator', 'user'],
        data: function () {
            return {
                bc: new BroadcastChannel('item_barcode_copy_to_invoice'),
                translator: null,
                errors: new Map,
                document: '',
                vendor_inc_number: '',
                pdfLink: '',
                department: '',
                errorMessage: "",
                error: "",
                vendor: "",
                receiver: "",
                status: 'paid',
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


            // $("#pop_"+ this.item_index).fadeIn(1, function() {
            //     $("#serial_field_0").focus();
            // });

            // console.log('works..');
            this.vendor_inc_number = '0000';
            this.vendor = this.user;
            this.receiver = this.user;
            this.department = this.creator.department.title;
            this.timerLoop();
            this.translator = JSON.parse(window.translator);

        },
        mounted: function () {
            this.watchCopiedItems();

            this.$refs.search_input_ref.focus();
        },

        methods: {


            watchCopiedItems() {

                var vm = this;
                this.bc.onmessage = function (ev) {
                    if (ev.isTrusted) {
                        var item = JSON.parse(ev.data);
                        vm.addItemToList(item);
                    }
                }

            },


            clearError() {
                this.error = '';
            },
            setDocFile(e) {
                this.document = e.file;
                // console.log(e);
            },

            show(index, item) {
                //
                item.isOpen = !item.isOpen;
                //
                this.$modal.show('serialList_' + index);

                //
                this.items.splice(this.items.indexOf(item), 1, item);

                // console.log('hello');
            },


            updatedItemSerials(e) {
                //console.log(e);
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
                if (this.error == 'items') {
                    this.error = '';
                }
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
                                vm.itemsSearchList = null;
                                // console.log('full barcode');
                            } else if (response.data.length == 0) {
                                vm.error = 'items';
                                vm.$refs.search_input_ref.select();
                                vm.itemsSearchList = [];

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

                item.isOpen = false;

                if (helpers.checkIfObjectExistsOnArrayBYIdentifer(this.items, item.id)) {
                    var old_item = helpers.getDataFromArrayById(this.items, item.id);
                    if (!old_item.is_need_serial) {
                        old_item.qty = parseInt(old_item.qty) + 1;
                        this.onChangeQtyField(old_item); // update the product after increse the value of qty by one
                    }


                } else {
                    var added = this.callInitValuesForItem(item);

                    this.items.push(added); // add item after add  new props to the objecs like total,subtotal
                    this.updateInvoiceDetails();

                }

                this.itemsSearchList = []; // clear the search items list
                this.search_field = ""; /// clear the text on the search field
                this.$refs.search_input_ref.focus();// focus on the search field after make nice search
            },


            callInitValuesForItem(item) {
                item.qty = 1;
                if (item.is_need_serial) {
                    item.qty = 0;
                    item.serials = [];
                }

                item.purchase_price = item.last_p_price;

                item.total = item.qty * item.purchase_price;
                item.discount = 0;
                item.subtotal = item.total;
                item.tax = this.updateTaxForOneItem(item);
                item.net = this.updateNetForOneItem(item);
                item.variation = this.purchase_price >= 0 ? this.purchase_price : 0;
                item.temp_p_price = this.purchase_price >= 0 ? this.purchase_price : 0;


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


            },

            // helpers
            updateTotalForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.purchase_price * item.qty);
            },

            updateVariationForOneItem(item) {
                var vig = parseFloat(item.purchase_price) - parseFloat(item.temp_p_price);
                console.log(item.temp_p_price);
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(vig);
            },
            updateSubtotalForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.total - item.discount);
            },
            updateTaxForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(0);
            },
            updateNetForOneItem(item) {
                // return counting.convertVatToValue(taxRate) *
                var net = parseFloat(item.tax) + parseFloat(item.subtotal);
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(net);
            },
            updateItemInListBYindex(index, newItem) {

                this.items.splice(index, 1, newItem);
                // console.log('inside the update updateItemInListBYindex ' + newItem.serials);
            },

            updateInvoiceDetails() {
                this.total = helpers.getColumnSumationFromArrayOfObjects(this.items, 'total');
                this.discount = helpers.getColumnSumationFromArrayOfObjects(this.items, 'discount');
                this.subtotal = helpers.getColumnSumationFromArrayOfObjects(this.items, 'subtotal');
                this.tax = helpers.getColumnSumationFromArrayOfObjects(this.items, 'tax');
                this.net = helpers.getColumnSumationFromArrayOfObjects(this.items, 'net');
                this.remaining = this.net;
            },


            saveAndPrintInvoiceButtonClicked(e) {

            },

            validateInvoiceData() {
                return true;
            },
            saveInvoiceButtonClicked(e) {
                if (this.validateInvoiceData()) {
                    this.sendDataToServer();
                }

                // console.log('clicked..');
            },

            cancelInvoiceButtonClicked(e) {
                // window.print();
            },
            sendDataToServer() {
                // console.log(this.creator);
                var data_to = {
                    document: this.document,
                    vendor_id: this.vendor.id,
                    receiver_id: this.receiver.id,
                    items: this.items,
                    total: this.total,
                    subtotal: this.subtotal,
                    net: this.net,
                    creator_id: this.creator.id,
                    branch_id: this.creator.branch_id,
                    department_id: this.creator.department_id,
                    tax: this.tax,
                    invoice_type: 'beginning_inventory',
                    discount_value: this.discount,
                    discount_percent: this.discount,
                    remaining: 0,
                    current_status: 'paid',
                    issued_status: 'paid',
                    methods: this.methods,
                    vendor_inc_number: this.vendor_inc_number,
                };
                console.log(data_to);
                var vm = this;
                axios.post('/management/inventories/beginning', data_to)
                    .then(function (response) {
                        // console.log('works fine server response with good job..');

                        vm.pdfLink = response.data;
                        vm.startPrinting();
                        //
                        // print(response.data);
                        console.log(response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                        // vm.errors = error.response.data.errors;
                        // console.log(vm.errors);
                    });

            },
            startPrinting() {
                window.location.reload();
            },
            billingsUpdate(e) {

                if (parseFloat(this.remaining) > 0) {
                    this.status = 'paid';
                } else {
                    this.status = 'paid';
                }

                this.methods = e.methods;
                this.remaining = e.remaining;

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



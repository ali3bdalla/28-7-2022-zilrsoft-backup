<template>
    <!-- startup box -->
    <div class="message">


        <div class="panel-heading has-background-dark">
            <div class="columns">
                <div class="column">
                    <a class="button" href="/management/purchases"><i
                            class="fa fa-redo"></i>&nbsp;{{ translator.cancel }}</a>
                </div>

                <div class="column pull-right text-left">
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
                        <div class="">
                            <div class="input-group">
                                <span class="input-group-addon" id="vendors-list">{{ translator.vendor}}</span>
                                <input :value="user.name" class="form-control" readonly type="text">
                            </div>


                        </div>
                    </div>

                    <div class="column">
                        <!-- <label>date</label> -->
                        <div class="input-group">
                            <span class="input-group-addon" id="time-field">{{ translator.date}}</span>
                            <input :value="invoice.created_at" aria-describedby="time-field" class="form-control"
                                   name=""
                                   type="text">

                        </div>


                    </div>

                </div>


                <div>
                    <div class="columns">
                        <div class="column">
                            <!-- <label>client name</label> -->
                            <div class="input-group">
                                <span class="input-group-addon" id="creator">{{ reusable_translator.creator}}</span>
                                <input :value="creator.name" class="form-control" readonly type="text">
                            </div>
                        </div>

                        <div class="column">
                            <div class="input-group">
                                <span class="input-group-addon" id="department">{{ translator.department}}</span>
                                <input :value="department" class="form-control" readonly type="text">
                            </div>
                        </div>

                    </div>
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
                        <th class="has-text-white" width="3%">{{ translator.a_qty}}</th>
                        <th class="has-text-white" width="3%">{{ translator.r_qty}}</th>
                        <th class="has-text-white">{{ translator.price}}</th>
                        <th class="has-text-white">{{ translator.total}}</th>
                        <th class="has-text-white">{{ translator.discount}}</th>
                        <th class="has-text-white">{{ translator.subtotal}}</th>
                        <th class="has-text-white">{{ translator.tax}}</th>
                        <!--                        <th class="has-text-white">Tax.</th>-->
                        <th class="has-text-white">{{ translator.net}}</th>
                    </tr>
                    </thead>

                    <tbody>


                    <tr :key="item.id" v-for="(item,itemindex) in items">
                        <th class="has-text-white">
                            <item-serial-for-purchase-return-component
                                    :item="item.item"
                                    :itemindex="itemindex"
                                    :key="item.id"
                                    :seriallist="item.serials"
                                    @changed="updatedItemSerials"
                            >
                            </item-serial-for-purchase-return-component>

                        </th>
                        <th class="text-align:left !important" v-text="item.item.barcode"></th>
                        <th class="text-align: right !important;" v-text="item.item.locale_name"></th>
                        <th width="6%">
                            <input class="input" disabled readonly type="text"
                                   v-model="item.qty">

                        </th>
                        <th width="6%">
                            <input class="input" disabled readonly type="text"
                                   v-model="item.a_qty">

                        </th>

                        <th width="10px">
                            <input :class="{'is-danger':item.error=='error'}" :disabled="item.a_qty==0"
                                   :max="item.a_qty"
                                   @keyup="onReturnQtyChanged(item)"
                                   class="input"
                                   min='0' style="width:70px" type="number" v-if="!item.item.is_need_serial"
                                   v-model="item.returned_qty">
                            <p v-else>{{item.returned_qty}}</p>
                        </th>


                        <th class="has-text-white">
                            <input class="input" disabled type="text"
                                   v-model="item.price">

                        </th>
                        <th class="has-text-white">
                            <input class="input" disabled type="text" v-model="item.total">
                        </th>
                        <th class="has-text-white">
                            <input class="input" disabled placeholder="discount" type="text" v-model="item.discount">
                        </th>
                        <th class="has-text-white">
                            <input class="input" disabled placeholder="subtotal" readonly=""
                                   type="text" v-model="item.subtotal">
                        </th>
                        <!--                        <th class="has-text-white">-->
                        <!--                            <input class="input" disabled placeholder="vat sale" readonly="" type="text"-->
                        <!--                                   v-model="item.item.vtp + '%'">-->
                        <!--                        </th>-->
                        <th class="has-text-white">
                            <input class="input" disabled placeholder="tax" readonly="" type="text" v-model="item.tax">

                        </th>
                        <th class="has-text-white">
                            <input class="input" disabled placeholder="net" readonly="" type="text" v-model="item.net">
                        </th>

                    </tr>


                    </tbody>
                </table>
            </div>
<!--            <div class="form-group">-->
<!--                <div class="columns">-->
<!--                    <div class="column is-three-quarters"></div>-->
<!--                    <div class="column">-->
<!--                        <div class="card">-->

<!--                            <div class="message-body text-center">-->
<!--                                <div class="list-group-item">-->
<!--                                    <div class="columns">-->
<!--                                        <div class="column">{{ translator.total}}</div>-->
<!--                                        <div class="column">-->
<!--                                            <input class="input" disabled type="text" v-model="total">-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="list-group-item">-->
<!--                                    <div class="columns">-->
<!--                                        <div class="column">{{ translator.discount}}</div>-->
<!--                                        <div class="column">-->
<!--                                            <input class="input" disabled type="text" v-model="discount">-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--                                <div class="list-group-item">-->
<!--                                    <div class="columns">-->
<!--                                        <div class="column">{{ translator.subtotal}}</div>-->
<!--                                        <div class="column">-->
<!--                                            <input class="input" disabled readonly="" type="text" v-model="subtotal">-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="list-group-item">-->
<!--                                    <div class="columns">-->
<!--                                        <div class="column">{{ translator.tax}}</div>-->
<!--                                        <div class="column">-->
<!--                                            <input class="input" disabled type="text" v-model="tax">-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="list-group-item">-->
<!--                                    <div class="columns">-->
<!--                                        <div class="column">{{ translator.net}}</div>-->
<!--                                        <div class="column">-->
<!--                                            <input class="input" disabled type="text" v-model="net">-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->


<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->


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

                        </div>
                    </div>
                </div>
            </div>




            <!--            <div class="form-group">-->
            <!--                <div class="columns">-->
            <!--                    <div class="column">-->
            <!--                        <button @click="saveInvoiceButtonClicked" class="button is-primary is-medium is-fullwidth"><i-->
            <!--                                class="fa fa-print"></i>&nbsp; save and print-->
            <!--                        </button>-->
            <!--                    </div>-->

            <!--                </div>-->
            <!--            </div>-->

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

        props: ['creator', 'user', 'pitems', 'invoice', 'purchase', 'department', 'gateways'],
        data: function () {
            return {
                errorMessage: "",
                error: "",

                messages: [],
                translator: [],
                reusable_translator: [],
                status: 'credit',
                search_field: "",
                items: [],
                total: 0,
                discount: 0,
                tax: 0,
                net: 0,
                subtotal: 0,
                remaining: 0,
            };
        },
        created: function () {

            this.translator = JSON.parse(window.translator);
            this.messages = JSON.parse(window.messages);
            this.reusable_translator = JSON.parse(window.reusable_translator);


            this.initData();
        },

        methods: {


            updatedItemSerials(e) {
                var item = this.items[e.index];
                item.serials = e.serials;
                item.returned_qty = e.returned_qty;
                this.onReturnQtyChanged(item);
            },


            initData() {
                var len = this.pitems.length;

                for (var i = 0; i < len; i++) {
                    var item = this.pitems[i];
                    item.a_qty = parseInt(item.qty) - parseInt(item.r_qty);
                    item.init_discount = item.discount;
                    item.returned_qty = 0;
                    item.error = '';
                    item.total = 0;
                    item.subtotal = 0;
                    item.net = 0;
                    item.tax = 0;
                    this.items.push(item);
                }


            },


            /// events
            onReturnQtyChanged(item) {
                if (item.error == 'error') {
                    item.error = '';
                }


                if (!validate.isInteger(parseInt(item.returned_qty))) {
                    item.error = 'error';
                    return;
                }

                if (item.returned_qty > parseInt(item.qty)) {
                    item.error = 'error';
                    item.returned_qty = 0;
                    this.runUpdater(item);
                    return;
                }


                this.runUpdater(item);
            },


            runUpdater(item) {
                var index = this.items.indexOf(item);


                item.total = this.updateTotalForOneItem(item);
                item.discount = parseFloat(item.init_discount / item.qty * item.returned_qty).toFixed(2);
                item.subtotal = this.updateSubtotalForOneItem(item);

                item.tax = this.updateTaxForOneItem(item);
                item.net = this.updateNetForOneItem(item);
                item.r_qty = this.updateReturnQty(item);

                this.updateItemInListBYindex(index, item);
                this.updateInvoiceDetails();
                // console.log('item updated + ' + this);

            },
            updateTotalForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.price * item.returned_qty);
            },
            updateReturnQty(item) {
                if (item.returned_qty >= 1) {
                    return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.returned_qty + item.r_qty);
                }

                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.qty - item.r_qty);
            },


            updateSubtotalForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.total - item.discount);
            },
            updateTaxForOneItem(item) {
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(item.item.vtp * item.subtotal / 100);
            },
            updateNetForOneItem(item) {
                var net = parseFloat(item.tax) + parseFloat(item.subtotal);
                return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(net);
            },
            updateItemInListBYindex(index, newItem) {
                this.items.splice(index, 1, newItem);
            },

            updateInvoiceDetails() {
                this.total = this.getSum(this.items, 'total');
                this.discount = this.getSum(this.items, 'discount');
                this.subtotal = this.getSum(this.items, 'subtotal');
                this.tax = this.getSum(this.items, 'tax');
                this.net = this.getSum(this.items, 'net');
                this.remaining = this.net;
                // console.log(this.items);
            },


            getSum(arr = [], column) {
                var sum = 0;
                for (var i = arr.length - 1; i >= 0; i--) {
                    if (parseInt(arr[i].returned_qty) > 0)
                        sum = parseFloat(sum) + parseFloat(arr[i][column]);
                }

                // if(column=="net")
                //     return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(sum);

                return helpers.showOnlyTwoAfterComma(sum);
            },


            validateInvoiceData() {
                return true;
            },
            saveInvoiceButtonClicked(e) {
                if (this.validateInvoiceData()) {
                    this.sendDataToServer();
                }
            },


            sendDataToServer() {
                var data_to = {
                    items: this.items,
                    total: this.total,
                    subtotal: this.subtotal,
                    net: this.net,
                    tax: this.tax,
                    invoice_type: 'r_purchase',
                    discount_value: this.discount,
                    discount_percent: this.discount,
                    remaining: this.remaining,
                    current_status: this.status,
                    issued_status: this.status,
                    methods: this.methods
                };


                var vm = this;
                axios.put('/management/purchases/' + this.purchase.id, data_to)
                    .then(function (response) {


                        window.location.reload();
                    })
                    .catch(function (error) {

                        alert(error.response.data.message);
                        console.log(error.response.data);

                    });

            },

            billingsUpdate(e) {

                if (parseFloat(this.remaining) > 0) {
                    this.status = 'credit';
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



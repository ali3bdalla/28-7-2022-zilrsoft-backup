<template>
    <div>
        <div class="panel panel-primary">

            <div class="panel-body">
                <div :key="method.id" class="form-group" v-for="(method,index) in methods">
                    <div class="input-group">
                                        <span :id="method.id" class="input-group-addon" style="min-width:  130px
                                        !important;  font-weight: bolder;"
                                              v-if="method.is_default"
                                              v-text="method.name"></span>
                        <span :id="method.id" class="input-group-addon" style="min-width:  130px
                                        !important;  font-weight: bolder;" v-else v-text="method.name"
                        ></span>

                        <input

                                :aria-describedby="method.id"
                                :disabled="total_amount<=0"
                                :readonly="isCloseSTCPay"
                                :ref="'billing_filed_' + method.id"
                                @dblclick="openSTCPay"
                                @focus="$event.target.select()"
                                @keyup="handelPaidAmount(method,index)"
                                @keyup.enter="pushSaveInvoice('receipt')"
                                class="form-control onlyhidden"
                                style="font-weight:bolder"
                                type="text"
                                v-if="index==2"
                                v-model="method.amount"/>

                        <input
                                :aria-describedby="method.id"
                                :disabled="total_amount<=0"
                                :ref="'billing_filed_' + method.id"
                                @focus="$event.target.select()"
                                @keyup="handelPaidAmount(method,index)"
                                @keyup.enter="pushSaveInvoice('receipt')"
                                class="form-control"
                                style="font-weight:bolder"
                                type="text"
                                v-else
                                v-model="method.amount"/>
                    </div>
                </div>
            </div>

            <div class="panel-footer">
                <div class="columns">
                    <div class="column  text-center">
                        <button @click="pushSaveInvoice('receipt')" class="button is-primary is-large  is-fullwidth"
                                ref="save_invoice_button"
                                v-show="!error_in_remaing && !error_in_invoice && !error_paid">
                            <i class="fa fa-print"></i> &nbsp; حفظ وطباعة الايصال
                        </button>
                    </div>

                    <div class="column  text-center">
                        <button @click="pushSaveInvoice('a4')" class="button is-info is-large  is-fullwidth"
                                v-show="!error_in_remaing && !error_in_invoice && !error_paid">
                            <i class="fa fa-print"></i> &nbsp; حفظ وطباعة الفاتورة
                        </button>
                    </div>


                </div>


            </div>
        </div>
        <div class="padding:30px">

            <div :class="{'has-background-danger':parseFloat(total_remining)<parseFloat(0) || parseFloat(total_remining) > parseFloat(this.total_amount)}"
                 class="columns text-center ">
                <div class="column">
                    <div class="card">

                        <div class="card-content">
                            <span style="font-weight:bolder;font-size: 27px">الاجمالي</span>
                            <h1 class="title text-center">{{ total_amount }}</h1>
                        </div>
                    </div>
                </div>

                <div class="column">
                    <div class="card">

                        <div class="card-content">
                            <span style="font-weight:bolder;font-size: 27px">المدفوع</span>
                            <h1 class="title text-center">{{ total_paid }}</h1>
                        </div>
                    </div>
                </div>

                <!--                <div class="column">-->
                <!--                    <div class="card">-->

                <!--                        <div class="card-content">-->
                <!--                            <span style="font-weight:bolder;font-size: 27px">المتبقي</span>-->
                <!--                            <h1 class="title text-center">{{ unpaid }}</h1>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->


                <div class="column">
                    <div class="card">

                        <div class="card-content">
                            <span style="font-weight:bolder;font-size: 27px">المتبقي / آجل</span>
                            <h1 class="title text-center">
                                <input
                                        :class="{'is-danger':error_in_remaing,'has-error':error_in_remaing,'onlyhidden': isAllowedToMakeCredit}"
                                        :disabled="total_amount<=0 "
                                        :readonly="creditFieldIsOpen"
                                        @dblclick="openCreditField"
                                        @focus="$event.target.select()"
                                        @keyup="updateRemining"
                                        class="form-control"
                                        style="font-size: 32px;height: 36px;"
                                        type="text"
                                        v-model="total_remining" v-show="isAllowedToMakeCredit"/>


                                <input
                                        :class="{'is-danger':error_in_remaing,'has-error':error_in_remaing,'onlyhidden': isAllowedToMakeCredit}"
                                        :readonly="creditFieldIsOpen"
                                        @dblclick="openCreditField"
                                        @focus="$event.target.select()"
                                        @keyup="updateRemining"
                                        class="form-control"
                                        disabled
                                        style="font-size: 32px;height: 36px;"
                                        type="text"
                                        v-model="total_remining" v-show="!isAllowedToMakeCredit"/>


                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--        </modal>-->
    </div>
</template>
<script>
    export default {
        props: ['netAmount', 'gateways', 'button_counter', 'current_items_net', 'disable_button_counter', 'user'],
        data: function () {
            return {

                unpaid: 0,
                error_paid: true,
                error_in_invoice: true,
                isAllowedToMakeCredit: true,
                creditFieldIsOpen: true,
                error_in_remaing: true,
                is_credit_mode: false,
                isCloseSTCPay: true,
                default_index: 3,
                total_amount: 0,
                total_paid: 0,
                total_remining: 0,
                methods: [],
                last_selected_field_ref: ''
            };
        },
        created: function () {
            for (var i = 0; i < this.gateways.length; i++) {
                var method = this.gateways[i];
                method.amount = 0;

                this.methods.push(method);
            }

            if (this.user != null) {
                this.isAllowedToMakeCredit = this.user.can_make_credit == 1 ? true : false;
            }

            this.total_amount = this.netAmount;
        },

        methods: {


            openCreditField() {

                this.creditFieldIsOpen = false;
            },
            updateRemining(e) {


                if (this.creditFieldIsOpen == false) {
                    this.error_in_remaing = false;
                    this.is_credit_mode = true;
                    this.total_paid = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(this.total_amount - this.total_remining);


                    this.methods[0].amount = this.total_paid;
                    this.methods[1].amount = 0;
                    this.methods[1].amount = 0;


                    this.validatePayment();
                    // if (parseFloat(this.total_remining) < 0 ) {
                    //     this.error_in_remaing = true;
                    // }


                }


            },

            openSTCPay() {
                this.isCloseSTCPay = false;
            },

            show() {
                this.$modal.show('billing-panel');
            },
            hide() {
                this.$modal.hide('billing-panel');
            },


            showPopMessage(message) {


                let options = {
                    html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
                    loader: false, // set to true if you want the dailog to show a loader after click on "proceed"
                    reverse: false, // switch the button positions (left to right, and vise versa)
                    okText: 'متابعة',
                    cancelText: 'الغاء',
                    animation: 'bounce', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                    verification: 'continue', // for hard confirm, user will be prompted to type this to enable the proceed button
                    verificationHelp: 'Type "[+:verification]" below to confirm', // Verification help text. [+:verification] will be matched with 'options.verification' (i.e 'Type "continue" below to confirm')
                    clicksCount: 3, // for soft confirm, user will be asked to click on "proceed" btn 3 times before actually proceeding
                    backdropClose: false, // set to true to close the dialog when clicking outside of the dialog window, i.e. click landing on the mask
                    customClass: '' // Custom class to be injected into the parent node for the current dialog instance
                };


                this.$dialog
                    .confirm('سوف يتم حفظ الفاتورة', options)
                    .then(dialog => {

                        this.emitSaveEvent(message);
                    })
                    .catch(() => {

                    });
            },


            emitSaveEvent(event) {

                this.$emit('saveInvoice', {
                    event: event
                });

            },
            pushSaveInvoice(type) {
                this.showPopMessage(type);

            },


            handelPaidAmount(method, index) {

                // this.error_in_remaing = false;
                // if (!this.is_credit_mode) {
                //     if (index == 0) {
                //         this.methods[2].amount = 0;
                //         if (method.amount != '' && validate.isNumber(parseFloat(method.amount))) {
                //             this.methods[1].amount = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(this.total_amount) - parseFloat(method.amount));
                //         } else {
                //             this.methods[1].amount = parseFloat(this.total_amount);
                //         }
                //     }
                //     if (index == 1) {
                //         this.methods[2].amount = 0;
                //         if (method.amount != '' && validate.isNumber(parseFloat(method.amount))) {
                //             this.methods[0].amount = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(this.total_amount) - parseFloat(method.amount));
                //         } else {
                //             this.methods[0].amount = parseFloat(this.total_amount);
                //         }
                //     }
                // }
                //

                var len = this.methods.length;

                var paid = 0;


                for (var i = 0; i < len; i++) {
                    var method = this.methods[i];
                    if (validate.isNumber(parseFloat(method.amount))) {
                        paid += parseFloat(method.amount);
                    }
                }

                this.total_paid = parseFloat(paid);
                this.total_remining = helpers.roundTheFloatValueTo2DigitOnlyAfterComma(parseFloat(this.total_amount)
                    - parseFloat(this.total_paid));


                this.calcUpaid();

                if (this.validatePayment()) {
                    this.$emit("billingsUpdate", {
                        methods: this.methods,
                        remaining: this.total_remining,
                    });
//
                }


                // console.log(this.methods);


                //              }


            },

            putReminingOnTheLastSelectedField() {
                var ref = this.last_selected_field_ref;
                this.$refs[ref][0].value = this.total_remining;
            },


            validatePayment() {

                var any_error = false;
                var total = this.total_amount;
                var paid = 0;
                var unpaid = 0;
                var len = this.methods.length;
                for (var i = 0; i < len; i++) {
                    var method = this.methods[i];



                   if (i != 0) {

                        // console.log(parseFloat(method.amount));

                        if (parseFloat(method.amount) > parseFloat(this.total_amount)) {
                            this.error_paid = true;
                            any_error = true;


                            // alert('hello');
                        }


                   }


                    if (parseFloat(method.amount) < parseFloat(0))
                        any_error = true;


                    if (parseFloat(method.amount) < 0)
                        any_error = true;


                    if (!helpers.isNumber(parseFloat(method.amount)))
                        any_error = true;


                    paid = paid + parseFloat(method.amount);
                }


                unpaid = parseFloat(total) - paid;


                if (!helpers.isNumber(parseFloat(paid)) || !helpers.isNumber(parseFloat(unpaid)) ||
                    !helpers.isNumber(parseFloat(total)))
                    any_error = true;


                if (parseFloat(paid) < parseFloat(0))
                    any_error = true;


                this.error_in_remaing = any_error;


                this.error_paid = any_error;



                return !any_error;


            },

            calcUpaid() {
                if (!this.isAllowedToMakeCredit) {
                    this.unpaid = parseFloat(this.total_paid) - parseFloat(this.total_amount);


                    if (parseFloat(this.unpaid) < 0) {
                        this.error_paid = true;
                        this.error_in_remaing = true;
                    } else
                        this.error_paid = false;
                }


                // console.log(this.total_remining);
                    this.validatePayment();

            }
        },

        watch: {

            total_remining: function (value) {

                if (parseFloat(value) > parseFloat(this.total_amount)) {
                    this.error_in_remaing = true;
                }

                var t = parseFloat(value) + parseFloat(this.total_paid);
                if (t <= parseFloat(this.total_amount)) {
                    this.error_in_remaing = false;
                } else {
                    this.error_in_remaing = true;
                }

                this.calcUpaid();
            },


            user: function (value) {

                this.isAllowedToMakeCredit = value.can_make_credit == 1 ? true : false;


            },
            error_in_remaing: function (value) {
                this.$emit('errorInPayment', {
                    error: value
                })
            },
            button_counter: function (value) {
                this.$refs['save_invoice_button'].focus();
            },

            disable_button_counter: function (value) {


                this.error_in_invoice = value;

                this.validatePayment();

            },


            netAmount: function (value) {

                this.methods[0].amount = 0;
                this.methods[1].amount = 0;
                this.methods[2].amount = 0;
                this.total_amount = value;
                if (this.is_credit_mode) {
                    if (this.total_remining < 0) {
                        this.error_in_remaing = true;
                        this.methods[0].amount = this.total_paid;
                    }
                }
                var method = this.methods[0];
                method.amount = value;
                this.handelPaidAmount(method, 0);

                var real_net = parseFloat(helpers.roundTheFloatValueTo2DigitOnlyAfterComma(value));
                if (real_net <= this.current_items_net) {
                    this.error_in_remaing = false;
                } else {
                    this.error_in_remaing = true;
                }


                this.calcUpaid();
            }
        }
    }
</script>


<style>
    .dg-btn--cancel {

        float: left;
        background-color: white;
        color: #777;
        border: 2px solid #777;
        /*//  margin-right: 15px !important;*/
    }


    .onlyhidden {
        font-weight: bolder;
        background: #d6e4d6 !important;
    }


    input {
        direction: ltr !important;
    }
</style>
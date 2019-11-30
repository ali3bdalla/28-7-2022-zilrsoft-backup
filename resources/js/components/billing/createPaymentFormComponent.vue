<template>
    <div>
        <!--        <div class="columns">-->
        <!--            <div class="column">-->
        <div class="box">
            <h5>{{translator.amount}} : {{ total_paid_amount}}</h5>
        </div>

        <div class="box">

            <div class="columns">
                <custom-select-component
                        :options="voucherTypes"
                        :placeholder="translator.type"
                        :title="translator.type"
                        @valueUpdated="voucherTypesListUpdated"
                        default="1"
                        identity="2"
                >

                </custom-select-component>

                <custom-select-component
                        :default="user_id"
                        :options="vendors"
                        :placeholder="translator.select_vendor"
                        :title="translator.vendor"
                        @valueUpdated="userListUpdated"
                        identity="1"
                >
                </custom-select-component>


                <div class="column" v-if="voucherType_id==1">
                    <div class="filter_area">
                        <div class="input-group">
                            <span class="input-group-addon has-background-primary has-text-white">قيمة المبلغ</span>
                            <input
                                    autocomplete="value_paid_amount"
                                    class="form-control"
                                    placeholder="قيمة المبلغ المقبوض"
                                    type="text"
                                    v-model="total_paid_amount"
                            />
                        </div>
                    </div>
                </div>

                <custom-billing-invoices-component
                        :user="user"
                        @invoicesUpdated="invoicesListUpdated"
                        identity="3"
                        placeholder="اختر  الفواتير المراد سدادها"
                        title="الفواتير"
                        type="purchase"
                        v-if="voucherType_id==0"
                ></custom-billing-invoices-component>


            </div>


            <div class="columns">
                <custom-select-component
                        :options="organization_gateways"
                        @valueUpdated="gatewaysListUpdated"
                        identity="4"
                        placeholder="اختر وسيلة الدفع"
                        title="وسيلة الدفع"
                        v-if="showGatewaysList"
                ></custom-select-component>


            </div>


            <div class v-if="showOrganizationDataForTransferGateway">
                <div class="columns">
                    <div class="column">حساب المنشأة (المحول منه)</div>
                </div>
                <div class="columns">
                    <custom-select-component
                            :options="organization_banks"
                            @valueUpdated="organizationBanksListUpdated"
                            identity="7"
                            placeholder="اختر من قائمة البنوك"
                            title_class="has-background-link has-text-white"
                    ></custom-select-component>

                    <custom-select-component
                            :options="organization_accounts"
                            @valueUpdated="organizationAccountUpdated"
                            identity="8"
                            label_text="account"
                            placeholder="اختر الحساب"
                            title_class="has-background-link has-text-white"
                            v-if="organization_accounts.length>=1"

                    ></custom-select-component>
                </div>
            </div>


            <div class="" v-if="showUserDataForTransferGateway">
                <div class="columns">
                    <div class="column">حساب المورد (المحول له)</div>
                </div>
                <div class="columns">
                    <custom-select-component
                            :options="user_banks"
                            @valueUpdated="userBanksListUpdated"
                            identity="5"
                            placeholder="اختر من قائمة البنوك"
                            v-if="user_banks.length>=1"
                    ></custom-select-component>


                    <custom-select-component
                            :options="user_accounts"
                            @valueUpdated="userAccountUpdated"
                            default-index="1"
                            identity="6"
                            label_text="account"
                            placeholder="اختر الحساب"
                            v-if="user_accounts.length>=1"

                    ></custom-select-component>
                </div>
            </div>

            <!--        <hr>-->


            <div class="columns" v-if="showPayPalFields">
                <div class="column">
                    <input class="input" placeholder="ايميل الحساب" type="text" v-model="AccountName"/>
                </div>
                <div class="column">
                    <input class="input" placeholder="الرقم المرجعي" type="text" v-model="AccountNumber"/>
                </div>
            </div>


            <div class="columns" v-if="showChaqueFields">
                <custom-select-component
                        :options="countryBanks"
                        @valueUpdated="banksListUpdated"
                        identity="9"
                        placeholder="اختر من قائمة البنوك"
                ></custom-select-component>
                <div class="column">
                    <input class="input" placeholder="رقم الشيك" type="text" v-model="AccountName"/>
                </div>
                <div class="column">
                    <input class="input" placeholder="الاسم " type="text" v-model="AccountNumber"/>
                </div>


            </div>


            <div class="columns" v-if="showSTCPayFields">

                <div class="column">
                    <input class="input" placeholder="الرقم المرجعي" type="text" v-model="AccountName"/>
                </div>


            </div>


            <div class="form-group" v-show="showSubmitButton">
                <div class="columns">
                    <div class="column">
                        <button @click="validateDataAndSendItToServer" class="button is-primary">انشاء السند</button>
                    </div>
                    <div class="column">
                        <a class="button" href="/management/billings">الغاء العملية</a>
                    </div>
                </div>
            </div>


        </div>

    </div>

</template>
<script>
    export default {
        props: ["organization_gateways", "clients", "vendors", "countryBanks", 'organizationBanks', 'allbanks'],
        data: function () {
            return {
                showPayPalFields: false,
                showCashFields: false,
                showSTCPayFields: false,
                showChaqueFields: false,
                showUserDataForTransferGateway: false,
                showOrganizationDataForTransferGateway: false,
                showSubmitButton: false,
                total_paid_amount: 0,
                user: null,
                showVoucherTypes: false,
                voucherTypes: [],
                user_id: 0,
                voucherType_data: null,
                voucherType_id: null,
                showGatewaysList: false,
                invoices: [],
                gateway_data: null,
                is_need_bank: false,
                gateway_id: 0,
                user_banks: [],
                user_accounts: [],
                organization_banks: [],
                organization_accounts: [],
                user_account_id: 0,
                organization_account_id: 0,

                AccountNumber: '',
                AccountName: '',


                translator: null,
                messages: null,
                reusable_translator: null

            };
        },
        created: function () {
            // console.log(this.allbanks);
            // console.log(this.user_id)


            this.translator = JSON.parse(window.translator);
            this.messages = JSON.parse(window.messages);
            this.reusable_translator = JSON.parse(window.reusable_translator);


            this.voucherTypes = [{
                id: 0,
                type: 'invoice',
                name: this.translator.invoices_payment
            }, {
                id: 1,
                type: 'balance',
                name: this.translator.advance_payment
            }];
        },
        methods: {

            /*
            * @step 1 set user in data to the select user
            * @step 2 update  ui show the voucher types list
            * */
            userListUpdated(e) {
                this.user = e.value;//step 1
                this.showVoucherTypes = true;//step 2
            },

            /*
            *@step 1 set user in data to the select user
            * @update ui by showing the target type [text box on balance , and invoices in invoice option]
            * */
            voucherTypesListUpdated(e) {
                // this.user_id = this.vendors[0].id;
                this.voucherType_data = e.value;
                this.voucherType_id = e.value.id;
            },

            /*
            *
            * */
            invoicesListUpdated(e) {

                if (this.user == null) {
                    var localInvoices = [];
                    var len = e.invoices.length;
                    this.total_paid_amount = 0;
                    for (var i = 0; i < len; i++) {
                        var one = e.invoices[i];
                        if (one.isChecked) {
                            this.total_paid_amount += one.invoice.remaining;
                            localInvoices.push(one);
                        }
                    }
                    this.invoices = localInvoices;
                    this.user = e.user;
                    this.user_id = e.user.id;

                } else {
                    var localInvoices = [];
                    var len = e.invoices.length;
                    this.total_paid_amount = 0;
                    for (var i = 0; i < len; i++) {
                        var one = e.invoices[i];
                        if (one.isChecked) {
                            this.total_paid_amount += one.invoice.remaining;
                            localInvoices.push(one);
                        }
                    }
                    this.invoices = localInvoices;
                }


            },


            /*
            *
            *   just handle the detected gateway
            * */
            gatewaysListUpdated(e) {
                console.log(e.value.id);
                this.gateway_data = e.value;
                this.gateway_id = e.value.id;
                if (e.value.id == 1 || e.value.id == 3) {
                    this.showCashFields = true;
                    this.handleCashGateway(e);

                } else if (e.value.id == 2) {

                    this.handleTransferGateway(e);
                } else if (e.value.id == 6) {
                    this.handlePayPalGateway(e);
                } else if (e.value.id == 5) {
                    this.handleChaqueGateway(e);
                } else if (e.value.id == 4) {
                    this.handleSTCPayGateway(e);
                }


            },


            /*
            * handle cash gateway
            * show submit button direct
            * */
            handleCashGateway(e) {
                this.showSubmitButton = true;
            },


            /*
            * handle transfer gateway
            * show submit button direct
            * */
            handleTransferGateway(e) {
                // var user_accounts = this.user.accounts;

                this.showOrganizationDataForTransferGateway = true;
                this.organization_banks = this.organizationBanks;

            },


            /*
            * @show user account at this banks
            * */
            userBanksListUpdated(e) {
                this.user_accounts = e.value.user_accounts;
            },


            /*
           * @show user account at this banks
           * */
            userAccountUpdated(e) {

                this.user_account_id = e.value.id;
                this.showSubmitButton = true;
            },


            /*
          * @show organization account at this banks
          * */
            organizationBanksListUpdated(e) {
                this.organization_accounts = e.value.organization_accounts;

            },


            /*
           * @show organization account at this banks
           * */
            organizationAccountUpdated(e) {
                this.showUserDataForTransferGateway = true;
                this.user_banks = this.user.banks;

                this.organization_account_id = e.value.id;

            },


            /*
            * handle PayPal Gateway
            * */
            handlePayPalGateway(e) {
                this.showPayPalFields = true;

            },


            /*
             * handle transfer gateway
             * show submit button direct
             * */
            handleChaqueGateway(e) {
                this.showChaqueFields = true;

            },


            /*
             * handle transfer gateway
             * show submit button direct
             * */
            banksListUpdated(e) {
                this.bank_id = e.value.id;
            },


            /*
             * handle transfer gateway
             * show submit button direct
             * */
            handleSTCPayGateway(e) {
                this.showSTCPayFields = true;
            },


            /*
            * @step 1 validate if all fields are the right fields
            * @step 2 send data to server
            * */
            validateDataAndSendItToServer() {
                this.fireRequest();
            },


            /*
            *
            * fire request
            *
            * */
            fireRequest() {

                var data = {
                    gateway_id: this.gateway_id,
                    user_account_id: this.user_account_id,
                    organization_account_id: this.organization_account_id,
                    invoices: this.invoices,
                    amount: this.total_paid_amount,
                    voucher_type: this.voucherType_data.type,
                    bank_id: this.bank_id,
                    account: this.AccountName,
                    account_name: this.AccountNumber,
                    user_id: this.user.id,
                };


                var vm = this;
                axios.post("/management/payments/create/store_payment", data)
                    .then(response => {
                        window.location = '/management/payments/' + response.data.id + '?do=print';
                        console.log(response.data);
                    });


            }
        },


        watch: {


            showPayPalFields: function (value) {
                if (value) {
                    this.showCashFields = false;
                    this.showSubmitButton = false;
                    this.showSTCPayFields = false;
                    this.showChaqueFields = false;
                    this.showUserDataForTransferGateway = false;
                    this.showOrganizationDataForTransferGateway = false;
                }
            },
            showSTCPayFields: function (value) {
                if (value) {
                    this.showCashFields = false;
                    this.showSubmitButton = false;
                    this.showPayPalFields = false;
                    this.showChaqueFields = false;
                    this.showUserDataForTransferGateway = false;
                    this.showOrganizationDataForTransferGateway = false;
                }
            },
            showChaqueFields: function (value) {

                if (value) {
                    this.showCashFields = false;
                    this.showSubmitButton = false;
                    this.showSTCPayFields = false;
                    this.showPayPalFields = false;
                    this.showUserDataForTransferGateway = false;
                    this.showOrganizationDataForTransferGateway = false;
                }
            },

            showCashFields: function (value) {
                if (value) {
                    this.showSubmitButton = true;
                    this.showSTCPayFields = false;
                    this.showChaqueFields = false;
                    this.showPayPalFields = false;
                    this.showUserDataForTransferGateway = false;
                    this.showOrganizationDataForTransferGateway = false;
                }
            },
            showUserDataForTransferGateway: function (value) {
                if (value) {
                    this.showCashFields = false;
                    this.showSubmitButton = false;
                    this.showSTCPayFields = false;
                    this.showChaqueFields = false;
                    this.showPayPalFields = false;
                    //
                } else {
                    this.showOrganizationDataForTransferGateway = false;
                }
            },
            // showOrganizationDataForTransferGateway: function (value) {
            //     if(value)
            //     {
            //         this.showSubmitButton = false;
            //         this.showSTCPayFields = false;
            //         this.showChaqueFields = false;
            //         this.showUserDataForTransferGateway = false;
            //         this.showPayPalFields = false;
            //     }
            // },

            total_paid_amount: function (value) {
                if (value >= 1) {
                    this.showGatewaysList = true;
                } else {
                    this.showGatewaysList = false;
                }
            },

            AccountNumber: function (value) {
                if (value != '' && this.AccountName != '') {
                    this.showSubmitButton = true;
                } else {
                    this.showSubmitButton = false;
                }
            },


            AccountName: function (value) {

                if (value != '' && this.AccountNumber != '') {
                    this.showSubmitButton = true;
                } else {
                    this.showSubmitButton = false;
                }

                if (value != '' && this.gateway_id == 4) {
                    this.showSubmitButton = true;
                }


            },


        }
    }
</script>
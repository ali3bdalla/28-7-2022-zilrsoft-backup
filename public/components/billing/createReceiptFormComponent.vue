<template>
    <div>
        <div class="columns">
            <div class="column">
                <div class="box">
                    <h5>المبلغ الكلي : {{ total_paid_amount}}</h5>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="text-center " v-show="error!=''">{{ error }}</div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="filter_area">
                    <div class="input-group">
                        <span class="input-group-addon has-background-primary has-text-white">سند</span>
                        <input
                                autocomplete="search_field"
                                class="form-control"
                                placeholder="نوع السند"
                                readonly
                                type="text"
                                value="سند قبض"
                        />
                    </div>
                </div>
            </div>

            <custom-select-component
                    :options="clients"
                    @listFocused='clientStartingSelection'
                    @valueUpdated="clientWasSelected"
                    identity="1"
                    placeholder="اختر العميل"
                    title="العميل">
            </custom-select-component>
        </div>
        <div class="columns" v-show="client_data!=null">

            <custom-select-component
                    :options="receipt_options"
                    @valueUpdated="receiptTypesWasUpdated"
                    identity="2"
                    placeholder="اختر نوع العملية"
                    title="نوع عملية القبض"
                    v-if="work_step>=1"
            >

            </custom-select-component>


            <div class="column" v-show="receipt_selected_option==1 && work_step>=2">
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
                    :options="receipt_options"
                    :user="client_data"
                    @invoicesUpdated="salesInvoicesUpdated"
                    identity="3"
                    placeholder="اختر  الفواتير المراد سدادها"
                    title="الفواتير"
                    type="purchase"
                    v-if="receipt_selected_option==0  && work_step>=2"
            ></custom-billing-invoices-component>

        </div>
        <div class="columns" v-if="total_paid_amount>=1 && work_step>=3">
            <custom-select-component
                    :options="organization_gateways"
                    @valueUpdated="gatewayHadUpdated"
                    identity="4"
                    placeholder="اختر وسيلة الدفع"
                    title="وسيلة الدفع"
            ></custom-select-component>


<!--            <custom-select-component-->
<!--                    :options="abanks"-->
<!--                    @valueUpdated="banksListHadUpdated"-->
<!--                    identity="10"-->
<!--                    placeholder="اختر البنك "-->
<!--                    v-if="is_need_bank"></custom-select-component>-->


        </div>
        <div class v-if="user_sub_methods.length>0 && work_step>=4">
            <div class="columns">
                <div class="column">حساب العميل (المحول منه)</div>
            </div>
            <div class="columns">
                <custom-select-component
                        :options="user_sub_methods"
                        @valueUpdated="userSubPayMethodUpdated"
                        identity="5"
                        placeholder="اختر من القائمة"
                ></custom-select-component>

                <custom-select-component
                        :options="user_accounts"
                        @valueUpdated="userAccountUpdated"
                        identity="6"
                        label_text="account"
                        placeholder="اختر الحساب"
                        v-if="user_accounts.length>0 && work_step>=5"
                ></custom-select-component>
            </div>
        </div>
        <div class v-if="organization_sub_methods.length>0 && work_step>=6">
            <div class="columns">
                <div class="column">حساب المنشأة (المحول له)</div>
            </div>
            <div class="columns">
                <custom-select-component
                        :options="organization_sub_methods"
                        @listFocused="startChangeOrganizationSubMethod"
                        @valueUpdated="organizationSubMethodUpdated"
                        identity="7"
                        placeholder="اختر من القائمة"
                        title
                ></custom-select-component>

                <custom-select-component
                        :options="organization_accounts"
                        @valueUpdated="organizationAccountUpdated"
                        identity="8"
                        label_text="account"
                        placeholder="حساب المنشأة"
                        title
                        v-if="organization_accounts.length>0"
                ></custom-select-component>
            </div>
        </div>
        <div class="form-group" v-if="showSubmitButton && work_step>=5 || is_cash">
            <hr/>
            <div class="columns">
                <div class="column">
                    <button @click="submitDataToServer" class="button is-primary">انشاء السند</button>
                </div>
                <div class="column">
                    <a class="button" href="/management/billings">الغاء العملية</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import printJS from 'print-js'

    export default {
        components: {
            printJS
        },
        props: ["organization_gateways", "clients", "vendors", "abanks"],
        data: function () {
            return {
                is_cash: false,
                error: '',
                work_step: 0,
                client_data: {id: 0},
                total_paid_amount: 0, // total amount of paid value
                main_pay_method: {id: 0},
                user_selected_sub_method: {id: 0},
                user_selected_account: {id: 0},
                organization_selected_sub_method: {id: 0},
                organization_selected_account: {id: 0},
                showSubmitButton: false,
                user_sub_methods: [],
                user_accounts: [],
                organization_sub_methods: [],
                organization_accounts: [],
                invoices: [],
                receipt_selected_option: null,
                receipt_type: null,
                receipt_options: [
                    {
                        id: 0,
                        type: 'invoice',
                        name: "تسديد فواتير"
                    },
                    {
                        id: 1,
                        type: 'balance',
                        name: " دفعة مقدمة"
                    }
                ],




                is_need_bank:false,
                fields:[],
                banks:[],
                disableGatewayFields:true,
                disableSubmitButton:true
            };

        },
        methods: {

            gatewayHadUpdated(e) {
                this.gateway_data = e;
                this.fields = [];
                this.disableGatewayFields = true;
                if (e.value.is_need_banks) {
                    this.is_need_bank = true;

                } else {
                    this.is_need_bank = false;
                    this.disableGatewayFields = false;
                    this.showFields(e);
                }
                this.gateway_id = e.value.id;
            },

            showFields(e)
            {
                if(e.value.is_has_fields)
                {
                    this.fields = e.value.fields;
                }else
                {
                    this.disableSubmitButton = false;
                }

            },

            banksListHadUpdated(e)
            {
                this.bank_id = e.value.id;
                // this.disableGatewayFields = false;
                this.showFields(this.gateway_data);
            },








            printFile(html) {

                printJS({
                        modalMessage: 'Printing...',
                        css: '/template/css/payment.css',
                        showModal: true,
                        printable: html,
                        type: 'raw-html',
                        scanStyles: true,
                        onPrintDialogClose: () => window.location.reload(),
                        onPdfOpen: () => console.log('Pdf was opened in a new tab due to an incompatible browser')
                    }
                );
            },
            salesInvoicesUpdated(e) {
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
            },
            // client methods
            clientStartingSelection() {

            },
            clientWasSelected(e) {
                this.client_data = e.value;
                this.work_step = 1;
                this.total_paid_amount = 0;
            },
            // TypeOfPayment type
            receiptTypesWasUpdated(e) {
                this.receipt_selected_option = e.value.id;
                this.receipt_type = e.value.type;
                this.work_step = 2;
                this.total_paid_amount = 0;
                this.invoices = [];
            },





            // pay way
            // mainPayMethodWasUpdated(e) {
            //
            //
            //
            //
            //     // this.is_cash = false;
            //     // if (!e.value.is_need_account && !e.value.is_has_children) {
            //     //     this.is_cash = true;
            //     // } else {
            //     //     this.main_pay_method = e.value;
            //     //     this.loadUserPaymentMethods(this.client_data.id, e.value.id);//this.client_data.id
            //     //     this.work_step = 4;
            //     // }
            //
            // },
            // client payment methods
            loadUserPaymentMethods(user_id = 0, method_id = 0) {
                var vm = this;
                axios
                    .get("/management/users/" + user_id + "/" + method_id + "/accounts")
                    .then(response => {
                        var data = response.data;
                        if (data["children"].length > 0) {
                            vm.user_sub_methods = data["children"];
                        } else if (data["accounts"].length > 0) {
                            vm.work_step = 5;
                            vm.user_accounts = data["accounts"];
                        } else {
                            vm.is_cash = true;
                            vm.error = 'لا توجد ' + data.name + ' لهذا العميل الخاصة بك قم باضافتها اولا';
                        }
                    });
            },
            userSubPayMethodUpdated(e) {
                this.user_accounts = e.value.accounts;
                this.user_selected_sub_method = e.value;
                this.work_step = 5;
            },
            userAccountUpdated(e) {
                this.organization_sub_methods = [];
                this.orgainzation_accounts = [];
                this.work_step = 6;
                this.user_selected_account = e.value;
                this.loadOrganizationPayWaysWithAccounts(this.main_pay_method.id);
            },
            // organization payments
            loadOrganizationPayWaysWithAccounts(method_id = 0) {
                var vm = this;
                axios
                    .get("/management/organizations/" + method_id + "/accounts")
                    .then(response => {
                        var data = response.data;
                        if (data["children"].length > 0) {
                            vm.organization_sub_methods = data["children"];
                        } else if (data["accounts"].length > 0) {
                            vm.work_step = 7;
                            vm.orgainzation_accounts = data["accounts"];
                        } else {
                            vm.error = 'لا توجد ' + data.name + ' للمنشاءة الخاصة بك قم باضافتها اولا';
                        }


                    });
            },
            organizationSubMethodUpdated(e) {
                this.organization_selected_sub_method = e.value;
                this.organization_accounts = e.value.accounts;
            },
            startChangeOrganizationSubMethod(e) {
                this.organization_accounts = [];
            },
            organizationAccountUpdated(e) {
                this.organization_selected_account = e.value;
                this.showSubmitButton = true;
            },
            submitDataToServer() {
                var data = {
                    invoices: this.invoices,
                    user_id: this.client_data.id,
                    pay_method: this.main_pay_method.id,
                    organization_method_id: this.organization_selected_sub_method.id,
                    organization_account_id: this.organization_selected_account.id,
                    user_method_id: this.user_selected_sub_method.id,
                    user_account_id: this.user_selected_account.id,
                    receipt_type: this.receipt_type,
                    amount: this.total_paid_amount
                };
                var vm = this;
                axios.post("/management/payments/create/store_receipt", data)
                    .then(response => {
                        vm.printFile(response.data);

                    });
                // var data = response.data;
                //
            }


        },
        watch: {
            total_paid_amount: function (value) {
                if (value == 0) {
                    this.work_step = 2;
                    this.main_pay_method = null;
                } else {
                    this.work_step = 3;
                }
            }
        }
    };
</script>

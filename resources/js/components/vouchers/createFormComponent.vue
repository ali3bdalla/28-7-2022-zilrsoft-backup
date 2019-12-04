<template>
    <div>
        <div class="box">
            <h5>{{translator.amount}} : {{ total_paid_amount}}</h5>
            <div v-if="payment_type=='payment'">
                <div class="columns">

                    <div :dir="rtl ? 'rtl' : 'ltr'" class="column" style=" padding: 14px;">
                        <div>
                            <treeselect
                                    :options="accounts"
                                    :value="init_org_account"
                                    @select="organization_account_selected"
                                    class="input"
                                    placeholder="اختر حساب المؤسسة"
                            ></treeselect>
                        </div>
                    </div>

                </div>

                <div class="columns">
                    <div class="column">
                        <custom-select-component
                                :options="voucher_types"
                                @valueUpdated="voucher_type_has_been_selected"
                                identity="3"
                                label_text="locale_name"
                                placeholder="نوع التحويل "
                                v-show="true"></custom-select-component>
                    </div>


                </div>


                <div class="columns">
                    <div class="column">
                        <custom-select-component
                                :options="users"
                                @valueUpdated="user_has_been_selected"
                                identity="1"
                                label_text="name"
                                placeholder=" اختر المورد"
                                v-show="true"></custom-select-component>
                    </div>

                    <div class="column" v-if="user_accounts.length>=1 && voucher_type=='transfer'">
                        <custom-select-component
                                :options="user_accounts"
                                @valueUpdated="user_account_has_been_selected"
                                identity="2"
                                label_text="locale_name"
                                placeholder=" اختر حساب المورد"
                                v-show="true"></custom-select-component>
                    </div>

                </div>

            </div>


            <div v-else>

                <div class="columns">
                    <div class="column">
                        <custom-select-component
                                :options="users"
                                @valueUpdated="user_has_been_selected"
                                identity="1"
                                label_text="name"
                                placeholder=" اختر العميل"
                                v-show="true"></custom-select-component>
                    </div>


                </div>


                <div class="columns">
                    <div class="column">
                        <custom-select-component
                                :options="voucher_types"
                                @valueUpdated="voucher_type_has_been_selected"
                                identity="3"
                                label_text="locale_name"
                                placeholder="نوع التحويل "
                                v-show="true"></custom-select-component>
                    </div>


                </div>


                <div class="columns">

                    <div :dir="rtl ? 'rtl' : 'ltr'" class="column" style=" padding: 14px;">
                        <div>
                            <treeselect
                                    :options="accounts"
                                    :value="init_org_account"
                                    @select="organization_account_selected"
                                    class="input"
                                    placeholder="اختر حساب المؤسسة"
                            ></treeselect>
                        </div>
                    </div>

                </div>


            </div>


            <div class="columns">
                <div class="column">
                    <input @keyup="validator" class="input" placeholder="المبلغ" type="text"
                           v-model="total_paid_amount"/>
                </div>
                <div class="column">
                    <textarea class="form-control" placeholder="وصف العملية" v-model="description"></textarea>
                </div>
            </div>
            <div class="columns" v-if="!error">
                <div class="column">
                    <button @click="sendToServer" class="button is-primary">حفظ السند</button>
                </div>

            </div>
        </div>


    </div>
</template>

<script>


    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        components: {
            Treeselect
        },
        props: ['accounts', 'users', 'voucher_types', 'payment_type'],

        data: function () {
            return {

                description: "",
                user_accounts: [],
                selected_user: null,
                rtl: false,
                init_org_account: null,
                total_paid_amount: 0,
                translator: null,
                messages: null,
                reusable_translator: null,
                org_account: null,
                voucher_type: '',
                user_account: '',
                error: true
            };

        },
        created: function () {
            this.translator = JSON.parse(window.translator);
            this.messages = JSON.parse(window.messages);
            this.reusable_translator = JSON.parse(window.reusable_translator);
        },
        methods: {
            organization_account_selected(organization_account) {
                this.org_account = organization_account;
                this.validator();
            },

            user_account_has_been_selected(account) {
                this.user_account = account.value;
                this.validator();
            },
            voucher_type_has_been_selected(type) {
                this.voucher_type = type.value.slug;
                this.validator();
            },
            user_has_been_selected(user) {

                this.selected_user = user.value;
                this.user_accounts = user.value.gateways;
                this.validator();
                // console.log(this.selected_user);
            },

            validator() {
                if (this.payment_type == 'payment' && this.voucher_type == 'transfer') {
                    if (this.org_account == null) {
                        return false;
                    }
                }

                if (this.voucher_type == "" || parseFloat(this.total_paid_amount) <= 0 || this.selected_user == null) {
                    this.error = true;
                } else {
                    this.error = false;
                }

            },

            sendToServer() {

                var data = {
                    description: this.description,
                    user_id: this.selected_user.id,
                    amount: this.total_paid_amount,
                    voucher_type: this.voucher_type,
                    user_account_id: this.user_account.id,
                    org_account_id: this.org_account.id,
                    payment_type: this.payment_type

                };


                var vm = this;
                axios.post("/management/payments", data)
                    .then(response => {
                        location.href = '/management/payments';
                        console.log(response.data);
                    }).catch(function (error) {
                    console.log(error);
                    var errors = error.response.data.errors;
                    console.log(errors);
                    console.log(error);
                    console.log(error.response.data);
                });
            }
        }
    }
</script>
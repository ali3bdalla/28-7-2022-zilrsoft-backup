<template>
    <div class="">
        <div class="columns">
            <custom-select-component :options="organization_gateways" @valueUpdated="gatewayHadUpdated" identity="0"
                                     placeholder="اختر نوع الوسيلة"></custom-select-component>

            <custom-select-component
                    :options="banks"
                    @valueUpdated="banksListHadUpdated"
                    identity="1"
                    placeholder="اختر  الوسيلة الفرعية"
                    v-show="is_need_bank"></custom-select-component>


        </div>
        <div class="columns">
            <div class="column" v-for="field in fields">
                <input :disabled="disableGatewayFields" :placeholder="field.placeholder"
                       :readonly="disableGatewayFields"
                       class="input"
                       v-if="field.bind_vue_name=='account'" v-model='account'>


                <input :disabled="disableGatewayFields" :placeholder="field.placeholder"
                       :readonly="disableGatewayFields"
                       class="input"
                       v-if="field.bind_vue_name=='account_name'" v-model='account_name'>


            </div>
        </div>
        <div class="columns">
            <div class="column">
                <button :disabled="disableSubmitButton" @click="sendDataToServer"
                        class="button is-primary">اضافة
                    البيانات
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['organization_gateways', 'banks', 'user'],
        data: function () {
            return {
                account: '',
                account_name: '',
                is_need_bank: false,
                fields: [],
                disableGatewayFields: true,
                gateway_data: null,
                gateway_id: 0,
                bank_id: 0,
                disableSubmitButton: true,
                user_id: 0
            };
        },
        created: function () {
            if (this.user != null) {
                this.user_id = this.user.id;
                console.log(this.user);
            }
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


            showFields(e) {
                if (e.value.is_has_fields) {
                    this.fields = e.value.fields;
                } else {
                    this.disableSubmitButton = false;
                }

            },

            banksListHadUpdated(e) {
                this.bank_id = e.value.id;
                this.disableGatewayFields = false;
                this.showFields(this.gateway_data);
            },


            sendDataToServer() {
                if (this.user_id >= 1) {
                    var link = '/management/users/' + this.user_id + '/store_payments_accounts';

                } else {
                    var link = '/management/settings/payments_account_store';

                }
                var data = {
                    account: this.account,
                    account_name: this.account_name,
                    bank_id: this.bank_id,
                    gateway_id: this.gateway_id,
                    user_id: this.user_id
                };
                axios.post(link, data).then((response) => {
                    window.location = response.data;
                }).catch((error) => {
                    console.log(error)
                })
            }


        },
        watch: {
            account: function (value) {
                if (value != '') {
                    this.disableSubmitButton = false;
                } else {
                    this.disableSubmitButton = true;
                }
            }
        }
    }
</script>

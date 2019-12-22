<template>
    <div style="margin-top: 20px">
        <div class="row">
            <div class="col-md-2 col-md-offset-1 label-txt">
                <label>حسابات الدفع &nbsp;</label>
            </div>
            <div class="col-md-3">
                <select class="form-control" v-model="active_account">
                    <option :value="account" v-for="account in accounts" v-text="account.locale_name"></option>
                </select>

            </div>
            <div class="col-md-2">
                <button @click="add_new_gateway" class="btn btn-custom-primary ">
                    <i class="fa fa-plus"></i> &nbsp; اضافة
                </button>
            </div>
        </div>

        <div class="row" v-for="(gateway,index) in gateways">
            <div class="col-md-2 col-md-offset-1 label-txt">
                <label> {{ gateway.locale_name }}</label>
            </div>
            <div class="col-md-4">
                <input @keyup="publish_update" class="form-control input-sm" placeholder="الحساب" type="text"
                       v-model="gateway.account_name"/>
            </div>
            <div class="col-md-1">
                <a @click="delete_gateway(index)" class="btn btn-danger ">
                    <i class="fa fa-trash"></i> &nbsp; حذف
                </a>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['accounts', 'initGateways'],
        data: function () {

            return {
                active_account: null,
                gateways: []
            };
        },
        created: function () {
            if (this.initGateways != null) {
                for (var i = 0; i < this.initGateways.length; i++) {
                    var account = this.initGateways[i];
                    this.active_account = account.bank;
                    this.active_account.account_name = account.detail;
                    this.add_new_gateway();

                }
                this.active_account = null;
            }


        },
        methods: {

            add_new_gateway() {

                var active = this.active_account;
                if (active != null) {
                    if (active.account_name == null) {
                        active.account_name = "";
                    }
                    this.gateways.push(active);
                }


            },


            publish_update() {
                this.$emit("publishUpdate", {
                    gateways: this.gateways
                })
            },

            delete_gateway(index) {
                this.gateways.splice(index, 1);
                this.publish_update();
            }
        }
    }
</script>

<style scoped>
    .label-txt {
        vertical-align: middle;
        text-align: right;
    }

    input {
        height: 33px !important;
    }

</style>
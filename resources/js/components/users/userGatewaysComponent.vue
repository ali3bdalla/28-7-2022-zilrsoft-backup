<template>
    <div>
        <div class="columns">
            <div class="column">
                <h6>حسابات الدفع &nbsp;&nbsp;

                </h6>
            </div>
            <div class="column">
                <div class="select">
                    <select class="form-inline" v-model="active_account">
                        <option :value="account" v-for="account in accounts" v-text="account.locale_name"></option>
                    </select>
                </div>
                <a @click="add_new_gateway" class="button is-primary ">
                    <i class="fa fa-plus"></i> &nbsp; اضافة
                </a>
            </div>
        </div>
        <div class="columns" v-for="(gateway,index) in gateways">
            <div class="column">
                {{ gateway.locale_name }}
            </div>
            <div class="column">
                <input @keyup="publish_update" class="form-control" placeholder="الحساب" type="text"
                       v-model="gateway.account_name"/>
            </div>
            <div class="column">
                <a @click="delete_gateway(index)" class="button is-danger ">
                    <i class="fa fa-trash"></i> &nbsp; حذف
                </a>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['accounts'],
        data: function () {

            return {
                active_account: null,
                gateways: []
            };
        },
        methods: {

            add_new_gateway() {

                var active = this.active_account;
                if (active != null) {
                    active.account_name = '';
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
<template>
    <div>
        <div class="panel panel-primary">

            <div class="panel-body">
                <div :key="method.id" class="form-group" v-for="(method,index) in methods">
                    <div class="input-group">
                        <label :id="method.id" class="input-group-addon" style="min-width:  130px
                                        !important;  font-weight: bolder;"
                               v-text="method.locale_name"></label>
                        <input
                                :aria-describedby="method.id"
                                :disabled="totalAmount<=0"
                                :ref="'billing_filed_' + method.id"
                                @focus="$event.target.select()"
                                @keyup="gatewayAmountUpdated(method)"
                                class="form-control"
                                v-model="method.amount"/>
                    </div>
                </div>
            </div>

        </div>
        <div class="panel">
            <div class="row text-center ">
                <div class="col-md-4">
                    <div class="panel-footer">

                        <div class="card-content">
                            <span style="font-weight:bolder;font-size: 27px">الاجمالي</span>
                            <h3 class="title text-center">{{ totalAmount }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel-footer">

                        <div class="card-content">
                            <span style="font-weight:bolder;font-size: 27px">المدفوع</span>
                            <h3 class="title text-center">{{ paidAmount }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel-footer">
                        <div class="card-content">
                            <span style="font-weight:bolder;font-size: 27px">المتبقي / آجل</span>
                            <h3 class="title text-center">{{ remainingAmount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    import {math as ItemMath} from '../../item';

    export default {
        props: ['gateways', 'netAmount'],
        data: function () {
            return {
                totalAmount: 0,
                remainingAmount: 0,
                paidAmount: 0,
                methods: [],
            };
        },
        created: function () {
            this.initGateways();
        },

        methods: {
            initGateways() {
                this.totalAmount = this.netAmount;
                this.methods = db.model.addColumn(this.gateways, 'amount', 0);
                this.methods = db.model.addColumn(this.gateways, 'is_default', false);
                if (this.methods.length >= 1) {
                    this.methods[0].is_default = true;
                    this.methods[0].amount = this.totalAmount;
                    this.gatewayAmountUpdated(this.methods[0]);
                }

            },
            subFromGatewayAmount(gateway, value) {
                let index = db.model.index(this.methods, gateway.id);
                this.methods = db.model.update(this.methods, index, 'amount', ItemMath.sub(gateway.amount, value));
                this.gatewayAmountUpdated(db.model.findByIndex(this.methods, index));

            },
            gatewayAmountUpdated(gateway) {
                this.paidAmount = db.model.sum(this.methods, 'amount');
                let variation = ItemMath.sub(this.paidAmount, this.remainingAmount);
                if (variation >= 1) {
                    this.subFromGatewayAmount(gateway, variation);
                } else {
                    this.$emit('updateGatewaysAmounts', {
                        methods: this.methods,
                        status: variation === 0 ? 'paid' : 'credit'
                    });
                }

            }
        },
        watch: {
            netAmount: function (value) {
                this.initGateways();
            },
            paidAmount: function (value) {
                this.remainingAmount = ItemMath.sub(this.totalAmount, value);
            }
        }
    }
</script>


<style>
    input {
        text-align: center;
    }
</style>
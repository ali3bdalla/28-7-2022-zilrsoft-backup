<template>
    <div>
        <div class="panel panel-primary">
            <div class="panel-heading text-center">
                <div class="row">
                    <div class="col-md-4">
                        {{ periodSalesAmount}}
                    </div>
                    <div class="col-md-4">
                        {{ totalGatewaysAmount}}
                    </div>
                    <div class="col-md-4">
                        {{ parseFloat(periodSalesAmount - totalGatewaysAmount).toFixed(2)}}
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3" v-for="(gateway, index) in gatewaysList">
                        <div class="panel panel-primary">
                            <div class="panel-body">{{ gateway.locale_name }}</div>
                            <div class="panel-footer">
                                <input class="form-control" @keyup="updateAmountPrice"  placeholder="المبلغ"
                                       v-model="gateway.amount"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ["periodSalesAmount", 'gateways'],
        data: function () {
            return {
                totalGatewaysAmount: 0,
                gatewaysList: []
            };
        },
        created() {
            for (let index = 0; index < this.gateways.length; index++) {
                let gateway = this.gateways[index];
                gateway.amount = 0;
                this.gatewaysList.push(gateway);
            }

        },
        methods:{
            updateAmountPrice()
            {
                this.totalGatewaysAmount = db.model.sum(this.gatewaysList,'amount');
            }
        }
    }
</script>
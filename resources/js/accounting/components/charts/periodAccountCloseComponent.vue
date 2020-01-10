<template>
    <div>
        <div class="panel panel-primary">

            <div class="panel-body">
                <div class="row  text-center">
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">اجمالي المبيعات</div>
                            <div class="panel-body"><strong>{{ periodSalesAmount}}</strong></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">مجموع المبلغ</div>
                            <div
                                    class="panel-body"><strong>{{ parseFloat(totalGatewaysAmount).toFixed(2)}}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">عجز الفترة</div>
                            <div class="panel-body"><strong>{{ parseFloat(periodSalesAmount -
                                totalGatewaysAmount).toFixed(2)}}</strong></div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-3" v-for="(gateway, index) in gatewaysList">
                        <div class="panel panel-primary">
                            <div class="panel-body">{{ gateway.locale_name }}</div>
                            <div class="panel-footer">
                                <input @keyup="updateAmountPrice" class="form-control" placeholder="المبلغ"
                                       v-model.number="gateway.amount"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <button :disabled="!showButton" @click="sendSubmitRequest" class="btn btn-custom-primary">اكمال عملية
                    الاقفال
                </button>

                <a class="btn btn-custom-default pull-left" href="/accounting/dashboard">
                    الغاء
                </a>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ["periodSalesAmount", 'gateways'],
        data: function () {
            return {
                showButton: false,
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
        methods: {
            updateAmountPrice() {
                this.showButton = true;
                this.totalGatewaysAmount = db.model.sum(this.gatewaysList, 'amount');
            },
            sendSubmitRequest() {
                this.showButton = false;
                axios.post('/accounting/accounts/reseller/daily/account_close', {
                    gateways: this.gatewaysList,
                    period_sales_Amount: this.periodSalesAmount
                }).then(response => {
                    console.log(response)
                }).catch(error => {
                    console.log(error)
                    console.log(error.response)
                    console.log(error.response.data)
                    console.log(error.data)
                })
            }
        }
    }
</script>
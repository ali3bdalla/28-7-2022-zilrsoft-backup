<template>
    <div>
        <div class="panel panel-primary">

            <div class="panel-body">
                <div class="row  text-center">
                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">رصيد سابق</div>
                            <div class="panel-body"><strong>{{ lastRemainingTransfer}}</strong></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading"> اجمالي الفواتير</div>
                            <div class="panel-body"><strong>{{ parseFloat(periodSalesAmount).toFixed(2)-
                                parseFloat(lastRemainingTransfer).toFixed(2)}}</strong></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">المجموع </div>
                            <div
                                    class="panel-body"><strong>{{ parseFloat(periodSalesAmount).toFixed(2)}}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">المدفوع </div>
                            <div
                                    class="panel-body"><strong>{{ parseFloat(totalGatewaysAmount).toFixed(2)}}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">عجز الفترة</div>
                            <div class="panel-body"><strong>{{ parseFloat(totalGatewaysAmount -
                                periodSalesAmount).toFixed(2)}}</strong></div>
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
                <!--                <div class="row">-->
                <!--                    <div class="col-md-6">-->
                <!--                        <div class="panel panel-primary">-->
                <!--                            <div class="panel-body"><label>المتبقي</label></div>-->
                <!--                            <div class="panel-footer">-->
                <!--                                <div class="row">-->
                <!--                                    <div class="col-md-6">-->
                <!--                                        <select class="form-control" v-model="remainingAmountAccountId"-->
                <!--                                                @change="disabledRemainingAmount = false"-->
                <!--                                        >-->
                <!--                                            <option-->
                <!--                                                    :key="gateway.id"-->
                <!--                                                    :value="gateway.id"-->

                <!--                                                    v-for="(gateway, index) in gatewaysList">{{gateway.locale_name }}-->
                <!--                                            </option>-->
                <!--                                        </select>-->
                <!--                                    </div>-->
                <!--                                    <div class="col-md-6">-->
                <!--                                        <input-->

                <!--                                                :disabled="disabledRemainingAmount"-->
                <!--                                                class="form-control" placeholder="المبلغ"-->
                <!--                                                v-model.number="remainingAmount"/>-->
                <!--                                    </div>-->
                <!--                                </div>-->

                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </div>-->

                <!--                </div>-->

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
        props: ["periodSalesAmount", 'gateways',"lastRemainingTransfer"],
        data: function () {
            return {
                disabledRemainingAmount: true,
                remainingAmount: 0,
                remainingAmountAccountId: 0,
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
                axios.post('/accounting/reseller_daily/account_close', {
                    gateways: this.gatewaysList,
                    period_sales_amount: this.periodSalesAmount,
                    remaining_amount: this.remainingAmount,
                    remaining_amount_account_id: this.remainingAmountAccountId
                }).then(response => {
                    window.location = '/accounting/reseller_daily/account_close_list';
                }).catch(error => {
                    console.log(error);
                    alert(error.response.data);
                    console.log(error.response.data);
                    console.log(error.data)
                })
            }
        }
    }
</script>
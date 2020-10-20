<template>
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <label>من</label>
                    <br>
                    <select @change="updateGateway" class="form-control" v-model="activeGateway">
                        <option :key="gateway.id" :value="gateway" v-for="gateway in gateways">
                            {{ gateway.locale_name }} {{gateway.current_amount }}
                        </option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>الى</label>
                    <br>
                    <select @change="toUpdateReceiverGateway" class="form-control" v-model="receiveGateway">
                        <option :key="index" :value="gateway" v-for="(gateway,index) in toGateways">
                            {{ gateway.locale_name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label>المبلغ</label>
                    <br>
                    <input @keyup="toUpdateAmount" class="form-control" type="text" v-model.number="amount">
                </div>

                <div class="col-md-6">
                    <label>المتبقي</label>
                    <br>
                    <input class="form-control" disabled type="text" v-model="remaining">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-custom-primary" @click="toPushDataToServer">تحويل </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["gateways", 'toGateways'],
        data: function () {
            return {
                activeGateway: {
                    id:0,
                    locale_name:""
                },
                receiveGateway:  {
                    id:0,
                    locale_name:""
                },
                amount: 0,
                remaining: 0,
                gateway_id: 0,
                receiver_gateway_id: 0,
                receiver_id: 0,
            };
        },
        methods: {
            updateGateway() {
                this.gateway_id = this.activeGateway.id;
            },

            toUpdateAmount() {
                if (this.activeGateway !== null) {
                    this.remaining = this.activeGateway.balance - this.amount;
                }

            },

            toUpdateReceiverGateway() {
                this.receiver_gateway_id = this.receiveGateway.id;
                this.receiver_id = this.receiveGateway.receiver_id;
            },

            toPushDataToServer() {
                axios.post('/api/daily/reseller/accounts_transactions', {
                    amount: this.amount,
                    gateway_id: this.gateway_id,
                    receiver_gateway_id: this.receiver_gateway_id,
                    receiver_id: this.receiver_id,
                }).then(response => {
                    console.log(response.data);
                    window.location = '/daily/reseller/accounts_transactions';
                }).catch(error => {
                    alert(error.response.data);
                    console.log(error.response.data);
                })
            }
        },

    }
</script>
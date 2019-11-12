<template>
    <div class="">
        <div class="box">

            <table class="text-center table is-bordered is-dark  is-triped" id="" style="width:100%;">
                <thead class="thead-dark">


                <tr>
                    <th>{{ translator.movement.date }}</th>
                    <th>{{ translator.movement.invoice }}</th>
                    <th>{{ translator.movement.debit }}</th>
                    <th>{{ translator.movement.credit }}</th>
                    <th>{{ translator.movement.balance }}</th>


                </tr>
                </thead>


                <tbody :key="history.id" v-for="(history,index) in histories">
                <tr :class="{'has-background-light':true}">
                    <td class="datedirection">{{history.created_at}}</td>
                    <td><a :href="history.invoice_url">{{ history.invoice_title }}</a></td>
                    <td>{{parseFloat(history.debit).toFixed(2) }}</td>
                    <td>{{ parseFloat(history.credit).toFixed(2) }}</td>
                    <td>{{ parseFloat(history.total_balance).toFixed(2) }}</td>

                </tr>


                <tr v-if="history.discount_data!=null">
                    <td class="datedirection">{{history.created_at}}</td>
                    <td><a :href="history.invoice_url">خصم</a></td>
                    <td>{{ parseFloat(history.discount_data.debit).toFixed(2) }}</td>
                    <td>{{ parseFloat(history.discount_data.credit).toFixed(2)  }}</td>
                    <td>{{ parseFloat(history.discount_data.total_balance).toFixed(2) }}</td>

                </tr>


                <tr v-for="expense in history.expenses_data">
                    <td class="datedirection">{{history.created_at}}</td>
                    <td><a :href="history.invoice_url">{{ expense.expense.name}}</a></td>
                    <td>{{ parseFloat(expense.debit).toFixed(2) }}</td>
                    <td>{{ parseFloat(expense.credit).toFixed(2)  }}</td>
                    <td>{{ parseFloat(expense.total_balance).toFixed(2) }}</td>

                </tr>


                </tbody>


                <tbody>
                    <tr>
                        <td class="datedirection">-</td>
                        <td>-</td>
                        <td>{{ parseFloat(total_debit).toFixed(2) }}</td>
                        <td>{{ parseFloat(total_credit).toFixed(2)   }}</td>
                        <td>{{  parseFloat(total_debit).toFixed(2)  - parseFloat(total_credit).toFixed(2)}}</td>

                    </tr>
                </tbody>

            </table>


        </div>
        <div class="form-group">
            &nbsp;

            <a class="button is-right pull-right" href="/management/items"><i class="fa fa-undo-alt"></i>
                &nbsp;&nbsp;{{ reusable_translator.back }}</a>
        </div>


    </div>
</template>
<script>

    export default {

        props: ['item', 'activities'],

        data: function () {
            return {

                total_balance: 0,
                temp_total_balance:0,
                reusable_translator: null,
                translator: null,
                profits: 0,
                total_debit: 0,
                cost: 0,
                total_credit: 0,
                histories: []
            };
        },
        created: function () {
            this.translator = JSON.parse(window.translator);
            this.reusable_translator = JSON.parse(window.reusable_translator);
            this.roundNumber = helpers.roundTheFloatValueTo2DigitOnlyAfterComma;
            this.loadData();

        },


        methods: {




            getNewTotal(history)
            {
                this.temp_total_balance =    parseFloat(history.debit) -  parseFloat(history.credit);
            },
            loadData(startDate = '', endDate = '') {
                var vm = this;

                vm.histories = this.activities.data;
                vm.total_debit = this.activities.total_debit;
                vm.total_credit = this.activities.total_credit;



                console.log(this.activities);
                // //
                // axios.get('/management/items/' + this.item.id + '/movement?startDate=' + startDate + '&&endDate=' + endDate).then((response) => {
                //     console.log(response.data);
                //     vm.histories = response.data.data;
                //     vm.stock_value = response.data.stock_value;
                //     vm.stock_qty = response.data.stock_qty;
                //     vm.profits = response.data.profits;
                //     vm.cost = response.data.cost;
                // })
            },
            datePickerUpdated(e) {
                var startDate = e.startDate.getFullYear() + '-' + e.startDate.getMonth() + '-' + e.startDate.getDate();
                var endDate = e.endDate.getFullYear() + '-' + e.endDate.getMonth() + '-' + e.endDate.getDate();
                // var endDate = e.endDate;

                // console.log(startDate);
                this.loadData(startDate, endDate);
            }


        }
        ,
        watch:
            {
                temp_total_balance:function (value) {
                  // this.total_balance = parseFloat(this.total_balance) + parseFloat(value);
                }
            }
    }
</script>

<template>
    <div class="">
        <div class="box">
            <div class="">
                <div class="panel-heading has-background-dark has-text-white">
                    <div class="columns is-center">
                        <div class="column text-center">{{ translator.movement.stock_qty }} : ( {{ stock_qty }} )</div>
                        <div class="column text-center">{{ translator.movement.stock_value }} : ({{ roundNumber(
                            stock_value) }})
                        </div>
                        <div class="column text-center">{{ translator.movement.cost }} : ({{ roundNumber( cost) }})
                        </div>
                        <!--                    <div class="column text-center" >{{ translator.movement.profits }} : ({{ roundNumber( profits) }})</div>-->

                    </div>
                </div>
            </div>
            <table class="text-center table is-bordered is-dark  is-triped" id="" style="width:100%;">
                <thead class="thead-dark">

                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th colspan="3">{{ translator.movement.in }}</th>
                    <th class="has-background-light" colspan="4">{{ translator.movement.out }}</th>
                    <th colspan="3">{{ translator.movement.stock }}</th>
                    <th></th>


                </tr>


                <tr>
                    <th>{{ translator.movement.date }}</th>
                    <th>{{ translator.movement.invoice }}</th>
                    <th>{{ translator.movement.user }}</th>
                    <th>{{ translator.movement.creator }}</th>
                    <th>{{ translator.movement.qty }}</th>
                    <th>{{ translator.movement.price }}</th>
                    <th>{{ translator.movement.value }}</th>
                    <th class="has-background-light">{{ translator.movement.qty }}</th>
                    <th class="has-background-light">{{ translator.movement.price }}</th>
                    <th class="has-background-light">{{ translator.movement.value }}</th>
                    <th>{{ translator.movement.total_cost }}</th>
                    <th>{{ translator.movement.stock_qty }}</th>
                    <th>{{ translator.movement.cost }}</th>

                    <th>{{ translator.movement.stock_value }}</th>
                    <th>{{ translator.movement.description }}</th>
                    <th>{{ translator.movement.profits }}</th>


                </tr>
                </thead>
                <tbody :key="history.id" v-for="(history,index) in histories">
                <tr :class="{'has-background-light':true}">
                    <td class="datedirection">{{history.created_at}}</td>
                    <td><a :href="history.invoice_url">{{ history.invoice_title }}</a></td>
                    <td>{{ history.user.locale_name }}</td>
                    <td>{{ history.creator.locale_name }}</td>
                    <td>
                    <span
                            v-if="history.invoice_type=='beginning_inventory'
                         || history.invoice_type=='purchase'
                         || history.invoice_type=='r_sale'
                    ">
                        {{history.qty}}
                    </span>
                    </td>
                    <td>
                    <span
                            v-if="history.invoice_type=='beginning_inventory'
                         || history.invoice_type=='purchase'
                         || history.invoice_type=='r_sale'
                    ">
                        {{roundNumber(history.price)}}
                    </span>
                    </td>
                    <td>
                    <span
                            v-if="history.invoice_type=='beginning_inventory'
                         || history.invoice_type=='purchase'
                         || history.invoice_type=='r_sale'
                    ">
                        {{roundNumber(history.total)}}
                    </span>
                    </td>
                    <!--out-->
                    <td>
                    <span
                            v-if="history.invoice_type=='sale'
                         || history.invoice_type=='r_purchase'
                    ">
                        {{history.qty}}
                    </span>
                    </td>
                    <td>
                    <span
                            v-if="history.invoice_type=='sale'
                         || history.invoice_type=='r_purchase'
                    ">
                        {{history.price}}
                    </span>
                    </td>
                    <td>
                    <span
                            v-if="history.invoice_type=='sale'
                         || history.invoice_type=='r_purchase'
                    ">
                        {{roundNumber(history.total)}}
                    </span>
                    </td>

                    <td><span v-if="history.invoice_type=='sale' || history.invoice_type=='r_sale'">{{roundNumber(history.total_cost)}}</span>
                    </td>

                    <td>{{history.current_move_stock_qty}}</td>
                    <td>{{roundNumber(history.current_move_stock_cost)}}</td>

                    <td>{{roundNumber(history.current_move_stock_total)}}</td>
                    <td>{{ history.description }}</td>
                    <td><span v-if="history.invoice_type=='sale' || history.invoice_type=='r_sale'">{{parseFloat(history.profits).toFixed(2)}}</span>
                    </td>
                </tr>


                <!--DICOUNT-->
                <tr v-if="history.discount>0">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    <span v-if="history.has_purchase_discount || history.has_return_sale_discount">
                        {{history.discount}}
                    </span>
                    </td>
                    <!--out-->
                    <td>

                    </td>

                    <td>

                    </td>

                    <td>
                   <span v-if="history.has_sale_discount || history.has_return_purchase_discount ">
                        {{history.discount}}
                    </span>
                    </td>
                    <td></td>

                    <td>{{ history.current_move_stock_qty}}</td>
                    <td>
                    <span v-if="history.has_purchase_discount || history.has_return_purchase_discount">
                        {{roundNumber(history.discount_data.discount_stock_cost) }}
                    </span>

                        <span v-if="history.has_sale_discount || history.has_return_sale_discount">
                        {{roundNumber(history.discount_data.discount_stock_cost) }}
                    </span>


                    </td>
                    <td>
                     <span v-if="history.has_purchase_discount || history.has_return_purchase_discount ">

                        {{roundNumber(history.discount_data.discount_stock_total) }}
                    </span>

                        <span v-if="history.has_sale_discount || history.has_return_sale_discount">
                        {{roundNumber(history.discount_data.discount_stock_total) }}
                    </span>
                    </td>

                    <td>{{ translator.movement.discount }}</td>
                    <td>
                         <span v-if="history.has_sale_discount || history.has_return_sale_discount">
                        {{roundNumber(history.discount_data.discount_profits) }}
                    </span>

                    </td>
                </tr>


                <!--expenses-->
                <tr v-for="expense in history.expenses_data" v-if="history.has_expenses==true">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                        {{roundNumber(expense.amount) }}
                    </td>
                    <!--out-->
                    <td>

                    </td>

                    <td>

                    </td>

                    <td>

                    </td>
                    <td></td>

                    <td>{{ history.current_move_stock_qty}}</td>
                    <td>

                        {{ roundNumber(expense.expense_stock_cost)}}
                    </td>
                    <td>

                        {{ roundNumber(expense.expense_stock_total)}}
                    </td>

                    <td>{{ expense.expense.locale_name }}</td>
                    <td></td>
                </tr>


                </tbody>
            </table>


        </div>
        <div class="form-group">
            &nbsp;

            <a class="button is-right pull-right" href="/accounting/items"><i class="fa fa-undo-alt"></i>
                &nbsp;&nbsp;{{ reusable_translator.back }}</a>
        </div>


    </div>
</template>
<script>

    export default {

        props: ['item'],

        data: function () {
            return {

                reusable_translator: null,
                translator: null,
                profits: 0,
                stock_value: 0,
                cost: 0,
                stock_qty: 0,
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
            loadData(startDate = '', endDate = '') {
                var vm = this;
                axios.get('/accounting/items/' + this.item.id + '/transactions_datatable?startDate=' + startDate + '&&endDate=' +
                    endDate).then((response) => {
                    vm.histories = response.data.data;
                    vm.stock_value = response.data.stock_value;
                    vm.stock_qty = response.data.stock_qty;
                    vm.profits = response.data.profits;
                    vm.cost = response.data.cost;
                })
            },
            datePickerUpdated(e) {
                var startDate = e.startDate.getFullYear() + '-' + e.startDate.getMonth() + '-' + e.startDate.getDate();
                var endDate = e.endDate.getFullYear() + '-' + e.endDate.getMonth() + '-' + e.endDate.getDate();

                this.loadData(startDate, endDate);
            }

        }
    }
</script>

<style scoped src='bulma/css/bulma.css'>

</style>
<template>
    <div class="table">
        <div class="box">


            <div class="panel-heading">
                <VueCtkDateTimePicker
                        :auto-close="true"

                        :behaviour="{time: {nearestIfDisabled: true}}"
                        :custom-shortcuts="customDateShortcuts"
                        :only-date="false"
                        :range="true"
                        label="تحديد التاريخ"
                        locale="en"
                        v-model="date_range"/>
            </div>
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
                    <td>
                        <span v-if="history.user!=null">{{ history.user.locale_name }}</span>
                        <span v-else></span>
                    </td>
                    <td>{{ history.creator?history.creator.locale_name : "" }}</td>
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

                    <td><span
                            v-if="history.invoice_type=='sale' || history.invoice_type=='r_sale'">{{roundNumber(parseFloat(history.current_stock_item_cost) * parseInt(history.qty))}}</span>
                    </td>

                    <td>{{history.current_stock_qty}}</td>
                    <td>{{roundNumber(history.current_stock_item_cost)}}</td>

                    <td>{{roundNumber(history.current_stock_amount)}}</td>
                    <td>{{ history.description }}</td>
                    <td><span v-if="history.invoice_type=='sale' || history.invoice_type=='r_sale'">{{parseFloat(history.current_profits).toFixed(2)}}</span>
                    </td>
                </tr>


                <!--DICOUNT-->
                <tr v-if="parseFloat(history.discount)>0">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    <span v-if="history.discount_data.purchase_discount || history.discount_data.return_sales_discount">
                        {{history.discount}}
                    </span>
                    </td>
                    <!--out-->
                    <td>

                    </td>

                    <td>

                    </td>

                    <td>
                   <span v-if="history.discount_data.sales_discount || history.discount_data.return_purchase_discount ">
                        {{history.discount}}
                    </span>
                    </td>
                    <td></td>

                    <td>{{ history.current_stock_qty}}</td>
                    <td>
                    <span v-if="history.discount_data.purchase_discount ||
                    history.discount_data.return_purchase_discount">
                        {{roundNumber(history.discount_data.discount_stock_cost) }}
                    </span>

                        <span v-if="history.discount_data.sales_discount ||
                        history.discount_data.return_sales_discount">
                        {{roundNumber(history.discount_data.discount_stock_cost) }}
                    </span>


                    </td>
                    <td>
                     <span v-if="history.discount_data.purchase_discount || history.discount_data.return_purchase_discount ">

                        {{roundNumber(history.discount_data.discount_stock_total) }}
                    </span>

                        <span v-if="history.discount_data.sales_discount || history.discount_data.return_sales_discount">
                        {{roundNumber(history.discount_data.discount_stock_total) }}
                    </span>
                    </td>

                    <td>{{ translator.movement.discount }}</td>
                    <td>
                         <span v-if="history.discount_data.sales_discount || history.discount_data.return_sales_discount">
<!--                        {{roundNumber(history.discount_data.discount_profits) }}-->
                    </span>

                    </td>
                </tr>


                <!--expenses-->
                <tr v-for="expense in history.expenses_data" v-if=" history.expenses_data != null">
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

                    <td>{{ history.current_stock_qty}}</td>
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
                <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>

                    </td>
                    <!--out-->
                    <td>

                    </td>

                    <td>

                    </td>

                    <td>

                    </td>
                    <td></td>

                    <td></td>
                    <td>


                    </td>
                    <td>


                    </td>

                    <td></td>
                    <td>{{ parseFloat(profits).toFixed(2) }}</td>
                </tr>
                </thead>
            </table>

            <div>
                <accounting-table-pagination-helper-layout-component
                        :data="paginationResponseData"
                        @pagePerItemsUpdated="pagePerItemsUpdated"
                        @paginateUpdatePage="paginateUpdatePage"
                ></accounting-table-pagination-helper-layout-component>
            </div>

        </div>

        <div class="form-group">
            &nbsp;

            <a class="button is-right pull-right" href="/accounting/items"><i class="fa fa-undo-alt"></i>
                &nbsp;&nbsp;{{ reusable_translator.back }}</a>
        </div>


    </div>
</template>
<script>
    import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';

    export default {

        props: ['item'],
        components: {
            VueCtkDateTimePicker

        },
        data: function () {
            return {
                date_range: null,
                reusable_translator: null,
                translator: null,
                profits: 0,
                stock_value: 0,
                cost: 0,
                stock_qty: 0,
                histories: [],
                total_profit: 0,
                itemsPerPage: 20,

                customDateShortcuts: [],
                showMultiTaskButtons: false,
                requestUrl: "",
                app: {
                    primaryColor: metaHelper.getContent('primary-color'),
                    secondColor: metaHelper.getContent('second-color'),
                    appLocate: metaHelper.getContent('app-locate'),
                    trans: trans('invoices-page'),
                    messages: trans('messages'),
                    table_trans: trans('table'),
                    datetimetrans: trans('datetime'),
                    datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
                    BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
                },
                filters: {
                    start_at: null,
                    end_at: null,
                    title: null,
                    clients: null,
                    creators: null,
                    creator_id: null,
                    departments: [],
                    net: null,
                    total: null,
                    tax: null,
                    current_status: null,
                    salesmen: [],
                    invoice_type: null,
                },
                paginationResponseData: null,


            };
        },
        created: function () {
            this.requestUrl = '/accounting/items/' + this.item.id + '/transactions_datatable';
            this.translator = JSON.parse(window.translator);
            this.reusable_translator = JSON.parse(window.reusable_translator);
            this.roundNumber = helpers.roundTheFloatValueTo2DigitOnlyAfterComma;
            this.customDateShortcuts = [
                {key: 'day', label: this.app.datetimetrans.today, value: 'day'},
                {key: '-day', label: this.app.datetimetrans.yesterday, value: '-day'},
                {key: 'thisWeek', label: this.app.datetimetrans.thisWeek, value: 'isoWeek'},
                {key: 'lastWeek', label: this.app.datetimetrans.lastWeek, value: '-isoWeek'},
                {key: 'last7Days', label: this.app.datetimetrans.last7Days, value: 7},
                {key: 'last30Days', label: this.app.datetimetrans.last30Days, value: 30},
                {key: 'thisMonth', label: this.app.datetimetrans.thisMonth, value: 'month'},
                {key: 'lastMonth', label: this.app.datetimetrans.lastMonth, value: '-month'},
                {key: 'thisYear', label: this.app.datetimetrans.thisYear, value: 'year'},
                {key: 'lastYear', label: this.app.datetimetrans.lastYear, value: '-year'}
            ];
            this.loadData();


        },

        watch: {
            date_range: function (value) {
                this.filters.start_at = null;
                this.filters.end_at = null;
                if (value != null) {
                    this.filters.start_at = value.start;
                    this.filters.end_at = value.end;
                }
                this.loadData();

            },

        },

        methods: {
            loadData() {
                let vm = this;

              
                axios.get(this.requestUrl, {
                    params: {
                        'start_at': vm.filters.start_at,
                        'end_at': vm.filters.end_at,
                        'perPage': vm.itemsPerPage,
                    }
                }).then((response) => {
                    // console.log(response.data);
                    vm.paginationResponseData = response.data;
                    vm.histories = response.data.data;
                    vm.stock_value = response.data.totals.total_stock_amount;
                    vm.stock_qty = response.data.totals.total_stock_qty;
                    vm.cost = response.data.totals.current_stock_item_cost;
                     vm.profits = response.data.totals.total_sales_profits;
                    // vm.cost = response.data.cost;
                })
            },

            paginateUpdatePage(event) {
                this.requestUrl = event.link;
                this.loadData();

            },

            pagePerItemsUpdated(event) {

                this.itemsPerPage = event.items;
                this.loadData();

            },


        }
    }
</script>

<style scoped src='bulma/css/bulma.css'>

</style>
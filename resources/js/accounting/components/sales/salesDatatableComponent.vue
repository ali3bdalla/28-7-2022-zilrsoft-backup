<template>
    <div class="table">
        <div class="table-posistion">
            <div v-if="onlyQuotations==true">
                <input @focus="$event.target.select()" @keyup="pushServerRequest"
                       autofocus="autofocus"
                       class="form-control"
                       placeholder=" رقم الفاتورة"
                       ref="barcodeAndNameUpdated"
                       type="text"
                       v-model="filters.title">
            </div>

            <div class="table-filters">
                <div @click="openOrCloseSearchPanel" class="text-right search-text" style="cursor: pointer;"><i
                        class="fa fa-search-plus"></i>
                    {{app.trans.search }}
                </div>

                <div v-show="isOpenSearchPanel">
                    <div class="row">
                        <div class="col-md-3">
                            <VueCtkDateTimePicker
                                    :auto-close="true"

                                    :behaviour="{time: {nearestIfDisabled: true}}"
                                    :custom-shortcuts="customDateShortcuts"
                                    :label="app.trans.created_at"
                                    :only-date="false"
                                    :range="true"
                                    locale="en"
                                    v-model="date_range"/>
                        </div>

<!--                        <div class="col-md-2">-->

<!--                        </div>-->

                        <div class="col-md-3">
                            <accounting-multi-select-with-search-layout-component

                                    :options="creators"
                                    :placeholder="app.trans.salesman"
                                    :title="app.trans.salesman"
                                    @valueUpdated="salesmanListUpdated"
                                    default="0"
                                    identity="000000003"
                                    label_text="locale_name"

                            >

                            </accounting-multi-select-with-search-layout-component>

                        </div>

<!--                        <div class="col-md-1" v-if="onlyQuotations!==true">-->
<!--                            <select @change="pushServerRequest" class="form-control" v-model="filters.current_status">-->
<!--                                <option value="null">{{ app.trans.current_status }}</option>-->
<!--                                <option value="paid">{{ app.trans.paid }}</option>-->
<!--                                <option value="credit">{{ app.trans.credit }}</option>-->
<!--                            </select>-->
<!--                        </div>-->
                        <div class="col-md-2" v-if="onlyQuotations!==true">
                            <select @change="pushServerRequest" class="form-control" v-model="filters.invoice_type">
                                <option value="null">{{ app.trans.invoice_type }}</option>
                                <option value="sale">{{ app.trans.sale }}</option>
                                <option value="return_sale">{{ app.trans.return_sale }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <accounting-multi-select-with-search-layout-component
                                    :options="clients"
                                    :placeholder="app.trans.client"
                                    :title="app.trans.client"
                                    @valueUpdated="clientListUpdated"
                                    default="0"
                                    identity="000000001"
                                    label_text="locale_name"

                            >

                            </accounting-multi-select-with-search-layout-component>

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-2">
                            <input :placeholder="app.trans.net" @keyup="pushServerRequest"
                                   class="form-control"
                                   type="text" v-model="filters.net">
                        </div>
                        <div class="col-md-2">
                            <input :placeholder="app.trans.invoice_number" @keyup="pushServerRequest"
                                   class="form-control"
                                   type="text" v-model="filters.title">
                        </div>


                        <div class="col-md-2" v-if="canViewAccounting==1">
                            <input :placeholder="app.trans.total" @keyup="pushServerRequest"
                                   class="form-control"
                                   type="text" v-model="filters.total">
                        </div>
                        <div class="col-md-3" v-if="canViewAccounting==1">
                            <accounting-multi-select-with-search-layout-component
                                    :options="creators"
                                    :placeholder="app.trans.creator"
                                    :title="app.trans.creator"
                                    @valueUpdated="creatorListUpdated"
                                    default="0"
                                    identity="000000000"
                                    label_text="locale_name"


                            >

                            </accounting-multi-select-with-search-layout-component>


                        </div>

                        <div class="col-md-3" v-if="canViewAccounting==1">
                            <input :placeholder="app.trans.tax" @keyup="pushServerRequest"
                                   class="form-control"
                                   type="text" v-model="filters.tax">
                        </div>

                        <div class="col-md-3" v-if="canViewAccounting==1">
                            <accounting-multi-select-with-search-layout-component
                                    :options="departments"
                                    :placeholder="app.trans.department"
                                    :title="app.trans.department"
                                    @valueUpdated="departmentListUpdated"
                                    default="0"
                                    identity="000000005"
                                    label_text="locale_title"

                            >

                            </accounting-multi-select-with-search-layout-component>

                        </div>

                    </div>


                </div>
            </div>
            <div class="table-multi-task-buttons" v-show="showMultiTaskButtons">

            </div>
            <div class="table-content" v-show="!isLoading">
                <table class="table table-striped table-bordered" width="100%">
                    <thead>
                    <tr>
                        <th @click="setOrderByColumn('id')" width="4%">
                            {{ app.trans.id }}
                        </th>

                        <th :class="{'orderBy':orderBy=='id'}" @click="setOrderByColumn('id')">
                            {{ app.trans.invoice_number }}
                        </th>
                        <th>
                            {{ app.trans.client }}
                        </th>

                        <th :class="{'orderBy':orderBy=='created_at'}" @click="setOrderByColumn('created_at')"
                            width="">
                            {{ app.trans.created_at }}
                        </th>


                        <th :class="{'orderBy':orderBy=='net'}" @click="setOrderByColumn('net')"
                            width="">
                            {{ app.trans.net }}
                        </th>

                        <th :class="{'orderBy':orderBy=='subtotal'}" @click="setOrderByColumn('subtotal')"
                            v-if="canViewAccounting==1"
                            width="">
                            {{ app.trans.subtotal }}
                        </th>

                        <th v-if="canViewAccounting"
                            width="">
                            {{ app.trans.cost }}
                        </th>
                        <th v-if="canViewAccounting"
                            width="">
                            {{ app.trans.profit }}
                        </th>

<!--                        <th :class="{'orderBy':orderBy=='current_status'}" @click="setOrderByColumn('current_status')"-->
<!--                            width="">-->
<!--                            {{ app.trans.current_status }}-->
<!--                        </th>-->

                        <th :class="{'orderBy':orderBy=='invoice_type'}" @click="setOrderByColumn('invoice_type')"
                            width="">
                            {{ app.trans.invoice_type }}
                        </th>


                        <th :class="{'orderBy':orderBy=='creator_id'}" @click="setOrderByColumn('creator_id')"
                            v-if="canViewAccounting==1" width="">
                            {{ app.trans.created_by }}
                        </th>

                        <th
                                width="">
                            {{ app.trans.salesman }}
                        </th>


                        <th :class="{'orderBy':orderBy=='tax'}" @click="setOrderByColumn('tax')"
                            v-if="canViewAccounting==1"
                            width="">
                            {{ app.trans.tax }}
                        </th>

                        <th v-text="app.trans.options" width="8%"></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr :key="row.id" v-for="(row,index) in table_rows">
                        <td v-text="index+1"></td>
                        <td class="text-center" v-text="row.invoice_number"></td>
                        <td class="text-center"
                            v-text="row.sale.alice_name==null ? row.sale.client.locale_name : row.sale.alice_name"></td>
                        <td v-text="row.created_at"></td>
                        <td class="text-center" v-text="row.net"></td>
                        <td class="text-center" v-if="canViewAccounting==1" v-text="row.subtotal"></td>
                        <td class="text-center" v-if="canViewAccounting==1 && row.invoice_type=='sale'"
                            v-text="parseFloat(row.invoice_cost).toFixed(2)"></td>
                        <td class="text-center" v-if="canViewAccounting==1 && row.invoice_type=='return_sale'"
                            v-text="parseFloat(-row.invoice_cost).toFixed(2)"></td>

                        <td class="text-center" v-if="canViewAccounting==1 && row.invoice_type=='sale'"
                            v-text="parseFloat(row.subtotal - row.invoice_cost).toFixed(2)"></td>
                        <td class="text-center" v-if="canViewAccounting==1 && row.invoice_type=='return_sale'"
                            v-text="-parseFloat(row.subtotal - row.invoice_cost).toFixed(2)"></td>


<!--                        <td class="text-center">-->
<!--                            <span v-if="row.current_status=='paid'">{{ app.trans.paid }}</span>-->
<!--                            <span v-else>{{ app.trans.credit }}</span>-->
<!--                        </td>-->
                        <td class="text-center">
                            <span v-if="row.invoice_type=='sale'">{{ app.trans.sale }}</span>
                            <span v-else-if="row.invoice_type=='quotation'">{{ app.trans.quotation }}</span>
                            <span v-else>{{ app.trans.return_sale }}</span>
                        </td>
                        <td class="text-center" v-if="canViewAccounting==1" v-text="row.creator.locale_name"></td>
                        <td class="text-center" v-text="row.sale.salesman.locale_name"></td>
                        <td class="text-center" v-if="canViewAccounting==1" v-text="row.tax"></td>
                        <td>
                            <div class="dropdown">
                                <button :id="'dropDownOptions'
                                + row.id" aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-options dropdown-toggle " data-toggle="dropdown"
                                        type="button">
                                    {{ app.trans.options }}
                                    <span class="caret"></span>
                                </button>
                                <ul :aria-labelledby="'dropDownOptions'
                                + row.id" class="dropdown-menu CustomDropDownOptions">
                                    <li><a :href="baseUrl + row.id "
                                           v-text="app.trans.view"></a></li>

                                    <li v-if="canEdit==1 && row.invoice_type=='sale' && row.is_deleted==0"><a
                                            :href="baseUrl + row.id + '/edit'" v-text="app.trans.return"></a></li>

                                    <li v-if="onlyQuotations==true"><a
                                            :href="'/sales/drafts/' + row.id + '/clone'">تحويل الى فاتورة</a></li>


                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                    <thead v-if="canViewAccounting==1 && onlyQuotations!=true">
                    <tr>
                        <th>

                        </th>

                        <th>

                        </th>
                        <th>

                        </th>

                        <th>

                        </th>


                        <th>
                            {{parseFloat( totals.net).toFixed(2) }}
                        </th>
                        <th>
                            {{parseFloat( totals.subtotal).toFixed(2) }}
                        </th>
                        <th>
                            {{parseFloat( totals.cost).toFixed(2) }}
                        </th>
                        <th>
                            {{parseFloat( totals.profit).toFixed(2) }}
                        </th>

<!--                        <th>-->

<!--                        </th>-->

                        <th>
                        </th>


                        <th>

                        </th>
                        <th>

                        </th>


                        <th>
                            {{parseFloat( totals.tax).toFixed(2) }}
                        </th>

                        <th></th>
                    </tr>
                    </thead>
                   <!-- <thead v-if="canViewAccounting==1 && onlyQuotations!=true">
                    <tr>
                        <th>

                        </th>

                        <th>

                        </th>
                        <th>

                        </th>

                        <th>

                        </th>


                        <th>
                            {{parseFloat(totals.net).toFixed(2) }}
                        </th>


                        <th>

                        </th>

                        <th>
                        </th>


                        <th>

                        </th>


                        <th></th>
                    </tr>
                    </thead>-->
                </table>


            </div>
            <tile :color="app.primaryColor" :loading="isLoading" v-show="isLoading"></tile>
            <div class="table-paginations">
                <accounting-table-pagination-helper-layout-component
                        :data="paginationResponseData"
                        @pagePerItemsUpdated="pagePerItemsUpdated"
                        @paginateUpdatePage="paginateUpdatePage"
                ></accounting-table-pagination-helper-layout-component>
            </div>

        </div>
    </div>
</template>

<script>


    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'
    import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
    import {math as ItemMath} from '../../item';

    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';


    export default {
        components: {
            VueCtkDateTimePicker, Treeselect

        },
        props: [
            "onlyQuotations",
            'creator',
            'canViewAccounting',
            "canEdit",
            "canDelete",
            "canCreate",
            "creators",
            "clients",
            "departments"
        ],
        data: function () {
            return {

                totals: {
                    net: 0,
                    tax: 0,
                    total: 0,
                    subtotal: 0,
                    discount: 0,
                    profit: 0,
                    cost: 0,
                },
                itemsPerPage: 20,
                isOpenSearchPanel: false,
                category: null,
                baseUrl: "",
                orderBy: "created_at",
                orderType: "desc",
                yourValue: null,
                table_rows: [],
                isLoading: true,
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
                customDateShortcuts: [],
                date_range: null,
                showMultiTaskButtons: false,
                requestUrl: "",
                filters: {
                    endDate: null,
                    startDate: null,
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
                tableSelectionActiveMode: false

            };
        },
        created() {
            this.initUi();
            this.pushServerRequest();


        },
        mounted: function () {
            let appVm = this;
            if (this.creator.id == 7) {
                setInterval(function () {
                    appVm.pushServerRequest();
                }, 40000)
            }


        },
        methods: {


            initUi() {
                this.requestUrl =  '/accounting/datatable/sales';
                this.baseUrl = this.app.trans.SaleBaseUrl + "/";
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
            },
            pushServerRequest: function () {

                this.isLoading = true;
                var appVm = this;
                var params = appVm.filters;
                params.orderBy = this.orderBy;
                params.itemsPerPage = this.itemsPerPage;
                params.orderType = this.orderType;
                if (this.onlyQuotations) {
                    params.is_draft = true;
                }


                axios.get(this.requestUrl, {
                    params: params
                }).then(function (response) {
                    // console.log(response.data);
                    appVm.table_rows = response.data.data;
                    appVm.isLoading = false;
                    appVm.paginationResponseData = response.data;
                    appVm.updateTotalsAmount();
                }).catch(function (error) {
                    alert(error)
                }).finally(function () {
                    appVm.isLoading = false;
                });
            },

            updateTotalsAmount() {

                let items = this.table_rows;
                let len = items.length;
                this.totals.net = 0;
                this.totals.tax = 0;
                this.totals.total = 0;
                this.totals.subtotal = 0;
                this.totals.discount = 0;
                this.totals.cost = 0;
                this.totals.profit = 0;
                for (let i = 0; i < len; i++) {
                    let row = items[i];
                    if (row.invoice_type === 'sale') {
                        if (parseFloat(row.invoice_cost) == NaN) {
                            console.log(row);
                        }
                        this.totals.net = ItemMath.sum(this.totals.net, row.net);
                        this.totals.tax = ItemMath.sum(this.totals.tax, row.tax);
                        this.totals.total = ItemMath.sum(this.totals.total, row.total);
                        this.totals.subtotal = ItemMath.sum(this.totals.subtotal, row.subtotal);
                        this.totals.discount = ItemMath.sum(this.totals.discount, row.discount);
                        this.totals.profit = ItemMath.sum(this.totals.profit, row.subtotal - row.invoice_cost);
                        this.totals.cost = ItemMath.sum(this.totals.cost, row.invoice_cost);
                    } else {
                        this.totals.net = ItemMath.sub(this.totals.net, row.net);
                        this.totals.tax = ItemMath.sub(this.totals.tax, row.tax);
                        this.totals.total = ItemMath.sub(this.totals.total, row.total);
                        this.totals.subtotal = ItemMath.sub(this.totals.subtotal, row.subtotal);
                        this.totals.discount = ItemMath.sub(this.totals.discount, row.discount);
                        this.totals.profit = ItemMath.sub(this.totals.profit, row.subtotal - row.invoice_cost);
                        this.totals.cost = ItemMath.sub(this.totals.cost, row.invoice_cost);
                    }
                }


            },
            setOrderByColumn(column_name) {
                if (this.orderBy == column_name) {
                    // alert('hello')
                    if (this.orderType == 'asc')
                        this.orderType = "desc";
                    else
                        this.orderType = "asc";

                } else {
                    this.orderBy = column_name;
                    this.orderType = "asc";
                }
                this.pushServerRequest();
            },

            paginateUpdatePage(event) {
                this.requestUrl = event.link;
                this.pushServerRequest();

            },

            pagePerItemsUpdated(event) {

                this.itemsPerPage = event.items;
                this.pushServerRequest();

            },

            checkAndUncheckAllRowsCheckBoxChanged() {

                var items = this.table_rows,
                    len = items.length;

                var new_items = [];
                for (var index = 0; index < len; index++) {
                    var item = items[index];
                    if (this.tableSelectionActiveMode) {
                        item.tb_row_selected = false;
                    } else {
                        item.tb_row_selected = true;
                    }
                    new_items.push(item);

                }


                if (this.tableSelectionActiveMode) {
                    this.showMultiTaskButtons = false;
                } else {
                    this.showMultiTaskButtons = true;
                }

                this.table_rows = new_items;
                this.tableSelectionActiveMode = !this.tableSelectionActiveMode;

            },
            rowSelectCheckBoxUpdated(item) {
                this.showOrHideMultiTaskButtons();
            },
            showOrHideMultiTaskButtons() {
                var items = this.table_rows,
                    len = items.length;

                var showButtons = false;
                for (var index = 0; index < len; index++) {
                    var item = items[index];
                    if (item.tb_row_selected) {
                        showButtons = true;
                    }
                }

                this.showMultiTaskButtons = showButtons;
            },

            openOrCloseSearchPanel() {
                this.isOpenSearchPanel = !this.isOpenSearchPanel;
            },
            exportsPdf() {

            },

            creatorListUpdated(e) {

                this.filters.creators = db.model.pluck(e.items, 'id');
                this.pushServerRequest();
            },

            salesmanListUpdated(e) {

                this.filters.salesmen = db.model.pluck(e.items, 'id');
                this.pushServerRequest();
            },

            clientListUpdated(e) {

                this.filters.clients = db.model.pluck(e.items, 'id');
                this.pushServerRequest();
            },


            departmentListUpdated(e) {

                this.filters.departments = db.model.pluck(e.items, 'id');
                this.pushServerRequest();
            },


        },

        watch: {
            date_range: function (value) {
                if (value == null) {
                    this.filters.startDate = null;
                    this.filters.endDate = null;

                } else {
                    this.filters.startDate = value.start;
                    this.filters.endDate = value.end;
                }
                this.pushServerRequest();

            },

        }
    }
</script>
<style scoped>

    .orderBy {
        background-color: #eee
    }

    .table-content {
        background: #f8f8f8;
        padding: 1px;
    }

    .table {
        border: 5px;
        table-layout: fixed;
        text-align: center !important;
    }

    .table thead th {
        text-align: center;
        cursor: pointer;

    }

    .table-filters {
        background: #f8f8f8;
        padding: 7px;
        margin-bottom: 7px;
    }

    .search-text {
        font-size: 19px;
        color: #999;
    }

    input[type=text],
    input[type=number],
    select {
        height: 42px;
    }


    .dropdown-menu li a {
        padding: 7px;
        font-size: 14px;
        border-bottom: 1px solid #eee;
    }

    .table-multi-task-buttons {
        padding: 5px;
    }


</style>

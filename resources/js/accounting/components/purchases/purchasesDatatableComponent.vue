<template>
    <div class="table">
        <div class="table-posistion">

            <div class="table-filters">
                <div @click="openOrCloseSearchPanel" class="text-right search-text" style="cursor: pointer;"><i
                        class="fa fa-search-plus"></i>
                    {{app.trans.search }}
                </div>

                <div v-show="isOpenSearchPanel">
                    <div class="row">
                        <div class="col-md-3">
                            <VueCtkDateTimePicker
                                    :behaviour="{time: {nearestIfDisabled: true}}"
                                    :custom-shortcuts="customDateShortcuts" :label="app.trans.created_at"
                                    :only-date="true"
                                    :range="true" locale="en" v-model="date_range"/>
                        </div>

                        <div class="col-md-3">
                            <accounting-multi-select-with-search-layout-component
                                    :options="creators"
                                    :placeholder="app.trans.creator"
                                    :title="app.trans.creator"
                                    @valueUpdated="creatorListUpdated"
                                    default="0"
                                    identity="000000000"
                                    label_text="name"

                            >

                            </accounting-multi-select-with-search-layout-component>

                        </div>

                        <div class="col-md-3">
                            <select @change="pushServerRequest" class="form-control" v-model="filters.current_status">
                                <option value="null">{{ app.trans.current_status }}</option>
                                <option value="paid">{{ app.trans.paid }}</option>
                                <option value="credit">{{ app.trans.credit }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input :placeholder="app.trans.invoice_number" @keyup="pushServerRequest"
                                   class="form-control"
                                   type="text" v-model="filters.title">
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <accounting-multi-select-with-search-layout-component
                                    :options="vendors"
                                    :placeholder="app.trans.vendor"
                                    :title="app.trans.vendor"
                                    @valueUpdated="vendorListUpdated"
                                    default="0"
                                    identity="000000001"
                                    label_text="name"

                            >

                            </accounting-multi-select-with-search-layout-component>

                        </div>
                        <div class="col-md-3">
                            <input :placeholder="app.trans.total" @keyup="pushServerRequest"
                                   class="form-control"
                                   type="text" v-model="filters.total">
                        </div>
                        <div class="col-md-3">
                            <input :placeholder="app.trans.net" @keyup="pushServerRequest"
                                   class="form-control"
                                   type="text" v-model="filters.net">
                        </div>
                        <div class="col-md-3">
                            <input :placeholder="app.trans.tax" @keyup="pushServerRequest"
                                   class="form-control"
                                   type="text" v-model="filters.tax">
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
                            {{ app.trans.vendor }}
                        </th>

                        <th :class="{'orderBy':orderBy=='created_at'}" @click="setOrderByColumn('created_at')"
                            width="">
                            {{ app.trans.created_at }}
                        </th>


                        <th :class="{'orderBy':orderBy=='net'}" @click="setOrderByColumn('net')"
                            width="">
                            {{ app.trans.net }}
                        </th>

                        <th :class="{'orderBy':orderBy=='current_status'}" @click="setOrderByColumn('current_status')"
                            width="">
                            {{ app.trans.current_status }}
                        </th>

                        <th :class="{'orderBy':orderBy=='invoice_type'}" @click="setOrderByColumn('invoice_type')"
                            width="">
                            {{ app.trans.invoice_type }}
                        </th>


                        <th :class="{'orderBy':orderBy=='creator_id'}" @click="setOrderByColumn('creator_id')"
                            width="">
                            {{ app.trans.created_by }}
                        </th>


                        <th :class="{'orderBy':orderBy=='tax'}" @click="setOrderByColumn('tax')"
                            width="">
                            {{ app.trans.tax }}
                        </th>

                        <th v-text="app.trans.options" width="8%"></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr :key="row.id" v-for="(row,index) in table_rows">
                        <td v-text="index+1"></td>
                        <td class="text-center" v-text="row.title"></td>
                        <td class="text-center" v-text="row.purchase.vendor.locale_name"></td>
                        <td v-text="row.created_at"></td>
                        <td class="text-center" v-text="row.net"></td>
                        <td class="text-center">
                            <span v-if="row.current_status=='paid'">{{ app.trans.paid }}</span>
                            <span v-else>{{ app.trans.credit }}</span>
                        </td>
                        <td class="text-center">
                            <span v-if="row.invoice_type=='purchase'">{{ app.trans.purchase }}</span>
                            <span v-else>{{ app.trans.return_purchase }}</span>
                        </td>
                        <td class="text-center" v-text="row.creator.locale_name"></td>
                        <td class="text-center" v-text="row.tax"></td>
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

                                    <li v-if="canEdit==1 && row.invoice_type=='purchase' && row.is_deleted==0"><a
                                            :href="baseUrl + row.id + '/edit' "
                                                             v-text="app.trans.return"></a></li>

                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tbody>
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

    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';


    export default {
        components: {
            VueCtkDateTimePicker, Treeselect
        },
        props: [
            "canEdit",
            "canDelete",
            "canCreate",
            "creators",
            "vendors",
        ],
        data: function () {
            return {

                itemsPerPage: 20,
                isOpenSearchPanel: false,
                category: null,
                baseUrl: "",
                orderBy: "id",
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
                    vendors: null,
                    creators: null,
                    creator_id: null,
                    received_by: null,
                    net: null,
                    total: null,
                    tax: null,
                    current_status: null
                },
                paginationResponseData: null,
                tableSelectionActiveMode: false

            };
        },
        created() {
            this.initUi();
            this.pushServerRequest();

        },
        methods: {


            initUi() {
                this.requestUrl = this.app.datatableBaseUrl + 'purchases';
                this.baseUrl = this.app.trans.PurchaseBaseUrl + "/";
                this.customDateShortcuts = [
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


                axios.get(this.requestUrl, {
                    params: params
                }).then(function (response) {
                    appVm.table_rows = response.data.data;
                    appVm.isLoading = false;
                    appVm.paginationResponseData = response.data;
                }).catch(function (error) {
                    alert(error)
                }).finally(function () {
                    appVm.isLoading = false;
                });
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

            vendorListUpdated(e) {

                this.filters.vendors = db.model.pluck(e.items, 'id');
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
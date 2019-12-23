<template>
    <div class="table">
        <tile :color="primaryColor" :loading="isLoading" v-show="isLoading"></tile>
        <div class="table-posistion">

            <div class="table-filters">
                <div @click="openOrCloseSearchPanel" class="text-right search-text" style="cursor: pointer;"><i
                        class="fa fa-search-plus"></i>
                    {{trans.search_by_filters }}
                </div>

                <div v-show="isOpenSearchPanel">
                    <div class="row">
                        <div class="col-md-3">
                            <VueCtkDateTimePicker
                                    :behaviour="{time: {nearestIfDisabled: true}}"
                                    :custom-shortcuts="customDateShortcuts" :label="trans.created_at"
                                    :only-date="true"
                                    :range="true" locale="en" v-model="date_range"/>
                        </div>
                        <div class="col-md-3">
                            <input :placeholder="trans.barcode" @keyup.enter="pushServerRequest" class="form-control"
                                   type="text" v-model="filters.barcode">
                        </div>
                        <div class="col-md-3">
                            <input :placeholder="trans.price_placeholder" @keyup="pushServerRequest"
                                   class="form-control"
                                   type="text" v-model="filters.price">
                        </div>
                        <div class="col-md-3">
                            <select @change="pushServerRequest" class="form-control" v-model="filters.current_status">
                                <option value="all">{{ trans.status }}</option>
                                <option value="active">{{ trans.active }}</option>
                                <option value="pending">{{ trans.pending }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input :placeholder="trans.price_tax_placeholder" @keyup="pushServerRequest"
                                   class="form-control"
                                   type="text" v-model="filters.price_with_tax">
                        </div>
                        <div class="col-md-3">
                            <input :placeholder="trans.qty" @keyup="pushServerRequest" class="form-control"
                                   type="text" v-model="filters.available_qty">
                        </div>
                        <div class="col-md-3">
                            <input :placeholder="trans.id" @keyup="pushServerRequest" class="form-control"
                                   type="text" v-model="filters.id">
                        </div>
                        <div class="col-md-3">
                            <input :placeholder="trans.name" @keyup="pushServerRequest" class="form-control"
                                   type="text" v-model="filters.name">
                        </div>

                    </div>

                    <div class="table-advanced-search">
                        <accounting-table-filter-search-component
                                :categories="categories"
                                :trans="trans"
                                @filterValuesUpdated="advancedSearchUpdated"></accounting-table-filter-search-component>
                    </div>

                </div>
            </div>
            <div class="table-multi-task-buttons" v-show="showMultiTaskButtons">

            </div>
            <div class="table-content " v-show="!isLoading">
                <table class="table table-striped table-bordered" width="100%">
                    <thead>
                    <tr>
                        <th width="2%"><input @click="checkAndUncheckAllRowsCheckBoxChanged" type="checkbox"/></th>
                        <th :class="{'orderBy':orderBy=='id'}" @click="setOrderByColumn('id')" width="4%">
                            {{ trans.id }}
                        </th>
                        <th :class="{'orderBy':orderBy=='barcode'}" @click="setOrderByColumn('barcode')" width="13%">
                            {{ trans.barcode }}
                        </th>
                        <th :class="{'orderBy':orderBy=='name'}" @click="setOrderByColumn('name')">
                            {{ trans.name }}
                        </th>

                        <th :class="{'orderBy':orderBy=='price'}" @click="setOrderByColumn('price')" width="6%">
                            {{ trans.price }}
                        </th>

                        <th :class="{'orderBy':orderBy=='price_with_tax'}"
                            @click="setOrderByColumn('price_with_tax')" width="10%">
                            {{ trans.price_tax }}
                        </th>
                        <th :class="{'orderBy':orderBy=='available_qty'}" @click="setOrderByColumn('available_qty')"
                            width="5%">
                            {{ trans.available_qty }}
                        </th>

                        <th :class="{'orderBy':orderBy=='creator_id'}" @click="setOrderByColumn('creator_id')"
                            width="13%">
                            {{ trans.created_by }}
                        </th>
                        <th :class="{'orderBy':orderBy=='created_at'}" @click="setOrderByColumn('created_at')"
                            width="10%">
                            {{ trans.created_at }}
                        </th>

                        <th v-text="trans.options" width="8%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr :key="row.id" v-for="(row,index) in table_rows">
                        <td><input @change="rowSelectCheckBoxUpdated(row)" type="checkbox"
                                   v-model="row.tb_row_selected"/>
                        </td>
                        <td v-text="index+1"></td>
                        <td @click="sendItemToOpenInvoice(row)" style="text-align:left;cursor: pointer">
                            &nbsp;<span :style="{'color' :primaryColor}" v-if="row.is_need_serial">{{ row.barcode }}
                            </span>

                            <span v-else>{{ row.barcode}}</span> &nbsp;
                            <i :style="{'color':primaryColor}" class="fa fa-check-circle pull-left"
                               style="margin-top: 3px;" v-show="row.status=='active'"></i>
                        </td>

                        <td class="text-right-with-padding">{{row.locale_name}}</td>
                        <td v-text="row.price"></td>
                        <td v-text="row.price_with_tax"></td>
                        <td v-text="row.available_qty"></td>
                        <td class="text-right-with-padding" v-text="row.creator.name"></td>
                        <td v-text="row.created_at"></td>
                        <td>

                            <div class="dropdown">
                                <button :id="'dropDownOptions'
                                + row.id" aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-options dropdown-toggle " data-toggle="dropdown"
                                        type="button">
                                    {{ trans.options }}
                                    <span class="caret"></span>
                                </button>
                                <ul :aria-labelledby="'dropDownOptions'
                                + row.id" class="dropdown-menu CustomDropDownOptions">
                                    <!--                                    <li><a :href="baseUrl + row.id" v-text="trans.show"></a></li>-->
                                    <li v-if="canCreate==1"><a :href="baseUrl + row.id + '/clone'"
                                                               v-text="trans.clone"></a></li>
                                    <li v-if="canViewAccounting==1"><a :href="baseUrl + row.id + '/transactions'"
                                                                       v-text="trans.transactions"></a></li>
                                    <li v-if="canEdit==1"><a :href="baseUrl + row.id + '/edit'"
                                                             v-text="trans.edit"></a></li>
                                    <li v-if="row.is_need_serial==1"><a :href="baseUrl + row.id + '/view_serials'"
                                                                        v-text="trans.view_serials"></a></li>
                                    <li v-if="row.status=='pending' && canEdit==1"><a :href="baseUrl + row.id +
                                    '/activate'" v-text="trans.activate"></a></li>
                                </ul>
                            </div>

                        </td>
                    </tr>
                    </tbody>
                </table>


            </div>

            <div class="table-paginations">
                <accounting-table-pagination-helper-layout-component :data="paginationResponseData"

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
    import {transfer} from '../../item';

    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';


    export default {
        components: {
            VueCtkDateTimePicker, Treeselect
        },
        props: [
            "categories",
            "canEdit",
            "canDelete",
            "canCreate",
            "canViewAccounting",
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
                primaryColor: metaHelper.getContent('primary-color'),
                secondColor: metaHelper.getContent('second-color'),
                appLocate: metaHelper.getContent('app-locate'),
                trans: trans('items-page'),
                messages: trans('messages'),
                table_trans: trans('table'),
                datetimetrans: trans('datetime'),
                datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
                customDateShortcuts: [],
                date_range: null,
                showMultiTaskButtons: false,
                requestUrl: "",
                filters: {
                    endDate: null,
                    startDate: null,
                    barcode: null,
                    price: null,
                    price_with_tax: null,
                    available_qty: null,
                    name: null,
                    current_status: "all",
                    categoryIds: [],
                    filters: [],
                    id: null
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
                this.requestUrl = this.datatableBaseUrl + 'items';
                this.baseUrl = this.trans.baseUrl + "/";
                this.customDateShortcuts = [
                    {key: 'thisWeek', label: this.datetimetrans.thisWeek, value: 'isoWeek'},
                    {key: 'lastWeek', label: this.datetimetrans.lastWeek, value: '-isoWeek'},
                    {key: 'last7Days', label: this.datetimetrans.last7Days, value: 7},
                    {key: 'last30Days', label: this.datetimetrans.last30Days, value: 30},
                    {key: 'thisMonth', label: this.datetimetrans.thisMonth, value: 'month'},
                    {key: 'lastMonth', label: this.datetimetrans.lastMonth, value: '-month'},
                    {key: 'thisYear', label: this.datetimetrans.thisYear, value: 'year'},
                    {key: 'lastYear', label: this.datetimetrans.lastYear, value: '-year'}
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

            sendItemToOpenInvoice(item) {
                transfer.pushToOpenInvoice(item);
                this.$toast.success({
                    type: 'success',
                    showMethod: 'lightSpeedIn',
                    closeButton: false,
                    timeOut: 2000,
                    icon: '',
                    title: this.messages.process_title,
                    message: this.messages.process_done,
                    progressBar: true,
                    hideDuration: 1000
                });
            },
            paginateUpdatePage(event) {
                this.requestUrl = event.link;
                this.pushServerRequest();

            },

            pagePerItemsUpdated(event) {

                this.itemsPerPage = event.items;
                this.pushServerRequest();

            },
            categoryUpdated(e) {
                this.filters.category_id = e.id;
                this.pushServerRequest();
            },


            openOrCloseSearchPanel() {
                this.isOpenSearchPanel = !this.isOpenSearchPanel;
            },

            advancedSearchUpdated(event) {
                this.filters.categoryIds = event.categoryIds;
                this.filters.filters = event.searchFilters;
                if (event.categoryIds == []) {
                    this.filters.filters = [];
                }

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

            exportsPdf() {

            }

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

    .form-control, .field-input {
        /*text-align: right !important;*/

    }

    .orderBy {
        background-color: cornsilk;
    }

    .sort-icon {
        color: #999;
        float: right;
        margin-right: 1px;
        font-size: 17px;
    }


    .dropdown-menu li a {
        padding: 7px;
        font-size: 14px;
        border-bottom: 1px solid #eee;
    }

    .vue-treeselect__control {
        padding: 7px !important;
        border-radius: 0px !important;
    }

    .table-multi-task-buttons {
        padding: 5px;
    }


</style>
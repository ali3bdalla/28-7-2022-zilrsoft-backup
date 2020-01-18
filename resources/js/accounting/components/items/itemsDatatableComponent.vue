<template>
    <div class="table">

        <div class="table-posistion">

            <div>
                <input :placeholder="trans.search_barcode_sale" @focus="$event.target.select()"
                       @keyup.enter="barcodeAndNameUpdated"
                       autofocus="autofocus"
                       class="form-control"
                       ref="barcodeAndNameUpdated"
                       type="text"
                       v-model="filters.barcodeNameAndSerial">
            </div>
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
                            <input :placeholder="trans.barcode" @keyup.enter="pushServerRequest('barcodeFieldRef')"
                                   class="form-control"
                                   ref="barcodeFieldRef"
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
                            <accounting-multi-select-with-search-layout-component
                                    :options="creators"
                                    :placeholder="trans.creator"
                                    :title="trans.creator"
                                    @valueUpdated="creatorListUpdated"
                                    default="0"
                                    identity="000000001"
                                    label_text="locale_name"

                            >

                            </accounting-multi-select-with-search-layout-component>

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
                <button @click="activateListItems" class="btn btn-default">{{ trans.activate }}</button>
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
                        <td v-text="getRowColumnIndex(index + 1)"></td>
                        <td @click="sendItemToOpenInvoice(row)" style="text-align:left;cursor: pointer">
                            &nbsp;<span :style="{'color' :primaryColor}" v-if="row.is_need_serial">{{ row.barcode }}
                            </span>

                            <span style="color:green" v-else-if="row.is_kit">{{ row.barcode}}</span>
                            <span v-else>{{ row.barcode}}</span> &nbsp;
                            <i :style="{'color':primaryColor}" class="fa fa-check-circle pull-left"
                               style="margin-top: 3px;" v-show="row.status=='active'"></i>
                        </td>

                        <td class="text-right-with-padding">{{row.ar_name}}<p align="left">{{row.name}}</p></td>
                        <td v-text="parseFloat(row.price).toFixed(2)"></td>
                        <td v-text="parseFloat(row.price_with_tax).toFixed(2)"></td>
                        <td v-text="row.available_qty"></td>
                        <td class="text-right-with-padding" v-text="row.creator.locale_name"></td>
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
                                    <li v-if="row.is_kit"><a :href="BaseApiUrl + 'kits/' + row.id"
                                                             v-text="trans.show"></a></li>
                                    <li v-if="canCreate==1  && !row.is_kit"><a :href="baseUrl + row.id + '/clone'"
                                                                               v-text="trans.clone"></a></li>
                                    <li v-if="!row.is_kit"><a :href="baseUrl + row.id +
                                    '/transactions'"
                                                                                      v-text="trans.transactions"></a>
                                    </li>
                                    <li v-if="canEdit==1  && !row.is_kit"><a :href="baseUrl + row.id + '/edit'"
                                                                             v-text="trans.edit"></a></li>
                                    <li v-if="row.is_need_serial==1  && !row.is_kit"><a
                                            :href="baseUrl + row.id + '/view_serials'"
                                            v-text="trans.view_serials"></a></li>
                                    <li v-if="row.status=='pending' && canEdit==1"><a :href="baseUrl + row.id +
                                    '/activate'" v-text="trans.activate"></a></li>

                                    <li v-if="canDelete==1 && canViewAccounting==1"><a @click="deleteItemClicked(row)"
                                                                                       v-text="trans.delete"></a></li>
                                </ul>
                            </div>

                        </td>
                    </tr>
                    </tbody>
                </table>


            </div>

            <tile :color="primaryColor" :loading="isLoading" v-show="isLoading"></tile>
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
    import {query as ItemQuery, transfer} from '../../item';

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
            "creators"
        ],
        data: function () {
            return {

                itemsPerPage: 20,
                isOpenSearchPanel: false,
                category: null,
                baseUrl: "",
                orderBy: "updated_at",
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
                BaseApiUrl: metaHelper.getContent('BaseApiUrl'),
                datetimetrans: trans('datetime'),
                datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
                customDateShortcuts: [],
                date_range: null,
                showMultiTaskButtons: false,
                requestUrl: "",
                filters: {
                    endDate: null,
                    startDate: null,
                    barcodeNameAndSerial: "",
                    barcode: null,
                    price: null,
                    price_with_tax: null,
                    available_qty: null,
                    name: null,
                    current_status: "all",
                    categoryIds: [],
                    filters: [],
                    creators: []
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

            barcodeAndNameUpdated() {
                this.pushServerRequest();
                this.$refs.barcodeAndNameUpdated.select()
            },


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
            pushServerRequest: function (ref = null) {


                this.isLoading = true;
                var appVm = this;
                var params = appVm.filters;
                params.orderBy = this.orderBy;
                params.itemsPerPage = this.itemsPerPage;
                params.orderType = this.orderType;


                axios.get(this.requestUrl, {
                    params: params
                }).then(function (response) {
                    // console.log(response.data);
                    appVm.table_rows = response.data.data;
                    appVm.isLoading = false;
                    appVm.paginationResponseData = response.data;
                }).catch(function (error) {
                    alert(error)
                }).finally(function () {
                    appVm.isLoading = false;
                });

                if (ref !== null) {
                    this.$refs[ref].focus();
                    this.$refs[ref].select();

                    // this.$refs[ref][0].focus();
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

            creatorListUpdated(e) {

                this.filters.creators = db.model.pluck(e.items, 'id');
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

            },
            getRowColumnIndex(index) {
                if (this.paginationResponseData != null) {
                    return index + ((this.paginationResponseData.current_page - 1) *
                        this.paginationResponseData.per_page);
                }
                return index;
            },
            activateListItems() {

                var appVm = this;
                var ids = db.model.pluck(this.table_rows, 'id', 'tb_row_selected', true);
                if (ids !== []) {
                    ItemQuery.sendQueryRequestToActivateItems(ids).then(response => {
                        appVm.pushServerRequest();

                    }).catch(error => {
                        alert(error);
                    })
                }
            },


            deleteItemClicked(itemData) {


                let options = {
                    html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
                    loader: false, // set to true if you want the dailog to show a loader after click on "proceed"
                    reverse: false, // switch the button positions (left to right, and vise versa)
                    okText: this.messages.ok_button_txt,
                    cancelText: this.messages.close_pop_txt,
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'hard', // coming soon: 'soft', 'hard'
                    verification: 'delete',
                    // for hard confirm, user will be prompted to type this to enable the proceed button
                    verificationHelp: 'اكتب "[+:verification]" لتأكيد عملية الحذف ',
                    // Verification help text. [+:verification] will be matched with 'options.verification' (i.e 'Type "continue" below to confirm')
                    clicksCount: 3, // for soft confirm, user will be asked to click on "proceed" btn 3 times before actually proceeding
                    backdropClose: false, // set to true to close the dialog when clicking outside of the dialog window, i.e. click landing on the mask
                    customClass: 'danger'
                    // Custom class to be injected into the parent node for the current dialog instance
                };

                var appVm = this;

                this.$dialog
                    .confirm(this.messages.confirm_msg, options)
                    .then(dialog => {

                        axios.delete(appVm.baseUrl + itemData.id)
                            .then(function (response) {
                                // console.log(response.data);
                                window.location.reload();
                            })
                            .catch(function (error) {

                            });

                    })
                    .catch(() => {

                    });
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
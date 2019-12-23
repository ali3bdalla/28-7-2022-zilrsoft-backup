<template>
    <div class="table">
        <tile :color="app.primaryColor" :loading="isLoading" v-show="isLoading"></tile>
        <div class="table-posistion">

            <div class="table-filters">
                <div @click="openOrCloseSearchPanel" class="text-right search-text" style="cursor: pointer;"><i
                        class="fa fa-search-plus"></i>
                    {{app.trans.search_by_filters }}
                </div>

                <div v-show="isOpenSearchPanel">
                    <div class="row">
                        <div class="col-md-4">
                            <VueCtkDateTimePicker
                                    :behaviour="{time: {nearestIfDisabled: true}}"
                                    :custom-shortcuts="customDateShortcuts" :label="app.trans.created_at"
                                    :only-date="true"
                                    :range="true" locale="en" v-model="date_range"/>
                        </div>

                        <div class="col-md-4">
                            <input :placeholder="app.trans.id" @keyup="pushServerRequest" class="form-control"
                                   type="text" v-model="filters.id">
                        </div>
                        <div class="col-md-4">
                            <input :placeholder="app.trans.name" @keyup="pushServerRequest" class="form-control"
                                   type="text" v-model="filters.global_name">
                        </div>

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
                            {{ app.trans.id }}
                        </th>
                        <th :class="{'orderBy':orderBy=='ar_name'}" @click="setOrderByColumn('ar_name')">
                            {{ app.trans.ar_name }}
                        </th>
                        <th :class="{'orderBy':orderBy=='name'}" @click="setOrderByColumn('name')">
                            {{ app.trans.name }}
                        </th>

                        <th :class="{'orderBy':orderBy=='creator_id'}" @click="setOrderByColumn('creator_id')"
                            width="13%">
                            {{ app.trans.created_by }}
                        </th>
                        <th :class="{'orderBy':orderBy=='created_at'}" @click="setOrderByColumn('created_at')"
                            width="10%">
                            {{ app.trans.created_at }}
                        </th>

                        <th v-text="app.trans.options" width="8%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr :key="row.id" v-for="(row,index) in table_rows">
                        <td><input @change="rowSelectCheckBoxUpdated(row)" type="checkbox"
                                   v-model="row.tb_row_selected"/>
                        </td>
                        <td v-text="index+1"></td>

                        <td class="text-center" v-text="row.ar_name"></td>
                        <td class="text-center" v-text="row.name"></td>
                        <td class="text-center" v-text="row.creator.name"></td>
                        <td v-text="row.created_at"></td>
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
                                    <li v-if="canEdit==1"><a :href="baseUrl + row.id + '/edit'"
                                                             v-text="app.trans.edit"></a></li>

                                    <li @click="deleteItemClicked(row)"
                                        v-if="canDelete==1"><a v-text="app.trans.delete"></a></li>

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

    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';


    export default {
        components: {
            VueCtkDateTimePicker, Treeselect
        },
        props: [
            "canEdit",
            "canDelete",
            "canCreate",
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
                    trans: trans('filters-page'),
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
                    name: null,
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
                this.requestUrl = this.app.datatableBaseUrl + 'filters';
                this.baseUrl = this.app.trans.baseUrl + "/";
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

            deleteItemClicked(filter) {


                let options = {
                    html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
                    loader: false, // set to true if you want the dailog to show a loader after click on "proceed"
                    reverse: false, // switch the button positions (left to right, and vise versa)
                    okText: this.app.messages.ok_button_txt,
                    cancelText: this.app.messages.close_pop_txt,
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
                    .confirm(this.app.messages.confirm_msg, options)
                    .then(dialog => {

                        axios.delete(appVm.app.BaseApiUrl + "filters/" + filter.id)
                            .then(function (response) {
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
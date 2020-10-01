<template>
    <div class="panel">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <input @change="sendUpdateFilterData" class="form-control arabic-input"
                           type="text"
                           v-model="filterData.ar_name"/>
                </div>
                <div class="col-md-6">
                    <input @change="sendUpdateFilterData" class="form-control arabic-input"
                           type="text"
                           v-model="filterData.name"/>
                </div>
            </div>
        </div>
        <!-- create new value model -->
        <div justify="center">
            <v-dialog
                    :dark="true"
                    :eager="true"
                    :full-width="false"
                    :hide-overlay="true"
                    :isActive="filterUpdate.showCreateValueDialog"
                    :smAndDown="true"
                    v-model="filterUpdate.showCreateValueDialog"
                    width="700">

                <v-card>
                    <v-card-title class="headline grey lighten-2" primary-title>
                        ({{ filter.locale_name }})
                    </v-card-title>

                    <v-card-text>
                        <div class='row'>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input
                                            :placeholder='app.trans.ar_name'
                                            @keyup.13="sendCreateValueRequest" class="form-control arabic-input"
                                            style="direction:rtl"
                                            v-model="filterUpdate.valueArName"/>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input :placeholder='app.trans.name'
                                           @keyup.13="sendCreateValueRequest"
                                           class="form-control"
                                           style="direction:ltr" v-model="filterUpdate.valueEnName"/>
                                </div>
                            </div>

                        </div>
                    </v-card-text>


                    <v-card-actions>

                        <div class="row">
                            <div class="col-md-6 text-center">
                                <button
                                        @click="sendCreateValueRequest"
                                        class="btn btn-custom-primary">
                                    {{app.trans.create}}
                                </button>
                            </div>
                            <div class="col-md-6 text-center">
                                <v-btn
                                        @click="filterUpdate.showCreateValueDialog = false"
                                        class="btn btn-default"
                                        text>
                                    {{app.trans.cancel}}
                                </v-btn>
                            </div>
                        </div>
                    </v-card-actions>
                </v-card>
            </v-dialog>

        </div>
        <!-- create new value model -->


        <div class="panel-body">
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
                                       type="text" v-model="filters.name">
                            </div>

                        </div>


                    </div>
                </div>
                <div class="table-multi-task-buttons">
                    <button @click="filterUpdate.showCreateValueDialog = true" class="btn btn-custom-primary">{{
                        app.trans.create_value
                        }}
                    </button>
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

                            <td class="text-right-with-padding">
                                <input @change="sendUpdateValueRequest(row)" class="form-control arabic-input"
                                       type="text"
                                       v-model="row.ar_name"/>
                            </td>
                            <td class="text-right-with-padding">
                                <input @change="sendUpdateValueRequest(row)" class="form-control"
                                       type="text"
                                       v-model="row.name"/>
                            </td>
                            <td class="text-right-with-padding" v-text="row.creator.name"></td>
                            <td v-text="row.created_at"></td>
                            <td>

                                <button @click="deleteItemClicked(row)" class="btn btn-danger">حذف</button>

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
            "filter"
        ],
        data: function () {
            return {

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
                tableSelectionActiveMode: false,
                filterData: null,
                filterUpdate: {
                    showCreateValueDialog: false,
                    filterDataToCreateValueFor: null,
                    defaultValueObj: null,
                    valueArName: "",
                    valueEnName: "",
                    filterValueUpdateMode: 'edit'
                }

            };
        },
        created() {
            this.filterUpdate.filterDataToCreateValueFor = this.filter;
            this.filterData = this.filter;
            this.initUi();
            this.pushServerRequest();

        },
        methods: {


            initUi() {
                this.requestUrl = this.app.datatableBaseUrl + this.filter.id + '/filter_values';
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

            deleteItemClicked(value) {


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

                        axios.delete(appVm.app.BaseApiUrl + "filter_values/" + value.id)
                            .then(function (response) {
                                window.location.reload();
                            })
                            .catch(function (error) {

                            });

                    })
                    .catch(() => {

                    });
            },


            createNewFilterValue() {
                this.filterUpdate.showCreateValueDialog = true;
            },

            sendUpdateFilterData() {
                let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                });
                axios.put(this.app.BaseApiUrl + 'filters/' + this.filterData.id,
                    {
                        name: this.filterData.name,
                        ar_name: this.filterData.ar_name,
                    })
                    .then(function (response) {

                        // var
                        loader.hide();


                    })
                    .catch(function (error) {
                        loader.hide();
                        alert(error.response.data.message);
                    });

            },


            sendUpdateValueRequest(value) {
                let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                });
                axios.put(this.app.BaseApiUrl + 'filter_values/' + value.id,
                    {
                        name: value.name,
                        ar_name: value.ar_name,
                        filter_id: value.filter_id
                    })
                    .then(function (response) {

                        // var
                        loader.hide();


                    })
                    .catch(function (error) {
                        loader.hide();
                        alert(error.response.data.message);
                    });

            },


            sendCreateValueRequest() {
                var vm = this;
                var appVm = this;
                let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                });


                axios.post(this.app.BaseApiUrl + 'filter_values', {
                    filter_id: this.filterUpdate.filterDataToCreateValueFor.id,
                    name: this.filterUpdate.valueEnName,
                    ar_name: this.filterUpdate.valueArName,
                })
                    .then(function (response) {

                        vm.filterUpdate.showCreateValueDialog = false;
                        vm.filterUpdate.valueArName = "";
                        vm.filterUpdate.valueEnName = "";
                        loader.hide();
                        vm.table_rows.push(response.data);
                    })
                    .catch(function (error) {
                        loader.hide();
                        alert(error.response.data.message);
                    });


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
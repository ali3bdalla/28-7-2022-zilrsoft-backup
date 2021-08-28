<template>
    <div class="table">

        <div class="table-posistion">

          <div class="table-filters">

                <shipping-transactions-asgin v-if="asingedItems.length" :items="asingedItems" :shipping-men='deliveryMen'></shipping-transactions-asgin>

                <!--<div @click="openOrCloseSearchPanel" class="text-right search-text" style="cursor: pointer;"><i
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
                            <select @change="pushServerRequest" class="form-control" v-model="filters.identityType">
                                <option value="all">{{ trans.all }}</option>
                                <option value="vendor">{{ trans.vendor }}</option>
                                <option value="supplier">{{ trans.supplier }}</option>
                                <option value="client">{{ trans.client }}</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input :placeholder="trans.id" @keyup="pushServerRequest" class="form-control"
                                   type="text" v-model="filters.id">
                        </div>
                        <div class="col-md-3">
                            <input :placeholder="trans.global_name" @keyup="pushServerRequest" class="form-control"
                                   type="text" v-model="filters.name">
                        </div>

                    </div>

                </div>-->
            </div>

            <div class="table-content " v-show="!isLoading">
                <table class="table table-striped table-bordered" width="100%">
                    <thead>
                    <tr>
                        <th :class="{'orderBy':orderBy=='id'}" @click="setOrderByColumn('id')" width="4%">
                            #
                        </th>
                        <!-- <th :class="{'orderBy':orderBy=='id'}" @click="setOrderByColumn('id')" width="4%">
                            #
                        </th> -->

                        <th :class="{'orderBy':orderBy=='name'}" @click="setOrderByColumn('name')">
                            وسيلة الشحن
                        </th>
                        <th>
                           المدينة
                        </th>
                        <th>
                          رقم التتبع
                        </th>
                        <th>
                          رقم الطلب
                        </th>
                        <th>
                            {{ trans.phone_number }}
                        </th>
                        <th :class="{'orderBy':orderBy=='creator_id'}" @click="setOrderByColumn('creator_id')"
                        >
                            الحالة
                        </th>
                        <th :class="{'orderBy':orderBy=='creator_id'}" @click="setOrderByColumn('creator_id')"
                        >
                            المندوب
                        </th>

                        <th :class="{'orderBy':orderBy=='creator_id'}" @click="setOrderByColumn('creator_id')"
                        >
                            {{ trans.created_by }}
                        </th>
                        <th :class="{'orderBy':orderBy=='created_at'}" @click="setOrderByColumn('created_at')"
                        >
                            {{ trans.created_at }}
                        </th>

                        <th  width="8%">البوليصة</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr :key="row.id" v-for="(row,index) in table_rows">
                      <td><input v-if="row.status =='issued'" @change="rowSelectCheckBoxUpdated(row)" type="checkbox"
                                   v-model="row.tb_row_selected"/>
                        <!-- <td v-text="index+1"></td> -->

                        <td class="text-center">{{shippingMethod.locale_name}}</td>
                        <td class="text-center">
                            {{  row.city.locale_name }}
                        </td>
                         <td class="text-center">
                             <a target="_blank" :href="`https://www.smsaexpress.com/ar/trackingdetails?tracknumbers=${row.tracking_number}`">{{  row.tracking_number }}</a>
                        </td>
                         <td class="text-center">
                            {{  row.order_id }}
                        </td>
                        <td class="text-center">{{row.phone_number}}</td>
                        <td class="text-center">
                          <span v-if="row.status =='issued'" class="label label-danger">لم يتم تشحن</span>
                          <span v-else class="label label-success">تم تشحن</span>
                        <td class="text-center">{{row.delivery_man ? row.delivery_man.locale_name: "-" }}</td>

                        <td class="" v-text="row.creator.locale_name"></td>
                        <td v-text="row.created_at"></td>
                        <td>

                            <a v-if="shippingMethod.id == 2" :href="`/store/shipping/${shippingMethod.id}/${row.id}/download`" class="btn btn-primary">
                                   تحميل
                                </a>
                            <!-- <div class="dropdown">
                                <button :id="'dropDownOptions'
                                + row.id" aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-options dropdown-toggle " data-toggle="dropdown"
                                        type="button">
                                    {{ trans.options }}
                                    <span class="caret"></span>
                                </button>
                                <ul :aria-labelledby="'dropDownOptions'
                                + row.id" class="dropdown-menu CustomDropDownOptions">

                                    <li><a :href="baseUrl + row.id" v-text="trans.view"></a></li>

                                    <li v-if="canEdit==1"><a :href="baseUrl + row.id + '/edit'"
                                                             v-text="trans.edit"></a></li>

                                </ul>
                            </div> -->

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

// import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'

// import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'

export default {

  props: [
    'categories',
    'canEdit',
    'canDelete',
    'canCreate',
    'canViewAccounting',
    'shippingMethod',
    'deliveryMen'
  ],
  data: function () {
    return {
      asingedItems: [],
      itemsPerPage: 20,
      isOpenSearchPanel: false,
      category: null,
      baseUrl: '',
      orderBy: 'created_at',
      orderType: 'desc',
      yourValue: null,
      table_rows: [],

      isLoading: true,
      primaryColor: metaHelper.getContent('primary-color'),
      secondColor: metaHelper.getContent('second-color'),
      appLocate: metaHelper.getContent('app-locate'),
      trans: trans('users-page'),
      messages: trans('messages'),
      table_trans: trans('table'),
      datetimetrans: trans('datetime'),
      datatableBaseUrl: metaHelper.getContent('datatableBaseUrl'),
      BaseApiUrl: metaHelper.getContent('BaseApiUrl'),
      customDateShortcuts: [],
      date_range: null,
      showMultiTaskButtons: false,
      requestUrl: '',
      filters: {
        endDate: null,
        startDate: null,
        name: null,
        identityType: 'all',
        id: null
      },
      paginationResponseData: null,
      tableSelectionActiveMode: false

    }
  },
  created () {
    this.initUi()
    this.pushServerRequest()
  },
  methods: {

    initUi () {
      this.requestUrl = '/store/shipping/' + this.shippingMethod.id + '/fetch_transactions'
      this.baseUrl = this.trans.baseUrl + '/'
      this.customDateShortcuts = [
        { key: 'thisWeek', label: this.datetimetrans.thisWeek, value: 'isoWeek' },
        { key: 'lastWeek', label: this.datetimetrans.lastWeek, value: '-isoWeek' },
        { key: 'last7Days', label: this.datetimetrans.last7Days, value: 7 },
        { key: 'last30Days', label: this.datetimetrans.last30Days, value: 30 },
        { key: 'thisMonth', label: this.datetimetrans.thisMonth, value: 'month' },
        { key: 'lastMonth', label: this.datetimetrans.lastMonth, value: '-month' },
        { key: 'thisYear', label: this.datetimetrans.thisYear, value: 'year' },
        { key: 'lastYear', label: this.datetimetrans.lastYear, value: '-year' }
      ]
    },
    pushServerRequest: function () {
      this.isLoading = true
      const appVm = this
      const params = appVm.filters
      params.orderBy = this.orderBy
      params.itemsPerPage = this.itemsPerPage
      params.orderType = this.orderType

      axios.get(this.requestUrl, {
        params: params
      }).then(function (response) {
        appVm.table_rows = response.data.data
        appVm.isLoading = false
        appVm.paginationResponseData = response.data
      }).catch(function (error) {
        alert(error)
      }).finally(function () {
        appVm.isLoading = false
      })
    },

    setOrderByColumn (column_name) {
      if (this.orderBy == column_name) {
        // alert('hello')
        if (this.orderType == 'asc') { this.orderType = 'desc' } else { this.orderType = 'asc' }
      } else {
        this.orderBy = column_name
        this.orderType = 'asc'
      }
      this.pushServerRequest()
    },

    paginateUpdatePage (event) {
      this.requestUrl = event.link
      this.pushServerRequest()
    },

    pagePerItemsUpdated (event) {
      this.itemsPerPage = event.items
      this.pushServerRequest()
    },

    openOrCloseSearchPanel () {
      this.isOpenSearchPanel = !this.isOpenSearchPanel
    },

    advancedSearchUpdated (event) {
      this.filters.category_id = event.categoryId
      this.filters.filters = event.searchFilters
      this.pushServerRequest()
    },
    checkAndUncheckAllRowsCheckBoxChanged () {
      const items = this.table_rows
      const len = items.length

      const newItems = []
      for (let index = 0; index < len; index++) {
        const item = items[index]
        if (this.tableSelectionActiveMode) {
          item.tb_row_selected = false
        } else {
          item.tb_row_selected = true
        }
        newItems.push(item)
      }

      if (this.tableSelectionActiveMode) {
        this.showMultiTaskButtons = false
      } else {
        this.showMultiTaskButtons = true
      }

      this.table_rows = newItems
      this.tableSelectionActiveMode = !this.tableSelectionActiveMode
    },
    rowSelectCheckBoxUpdated (item) {
      if (!this.asingedItems.includes(item.id)) { this.asingedItems.push(item.id) }
      // else { this.asingedItems.push(item.id) }
    },
    showOrHideMultiTaskButtons () {
      const items = this.table_rows
      const len = items.length

      let showButtons = false
      for (let index = 0; index < len; index++) {
        const item = items[index]
        if (item.tb_row_selected) {
          showButtons = true
        }
      }

      this.showMultiTaskButtons = showButtons
    }

  },

  watch: {
    date_range: function (value) {
      if (value == null) {
        this.filters.startDate = null
        this.filters.endDate = null
      } else {
        this.filters.startDate = value.start
        this.filters.endDate = value.end
      }
      this.pushServerRequest()
    }

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

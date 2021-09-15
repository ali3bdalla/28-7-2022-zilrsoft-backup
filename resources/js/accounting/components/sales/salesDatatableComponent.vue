<template>
  <div class="table">
    <div class="table-posistion">
      <div v-if="onlyQuotations===true">
        <input ref="barcodeAndNameUpdated" v-model="filters.title"
               autofocus="autofocus"
               class="form-control"
               placeholder=" رقم الفاتورة"
               type="text"
               @focus="$event.target.select()"
               @keyup="pushServerRequest">
      </div>

      <div class="table-filters">
        <div class="text-right search-text" style="cursor: pointer;" @click="openOrCloseSearchPanel"><i
            class="fa fa-search-plus"></i>
          {{ app.trans.search }}
        </div>

        <div v-show="isOpenSearchPanel">
          <div class="row">
            <div class="col-md-3">
              <VueCtkDateTimePicker
                  v-model="date_range"

                  :auto-close="true"
                  :behaviour="{time: {nearestIfDisabled: true}}"
                  :custom-shortcuts="customDateShortcuts"
                  :label="app.trans.created_at"
                  :only-date="false"
                  :range="true"
                  locale="en"/>
            </div>

            <div class="col-md-3">
              <accounting-multi-select-with-search-layout-component

                  :options="creators"
                  :placeholder="app.trans.salesman"
                  :title="app.trans.salesman"
                  default="0"
                  identity="000000003"
                  label_text="locale_name"
                  @valueUpdated="salesmanListUpdated"

              >

              </accounting-multi-select-with-search-layout-component>

            </div>

            <div v-if="onlyQuotations!==true" class="col-md-2">
              <select v-model="filters.invoice_type" class="form-control" @change="pushServerRequest">
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
                  default="0"
                  identity="000000001"
                  label_text="locale_name"
                  @valueUpdated="clientListUpdated"

              >

              </accounting-multi-select-with-search-layout-component>

            </div>
          </div>

          <div class="row">
            <div class="col-md-2">
              <input v-model="filters.net" :placeholder="app.trans.net"
                     class="form-control"
                     type="text" @keyup="pushServerRequest">
            </div>
            <div class="col-md-2">
              <input v-model="filters.title" :placeholder="app.trans.invoice_number"
                     class="form-control"
                     type="text" @keyup="pushServerRequest">
            </div>

            <div v-if="canViewAccounting===1" class="col-md-2">
              <input v-model="filters.total" :placeholder="app.trans.total"
                     class="form-control"
                     type="text" @keyup="pushServerRequest">
            </div>
            <div v-if="canViewAccounting===1" class="col-md-3">
              <accounting-multi-select-with-search-layout-component
                  :options="creators"
                  :placeholder="app.trans.creator"
                  :title="app.trans.creator"
                  default="0"
                  identity="000000000"
                  label_text="locale_name"
                  @valueUpdated="creatorListUpdated"

              >

              </accounting-multi-select-with-search-layout-component>

            </div>

            <div v-if="canViewAccounting===1" class="col-md-3">
              <input v-model="filters.tax" :placeholder="app.trans.tax"
                     class="form-control"
                     type="text" @keyup="pushServerRequest">
            </div>

            <div v-if="canViewAccounting===1" class="col-md-3">
              <accounting-multi-select-with-search-layout-component
                  :options="departments"
                  :placeholder="app.trans.department"
                  :title="app.trans.department"
                  default="0"
                  identity="000000005"
                  label_text="locale_title"
                  @valueUpdated="departmentListUpdated"

              >

              </accounting-multi-select-with-search-layout-component>

            </div>

            <div class="col-md-3">
              <input v-model="filters.aliceName" class="form-control"
                     placeholder="الاسم المستعار"
                     type="text" @keyup="pushServerRequest">
            </div>
          </div>

        </div>
      </div>
      <div v-show="showMultiTaskButtons" class="table-multi-task-buttons">

      </div>
      <div v-show="!isLoading" class="table-content">
        <table :class="{'table-striped':!onlyQuotations}" class="table  table-bordered" width="100%">
          <thead>
          <tr>
            <th width="4%" @click="setOrderByColumn('id')">
              {{ app.trans.id }}
            </th>

            <th :class="{'orderBy':orderBy==='id'}" @click="setOrderByColumn('id')">
              {{ app.trans.invoice_number }}
            </th>
            <th>
              {{ app.trans.client }}
            </th>

            <th :class="{'orderBy':orderBy==='created_at'}" width=""
                @click="setOrderByColumn('created_at')">
              {{ app.trans.created_at }}
            </th>

            <th :class="{'orderBy':orderBy==='net'}" width=""
                @click="setOrderByColumn('net')">
              {{ app.trans.net }}
            </th>

            <th v-if="canViewAccounting===1" :class="{'orderBy':orderBy==='subtotal'}"
                width=""
                @click="setOrderByColumn('subtotal')">
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

            <th :class="{'orderBy':orderBy==='invoice_type'}" width=""
                @click="setOrderByColumn('invoice_type')">
              {{ app.trans.invoice_type }}
            </th>

            <th v-if="canViewAccounting===1" :class="{'orderBy':orderBy==='creator_id'}"
                width="" @click="setOrderByColumn('creator_id')">
              {{ app.trans.created_by }}
            </th>

            <th
                width="">
              {{ app.trans.salesman }}
            </th>

            <th v-if="canViewAccounting===1" :class="{'orderBy':orderBy==='tax'}"
                width=""
                @click="setOrderByColumn('tax')">
              {{ app.trans.tax }}
            </th>

            <th width="8%" v-text="app.trans.options"></th>
          </tr>
          </thead>
          <tbody>

          <tr v-for="(row,index) in table_rows" :key="row.id"
              :class="{'bg-info':onlyQuotations === true && row.is_draft_converted}">
            <td v-text="index+1"></td>
            <td class="text-center" v-text="row.invoice_number"></td>
            <td class="text-center"
                v-text="row.sale  == null || row.user_alice_name==null  || row.user_alice_name === '' ? row.user ? row.user.locale_name : '': ''"></td>
            <td v-text="row.created_at"></td>
            <td class="text-center" v-text="row.net"></td>
            <td v-if="canViewAccounting===1" class="text-center" v-text="row.subtotal"></td>
            <td v-if="canViewAccounting===1 && row.invoice_type==='sale'" class="text-center"
                v-text="parseFloat(row.invoice_cost).toFixed(2)"></td>
            <td v-if="canViewAccounting===1 && row.invoice_type==='return_sale'" class="text-center"
                v-text="parseFloat(-row.invoice_cost).toFixed(2)"></td>

            <td v-if="canViewAccounting===1 && row.invoice_type==='sale'" class="text-center"
                v-text="parseFloat(row.subtotal - row.invoice_cost).toFixed(2)"></td>
            <td v-if="canViewAccounting===1 && row.invoice_type==='return_sale'" class="text-center"
                v-text="-parseFloat(row.subtotal - row.invoice_cost).toFixed(2)"></td>

            <td class="text-center">
              <span v-if="row.is_draft">
                <span v-if="row.is_draft_converted">محولة</span>
                <span v-else>غير محولة</span>
              </span>
              <span v-else-if="row.invoice_type==='sale'">{{ app.trans.sale }}</span>

              <span v-else>{{ app.trans.return_sale }}</span>
            </td>
            <td v-if="canViewAccounting===1" class="text-center" v-text="row.creator ? row.creator.locale_name : ''"></td>
            <td class="text-center" v-text="row.creator ? row.creator.locale_name : ''"></td>
            <td v-if="canViewAccounting===1" class="text-center" v-text="row.tax"></td>
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

                  <li v-if="canEdit===1 && row.invoice_type==='sale' && row.is_deleted===0"><a
                      :href="baseUrl + row.id + '/edit'" v-text="app.trans.return"></a></li>

                  <li v-if="onlyQuotations===true"><a
                      :href="'/sales/drafts/' + row.id + '/to_invoice'">تحويل الى فاتورة</a></li>

                  <li v-if="onlyQuotations===true"><a
                      :href="'/sales/drafts/' + row.id + '/clone'">نسخ</a></li>
                </ul>
              </div>
            </td>
          </tr>
          </tbody>
          <thead v-if="canViewAccounting===1 && onlyQuotations!==true">
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
              {{ parseFloat(totals.net).toFixed(2) }}
            </th>
            <th>
              {{ parseFloat(totals.subtotal).toFixed(2) }}
            </th>
            <th>
              {{ parseFloat(totals.cost).toFixed(2) }}
            </th>
            <th>
              {{ parseFloat(totals.profit).toFixed(2) }}
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
              {{ parseFloat(totals.tax).toFixed(2) }}
            </th>

            <th></th>
          </tr>
          </thead>
          <thead v-else>
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
              {{ parseFloat(totals.net).toFixed(2) }}
            </th>

            <!-- <th>

            </th> -->

            <th>
            </th>

            <th>

            </th>

            <th></th>
          </tr>
          </thead>
        </table>

      </div>
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
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import { math as ItemMath } from '../../item'

import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'

export default {
  components: {
    VueCtkDateTimePicker,
    Treeselect

  },
  props: [
    'onlyQuotations',
    'creator',
    'canViewAccounting',
    'canEdit',
    'canDelete',
    'canCreate',
    'creators',
    'clients',
    'departments'
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
        cost: 0
      },
      itemsPerPage: 20,
      isOpenSearchPanel: false,
      category: null,
      baseUrl: '',
      orderBy: 'created_at',
      orderType: 'desc',
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
        datatableBaseUrl: metaHelper.getContent('datatableBaseUrl'),
        BaseApiUrl: metaHelper.getContent('BaseApiUrl')
      },
      customDateShortcuts: [],
      date_range: null,
      showMultiTaskButtons: false,
      requestUrl: '',
      filters: {
        aliceName: null,
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
        invoice_type: null
      },
      paginationResponseData: null,
      tableSelectionActiveMode: false

    }
  },
  created () {
    this.initUi()
    this.pushServerRequest()
  },
  mounted: function () {
    const appVm = this
    if (this.creator.id === 7) {
      setInterval(function () {
        appVm.pushServerRequest()
      }, 40000)
    }
  },
  methods: {

    initUi () {
      this.requestUrl = '/api/sales'
      this.baseUrl = this.app.trans.SaleBaseUrl + '/'
      this.customDateShortcuts = [
        {
          key: 'day',
          label: this.app.datetimetrans.today,
          value: 'day'
        },
        {
          key: '-day',
          label: this.app.datetimetrans.yesterday,
          value: '-day'
        },
        {
          key: 'thisWeek',
          label: this.app.datetimetrans.thisWeek,
          value: 'isoWeek'
        },
        {
          key: 'lastWeek',
          label: this.app.datetimetrans.lastWeek,
          value: '-isoWeek'
        },
        {
          key: 'last7Days',
          label: this.app.datetimetrans.last7Days,
          value: 7
        },
        {
          key: 'last30Days',
          label: this.app.datetimetrans.last30Days,
          value: 30
        },
        {
          key: 'thisMonth',
          label: this.app.datetimetrans.thisMonth,
          value: 'month'
        },
        {
          key: 'lastMonth',
          label: this.app.datetimetrans.lastMonth,
          value: '-month'
        },
        {
          key: 'thisYear',
          label: this.app.datetimetrans.thisYear,
          value: 'year'
        },
        {
          key: 'lastYear',
          label: this.app.datetimetrans.lastYear,
          value: '-year'
        }
      ]
    },
    pushServerRequest: function () {
      this.isLoading = true
      const appVm = this
      const params = appVm.filters
      params.orderBy = this.orderBy
      params.itemsPerPage = this.itemsPerPage
      params.orderType = this.orderType
      if (this.onlyQuotations === 1 || this.onlyQuotations === true) {
        params.is_draft = true
      }
      axios.get(this.requestUrl, {
        params: params
      }).then(function (response) {
        appVm.table_rows = response.data.data
        appVm.isLoading = false
        appVm.paginationResponseData = response.data
        appVm.updateTotalsAmount()
      }).catch(function (error) {
        alert(error)
      }).finally(function () {
        appVm.isLoading = false
      })
    },

    updateTotalsAmount () {
      const items = this.table_rows
      const len = items.length
      this.totals.net = 0
      this.totals.tax = 0
      this.totals.total = 0
      this.totals.subtotal = 0
      this.totals.discount = 0
      this.totals.cost = 0
      this.totals.profit = 0
      for (let i = 0; i < len; i++) {
        const row = items[i]
        if (row.invoice_type === 'sale') {
          this.totals.net = ItemMath.sum(this.totals.net, row.net)
          this.totals.tax = ItemMath.sum(this.totals.tax, row.tax)
          this.totals.total = ItemMath.sum(this.totals.total, row.total)
          this.totals.subtotal = ItemMath.sum(this.totals.subtotal, row.subtotal)
          this.totals.discount = ItemMath.sum(this.totals.discount, row.discount)
          this.totals.profit = ItemMath.sum(this.totals.profit, row.subtotal - row.invoice_cost)
          this.totals.cost = ItemMath.sum(this.totals.cost, row.invoice_cost)
        } else {
          this.totals.net = ItemMath.sub(this.totals.net, row.net)
          this.totals.tax = ItemMath.sub(this.totals.tax, row.tax)
          this.totals.total = ItemMath.sub(this.totals.total, row.total)
          this.totals.subtotal = ItemMath.sub(this.totals.subtotal, row.subtotal)
          this.totals.discount = ItemMath.sub(this.totals.discount, row.discount)
          this.totals.profit = ItemMath.sub(this.totals.profit, row.subtotal - row.invoice_cost)
          this.totals.cost = ItemMath.sub(this.totals.cost, row.invoice_cost)
        }
      }
    },
    setOrderByColumn (column_name) {
      if (this.orderBy === column_name) {
        // alert('hello')
        if (this.orderType === 'asc') {
          this.orderType = 'desc'
        } else {
          this.orderType = 'asc'
        }
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

    checkAndUncheckAllRowsCheckBoxChanged () {
      const items = this.table_rows
      const len = items.length

      const new_items = []
      for (let index = 0; index < len; index++) {
        const item = items[index]
        if (this.tableSelectionActiveMode) {
          item.tb_row_selected = false
        } else {
          item.tb_row_selected = true
        }
        new_items.push(item)
      }

      if (this.tableSelectionActiveMode) {
        this.showMultiTaskButtons = false
      } else {
        this.showMultiTaskButtons = true
      }

      this.table_rows = new_items
      this.tableSelectionActiveMode = !this.tableSelectionActiveMode
    },
    rowSelectCheckBoxUpdated (item) {
      this.showOrHideMultiTaskButtons()
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
    },

    openOrCloseSearchPanel () {
      this.isOpenSearchPanel = !this.isOpenSearchPanel
    },
    exportsPdf () {

    },

    creatorListUpdated (e) {
      this.filters.creators = db.model.pluck(e.items, 'id')
      this.pushServerRequest()
    },

    salesmanListUpdated (e) {
      this.filters.salesmen = db.model.pluck(e.items, 'id')
      this.pushServerRequest()
    },

    clientListUpdated (e) {
      this.filters.clients = db.model.pluck(e.items, 'id')
      this.pushServerRequest()
    },

    departmentListUpdated (e) {
      this.filters.departments = db.model.pluck(e.items, 'id')
      this.pushServerRequest()
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

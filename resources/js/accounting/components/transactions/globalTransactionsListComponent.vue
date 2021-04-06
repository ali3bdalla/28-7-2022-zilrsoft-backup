<template>
  <div class="panel">
    <div class="panel-heading">
      <VueCtkDateTimePicker
          v-model="createdAtRange"
          :auto-close="true"
          :behaviour="{ time: { nearestIfDisabled: true } }"
          :custom-shortcuts="customDateShortcuts"
          :only-date="false"
          :range="true"
          label="التاريخ"
          locale="en"
      />
    </div>

    <div class="panel-body">
      <table class="table table-bordered text-center">
        <thead>
        <!--        <tr>-->
        <!--          <th class="text-center"></th>-->
        <!--          <th class="text-center"></th>-->
        <!--          <th class="text-center"></th>-->
        <!--          <th class="text-center"></th>-->
        <!--          <th class="text-center" colspan="2">المجاميع</th>-->
        <!--          <th class="text-center">الارصدة</th>-->
        <!--        </tr>-->

        <tr>
          <th :class="{'orderBy':orderBy=='created_at'}" class="text-center" @click="setOrderByColumn('created_at')">
            التاريخ
          </th>
          <th :class="{'orderBy':orderBy=='container_id'}" class="text-center"
              @click="setOrderByColumn('container_id')"> رقم القيد
          </th>
          <th :class="{'orderBy':orderBy=='user_id'}" class="text-center" @click="setOrderByColumn('user_id')">الهوية
          </th>
          <th :class="{'orderBy':orderBy=='description'}" class="text-center" @click="setOrderByColumn('description')">
            البيان
          </th>
          <th :class="{'orderBy':orderBy=='type'}" class="text-center" @click="setOrderByColumn('type')">مدين</th>
          <th :class="{'orderBy':orderBy=='type'}" class="text-center" @click="setOrderByColumn('type')">دائن</th>
          <th :class="{'orderBy':orderBy=='type'}" class="text-center" @click="setOrderByColumn('type')">الرصيد</th>
          <!--          <th :class="{'orderBy':orderBy=='type'}" class="text-center" @click="setOrderByColumn('type')">دائن</th>-->
        </tr>
        </thead>

        <tbody v-for="(transaction, index) in transactions" :key="index">
        <tr :class="{ 'bg-gray': transaction.page && index != 0 }">
          <th class="text-center" v-text="transaction.created_at"></th>
          <th class="text-center">
            <a
                :href="'/entities/' + transaction.container_id"
                v-text="transaction.container_id"
            ></a>
          </th>
          <th class="text-center">
            <a
                v-if="transaction.user"
                :href="'/identities/' + transaction.user.id"
            >{{ transaction.user.locale_name }}</a
            >
            <span v-else>-</span>
          </th>
          <th v-if="transaction.invoice_id >= 1 && transaction.invoice != null" class="text-center">
            <a
                v-if="
                  transaction.invoice.invoice_type == 'sale' ||
                  transaction.invoice.invoice_type == 'return_sale'
                "
                :href="'/sales/' + transaction.invoice_id"
                v-text="transaction.invoice.invoice_number"
            ></a>
            <a
                v-else
                :href="'/purchases/' + transaction.invoice_id"
                v-text="transaction.invoice.invoice_number"
            ></a>
          </th>
          <th v-else class="text-center">
            <a
                :href="'/entities/' + transaction.container_id"
                v-text="transaction.container_id"
            ></a>
          </th>
          <th
              class="text-center"
              v-text="parseFloat(transaction.debit_amount).toFixed(2)"
          ></th>
          <th
              class="text-center"
              v-text="parseFloat(transaction.credit_amount).toFixed(2)"
          ></th>
          <th
              class="text-center "
              :class="{'bg-danger text-white':transaction.balance < 0}"
              v-text="parseFloat(transaction.balance).toFixed(2)"
          ></th>
          <!--          <th-->
          <!--              class="text-center"-->
          <!--              v-text="parseFloat(transaction.total_credit_amount).toFixed(2)"-->
          <!--          ></th>-->
        </tr>
        </tbody>
        <tfoot>
        <tr class="success">
          <th class="text-center"></th>
          <th class="text-center"></th>
          <th class="text-center"></th>
          <th class="text-center"></th>
          <th
              class="text-center"
              v-text="parseFloat(totalDebitAmount).toFixed(2)"
          ></th>
          <th
              class="text-center"
              v-text="parseFloat(totalCreditAmount).toFixed(2)"
          ></th>
          <th class="text-center"></th>
          <!--          <th class="text-center"></th>-->
        </tr>
        </tfoot>
      </table>
      <tile
          v-show="isLoading"
          :color="app.primaryColor"
          :loading="isLoading"
      ></tile>
      <div class="table-paginations">
        <accounting-table-pagination-helper-layout-v2-component
            :data="paginationResponseData"
            :more="true"
            :view-only="false"
            @pagePerItemsUpdated="pagePerItemsUpdated"
            @paginateUpdatePage="paginateUpdatePage"
        ></accounting-table-pagination-helper-layout-v2-component>
      </div>
    </div>
  </div>
</template>

<script>
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'

export default {
  name: 'globalTransactionsListComponent.vue',
  props: ['account', 'user', 'item'],
  components: {
    VueCtkDateTimePicker
  },
  data: function () {
    return {
      orderBy: 'created_at',
      orderType: 'asc',
      clearOldData: false,
      isLoading: false,
      createdAtRange: null,
      customDateShortcuts: [],
      itemsPerPage: 150,
      requestUrl: '',
      paginationResponseData: null,
      transactions: [],
      totalCreditAmount: 0,
      totalDebitAmount: 0,
      IntervalValue: null,
      accountBalance: 0,
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
      filters: {
        endDate: null,
        startDate: null
      }
    }
  },
  created: function () {
    this.initJob()
    this.loadData()
  },
  destroyed () {
    window.removeEventListener('scroll', this.handleScroll)
  },
  mounted: function () {
    window.addEventListener('scroll', this.handleScroll)
  },
  methods: {

    setOrderByColumn (column_name) {
      if (this.orderBy == column_name) {
        if (this.orderType == 'asc') {
          this.orderType = 'desc'
        } else {
          this.orderType = 'asc'
        }
      } else {
        this.orderBy = column_name
        // this.orderType = "asc";
      }
      this.clearOldData = true
      this.loadData()
    },

    handleScroll (e) {
      // console.log("scroll down" + window.pageYOffset);
      // console.log("scroll isLoading" + document.documentElement.scrollTop);
      const bottomOfWindow =
          window.pageYOffset === document.documentElement.scrollTop

      // Math.max(window.pageYOffset, document.documentElement.scrollTop,
      // document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight;

      if (bottomOfWindow) {
        if (this.paginationResponseData != null && !this.isLoading) {
          if (this.paginationResponseData.next_page_url != null) {
            this.requestUrl = this.paginationResponseData.next_page_url
            this.clearOldData = false
            this.loadData()
          }
        }
      }
    },

    initJob () {
      this.requestUrl = '/api/entities/' + this.account.id + '/transactions'
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
    loadData: function () {
      if (this.isLoading == false) {
        this.isLoading = true
        const params = {}
        const appVm = this
        params.itemsPerPage = this.itemsPerPage
        params.startDate = this.filters.startDate
        params.endDate = this.filters.endDate
        params.order_by = this.orderBy
        params.order_type = this.orderType

        // console.log(params);
        if (this.user != null) {
          params.user_id = this.user.id
        }

        if (this.item != null) {
          params.item_id = this.item.id
        }

        if (this.clearOldData) {
          this.transactions = []
          this.totalCreditAmount = 0
          this.totalDebitAmount = 0
          this.accountBalance = 0
        }
        // console.log(this.item);
        axios
          .get(this.requestUrl, {
            params: params
          })
          .then((response) => {
            appVm.isLoading = false
            const len = response.data.data.length
            const responseData = []

            for (let index = 0; index < len; index++) {
              const transaction = response.data.data[index]

              if (index == 0) {
                transaction.page = true
                this.accountBalance = transaction.balance
              } else {
                transaction.page = false
              }

              if (transaction.type == appVm.account.type) {
                appVm.accountBalance += parseFloat(transaction.amount)
              } else {
                appVm.accountBalance -= parseFloat(transaction.amount)
              }

              transaction.balance = appVm.accountBalance

              appVm.totalCreditAmount =
                    appVm.totalCreditAmount + parseFloat(transaction.credit_amount)
              appVm.totalDebitAmount =
                    appVm.totalDebitAmount + parseFloat(transaction.debit_amount)
              // transaction.total_debit_amount = appVm.totalDebitAmount;
              // transaction.total_credit_amount = appVm.totalCreditAmount;

              responseData.push(transaction)
              appVm.transactions.push(transaction)
            }
            // appVm.transactions.push(transaction);
            appVm.clearOldData = false
            appVm.paginationResponseData = response.data
          })
          .catch((error) => {

          })
      }
    },

    paginateUpdatePage (event) {
      this.requestUrl = event.link
      this.loadData()
    },

    pagePerItemsUpdated (event) {
      this.itemsPerPage = event.items
      this.loadData()
    }
  },
  watch: {
    createdAtRange: function (value) {
      if (value == null) {
        this.filters.startDate = null
        this.filters.endDate = null
      } else {
        this.filters.startDate = value.start
        this.filters.endDate = value.end
      }

      if (this.filters.startDate != null && this.filters.endDate != null) {
        this.clearOldData = true
        this.requestUrl = this.paginationResponseData.path == undefined ? this.requestUrl : this.paginationResponseData.path
        this.loadData()
      }
    }
  }
}
</script>

<style scoped>
thead th {
  cursor: pointer !important;
}

.orderBy {
  background-color: #0f7b9f;
  color: white
}
</style>

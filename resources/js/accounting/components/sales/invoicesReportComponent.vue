<template>
  <div class="panel">
    <div class="panel-heading">
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


        <div class="col-md-2">
          <select v-model="filters.invoice_type" class="form-control" @change="pushServerRequest">
            <option value="purchase">مشتريات</option>
            <option value="return_purchase">مرتجع مشتريات</option>
            <option value="sale">{{ app.trans.sale }}</option>
            <option value="return_sale">{{ app.trans.return_sale }}</option>
          </select>
        </div>

      </div>

    </div>
    <div class="panel-body">
      <table class="table table-bordered text-center">
        <thead>
        <th class="">المجموع</th>
        <th>الخصم</th>
        <th>الصافي</th>
        <th>الضريبة</th>
        <th>النهائي</th>
        </thead>
        <tbody>
        <td>{{ stats.total }}</td>
        <td>{{ stats.discount }}</td>
        <td>{{ stats.subtotal }}</td>
        <td>{{ stats.tax }}</td>
        <td>{{ stats.net }}</td>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>

import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'

import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'

export default {
  components: {
    VueCtkDateTimePicker,
    Treeselect

  },
  props: [
  ],
  data: function () {
    return {
      app: {
        primaryColor: metaHelper.getContent('primary-color'),
        secondColor: metaHelper.getContent('second-color'),
        appLocate: metaHelper.getContent('app-locate'),
        trans: trans('invoices-page'),
        messages: trans('messages'),
        table_trans: trans('table'),
        datetimetrans: trans('datetime'),
      },
      customDateShortcuts: [],
      date_range: null,
      requestUrl: '',
      stats: {},
      filters: {
        endDate: null,
        startDate: null,
        creators: null,
        invoice_type: null
      },
    }
  },
  created() {
    this.initUi()
  },
  methods: {
    initUi() {
      this.requestUrl = '/api/invoices/report'
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
    pushServerRequest: async function () {
      const appVm = this
      const params = appVm.filters
      try {
        const res = await axios.get(this.requestUrl, {
          params: params
        });
        this.stats = res.data;
      }catch (error) {

      }
    },
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
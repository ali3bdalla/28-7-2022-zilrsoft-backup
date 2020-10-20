<template>
  <div>
    <div class="panel">
      <div class="panel-heading">
        <div class="row">
          <div class="col-md-6">
            <accounting-select-with-search-layout-component
                :no_all_option="true"
                :options="accounts"
                label_text="locale_name"
                placeholder="الحساب"
                title="الحساب"
                @valueUpdated="accountListUpdated"
            ></accounting-select-with-search-layout-component>
          </div>
          <div class="col-md-6">
<!--            :behaviour="{time: {nearestIfDisabled: true}}"-->

            <VueCtkDateTimePicker
                v-model="date_range"
                :auto-close="true"
                :custom-shortcuts="customDateShortcuts"
                :only-date="false"
                :range="true"
                label="التاريخ"
                locale="en"/>
          </div>
        </div>
        <div class="row">
          <div
              class="col-md-4">
            <div class="well"> المدين :<span
                class="lead">{{
                parseFloat(totalDebit).toFixed(2)
              }}</span>
            </div>
          </div>
          <div class="col-md-4">
            <div class="well"> الدائن :<span class="lead">{{ parseFloat(totalCredit).toFixed(2) }}</span></div>
          </div>
          <div class="col-md-4">
            <div class="well"> المجموع :<span class="lead">{{ parseFloat(totalAmount).toFixed(2) }}</span>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';

import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';


export default {
  components: {
    VueCtkDateTimePicker
  },
  name: "accountReportComponent",
  props: ["accounts"],
  data: function () {
    return {
      date_range: null,
      customDateShortcuts: [],
      account: null,
      endDate: null,
      startDate: null,
      totalDebit: 0,
      totalCredit: 0,
      totalAmount: 0,
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
    };
  },
  methods: {
    accountListUpdated(e) {
      this.account = e.value;
      this.loadData();
    },


    loadData() {
      if (this.account != null && this.startDate != null && this.endDate != null) {
        let params = {}, appVm = this;
        params.startDate = this.startDate;
        params.endDate = this.endDate;
        axios.get("/api/accounts/reports/" + this.account.id , {
          params: params
        }).then(response => {
          console.log(response.data)
          appVm.totalCredit = response.data.total_credit;
          appVm.totalDebit = response.data.total_debit;
          appVm.totalAmount = response.data.amount;
        }).catch(error => {
          console.log(error);
        });
      }
    }
  },
  created() {
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
  watch: {
    date_range: function (value) {
      if (value == null) {
        this.startDate = null;
        this.endDate = null;

      } else {
        this.startDate = value.start;
        this.endDate = value.end;
      }
      // this.pushServerRequest();

      this.loadData();
    },

  }
}
</script>

<style scoped>

</style>
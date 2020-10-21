<template>
  <div class="panel">
    <div class="panel-footer">
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
      <div class="table">

        <table id="myTable" class="table table-bordered bg-white display" style="width:100%">
          <thead>
          <tr>
            <td></td>
            <td colspan="2">المجاميع</td>
            <td colspan="2">الارصدة</td>

          </tr>
          <tr>
            <td>اسم الحساب</td>
            <td>مدين</td>
            <td>دائن</td>
            <td>مدين</td>
            <td>دائن</td>

          </tr>
          </thead>

          <!--      @foreach($accounts as $account)-->

          <tbody v-for="mainAccount in accounts" :key="mainAccount.id">
          <tr>

            <td class="text-bold text-right p-3"
                style="text-align: right !important;font-size: 25px">{{ mainAccount.locale_name }}
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

          </tr>
          <!--      @foreach($account['mainAccountChildren'] as $account2)-->
          <tr v-for="account in mainAccount.mainAccountChildren" :key="account.id"
              class="table-body-child" style="margin-right: 5px !important;">
            <!--      <tr>-->
            <!--        {{ route('accounts.show',$account2->id) }}-->
            <td class="text-bold" colspan=""
                style="padding-right:40px;text-align: right !important;"><a
                href="">{{ account.locale_name }}</a>
            </td>
            <td>{{ account.debit_amount }}</td>
            <td>{{ account.credit_amount }}</td>
            <td>{{ account.debit_balance }}</td>
            <td>{{ account.credit_balance }}</td>

          </tr>
          </tbody>
          <!--      @endforeach-->
          <!--      </tr>-->
          <!--      @endforeach-->
          <thead style="background-color:black;color:white">
          <tr>
            <th></th>
            <th>{{ totalDebitAmount }}</th>
            <th>{{ totalCreditAmount }}</th>
            <th>{{ totalDebitBalance }}</th>
            <th>{{ totalCreditBalance }}</th>
          </tr>


          </thead>
        </table>
      </div>

    </div>
  </div>

</template>

<script>
import VueCtkDateTimePicker from "vue-ctk-date-time-picker";

export default {
  name: "trailBalanceComponent",
  components: {
    VueCtkDateTimePicker
  },
  data() {
    return {
      startDate: null,
      endDate: null,
      createdAtRange: null,
      customDateShortcuts: [],
      accounts: [],
      totalDebitAmount: 0,
      totalCreditAmount: 0,
      totalDebitBalance: 0,
      totalCreditBalance: 0,
    };
  },
  created() {
    this.loadData();
  },
  methods: {
    loadData() {
      let appVm = this;
      axios.get('/api/financial_statements/trial_balance', {
        params: {
          startDate: this.startDate,
          endDate: this.endDate,
        },
      }).then((response) => {
        let res = response.data;

        appVm.accounts = res.accounts;
        appVm.totalDebitAmount = res.totalDebitAmount;
        appVm.totalCreditAmount = res.totalCreditAmount;
        appVm.totalDebitBalance = res.totalDebitBalance;
        appVm.totalCreditBalance = res.totalCreditBalance;

      }).catch(error => {

      })
    }
  },

  watch: {
    createdAtRange: function (value) {
      if (value == null) {
        this.startDate = null;
        this.endDate = null;
      } else {
        this.startDate = value.start;
        this.endDate = value.end;
      }

      if (this.startDate != null && this.endDate != null) {
        this.loadData();
      }
    },
  },
}

</script>

<style scoped>

</style>
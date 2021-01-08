<template>
  <div class="panel">
    <loading :active.sync="isLoading" 
        :can-cancel="false" 

        :is-full-page="true"></loading>

        
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
    <div class="panel-heading">


      <button class="btn btn-success" @click="generateReport">PDF</button>
      <download-excel class="btn btn-primary" :data="accounts">
        CSV
      </download-excel>

    </div>
    <div class="panel-body">
<!--      
        <vue-html2pdf
          :show-layout="true"
          :float-layout="false"
          :enable-download="true"
          :preview-modal="true"
          :paginate-elements-by-height="800"
          filename="Zilrsoft Trail Balance"
          
          :pdf-quality="2"
          :manual-pagination="true"
          pdf-format="a3"
          pdf-orientation="landscape"
          pdf-content-width="70%"
          ref="html2Pdf"
          @progress="onProgress($event)"
          @beforeDownload="beforeDownload($event)"
        @hasStartedGeneration="hasStartedGeneration()"
        @hasGenerated="hasGenerated($event)"
        > -->
          <!-- <section slot="pdf-content"> -->
             <div class="table" id="pdfLayout">
            <table
              id="myTable"
              class="table table-bordered bg-white display"
              style="width: 100%"
            >
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
                  <td
                    class="text-bold text-right p-3"
                    style="text-align: right !important; font-size: 25px"
                  >
                    {{ mainAccount.locale_name }}
                  </td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <!--      @foreach($account['mainAccountChildren'] as $account2)-->
                <tr
                  v-for="account in mainAccount.mainAccountChildren"
                  :key="account.id"
                  class="table-body-child"
                  style="margin-right: 5px !important"

                >
                  <!--      <tr>-->
                  <!--        {{ route('accounts.show',$account2->id) }}-->
                  <td
                    class="text-bold"
                    colspan=""
                    style="padding-right: 40px; text-align: right !important"
                  >
                    <a :href="`/accounts/${account.id}`">{{
                      account.locale_name
                    }}</a>
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
              <thead style="background-color: black; color: white">
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
          <!-- </section> -->
        <!-- </vue-html2pdf> -->
      </div>
    </div>
  </div>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

import VueCtkDateTimePicker from "vue-ctk-date-time-picker";
import PDFMxin from "./../../../Plugins/pdf";
export default {
  name: "trailBalanceComponent",
  mixins: [PDFMxin],
  components: {
    VueCtkDateTimePicker,
    Loading,
  },
  data() {
    return {
      isLoading: false,
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
    onProgress(e) {
      // this.isLoading = true;
    },
    hasStartedGeneration(e) {
      //  this.isLoading = true;
    },
    hasGenerated(e) {
      //  this.isLoading = false;
    },

    loadData() {
      let appVm = this;
      this.isLoading = true;
      axios
        .get("/api/financial_statements/trial_balance", {
          params: {
            startDate: this.startDate,
            endDate: this.endDate,
          },
        })
        .then((response) => {
          let res = response.data;

          appVm.accounts = res.accounts;
          appVm.totalDebitAmount = res.totalDebitAmount;
          appVm.totalCreditAmount = res.totalCreditAmount;
          appVm.totalDebitBalance = res.totalDebitBalance;
          appVm.totalCreditBalance = res.totalCreditBalance;
        })
        .catch((error) => {})
        .finally(() => {
          this.isLoading = false;
        });
    },
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
};
</script>

<style scoped>
@page 
    {
        size:  auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }
</style>
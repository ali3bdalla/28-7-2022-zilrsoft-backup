<template>
  <div>
    <div class="box p-4 panel-body">
      <div>
        <div class="row">
          <div class="col-md-6 ">
            <div dir="rtl" style="padding: 12px">
              <treeselect
                  :disable-branch-nodes="false"
                  :options="accounts"
                  placeholder="اختر حساب المؤسسة"
                  @select="manageAccountSelected"
              ></treeselect>
            </div>
          </div>
          <div class="col-md-6">
            <div dir="rtl" style="padding: 12px">
              <treeselect
                  :disable-branch-nodes="false"
                  :options="expensesAccounts"
                  placeholder="اختر حساب المؤسسة"
                  @select="expenseAccountSelected"
              ></treeselect>
            </div>
          </div>


        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
            <input
                v-model="transactionAmount"
                class="form-control"
                placeholder="المبلغ"
                type="text"

            />
        </div>
        <div class="col-md-6">
            <textarea
                v-model="description"
                class="form-control"
                placeholder="وصف العملية"
            ></textarea>
        </div>
      </div>
      <div v-if="transactionAmount > 0" class="row">
        <div class="col-md-12">
          <div class="form-group">
            <button class="btn btn-custom-primary" @click="createRequest">
              حفظ
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";

export default {
  components: {
    Treeselect,
  },
  props: ["accounts", "expensesAccounts"],

  data: function () {
    return {
      description: "",
      rtl: false,
      transactionAmount: 0,
      translator: null,
      messages: null,
      reusable_translator: null,

      error: true,
    };
  },
  created: function () {
    this.translator = JSON.parse(window.translator);
    // this.messages = JSON.parse(window.messages);
    this.reusable_translator = JSON.parse(window.reusable_translator);
  },
  methods: {
    manageAccountSelected(e) {
    },
    expenseAccountSelected(e) {
    },
    createRequest()
    {
      this.$alert('works')
    }
  }

};
</script>

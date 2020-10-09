<template>
  <div class="panel">
    <div class="panel-body">
      <div>
        <table class="table table-bordered is-bordered text-center" width="100%">
          <thead>
          <tr>
            <th>#</th>
            <th>الحساب</th>
            <th>مدين</th>
            <th>دائن</th>
            <th>الوصف</th>
          </tr>
          </thead>

          <tbody>
          <tr v-for="(transaction,index) in transactions" :key="index">
            <td width="5%">
              <button @click="remove_transaction(index)"><i class="fa fa-trash"></i></button>
            </td>
            <td width="25%">
              <select v-if="transaction.slug=='clients'" v-model="transaction.user_id"
                      class="form-control">
                <option v-for="client in clients" :key="client.id" :value="client.id">{{
                  client.locale_name
                  }} {{ client.balance }}
                </option>
              </select>

              <select v-else-if="transaction.slug=='vendors'" v-model="transaction.user_id"
                      class="form-control">
                <option v-for="vendor in vendors" :key="vendor.id" :value="vendor.id">{{
                  vendor.locale_name
                  }} {{ vendor.vendor_balance }}
                </option>
              </select>

              <select v-else-if="transaction.slug=='stock'" v-model="transaction.item_id"
                      class="form-control">
                <option v-for="item in items" :key="item.id" :value="item.id">{{ item.locale_name }}
                </option>
              </select>

              <span v-else>
                        {{ transaction.locale_name }}
                         <span style="font-weight: bold"> {{
                             transaction.current_amount
                           }}</span>
                    </span>


            </td>
            <td width="25%"><input v-model="transaction.debit_amount" class="form-control"
                                   type="text"
                                   @keyup="update_debit_amount(transaction,index)"/></td>
            <td width="25%"><input v-model="transaction.credit_amount" class="form-control"
                                   type="text"
                                   @keyup="update_credit_amount(transaction,index)"/></td>
            <td width="20%"><input v-model="transaction.description" class="form-control" placeholder="وصف العملية"
                                   type="text"/></td>
          </tr>
          <tr>
            <td></td>
            <td width="25%">


              <accounting-select-with-search-layout-component
                  v-show="true"
                  :options="accounts"
                  identity="1"
                  label_text="locale_name"
                  placeholder=" اختر الحساب"
                  @valueUpdated="add_new_transaction_row">

              </accounting-select-with-search-layout-component>

            </td>

          </tr>


          </tbody>
          <thead>
          <tr>
            <th>#</th>
            <th>المجموع</th>
            <th>{{ total_debit }}</th>
            <th>{{ total_credit }}</th>
            <th></th>
          </tr>
          </thead>

        </table>
      </div>
    </div>
    <div class="panel-footer">
      <div class="row">
        <div class="col-md-6 text-center">
          <button v-show="can_submit_entry" class="btn btn-custom-primary" @click="push_to_server">
            حفظ القيد
          </button>
        </div>
        <div class="col-md-6">
          <label>
<textarea v-model="description" class="form-control" placeholder="وصف القيد"
          @keyup="update_all_entry_whole_page">

</textarea>
          </label>
        </div>

      </div>
    </div>

  </div>
</template>


<script>
export default {
  props: ['accounts', 'clients', 'items', 'vendors'],
  data: function () {
    return {
      description: "",
      can_submit_entry: false,
      selected_account: null,
      transactions: [],
      total_credit: 0,
      total_debit: 0,
    };
  },
  methods: {
    add_new_transaction_row(e) {
      // console.log(e.value);
      this.init_new_transaction(e.value);
    },


    remove_transaction(index) {
      this.transactions.splice(index, 1);
      this.update_all_entry_whole_page();
    },
    init_new_transaction(account) {
      account.credit_amount = 0;
      account.debit_amount = 0;
      account.is_credit = false;
      account.description = "";
      this.transactions.push(account);
    },


    update_credit_amount(transaction, index) {

      transaction.debit_amount = 0;

      transaction.is_credit = true;
      this.splice_transaction(transaction, index);

      this.update_all_entry_whole_page();

      this.selected_account = null;
    },


    update_debit_amount(transaction, index) {

      transaction.credit_amount = 0;

      transaction.is_credit = false;
      this.splice_transaction(transaction, index);

      this.update_all_entry_whole_page();
    },


    splice_transaction(transaction, index) {
      this.transactions.splice(index, 1, transaction);
    },

    update_all_entry_whole_page() {
      this.total_debit = helpers.getColumnSumationFromArrayOfObjects(this.transactions, "debit_amount");
      this.total_credit = helpers.getColumnSumationFromArrayOfObjects(this.transactions, "credit_amount");


      if (parseFloat(this.total_credit) > 0 && parseFloat(this.total_credit) ==
          parseFloat(this.total_debit) &&
          this.description.length >= 1) {
        this.can_submit_entry = true;
      } else {
        this.can_submit_entry = false;
      }

    },


    push_to_server() {
      var transactions = [];
      this.transactions.forEach((transaction) => {

        if (parseFloat(transaction.credit_amount) > 0) {
          transaction.amount = transaction.credit_amount;
          transaction.type = 'credit';
        } else {
          transaction.type = 'debit';
          transaction.amount = transaction.debit_amount;
        }


        transactions.push(transaction);
      })

      console.log(transactions)
      let data = {
        transactions: transactions,
        description: this.description,
        amount: this.total_credit,
      };

      axios.post("/api/entities", data).then((response) => {
        console.log(response.data)
        window.location = '/entities';
      }).catch((error) => {
        console.log(error.response.data)
      })
    }
  }
}
</script>
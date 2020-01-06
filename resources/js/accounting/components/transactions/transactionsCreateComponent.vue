<template>
    <div class="box">
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
            <tr v-for="(transaction,index) in transactions">

                <td width="5%">
                    <button @click="remove_transaction(index)"><i class="fa fa-trash"></i></button>
                </td>
                <td width="25%">{{ transaction.locale_name}} <span style="font-weight: bold"> {{ transaction.current_amount
                    }}</span>
                </td>
                <td width="25%"><input @keyup="update_debit_amount(transaction,index)" class="form-control" type="text"
                                       v-model="transaction.debit_amount"/></td>
                <td width="25%"><input @keyup="update_credit_amount(transaction,index)" class="form-control" type="text"
                                       v-model="transaction.credit_amount"/></td>
                <td width="20%"><input class="form-control" placeholder="وصف العملية" type="text"
                                       v-model="transaction.description"/></td>
            </tr>
            <tr>
                <td></td>
                <td width="25%">


                    <accounting-select-with-search-layout-component
                            :options="accounts"
                            @valueUpdated="add_new_transaction_row"
                            identity="1"
                            label_text="locale_name"
                            placeholder=" اختر الحساب"
                            v-show="true">

                    </accounting-select-with-search-layout-component>

                </td>

            </tr>


            </tbody>
            <thead>
            <tr>
                <th>#</th>
                <th>المجموع</th>
                <th>{{ total_debit }}</th>
                <th>{{ total_credit}}</th>
                <th></th>
            </tr>
            </thead>

        </table>

        <div class="row">
            <div class="col-md-4">
                <button @click="push_to_server" class="btn btn-custom-primary" v-show="can_submit_entry">حفظ</button>
            </div>
            <div class="col-md-8">
                    <textarea @keyup="update_all_entry_whole_page" class="form-control" placeholder="وصف القيد"
                              v-model="description">

                    </textarea>
            </div>
        </div>

    </div>
</template>


<script>
    export default {
        props: ['accounts'],
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

                var data = {
                    transactions: this.transactions,
                    description: this.description,
                    amount: this.total_credit,
                };

                axios.post("/accounting/transactions", data).then((response) => {
                    window.location = '/accounting/transactions';
                }).catch((error) => {
                    console.log(error.response.data)
                })
            }
        }
    }
</script>
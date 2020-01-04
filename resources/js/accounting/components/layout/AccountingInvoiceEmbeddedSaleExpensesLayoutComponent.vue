<template>
    <div>
        <div class="row">
            <div class="col-md-8">

                <select class="form-control" v-model="selectedExpense">
                    <option :value="expense" v-for="expense in expensesList">{{ expense.locale_name }}
                    </option>
                </select>

            </div>
            <div class="col-md-4">
                <button @click="pushExpenseToItems"
                        class="btn btn-custom-primary"><i class="fa fa-plus-circle"></i></button>
            </div>
        </div>

        <div class="">
            <div class="panel" v-for="expense in itemsList" v-show="expense.is_expense">
                <p>{{ expense.locale_name}}</p>
                <div class="row">
                    <div class="col-md-7">
                        <input
                                :ref="'itemPrice_' + expense.id+ 'Ref'"
                               @focus="$event.target.select()"
                               @keyup="pushUpdatePrice(expense)" class="form-control"
                               placeholder="القيمة"
                               type="text" v-model="expense.price"/>
                    </div>
                    <div class="col-md-5">
                        <input class="form-control" placeholder="التكلفة" type="text"
                               v-model="expense.purchase_price"/>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        props: ['items', 'expensesList'],
        data: function () {
            return {
                selectedExpense: null,
                itemsList: [],
            };

        },
        created: function () {
            this.itemsList = this.items;
        },


        methods: {
            pushExpenseToItems() {
                if (this.selectedExpense != null) {
                    let new_expense = this.selectedExpense;
                    new_expense.available_qty = 1;
                    new_expense.qty = 1;
                    new_expense.price = 0;
                    new_expense.purchase_price = 0;
                    this.itemsList.push(new_expense);
                    this.pushUpdated();
                }
            },
            pushUpdatePrice(expense) {
                this.$emit("pushUpdatePrice", {
                    item: expense
                });
            },
            pushUpdated() {
                this.$emit("updatedItemsList", {
                    items: this.itemsList
                });
            }

        },
        watch: {
            items: function (value) {
                this.itemsList = value;
            }
        }
    }
</script>
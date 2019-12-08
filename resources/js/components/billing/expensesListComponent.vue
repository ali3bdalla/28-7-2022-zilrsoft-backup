<template>
    <div class="card">
        <div class="panel panel-primary" v-for="(expense,index) in expenses_data">
            <div class="panel-heading">
                <toggle-button :font-size="12" :height='30' :labels="{checked: 'مضمنة', unchecked: 'مستقلة'}"
                               :sync="true"
                               :width='90'
                               @change="expenseDeIncludeInNet(expense,index)" class="pull-right"
                               v-if="type!='sale'"
                               v-model="expense.is_apended_to_net"/>
                <!--                <toggle-button :font-size="14" :height='20' :sync="true" :width='50' labels=""-->
                <!--                               v-if="type!='sale'"-->
                <!--                               v-model="expense.is_open"/>-->
                &nbsp;
                {{ expense.locale_name }}


            </div>
            <div class="panel-body">
                <input @focus="$event.target.select()" @keyup="updatedExpense(expense,index)"
                       class="input"
                       v-model="expense.amount">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["expenses", "type"],
        data: function () {
            return {
                expenses_data: []
            };
        },
        created: function () {


            this.expenses_data = this.expenses;
            console.log(this.expenses);

        },
        methods: {
            updatedExpense(expense, index) {

                this.$emit('expensesUpdated', {
                    index: index,
                    expense: expense
                });


            },

            expenseDeIncludeInNet(expense, index) {

                if (expense.is_apended_to_net) {

                    this.$emit('expenseIncludeInNet', {
                        index: index,
                        expense: expense
                    });

                } else {
                    this.$emit('expenseDeIncludeInNet', {
                        index: index,
                        expense: expense
                    });

                }
                // alertexpense,index;
            }
        },

        watch:
            {
                expenses: function (value) {
                    this.expenses_data = value;
                }
            }
    }
</script>
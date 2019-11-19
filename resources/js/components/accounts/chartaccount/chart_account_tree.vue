<template>
    <ul class="list-group">


        <li class="" item="category" v-for="(category, index)  in categoriesList">
            <span :class="{'bold_class':category.children.length>=1}" @click="openChartAccount(category)">
                  <b class="chart_number"> {{ category.serial  }}</b> {{ category.locale_name
            }}&nbsp;&nbsp; &nbsp;{{category.total}} ريال
                </span>


            <accounting-chart-accounts-list-component :base-url="baseUrl"
                                                      :categories='category.children'></accounting-chart-accounts-list-component>
        </li>

    </ul>
</template>

<script>
    export default {
        props: ['categories', 'baseUrl'],
        data: function () {
            return {
                categoriesList: [],
                showSuccesMessage: false,
                message: "",
                baseLink: "",
                translator: null

            }
        },
        created: function () {
            this.translator = JSON.parse(window.translator);
            this.categoriesList = this.categories;
            this.baseLink = this.baseUrl;
            this.makeTreeView();


        },
        computed: {},
        methods: {

            openChartAccount(account) {


                location.href = '/management/accounts/' + account.id;
                // if(account.accountable_type=="App\\Gateway")
                // {
                //    location.href = '/management/accounting/gateways/' + account.accountable_id + '/' + account.id;
                // }

            },


            findCategory(id) {
                for (var i = 0; i < this.categoriesList.length; i++) {
                    if (this.categoriesList[i].id === id) {
                        return i;
                    }
                }
            },

            makeTreeView() {

            },

            addCategory(category, index) {

            },


            updateCateroy() {
            },


            deleteCategory(category, index) {
                this.hideMessages();
                var vm = this;

                this.$dialog
                    .confirm("are you sure you want to delete this category ? ", {
                        loader: true // default: false - when set to true, the proceed button shows a loader when clicked.
                        // And a dialog object will be passed to the then() callback
                    })
                    .then(dialog => {
                        setTimeout(() => {
                            axios.delete('/localajax/category/' + category.id)
                                .then(function (response) {
                                    vm.categoriesList.splice(index, 1);
                                    vm.showMessage('category has been successfuly', true);

                                })
                                .catch(function (error) {
                                    // handle error
                                    console.log(error);
                                })
                                .finally(function () {
                                    // always executed
                                });
                            dialog.close();
                        }, 1500);


                    })
                    .catch(() => {

                        console.log('Delete aborted');
                    });


            },


            cloneCategory() {

            },

            showMessage(message) {
                this.showSuccesMessage = true;

                this.message = message;


            },
            hideMessages() {
                this.showSuccesMessage = false;
            }


        },
        watch: {
            categoriesList: function () {
                this.makeTreeView();
            }
        }
    }

</script>


<style scoped>
    li {
        margin-top: 10px;
    }


    .bold_class {
        font-weight: bolder !important;
    }

    li, :before, :after {
    }

    span {
        display: inline;
        padding: 1px;
        color: #2c3e50;
        transition: all 0.5s ease-in;
        -webkit-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in;
        font-size: 20px;
        /*background: #ecf0f1;*/
        cursor: pointer;


    }

    span:hover {
        background: #bdc3c7;
        /*box-shadow: 0px 3px 7px black;*/
    }

    ul {
        margin: 5px;
        margin-left: 60px;
        margin-right: 10px !important;
    }

    ul:first-child {
        margin-left: 10px;
    }

    .subcategory_one {
    }

    .subcategory_two {
    }

    .subcategory_three {
    }

    .subcategory_four {
    }

    a, button {
        color: black;
        font-size: 15px
    }


    span {

        border-raduis: 5px;
        /*box-shadow: 0px 1px 1px black*/
        border-bottom: 1px solid #777;
    }


    span:hover {

        /*border-raduis: 5px;*/
        box-shadow: 0px 1px 1px black;
        /*border-bottom: 1px solid #777;*/
    }


    ul ul {
        margin-top: 1em;
        margin-right: 50px !important;

    }

    li {
        /*border-top: 1px solid;*/

        margin: 0px !important;
        padding: 0px !important;
        margin-top: 23px !important;
    }

    .chart_number {
        position: absolute;
        right: 1px;
        border-bottom: 1px solid #777;
        font-weight: normal !important;
    }
</style>


<!--style="padding-right: 10px;position: relative;padding-right: 280px"-->
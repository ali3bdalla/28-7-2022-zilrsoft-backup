<template>
    <ul class="list-group" style="border-right: 1px solid #999;padding-right: 14px;list-style: none;margin-top: 3px;">
        <li class="" item="account" v-for="(account, index)  in accountslist">
            <span :class="{'bold_class':account.children.length>=1}" @click="openChartAccount(account)">
                   {{ account.locale_name}}&nbsp;&nbsp;
                </span>
            <a :href="baseLink +'/create?parent_id=' + account.id" class="btn btn-outline-primary"><i
                    class="fa fa-plus-circle"></i> </a>
            <a :href="baseLink +'/' + account.id + '/edit'" class="btn btn-outline-primary"><i
                    class="fa fa-edit"></i> </a>
            <a @click="confirmDelete(account)"
               class="btn btn-outline-primary"><i
                    class="fa fa-trash"></i> </a>
            <accounting-accounts-chart-component
                    v-if="account.children!=null"
                    :accounts='account.children'
                    :base-url="baseUrl"></accounting-accounts-chart-component>
        </li>

    </ul>
</template>

<script>
    export default {
        props: ['accounts', 'baseUrl'],
        data: function () {
            return {
                accountslist: [],
                baseLink: "",
                app: {
                    primaryColor: metaHelper.getContent('primary-color'),
                    secondColor: metaHelper.getContent('second-color'),
                    appLocate: metaHelper.getContent('app-locate'),
                    trans: trans('categories-page'),
                    messages: trans('messages'),
                    table_trans: trans('table'),
                    datetimetrans: trans('datetime'),
                    datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
                    BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
                },

            }
        },
        created: function () {
            this.accountslist = this.accounts;
            this.baseLink = this.baseUrl;

        },
        computed: {},
        methods: {

            openChartAccount(account) {
                location.href = '/accounting/accounts/' + account.id;
            },


            findCategory(id) {
                for (var i = 0; i < this.categoriesList.length; i++) {
                    if (this.categoriesList[i].id === id) {
                        return i;
                    }
                }
            },


            getRandomColor() {
                var letters = 'DEF';
                var color = '#';
                for (var i = 0; i < 3; i++) {
                    color += letters[Math.floor(Math.random() * 3)];
                }
                return color;
            },


            confirmDelete(account) {

                let options = {
                    html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
                    loader: true, // set to true if you want the dailog to show a loader after click on "proceed"
                    reverse: false, // switch the button positions (left to right, and vise versa)
                    okText: 'متاكد',
                    cancelText: 'الغاء',
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                    verification: 'continue', // for hard confirm, user will be prompted to type this to enable the proceed button
                    verificationHelp: 'Type "[+:verification]" below to confirm', // Verification help text. [+:verification] will be matched with 'options.verification' (i.e 'Type "continue" below to confirm')
                    clicksCount: 3, // for soft confirm, user will be asked to click on "proceed" btn 3 times before actually proceeding
                    backdropClose: false, // set to true to close the dialog when clicking outside of the dialog window, i.e. click landing on the mask
                    customClass: '' // Custom class to be injected into the parent node for the current dialog instance
                };

                this.$dialog
                    .confirm("هل انت متاكد من هذه العملية ؟ ستفقد الحسابات الفرعية لهذا الحساب ، ستكون قادراً على عرض العمليات السابقة  التي تمت على هذا الحساب والحسابات الفرعية منه ، ننصحك بالغاء هذه العملية اذا كنت لا تفهم ماذا تفعل", options)
                    .then(dialog => {
                        location.href = this.baseLink + '/' + account.id + '/delete';


                    })
                    .catch(() => {

                        console.log('Delete aborted');
                    });


            },


        },
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

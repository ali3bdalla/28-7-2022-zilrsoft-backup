<template>
    <div class="panel">

        <div class="panel-heading">
            <!--            <div class="row">-->
            <!--                <div class="col-md-9">-->
            <VueCtkDateTimePicker
                    :auto-close="true"
                    :behaviour="{time: {nearestIfDisabled: true}}"
                    :custom-shortcuts="customDateShortcuts"
                    :only-date="false"
                    :range="true"
                    label="التاريخ"
                    locale="en"
                    v-model="createdAtRange"/>
            <!--                </div>-->
            <!--                <div class="col-md-3">-->
            <!--                    <button class="btn btn-primary" @click="exportTableToCSV('datatable')">استخراج الجدول CSV</button>-->
            <!--                </div>-->
            <!--            </div>-->

        </div>


        <div class="panel-body">
            <table @scroll="scrollToUpdate" class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center " colspan="2">المجاميع</th>
                    <th class="text-center " colspan="2">الارصدة</th>
                </tr>
                <tr>
                    <th class="text-center "> التاريخ</th>
                    <th class="text-center "> رقم القيد</th>
                    <th class="text-center "> الهوية</th>
                    <th class="text-center ">البيان</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                </tr>
                </thead>

                <tbody :key="index" v-for="(transaction,index) in transactions">


                <tr v-if="transaction.is_transaction">
                    <th class="text-center " v-text="transaction.created_at"></th>
                    <th class="text-center " v-text="transaction.container_id"></th>
                    <th class="text-center ">
                        <a :href="'/accounting/identities/' + transaction.user.id" v-if="transaction.user">{{
                            transaction.user.locale_name }}</a>
                        <span v-else>-</span>
                    </th>
                    <th class="text-center " v-if="transaction.invoice_id>=1">
                        <a :href="'/accounting/sales/' + transaction.invoice_id"
                           v-if="transaction.invoice.invoice_type=='sale' || transaction.invoice.invoice_type=='sale'"
                           v-text="transaction.invoice.title"></a>
                        <a :href="'/accounting/purchases/' + transaction.invoice_id"
                           v-else
                           v-text="transaction.invoice.title"></a>

                    </th>
                    <th class="text-center " v-else>
                        <a :href="'/accounting/transactions/' + transaction.container_id"

                           v-text="transaction.container_id"></a>
                    </th>
                    <th class="text-center " v-text="parseFloat(transaction.debit_amount).toFixed(2)"></th>
                    <th class="text-center " v-text="parseFloat(transaction.credit_amount).toFixed(2)"></th>
                    <th class="text-center " v-text="parseFloat(transaction.total_debit_amount).toFixed(2)"></th>
                    <th class="text-center " v-text="parseFloat(transaction.total_credit_amount).toFixed(2)"></th>
                </tr>

                <tr class="info" v-else>
                    <th class="text-center " v-text="transaction.created_at"></th>
                    <th class="text-center " v-text="transaction.id"></th>
                    <th class="text-center ">-</th>
                    <th class="text-center "><a :href="'/accounting/accounts/' + transaction.id"
                                                v-text="transaction.locale_name "></a></th>
                    <th class="text-center " v-text="parseFloat(transaction.debit_amount).toFixed(2)"></th>
                    <th class="text-center " v-text="parseFloat(transaction.credit_amount).toFixed(2)"></th>
                    <th class="text-center " v-text="parseFloat(transaction.total_debit_amount).toFixed(2)"></th>
                    <th class="text-center " v-text="parseFloat(transaction.total_credit_amount).toFixed(2)"></th>
                </tr>

                </tbody>
                <tfoot>
                <tr class="success">
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></a></th>
                    <th class="text-center " v-text="parseFloat(totalDebitAmount).toFixed(2)"></th>
                    <th class="text-center " v-text="parseFloat(totalCreditAmount).toFixed(2)"></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                </tr>
                </tfoot>
            </table>
            <tile :color="app.primaryColor" :loading="isLoading" v-show="isLoading"></tile>
            <!--            <div>-->
            <!--                <button @click="csvExport(transactions)">export csv</button>-->
            <!--            </div>-->
            <!--            <div class="btn"-->
            <div class="table-paginations">
                <accounting-table-pagination-helper-layout-component
                        :data="paginationResponseData"
                        :view-only="true"
                        @pagePerItemsUpdated="pagePerItemsUpdated"
                        @paginateUpdatePage="paginateUpdatePage"

                ></accounting-table-pagination-helper-layout-component>
            </div>


        </div>

    </div>
</template>

<script>
    import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';

    export default {
        name: "globalTransactionsListComponent.vue",
        props: ["account"],
        components: {
            VueCtkDateTimePicker
        },
        data: function () {
            return {
                clearOldData: false,
                isLoading: false,
                createdAtRange: null,
                customDateShortcuts: [],
                itemsPerPage: 25,
                requestUrl: "",
                paginationResponseData: null,
                transactions: [],
                totalCreditAmount: 0,
                totalDebitAmount: 0,
                IntervalValue: null,
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
                filters: {
                    endDate: null,
                    startDate: null,
                },
            };
        },
        created: function () {

            this.initJob();
            this.loadData();

        },
        destroyed() {
            window.removeEventListener('scroll', this.handleScroll);
        },
        mounted: function () {
            window.addEventListener('scroll', this.handleScroll);
            // let appVm = this;
            // this.IntervalValue = setInterval(function () {
            //     if (!appVm.isLoading)
            //         // appVm.scrollToUpdate();
            // }, 1000);


        },
        methods: {
            downloadCSV(csv, filename) {
                let csvFile;
                let downloadLink;

                // CSV file
                csvFile = new Blob([csv], {type: "text/csv"});

                // Download link
                downloadLink = document.createElement("a");

                // File name
                downloadLink.download = filename;

                // Create a link to the file
                downloadLink.href = window.URL.createObjectURL(csvFile);

                // Hide download link
                downloadLink.style.display = "none";

                // Add the link to DOM
                document.body.appendChild(downloadLink);

                // Click download link
                downloadLink.click();
            },
            exportTableToCSV(filename) {
                let csv = [];
                let rows = document.querySelectorAll("table tr");

                for (let i = 0; i < rows.length; i++) {
                    let row = [], cols = rows[i].querySelectorAll("td, th");

                    for (let j = 0; j < cols.length; j++)
                        row.push(cols[j].innerText);

                    csv.push(row.join(","));
                }
                this.downloadCSV(csv.join("\n"), filename);
            },
            handleScroll(e) {
                let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight;

                if (bottomOfWindow) {
                    if (this.paginationResponseData != null && !this.isLoading) {
                        if (this.paginationResponseData.next_page_url != null) {
                            this.requestUrl = this.paginationResponseData.next_page_url;
                            this.clearOldData = false;
                            this.loadData();
                        }

                    }
                }
            },
            scrollToUpdate(event) {
                // console.log(event)
                // window.onscroll = () => {
                //     let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight;
                //
                //     if (bottomOfWindow) {
                //         if (this.paginationResponseData != null) {
                //             console.log(this.paginationResponseData.next_page_url );
                //             if (this.paginationResponseData.next_page_url === "" &&
                //                 this.paginationResponseData.next_page_url==null) {
                //                 // clearInterval(this.IntervalValue);
                //                 // alert('hell')
                //             }else
                //             {
                //                 this.requestUrl = this.paginationResponseData.next_page_url;
                //                 this.clearOldData = false;
                //                 this.loadData();
                //             }
                //
                //         }
                //     }
                // }
            },
            initJob() {
                this.requestUrl = "/accounting/accounts/" + this.account.id + "/transactions_datatable";
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
            loadData: function () {
                this.isLoading = true;
                let params = {}, appVm = this;
                params.itemsPerPage = this.itemsPerPage;
                params.startDate = this.filters.startDate;
                params.endDate = this.filters.endDate;
                if (this.clearOldData) {
                    this.transactions = [];
                    this.totalCreditAmount = 0;
                    this.totalDebitAmount = 0;
                }

                // console.log(params);
                axios.get(this.requestUrl, {
                    params: params
                }).then(response => {
                    appVm.isLoading = false;
                    let len = response.data.data.length;
                    for (let index = 0; index < len; index++) {
                        let transaction = response.data.data[index];
                        appVm.totalCreditAmount = appVm.totalCreditAmount + parseFloat(transaction.credit_amount);
                        appVm.totalDebitAmount = appVm.totalDebitAmount + parseFloat(transaction.debit_amount);
                        appVm.transactions.push(transaction);
                    }
                    appVm.paginationResponseData = response.data;
                }).catch(error => {
                    alert(error);
                });
            },


            paginateUpdatePage(event) {
                // this.requestUrl = event.link;
                // this.loadData();

            },

            pagePerItemsUpdated(event) {
                // this.itemsPerPage = event.items;
                // this.loadData();
            },


        },
        watch: {
            createdAtRange: function (value) {
                if (value == null) {
                    this.filters.startDate = null;
                    this.filters.endDate = null;

                } else {
                    this.filters.startDate = value.start;
                    this.filters.endDate = value.end;
                }

                this.clearOldData = true;
                this.requestUrl = this.paginationResponseData.path;
                this.loadData();

            },

        }

    }

</script>

<style scoped>

</style>
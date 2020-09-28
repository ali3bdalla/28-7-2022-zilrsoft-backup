<template>
    <div>
        <div :key="account.id" class="" v-for="(account,index) in accounts_list">
            <div class="account-cell">
                <div class="" style="padding: 10px">
                <span class="pull-left ">
                    <label :class="{'label-primary':account.current_amount>=0}" class="label label-danger ">
                        {{ parseFloat(account.current_amount).toFixed(2)}}
                    </label> &nbsp;
                    <label class="label label-info label-sm" v-if="account.type=='credit'">
                       دائن
                    </label>
                    <label class="label label-warning label-sm" v-else>
                       مدين
                    </label>
                     &nbsp;
                    <a :href="'/accounts/' + account.id + '/edit'" v-if="account.parent_id!=0">
                        <label class="label label-success label-sm">تعديل</label></a>

                    <accounting-delete-button-layout-component :url="'/accounts/' + account.id" class=''
                                                               v-if="account.children_count<=0"><label
                            class="label label-danger label-sm">حذف</label>
                    </accounting-delete-button-layout-component>

                    <a :href="'/accounts/create?parent_id=' + account.id">
                        <label class="label label-success label-sm">اضافة</label></a>
                </span>
                    <span class="lead"><button
                            @click="loadChildren(account,index)"
                            v-if="account.children_count>=1"
                    ><i class="fa fa-list"></i></button>
                    <a :href="'/accounts/' + account.id ">{{account.locale_name }}</a>
                </span>

                </div>
                <div style="margin-top: 10px;!important;">
                    <accounting-chart-of-accounts-list-component
                            :accounts="account.children"
                            :key="account.id"
                            v-if="account.children!=null"
                            v-show="account.is_expanded"></accounting-chart-of-accounts-list-component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["accounts"],
        name: "chartOfAccountsListComponent",
        data: function () {
            return {
                accounts_list: [],
            };
        },
        created() {
            for (let i = 0; i < this.accounts.length; i++) {
                let account = this.accounts[i];
                account.children = null;
                this.accounts_list.push(account);
            }

        },
        methods: {
            loadChildren(account, index) {
                let appVm = this;
                if (account.is_expanded) {
                    account.is_expanded = false;
                    appVm.accounts_list = db.model.replace(appVm.accounts_list, db.model.index(appVm.accounts_list,
                        account.id), item);
                } else {

                    axios.get('/api/accounts/' + account.id + '/children').then(response => {
                        let item = appVm.accounts_list[index];
                        item.children = response.data;
                        item.is_expanded = true;
                        appVm.accounts_list =
                            db.model.replace(appVm.accounts_list, db.model.index(appVm.accounts_list, account.id), item);
                        // appVm.accounts_list = appVm.temp_accounts_list;
                        // console.log(appVm.accounts_list)
                    })
                }


            }
        },
        watch: {
            accounts: function (value) {
                this.accounts_list = value;
            }
        }
    }
</script>

<style scoped>
    .account-cell {
        padding: 5px;
        background-color: white;
        border-bottom: 1px solid #eee;
        padding-right: 40px !important;
        /*padding-bottom: 1px;*/
    }

    .purple_class {
        background-color: purple !important
    }
</style>
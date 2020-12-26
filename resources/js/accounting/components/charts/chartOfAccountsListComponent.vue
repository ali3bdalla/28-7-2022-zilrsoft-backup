<template>
  <div>
    <div v-for="(account,index) in accounts_list" :key="account.id" class="">
      <div class="account-cell">
        <div class="" style="padding: 10px">
                <span class="pull-left ">
                    <label :class="{'label-primary':account.current_amount>=0}" class="label label-danger ">
                        {{ parseFloat(account.current_amount).toFixed(2) }}
                    </label> &nbsp;
                    <label v-if="account.type=='credit'" class="label label-info label-sm">
                       دائن
                    </label>
                    <label v-else class="label label-warning label-sm">
                       مدين
                    </label>
                     &nbsp;
                    <a v-if="manageChartOfAccounts && account.parent_id!=0"
                       :href="'/accounts/' + account.id + '/edit'">
                        <label class="label label-success label-sm">تعديل</label></a>

                    <accounting-delete-button-layout-component
                        v-if="account.children_count<=0 && manageChartOfAccounts"
                        :url="'/api/accounts/' + account.id"
                        class=''><label
                        class="label label-danger label-sm">حذف</label>
                    </accounting-delete-button-layout-component>

                    <a v-if="manageChartOfAccounts" :href="'/accounts/create?parent_id=' + account.id">
                        <label class="label label-success label-sm">اضافة</label></a>
                </span>
          <span class="lead"><button
              v-if="account.children_count>=1"
              @click="loadChildren(account,index)"
          >
            <i v-if="account.is_expanded == false" class="fa fa-plus-circle text-primary "></i>
            <i v-else class="fa fa-minus-circle text-primary"></i>
          </button>
                    <a :href="'/accounts/' + account.id ">{{ account.locale_name }}</a>
                </span>

        </div>
        <div style="margin-top: 10px;!important;">
          <accounting-chart-of-accounts-list-component
              v-if="account.children!=null"
              v-show="account.is_expanded"
              :key="account.id"
              :accounts="account.children"
              :logged-user-id="loggedUserId"></accounting-chart-of-accounts-list-component>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["accounts", 'loggedUserId'],
  name: "chartOfAccountsListComponent",
  data: function () {
    return {
      // canEdit:true,
      accounts_list: [],
    };
  },
  computed: {
    manageChartOfAccounts() {

      // console.log(parseInt(this.loggedUserId) !== 19);
      // console.log(typeof parseInt(this.loggedUserId),typeof 19);
      return parseInt(this.loggedUserId) !== 19 ? true : false;

    }
  },
  created() {
    for (let i = 0; i < this.accounts.length; i++) {
      let account = this.accounts[i];
      account.children = null;
      account.is_expanded = false;
      this.accounts_list.push(account);
    }

  },
  methods: {
    loadChildren(account, index) {
      let appVm = this;
      if (account.is_expanded === true) {
        account.is_expanded = false;
        this.accounts_list.splice(index, 1, account);
      } else {
        account.is_expanded = true;

        axios.get('/api/accounts/' + account.id + '/children').then(response => {
          let item = appVm.accounts_list[index];
          item.children = response.data;
          item.is_expanded = true;
          appVm.accounts_list = db.model.replace(appVm.accounts_list, db.model.index(appVm.accounts_list, account.id), item);

        })
      }
      this.accounts_list.splice(index, 1, account);


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
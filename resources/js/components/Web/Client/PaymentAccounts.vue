<template>
  <div>
    <div class="flex">

      <div class="flex-1 group-input">
        <div class="flex justify-between">
          <div class="flex-1 mb-2">
            <button :class="[activeTab == 'select' ? 'activeClass' : 'inActiveClass']" :disabled="disableSelect"
                    class="site-btn login-btn"
                    type="button"
                    @click="activeTab = 'select'">{{ $page.$t.common.select}}
            </button>

          </div>
          <div class="flex-1">
            <button :class="[activeTab == 'create' ? 'activeClass' : 'inActiveClass']" class="site-btn login-btn"
                    type="button"
                    @click="activeTab = 'create'"> {{ $page.$t.common.add_new}}
            </button>
          </div>
        </div>

      </div>
    </div>

    <div v-if="activeTab == 'select'">
      <div class="flex-1 group-input">
        <select v-model="selectedAccountId" class="">
          <option v-for="account in accounts" :key="account.id" :value="account.id">{{ account.bank.locale_name }} - {{
              account.detail
            }}
          </option>
        </select>
      </div>
    </div>
    <div v-else>
      <div class="flex">
        <div class="flex-1 group-input">

          <!--        sendAccountBankId: 1,-->
          <!--        sendAccountBankNumber: "",-->
          <div>
            <select v-model="bankId" class="">
              <option v-for="bank in $page.banks" :key="bank.id" :value="bank.id">{{ bank.locale_name }}</option>
            </select>
          </div>
          <!--        <div-->
          <!--            v-if="$page.errors.sender_bank_id"-->
          <!--            class="p-2 text-red-500"-->
          <!--        >-->
          <!--          {{ $page.errors.sender_bank_id }}-->
          <!--        </div>-->
        </div>
        <div class="flex-1 group-input">

          <input
              id="sender_account_number"
              v-model="accountNumber"
              max-length="30"
              :placeholder="$page.$t.profile.account_number"
              type="text"
          />
          <div
              v-if="$page.errors.sender_account_number"
              class="p-2 text-red-500"
          >
            {{ $page.errors.sender_account_number }}
          </div>
        </div>
        <div class="w-1/4">
          <button
              class="site-btn login-btn inActiveClass"
              style="margin-top: -1px;height: 50px"
              type="button"
              @click="addNewAccount">{{ $page.$t.common.save }}
          </button>
        </div>
      </div>
    </div>


  </div>

</template>

<script>
export default {
  name: "PaymentAccounts",
  data() {
    return {
      activeTab: 'select',
      disableSelect: false,
      selectedAccountId: null,
      bankId: null,
      accountNumber: "",
      accounts: []
    }
  },
  created() {
    axios.get(`/api/web/${this.$page.order.user_id}/payment_accounts`).then(res => {
      if (res.data.length === 0) {
        this.activeTab = 'create';
        this.disableSelect = true;
      }

      this.accounts = res.data
    });
  },
  methods: {
    addNewAccount() {
      axios.post(`/api/web/${this.$page.order.user_id}/payment_accounts`, {
        detail: this.accountNumber,
        bank_id: this.bankId,
      }).then(res => {
        console.log(res.data);
        this.detail = "";
        this.bank_id = "";
        this.activeTab = 'select';

        this.accounts.push(res.data);
        this.selectedAccountId = res.data.id;
        this.$alert(this.$page.$t.messages.success,this.$page.$t.messages.bank_account_has_been_created,'success');
      }).catch(error => this.$alert('Please Check Your Account Information Again','','error'));
    }
  },
  watch: {
    selectedAccountId(value) {
      this.$emit('updateAccountId', {accountId: value})
    }
  }
}
</script>

<style scoped>
.inActiveClass {
  background-color: #777;
  color: #ddd;
  border: 1px solid white;
}

.activeClass {
  background-color: #ddd;
  color: #777;
  border: 1px solid #e7ab3c;
}
</style>
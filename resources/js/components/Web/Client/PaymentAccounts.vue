<template>
    <div>
      <div class="flex">
        <div class="flex-1 group-input">
          <label for="first_name">{{ $page.$t.order.transmitter_name }}</label>
          <input
            id="first_name"
            v-model="form.firstName"
            :placeholder="$page.$t.profile.first_name"
            type="text"
          />
          <div v-if="$page.errors.first_name" class="p-2 text-red-500">
            {{ $page.errors.first_name }}
          </div>
        </div>
        <div class="flex-1 group-input">
          <label for="first_name">.</label>

          <input
            id="last_name"
            v-model="form.lastName"
            :placeholder="$page.$t.profile.last_name"
            type="text"
          />
          <div v-if="$page.errors.last_name" class="p-2 text-red-500">
            {{ $page.errors.last_name }}
          </div>
        </div>
      </div>

      <div class="flex flex-col">
        <div class="flex-1 group-input">
          <div>
            <select v-model="form.bankId" class="">
              <option :value="null">{{ $page.$t.common.select_bank }}</option>
              <option
                v-for="bank in $page.banks"
                :key="bank.id"
                :value="bank.id"
              >
                {{ bank.locale_name }}
              </option>
            </select>
          </div>
        </div>
        <div class="flex-1 group-input">
          <input
            id="sender_account_number"
            v-model="form.accountNumber"
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
        <!-- <div class="w-1/4">
          <button
              class="inActiveClass"
              @click="addNewAccount">{{ $page.$t.common.save }}
          </button>
        </div> -->
      </div>
    </div>
</template>

<script>
export default {
  name: 'PaymentAccounts',
  data () {
    return {
      activeTab: 'select',
      disableSelect: false,
      accounts: [],
      form: {
        accountNumber: '',
        bankId: null,
        selectedAccountId: null,

        firstName: this.$page.user.first_name,
        lastName: this.$page.user.last_name
      }
    }
  },
  created () {
    axios
      .get(`/api/web/${this.$page.order.user_id}/payment_accounts`)
      .then((res) => {
        if (res.data.length === 0) {
          this.activeTab = 'create'
          this.disableSelect = true
        }

        this.accounts = res.data
      })
  },

  watch: {
    form: {
      deep: true,
      handler (value) {
        this.$emit('formUpdated', value)
      }
    },
    selectedAccountId (value) {
      this.$emit('updateAccountId', { accountId: value })
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

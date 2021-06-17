<template>
  <div class="register-login-section">
    <div class="container">
      <language-switcher></language-switcher>
      <div class="">
        <div class="col-lg-6 offset-lg-3">
          <div class="login-form">
            <h2>{{ $page.$t.order.payment_confirmation }}</h2>
            <h2>{{ $page.$t.order.order }} #{{ $page.order.id }}</h2>
            <div class="flex">
              <div
                  class="flex-1 group-input text-center"
                  style="direction: ltr"
              >
                <div>
                  <label for="first_name">{{
                      $page.$t.order.remmning_time_to_auto_cancel_order
                    }}</label>
                </div>
                <br/>
                <flip-countdown
                    :deadline="$page.order.auto_cancel_at"
                    :showDays="false"
                    :showHours="false"
                ></flip-countdown>
              </div>
            </div>
            <br/>
            <form action="#">
              <!-- <PaymentAccounts @updateAccountId="updateAccountId"></PaymentAccounts> -->
              <div>
                <!-- <label for="first_name">{{ $page.$t.common.select_or_create_account }}</label> -->
                <!-- <div class="flex-1 flex items-center justify-between group-input gap-2">
                  <select v-model="senderAccountId" class="">
                    <option :value="null">
                      {{ $page.$t.common.select_account }}
                    </option>

                    <option
                      v-for="account in accounts"
                      :key="account.id"
                      :value="account.id"
                    >
                      {{ account.bank.locale_name }} - {{ account.detail }}
                    </option>
                  </select>
                  <el-button @click="showCreate = true" size="" style="height:50px"> <i class="fa fa-plus"></i></el-button>
                </div> -->
              </div>
              <div class="flex">
                <div class="flex-1 group-input">
                  <label for="first_name">{{
                      $page.$t.order.transmitter_name
                    }}</label>
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
                      <option :value="null">
                        {{ $page.$t.common.select_sender_bank }}
                      </option>
                      <option
                          v-for="bank in $page.banks"
                          :key="bank.id"
                          :value="bank.id"
                      >
                        {{ bank.locale_name }}
                      </option>
                    </select>
                    <div v-if="$page.errors.bank_id" class="p-2 text-red-500">
                      {{ $page.errors.bank_id }}
                    </div>
                  </div>
                </div>
                <!-- <div class="flex-1 group-input">
                  <input
                    id="sender_account_number"
                    v-model="form.accountNumber"
                    max-length="30"
                    :placeholder="$page.$t.profile.account_number"
                    type="text"
                  />
                  <div v-if="$page.errors.detail" class="p-2 text-red-500">
                    {{ $page.errors.detail }}
                  </div>
                </div> -->
              </div>

              <div class="flex mt-3 border-t pt-4">
                <div class="flex-1 group-input">
                  <label for="first_name">{{ $page.$t.order.to_blank }}</label>
                  <div
                      class="flex flex-col text-center items-center justify-between"
                  >
                    <div>{{ $page.receivedBank.locale_name }}</div>
                    <div>SA7280000122608010398991</div>
                  </div>
                </div>
              </div>
              <button class="site-btn login-btn" type="button" @click="submit">
                {{ $page.$t.messages.confirm }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FlipCountdown from 'vue2-flip-countdown'
import LanguageSwitcher from '../../../components/Web/Page/LanguageSwitcher'

export default {
  components: {
    LanguageSwitcher,
    FlipCountdown
  },
  data () {
    return {
      showCreate: false,
      accounts: [],
      form: {
        accountNumber: '',
        bankId: null,
        firstName: this.$page.user.first_name,
        lastName: this.$page.user.last_name
      },
      senderAccountId: null,
      receiverAccountBankId: 1
    }
  },
  methods: {
    submit () {
      if (this.senderAccountId) {
        this.confirmPayment()
      } else {
        this.addNewAccount()
      }
    },
    addNewAccount () {
      window.getGooogleTag('event', 'conversion', {
        send_to: 'AW-851059339/U_sVCLfmhI0CEIvF6JUD',
        transaction_id: this.$page.order.id
      })
      // this.form.accountNumber
      this.$inertia.post(
          `/api/web/${this.$page.order.user_id}/payment_accounts`,
          {
            detail: '00000000',
            first_name: this.form.firstName,
            last_name: this.form.lastName,
            bank_id: this.form.bankId
          }
      )
      this.$inertia.on('invalid', (event) => {
        event.preventDefault()

        this.senderAccountId = event.detail.response.data.id

        this.confirmPayment()
      })
    },
    updateAccountId (e) {
      this.senderAccountId = e.accountId
    },
    confirmPayment () {
      console.log('done', this.$page.order.net)
      getGooogleTag('event', 'conversion', {
        send_to: 'AW-851059339/MESSCPaAycICEIvF6JUD',
        value: this.$page.order.net,
        currency: 'SAR',
        transaction_id: this.$page.order.id
      })
      this.$inertia.post(
        '/web/orders/' +
          this.$page.order.id +
          '/confirm_payment?code=' +
          this.$page.code,
        {
          sender_account_id: this.senderAccountId,
          receiver_bank_id: this.receiverAccountBankId,
          first_name: this.form.firstName,
          last_name: this.form.lastName
        }
      )
    }
  },
  created () {
    axios
      .get(`/api/web/${this.$page.order.user_id}/payment_accounts`)
      .then((res) => {
        this.accounts = res.data
      })
  }
}
</script>

<style>
</style>

<template>
  <div class="register-login-section">
    <div class="container">
      <language-switcher></language-switcher>
      <div class="">
        <div class="col-lg-6 offset-lg-3">
          <div class="login-form">
            <h2>{{ $page.props.$t.order.payment_confirmation }}</h2>
            <h2>{{ $page.props.$t.order.order }} #{{ $page.props.order.id }}</h2>
            <div class="flex">
              <div
                  class="flex-1 group-input text-center"
                  style="direction: ltr"
              >
                <div>
                  <label for="first_name">{{
                      $page.props.$t.order.remmning_time_to_auto_cancel_order
                    }}</label>
                </div>
                <br/>
                <flip-countdown
                    :deadline="$page.props.order.auto_cancel_at"
                    :showDays="false"
                    :showHours="false"
                ></flip-countdown>
              </div>
            </div>
            <br/>
            <form action="#">
              <div class="flex">
                <div class="flex-1 group-input">
                  <label for="first_name">{{
                      $page.props.$t.order.transmitter_name
                    }}</label>
                  <input
                      id="first_name"
                      v-model="form.firstName"
                      :placeholder="$page.props.$t.profile.first_name"
                      type="text"
                  />
                  <div v-if="$page.props.errors.first_name" class="p-2 text-red-500">
                    {{ $page.props.errors.first_name }}
                  </div>
                </div>
                <div class="flex-1 group-input">
                  <label for="first_name">.</label>

                  <input
                      id="last_name"
                      v-model="form.lastName"
                      :placeholder="$page.props.$t.profile.last_name"
                      type="text"
                  />
                  <div v-if="$page.props.errors.last_name" class="p-2 text-red-500">
                    {{ $page.props.errors.last_name }}
                  </div>
                </div>
              </div>

              <div class="flex flex-col">
                <div class="flex-1 group-input">
                  <div>
                    <select v-model="form.bankId" class="">
                      <option :value="null">
                        {{ $page.props.$t.common.select_sender_bank }}
                      </option>
                      <option
                          v-for="bank in $page.props.banks"
                          :key="bank.id"
                          :value="bank.id"
                      >
                        {{ bank.locale_name }}
                      </option>
                    </select>
                    <div v-if="$page.props.errors.bank_id" class="p-2 text-red-500">
                      {{ $page.props.errors.bank_id }}
                    </div>
                  </div>
                </div>
                <!-- <div class="flex-1 group-input">
                  <input
                    id="sender_account_number"
                    v-model="form.accountNumber"
                    max-length="30"
                    :placeholder="$page.props.$t.profile.account_number"
                    type="text"
                  />
                  <div v-if="$page.props.errors.detail" class="p-2 text-red-500">
                    {{ $page.props.errors.detail }}
                  </div>
                </div> -->
              </div>

              <div class="flex mt-3 border-t pt-4">
                <div class="flex-1 group-input">
                  <label for="first_name">{{ $page.props.$t.order.to_blank }}</label>
                  <div
                      class="flex flex-col text-center items-center justify-between"
                  >
                    <div>{{ $page.props.receivedBank.locale_name }}</div>
                    <div>SA7280000122608010398991</div>
                  </div>
                </div>
              </div>
              <button class="site-btn login-btn" type="button" @click="submit">
                {{ $page.props.$t.messages.confirm }}
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
import LanguageSwitcher from '../../Components/Layout/LanguageSwitcher.vue'

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
        firstName: this.$page.props.user.first_name,
        lastName: this.$page.props.user.last_name
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
      this.$inertia.post(
          `/api/web/${this.$page.props.order.user_id}/payment_accounts`,
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
      this.$inertia.post(
        '/api/web/orders/' +
          this.$page.props.order.id +
          '/confirm_payment?code=' +
          this.$page.props.code,
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
      .get(`/api/web/${this.$page.props.order.user_id}/payment_accounts`)
      .then((res) => {
        this.accounts = res.data
      })
  }
}
</script>

<style>
</style>

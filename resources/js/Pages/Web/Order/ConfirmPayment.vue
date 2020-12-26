<template>
  <div class="register-login-section">
    <div class="container">

      <div class="">
        <div class="col-lg-6 offset-lg-3">
          <div class="login-form">
            <h2>Payment Confirmation</h2>
            <h2>Order #{{ $page.order.id }}</h2>
            <div class="flex">
              <div class="flex-1 group-input text-center">
                <div><label for="first_name">Ramming Time To Auto Cancel Order</label></div>
                <br/>
                <flip-countdown :deadline="$page.order.auto_cancel_at" :showDays="false"
                                :showHours="false"></flip-countdown>
              </div>

            </div>
            <br/>
            <!--              <h3>Order #{{$page.order.id}}</h3>-->
            <form action="#">


              <div class="flex">
                <div class="flex-1 group-input">
                  <label for="first_name">Transmitter Name</label>
                  <input
                      id="first_name"
                      v-model="firstName"
                      placeholder="First Name"
                      type="text"
                  />
                  <div
                      v-if="$page.errors.first_name"
                      class="p-2 text-red-500"
                  >
                    {{ $page.errors.first_name }}
                  </div>
                </div>
                <div class="flex-1 group-input">
                  <label for="first_name">.</label>

                  <input
                      id="last_name"
                      v-model="lastName"
                      placeholder="Last Name"
                      type="text"
                  />
                  <div v-if="$page.errors.last_name" class="p-2 text-red-500">
                    {{ $page.errors.last_name }}
                  </div>
                </div>
              </div>


              <PaymentAccounts @updateAccountId="updateAccountId"></PaymentAccounts>


              <div class="flex">

                <div class="flex-1 group-input">
                  <label for="first_name">To Bank</label>

                  <select v-model="receiverAccountBankId" class="">
                    <option v-for="bank in $page.receivedBanks" :key="bank.id" :value="bank.id">{{ bank.name }}</option>
                  </select>
                  <div
                      v-if="$page.errors.receiver_bank_id"
                      class="p-2 text-red-500"
                  >
                    {{ $page.errors.receiver_bank_id }}
                  </div>
                </div>


              </div>
              <button
                  class="site-btn login-btn"
                  type="button"
                  @click="confirmPayment"
              >
                Confirm
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
import PaymentAccounts from "../../../components/Web/Client/PaymentAccounts";

export default {
  components: {FlipCountdown, PaymentAccounts},
  data() {
    return {
      firstName: "",
      lastName: "",
      senderAccountId: null,
      receiverAccountBankId: 1,
    };
  },
  methods: {

    updateAccountId(e) {
      this.senderAccountId = e.accountId;
    },
    confirmPayment() {
      this.$inertia.post('/web/orders/' + this.$page.order.id + '/confirm_payment?code='+ this.$page.code, {
        sender_account_id: this.senderAccountId,
        receiver_bank_id: this.receiverAccountBankId,
        first_name: this.firstName,
        last_name: this.lastName,
      })
    }
    // items() {
    //   return this.$page.items.data;
    // },
  },
};
</script>

<style>
</style>
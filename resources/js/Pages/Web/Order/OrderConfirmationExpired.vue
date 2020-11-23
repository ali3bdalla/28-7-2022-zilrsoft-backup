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


              <div class="flex">

                <div class="flex-1 group-input">
                  <label for="first_name">Transmitter Account</label>

                  <select v-model="sendAccountBankId" class="">
                    <option v-for="bank in $page.banks" :key="bank.id" :value="bank.id">{{ bank.name }}</option>
                  </select>
                  <div
                      v-if="$page.errors.sender_bank_id"
                      class="p-2 text-red-500"
                  >
                    {{ $page.errors.sender_bank_id }}
                  </div>
                </div>

                <div class="flex-1 group-input">
                  <label for="sender_account_number">.</label>

                  <input
                      id="sender_account_number"
                      v-model="sendAccountBankNumber"
                      max-length="30"
                      placeholder="Account Number"
                      type="text"
                  />
                  <div
                      v-if="$page.errors.sender_account_number"
                      class="p-2 text-red-500"
                  >
                    {{ $page.errors.sender_account_number }}
                  </div>
                </div>

              </div>

              <div class="flex">

                <div class="flex-1 group-input">
                  <label for="first_name">To Bank</label>

                  <select v-model="receiverAccountBankId" class="">
                    <option v-for="bank in $page.banks" :key="bank.id" :value="bank.id">{{ bank.name }}</option>
                  </select>
                  <div
                      v-if="$page.errors.receiver_bank_id"
                      class="p-2 text-red-500"
                  >
                    {{ $page.errors.receiver_bank_id }}
                  </div>
                </div>

                <!--                  <div class="flex-1 group-input">-->
                <!--                    <label for="first_name">.</label>-->

                <!--                    <input-->
                <!--                        id="first_name"-->
                <!--                        v-model="first_name"-->
                <!--                        max-length="30"-->
                <!--                        placeholder="Transmitter Account Number"-->
                <!--                        type="text"-->
                <!--                    />-->
                <!--                    <div-->
                <!--                        v-if="$page.errors.first_name"-->
                <!--                        class="p-2 text-red-500"-->
                <!--                    >-->
                <!--                      {{ $page.errors.first_name }}-->
                <!--                    </div>-->
                <!--                  </div>-->

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

export default {
  components: {FlipCountdown},
  data() {
    return {
      firstName: "",
      lastName: "",
      sendAccountBankId: 1,
      sendAccountBankNumber: "",
      receiverAccountBankId: 1,
    };
  },
  methods: {

    confirmPayment() {
      this.$inertia.post('/web/orders/' + this.$page.order.id + '/confirm_payment', {
        sender_bank_id: this.sendAccountBankId,
        sender_account_number: this.sendAccountBankNumber,
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
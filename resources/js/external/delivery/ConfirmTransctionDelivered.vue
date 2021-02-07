<template>
  <div>
    <button
      @click="showConfirmationCode"
      :disabled="transaction.status != 'shipped'"
      style="background-color: transparent; border: none; color: black"
    >
      <h2
        :style="[
          transaction.status == 'shipped'
            ? 'color: blue;cursor: pointer'
            : 'color:white',
        ]"
      >
        #{{ transaction.order_id }}
      </h2>
    </button>

    <button @click="resendOtp" class="btn btn-primary">resend</button>
  </div>
</template>

<script>
export default {
  name: 'ConfirmTransctionDelivered',
  props: {
    transaction: {
      type: Object,
      required: true
    },
    deliveryMan: {
      type: Object,
      required: true
    }
  },
  methods: {
    resendOtp () {
      axios
        .get(
          `/delivery_man/${this.transaction.id}/resend_otp`
        )
        .then((res) => {
          this.$alert(
            'Done',
            'Success',
            'success'
          ).then((res) => {
            // location.reload()
          })
        })
    },
    showConfirmationCode () {
      this.$prompt('Confirm Delivery Code', '', this.transaction.order_id).then(
        (text) => {
          if (text !== null) {
            axios
              .post(
                `/delivery_man/confirm/${this.deliveryMan.hash}/${this.transaction.order_id}`,
                {
                  code: text
                }
              )
              .then((res) => {
                console.log(res.data)
                this.$alert(
                  'order Delivered Successfully ',
                  'Success',
                  'success'
                ).then((res) => {
                  location.reload()
                })
              })
              .catch((error) => {
                console.log(error.response)
                this.$alert('Wrong Code', 'Error', 'error').then((res) => {
                  this.showConfirmationCode()
                })
              })
          }
        }
      )
    }
  }
}
</script>

<style scoped>
</style>

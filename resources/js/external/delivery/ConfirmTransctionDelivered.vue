<template>
  <div v-if="transaction.status == 'shipped'" class="d-flex justify-content-between">
    <button
      @click="showConfirmationCode"
      :disabled="transaction.status != 'shipped'"
      class="btn btn-success"
    >

        تسليم الطلب #{{ transaction.order_id }}

    </button>

    <button @click="resendOtp" class="btn btn-primary">اعادة ارسال</button>
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
            'تم اعادة ارسال الرمز',
            'نجاح',
            'success'
          ).then((res) => {
            // location.reload()
          })
        })
    },
    showConfirmationCode () {
      this.$prompt('ادخل رمز التسليم', '', `رقم الطلب ${this.transaction.order_id}`, {
        confirmButtonText: 'تاكيد',
        cancelButtonText: 'الغاء'
      }).then(
        (text) => {
          if (text !== null) {
            axios
              .post(
                `/delivery_man/confirm/${this.deliveryMan.hash}/${this.transaction.id}`,
                {
                  code: text
                }
              )
              .then((res) => {
                this.$alert(
                  'تم تسليم الشحنة بنجاح ',
                  'نجاح',
                  'success'
                ).then((res) => {
                  location.reload()
                })
              })
              .catch((error) => {
                console.log(error.response)
                this.$alert('الرمز المدخل غير صحيح', 'خطأ', 'error').then((res) => {
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

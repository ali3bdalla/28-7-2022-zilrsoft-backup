<template>
  <div>
    <!-- :default-index="clientList[0].id" -->
    <div class="row">
      <div class="col-md-6">
        <accounting-select-with-search-layout-component
          :no_all_option="true"
          :options="shippingMen"
          placeholder="مندوب التوصيل"
          title="مندوب التوصيل"
          identity="001"
          index="001"
          label_text="locale_name"
          @valueUpdated="deliveryManHasBeenChanged"
        >
        </accounting-select-with-search-layout-component>
      </div>
      <div class="col-md-6">
        <button class="btn btn-primary confirm" @click="confirm">
          تاكيد التسليم للمندوب
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OrderPaymentOptions',
  props: {
    order: {
      type: Object,
      required: true
    },
    shippingMen: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      deliveryManId: null
    }
  },

  methods: {
    deliveryManHasBeenChanged (e) {
      this.deliveryManId = e.value ? e.value.id : null
    },
    confirm () {
      this.$confirm('هل انت متاكد ؟', 'تاكيد الحوالة', 'success').then(() => {
        axios
          .post(`/api/orders/${this.order.id}/sign-to-delivery-man`, {
            delivery_man_id: this.deliveryManId
          })
          .then((response) => { this.verifyCode() })
          .catch((error) => {
            this.$alert('بيانات الطلب/المندوب غير سليمة', 'خطأ', 'error')
            console.log(error.response)
          })
      })
    },

    verifyCode () {
      this.$prompt('الرجاء ادخال رمز المندوب').then((text) => {
        axios
          .post(`/api/orders/${this.order.id}/activate-sign-to-delivery-man`, {
            delivery_man_id: this.deliveryManId,
            verification_code: text
          })
          .then((response) => {
            this.$alert('', 'تمت العملية', 'success').then(() => {
              location.reload()
            })
          })
          .catch((error) => {
            this.$alert('بيانات تاكيد العملية  غير سليمة', 'خطأ', 'error').then(() => {
              this.verifyCode()
            })
            // console.log(error.response);
          })
      })
    }
  }
}
</script>

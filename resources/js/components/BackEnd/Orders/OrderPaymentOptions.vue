<template>
  <div>
    <button class="btn btn-primary confirm" @click="confirm">تاكيد الحوالة
    </button>
    <button class="btn btn-danger confirm" @click="cancel">الغاء الطلب</button>
  </div>
</template>

<script>
export default {
  name: 'OrderPaymentOptions',
  props: {
    order: {
      type: Object,
      required: true

    }
  },
  methods: {
    confirm () {
      this.$confirm('هل انت متاكد ؟', 'تاكيد الحوالة', 'success', { confirmButtonText: 'نعم', cancelButtonText: 'لا' }).then(() => {
        axios.patch(`/api/orders/${this.order.id}`).then(response => console.log(response.data))
      })
    },
    cancel () {
      this.$confirm('هل انت متاكد ؟', 'الغاء الحوالة', 'error', { confirmButtonText: 'نعم', cancelButtonText: 'لا' }).then(function () {
        axios.delete(`/api/orders/${this.order.id}`).then(response => console.log(response.data))
      })
    }
  }
}
</script>

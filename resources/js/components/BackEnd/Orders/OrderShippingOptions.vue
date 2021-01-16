<template>
  <div>
    <button class="btn btn-primary confirm" @click="confirm">تاكيد الحوالة
    </button>

  </div>
</template>

<script>
export default {
  name: "OrderPaymentOptions",
  props: {
    order: {
      type: Object,
      required: true

    }
  },
  methods: {
    confirm() {
      this.$confirm('هل انت متاكد ؟', "تاكيد الحوالة", 'success').then(() => {
        axios.patch(`/api/orders/${this.order.id}`).then(response => console.log(response.data));
      });
    },
    cancel() {
      this.$confirm('هل انت متاكد ؟', "الغاء الحوالة", 'error').then(function () {
        axios.delete(`/api/orders/${this.order.id}`).then(response => console.log(response.data));
      });
    }
  }
}
</script>

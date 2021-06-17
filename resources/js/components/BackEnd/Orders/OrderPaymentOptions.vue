<template>
  <div>
    <div class="mb-4">

      <accounting-select-with-search-layout-component
          :default-index="accountId"
          :no_all_option="true"
          :options="accounts"
          placeholder="الحساب"
          title="الحساب"
          identity="001"
          index="001"
          label_text="locale_name"
          @valueUpdated="clientListChanged"
      >

      </accounting-select-with-search-layout-component>
    </div>

    <div class="mt-5" style="margin-top:10px">
      <button class="btn btn-primary confirm" @click="confirm">تاكيد الحوالة
      </button>
      <button class="btn btn-danger confirm" @click="cancel">الغاء الطلب</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OrderPaymentOptions',
  props: {
    accounts: {
      type: Array,
      required: true
    },
    order: {
      type: Object,
      required: true

    }
  },
  data () { return { accountId: 0 } },
  methods: {
    clientListChanged (account) { this.accountId = account.value.id },
    confirm () {
      this.$confirm('هل انت متاكد ؟', 'تاكيد الحوالة', 'success', { confirmButtonText: 'نعم', cancelButtonText: 'لا' }).then(() => {
        axios.patch(`/api/orders/${this.order.id}?account_id=${this.accountId}`).then(response => {
          getGooogleTag('event', 'conversion', {
            send_to: 'AW-851059339/MESSCPaAycICEIvF6JUD',
            value: this.order.net,
            currency: 'SAR',
            transaction_id: this.order.id
          })
          location.reload()
        }).catch(error => {
          this.$alert('يجب ان تقوم باختيار حساب', 'خطأ', 'error')
        })
      })
    },
    cancel () {
      this.$confirm('هل انت متاكد ؟', 'الغاء الحوالة', 'error', { confirmButtonText: 'نعم', cancelButtonText: 'لا' }).then(() => {
        axios.delete(`/api/orders/${this.order.id}`).then(response => location.reload())
      })
    }
  }
}
</script>

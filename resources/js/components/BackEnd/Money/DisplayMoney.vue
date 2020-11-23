<template>
  <div style="direction:ltr">{{ $currency.formatter.format(money, {currency: ""}) }}</div>
</template>

<script>

export default {
  props: {
    money: {
      type: String,
      required: true
    },
    currency: {
      type: String,
      default: 'SAR'
    }
  },
  name: "DisplayMoney"
}
</script>

<style scoped>

</style>
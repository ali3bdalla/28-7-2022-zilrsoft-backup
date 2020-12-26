<template>
  <span style="direction:ltr">{{ $currency.formatter.format(money, {currency: ""}) }}</span>
</template>

<script>

export default {
  props: {
    money: {
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
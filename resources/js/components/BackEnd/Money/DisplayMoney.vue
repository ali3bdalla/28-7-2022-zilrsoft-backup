<template>
  <span style="direction:ltr">{{ currencyFormatter.format(money, { currency: '' }) }}</span>
</template>

<script>
import currencyFormatter from 'currency-formatter';
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
  data() {
      return {
        currencyFormatter: currencyFormatter
      }
  },
  name: 'DisplayMoney'
}
</script>

<style scoped>

</style>

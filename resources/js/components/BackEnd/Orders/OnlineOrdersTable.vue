<template>
  <div class="page__content">
    <!-- <loading
      :active.sync="loading"
      :can-cancel="true"
      :is-full-page="true"
    ></loading> -->
    <table class="data__table" width="100%">
      <thead>
        <tr class="data__table__header">
          <th
            v-for="(column, index) in columns"
            :key="index"
            class="data__table__title"
            v-text="column.name"
          ></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in rows" :key="order.id" class="data__table__row">
          <td class="data__table__cell" v-text="order.id"></td>
          <td class="data__table__cell" v-text="order.user.name"></td>
          <td class="data__table__cell"></td>
          <td class="data__table__cell">
            <display-money :money="order.net" />
          </td>
          <td class="data__table__cell">
            <display-money :money="order.shipping_amount" />
          </td>

          <td class="data__table__cell"></td>
          <td class="data__table__cell" v-text="order.tracking_number"></td>
          <td class="data__table__cell" v-text="order.status"></td>
          <td class="data__table__cell">
            <a
              :href="`/store/orders/${order.id}`"
              class="data__table__dropdown__button"
            >
              لوحة التحكم
            </a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data () {
    return {
      loading: true,
      rows: []
    }
  },
  computed: {
    columns () {
      return [
        {
          name: 'orderId',
          show: true,
          searchable: true,
          sortable: true
        },
        {
          name: 'clientName',
          show: true,
          searchable: true,
          sortable: true
        },
        {
          name: 'manager',
          show: true,
          searchable: true,
          sortable: true
        },
        {
          name: 'net',
          show: true,
          searchable: true,
          sortable: true
        },
        {
          name: 'shippingAmount',
          show: true,
          searchable: true,
          sortable: true
        },
        {
          name: 'shippingMethod',
          show: true,
          searchable: true,
          sortable: true
        },

        {
          name: 'trackingNumber',
          show: true,
          searchable: true,
          sortable: true
        },
        {
          name: 'status',
          show: true,
          searchable: true,
          sortable: true
        },
        {
          name: 'options',
          show: true,
          searchable: false,
          sortable: false
        }
      ]
    },
    dropdownItems () {
      return [
        {
          link: 'User',
          name: 'User'
        }
      ]
    }
  },
  created () {
    this.getData()
  },
  methods: {
    getData () {
      axios
        .get('/api/orders')
        .then((res) => {
          this.rows = res.data
          console.log(res)
        })
        .catch((error) => {
          console.log(error)
        })
        .finally(() => (this.loading = false))
    }
  }
}
</script>

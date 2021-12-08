<template>
  <div class="panel-body">
    <input
        ref="pickupItemsField"
        v-model="searchKeywords"
        class="form-control"
        placeholder="بحث عن المنتجات"
        tabindex="1"
        type="text"
        @keyup.enter="filterItems"
    />
    <div
        v-if="items && items.length"
        class="live-vue-search panel"
    >
      <div
          v-for="item in items"
          :key="item.id"
          class="panel-footer"
          @click="picked(item)"
      >
        <div class="w-100 title has-text-white">
          {{ item.locale_name }}
          <small class="has-text-white">{{ item.barcode }} - {{ item.price_with_tax }}</small>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  data () {
    return {
      items: [],
      searchKeywords: ''
    }
  },
  mounted () {
    this.cleanPickupItemsField()
  },
  methods: {
    cleanPickupItemsField () {
      this.items = []
      this.searchKeywords = ''
      this.$refs.pickupItemsField.focus()
    },
    itemQtyUpdated (item) {

    },
    picked (item) {
      let validatedItem = this.validateItem(item)
      if (validatedItem) {
        this.$store.commit('addInvoiceItem', {
          quantity: 1,
          serials: item.serials ?? [],
          ...item
        })
      }
      this.cleanPickupItemsField()
    },

    validateItem (item) {
      item.printable = true
      if (item.has_init_serial) {
        return this.parseSerialItem(item)
      }
      if (item.is_kit) {
        return this.parseKitItem(item)
      }
      return item
    },
    parseSerialItem (item) {
      const serial = item.init_serial.serial
      item.serials = [serial]
      item.discount = 0
      return item
    },

    filterItems (keywords) {
      const txt = keywords.target.value
      axios.post('/api/items/query/search', {
        barcode_or_name_or_serial: txt,
      }).then(res => {
        this.items = res.data
      })
    },

    parseKitItem (item) {
      item.available_qty = 10000
      item.price = item.data.total
      item.quantity = 1
      item.tax = item.data.tax
      item.is_fixed_price = true
      return item
    },

  },
  name: 'FormItemPicker'
}
</script>

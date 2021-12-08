<template>
  <div class="panel panel-primary">
    <table class="table table-bordered text-center table-striped">
      <thead class="panel-heading">
      <tr>
        <th></th>
        <th></th>
        <th></th>
        <th>الباركود</th>
        <th>اسم المنتج</th>
        <th>المخزون</th>
        <th>الكمية</th>
        <th>السعر</th>
        <th>الاجمالي</th>
        <th>الصافي</th>
        <th>الضريبة</th>
        <th>النهائي</th>
      </tr>
      </thead>

      <tbody>
      <tr
          v-for="(item, index) in itemsOnly" :key="item.id"
      >
        <td>
          <button
              class="btn btn-danger btn-xs"
          >
            <i class="fa fa-trash"></i>
          </button>
        </td>
        <td>
          <button
              v-if="item.is_need_serial"
              class="btn btn-success btn-xs"
          >
            <i class="fa fa-bars"></i> &nbsp;
          </button>

          <accounting-kit-items-layout-component
              v-if="item.is_kit === true"
              :index="index"
              :kit="item"
              :qty="item.quantity"
          >
          </accounting-kit-items-layout-component>
        </td>
        <td>
          <button
              v-show="item.printable"
              class="btn btn-success btn-xs"
          >
            <i class="fa fa-eye"></i> &nbsp;
          </button>
          <button
              v-show="!item.printable"
              class="btn btn-danger btn-xs"
          >
            <i class="fa fa-eye-slash"></i> &nbsp;
          </button>
        </td>
        <td data-app>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <span v-on="on">{{ item.barcode }}</span>
            </template>
            <span>{{ itemCost(item) }}</span>
          </v-tooltip>
        </td>
        <td>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <span v-on="on">{{ item.locale_name }}</span>
            </template>
            <span>{{ item.warranty_title }}</span>
          </v-tooltip>
        </td>
        <td v-text="item.available_qty"></td>
        <td>
          <input
              v-if="!item.is_need_serial"
              :ref="'itemQty_' + item.id + 'Ref'"
              v-model="item.qty"
              class="form-control input-xs amount-input"
              type="text"
              @focus="$event.target.select()"
          />
          <p v-else>{{ item.qty }}</p>
        </td>

        <td>
          <input
              :ref="'itemPrice_' + item.id + 'Ref'"
              v-model="item.price"
              :disabled="item.is_fixed_price || item.is_service"
              class="form-control input-xs amount-input"
              type="text"
              @focus="$event.target.select()"
              @keyup.enter="clearAndFocusOnBarcodeField"
          />
        </td>

        <td>
          <input
              v-model="item.total"
              class="form-control input-xs amount-input"
              disabled
              type="text"
              @focus="$event.target.select()"
          />
        </td>
        <td>
          <input
              v-model="item.subtotal"
              class="form-control input-xs amount-input"
              disabled=""
              placeholder="subtotal"
              type="text"
              @focus="$event.target.select()"
          />
        </td>

        <td>
          <input
              v-model="item.tax"
              class="form-control input-xs amount-input"
              disabled=""
              placeholder="tax"
              type="text"
              @focus="$event.target.select()"
          />
        </td>
        <td>
          <input
              v-model="item.net"
              :disabled="item.is_fixed_price"
              class="form-control input-xs amount-input"
              placeholder="net"
              type="text"
              @focus="$event.target.select()"
          />
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'ItemsForm',
  computed: {
    itemsOnly () {
      return this.$store.state.invoiceItems.filter(p => !p.is_expense)
    }
  },
  methods: {
    itemCost (item) {
      return parseFloat(item.cost * (1 + item.vtp / 100)).toFixed(2)
    }
  }
}
</script>

<style scoped>

</style>

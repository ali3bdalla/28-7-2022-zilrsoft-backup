<template>
  <div class="table">
    <div class="table-posistion">
      <div class="mb-3">
        <input autofocus="autofocus" class="form-control" type="text"/>
      </div>

      <div v-show="!isLoading" class="table-content" style="overflow: scroll;">
        <table class="table table-striped table-bordered" width="100%">
          <thead>
          <tr>
            <th
                :class="{ orderBy: orderBy == 'id' }"
                width="25px"
                @click="setOrderByColumn('id')"
            >
              {{ trans.id }}
            </th>
            <th
                :class="{ orderBy: orderBy == 'barcode' }"
                width="100px"
                @click="setOrderByColumn('barcode')"
            >
              العميل
            </th>


            <th
                :class="{ orderBy: orderBy == 'price' }"
                width="100px"
                @click="setOrderByColumn('price')"
            >
              قيمة الطلب
            </th>

            <th
                :class="{ orderBy: orderBy == 'price_with_tax' }"
                width="100px"
                @click="setOrderByColumn('price_with_tax')"
            >
              وسيلة الشحن
            </th>
            <th
                :class="{ orderBy: orderBy == 'available_qty' }"
                width="130px"
                @click="setOrderByColumn('available_qty')"
            >
              رقم التتبع
            </th>
            <th
                :class="{ orderBy: orderBy == 'available_qty' }"
                width="100px"
                @click="setOrderByColumn('available_qty')"
            >
              المسؤل
            </th>

            <th
                :class="{ orderBy: orderBy == 'available_qty' }"
                width="120px"
                @click="setOrderByColumn('available_qty')"
            >
              مندوب التوصيل
            </th>
            <th
                :class="{ orderBy: orderBy == 'creator_id' }"
                width="100px"
                @click="setOrderByColumn('creator_id')"
            >
              التاريخ
            </th>
            <th
                :class="{ orderBy: orderBy == 'created_at' }"
                width="70px"
                @click="setOrderByColumn('created_at')"
            >
              الحالة
            </th>

            <th width="100px" v-text="trans.options"></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="row in table_rows" :key="row.id">
            <td class="" v-text="row.id"></td>
            <td class="" v-text="row.user.locale_name"></td>
            <!-- <td class=""></td> -->
            <td class="">
              <display-money :money="row.net"/>
            </td>
            <td class="">
              {{ row.shipping_method ? row.shipping_method.locale_name : ""}}
            </td>

            <td class="" v-text="row.tracking_number"></td>
            <td class="" v-text="row.managed_by ? row.managed_by.locale_name : '' "></td>
            <td class="" v-text="row.delivery_man ? row.delivery_man.locale_name : ''"></td>
            <td class="" v-text="row.created_at"></td>
            <td class="">{{ storeTranslations.statuses[row.status] }}</td>
            <!-- v-text="row.status" -->
            <td class="">
              <div class="dropdown">
                <button
                    :id="'dropDownOptions' + row.id"
                    aria-expanded="false"
                    aria-haspopup="true"
                    class="btn btn-options dropdown-toggle"
                    data-toggle="dropdown"
                    type="button"
                >
                  {{ trans.options }}
                  <span class="caret"></span>
                </button>
                <ul
                    :aria-labelledby="'dropDownOptions' + row.id"
                    class="dropdown-menu CustomDropDownOptions"
                >
                  <li>
                    <a
                        :href="`/sales/${row.draft_id}`"

                    > فاتورة الطلب</a>
                  </li>
                  <li>
                    <a
                        :href="`/store/orders/${row.id}/view-shipping`"

                    > عنوان الشحن</a>
                  </li>
                  <li v-if="row.status === 'pending'">
                    <a
                        :href="`/store/orders/${row.id}/view-payment`"

                    > تاكيد الدفع</a>
                  </li>
                  <li v-if="row.status === 'in_progress'">
                    <a
                        :href="`/sales/drafts/${row.draft_id}/to_invoice`"

                    > تحويل الى فاتورة</a>
                  </li>
                  <li v-if="row.invoice_id">
                    <a
                        :href="`/sales/${row.invoice_id}`"

                    > الفاتورة النهائية</a>
                  </li>

                  <li v-if="row.status === 'issued'">
                    <a
                        :href="row.payment_url"

                    > رابط السداد</a>
                  </li>

                  <li v-if="row.status === 'ready_for_shipping'">
                    <a
                        :href="`/store/shipping/${row.shipping_method_id}/${row.id}/create-order-transaction`"

                    >انشاء البوليصة</a>
                  </li>

                </ul>
              </div>

            </td>

          </tr>
          </tbody>
        </table>
      </div>
      <div class="table-paginations">
        <accounting-table-pagination-helper-layout-component
            :data="paginationResponseData"
        ></accounting-table-pagination-helper-layout-component>
      </div>
    </div>
  </div>
</template>

<script>
import DisplayMoney from '../../../components/BackEnd/Money/DisplayMoney.vue'

export default {
  components: { DisplayMoney },
  props: [
    'categories',
    'canEdit',
    'canDelete',
    'canCreate',
    'canViewAccounting',
    'creators',
    'storeTranslations'
  ],
  data: function () {
    return {
      itemsPerPage: 20,
      orderBy: 'updated_at',
      orderType: 'desc',
      table_rows: [],
      isLoading: true,
      trans: trans('items-page'),
      paginationResponseData: null,
      tableSelectionActiveMode: false
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    fetch () {
      this.isLoading = true
      axios
          .get('/api/orders')
          .then((response) => {
            this.table_rows = response.data.data
            this.isLoading = false
            this.paginationResponseData = response.data
          })
          .finally(() => {
            this.isLoading = false
          })
    },

    setOrderByColumn (column_name) {
    }
  }
}
</script>
<style scoped>
.table-content {
  background: #f8f8f8;
  padding: 1px;
}

.table {
  border: 5px;
  table-layout: fixed;
  text-align: center !important;
}

.table thead th {
  text-align: center;
  cursor: pointer;
}

.table-filters {
  background: #f8f8f8;
  padding: 7px;
  margin-bottom: 7px;
}

.search-text {
  font-size: 19px;
  color: #999;
}

input[type="text"],
input[type="number"],
select {
  height: 42px;
}

.form-control,
.field-input {
  /*text-align: right !important;*/
}

.orderBy {
  background-color: cornsilk;
}

.sort-icon {
  color: #999;
  float: right;
  margin-right: 1px;
  font-size: 17px;
}

.dropdown-menu li a {
  padding: 7px;
  font-size: 14px;
  border-bottom: 1px solid #eee;
}

.vue-treeselect__control {
  padding: 7px !important;
  border-radius: 0px !important;
}

.table-multi-task-buttons {
  padding: 5px;
}
</style>

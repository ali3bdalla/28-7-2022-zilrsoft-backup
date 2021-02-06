<template>
  <div class="table">
    <div class="table-posistion">
      <div class="mb-3">
        <input autofocus="autofocus" class="form-control" type="text" />
      </div>

      <div v-show="!isLoading" class="table-content">
        <table class="table table-striped table-bordered" width="100%">
          <thead>
            <tr>
              <th
                :class="{ orderBy: orderBy == 'id' }"
                width="4%"
                @click="setOrderByColumn('id')"
              >
                {{ trans.id }}
              </th>
              <th
                :class="{ orderBy: orderBy == 'barcode' }"
                width="13%"
                @click="setOrderByColumn('barcode')"
              >
                {{ trans.client }}
              </th>
              <th
                :class="{ orderBy: orderBy == 'name' }"
                @click="setOrderByColumn('name')"
              >
                {{ trans.creator }}
              </th>

              <th
                :class="{ orderBy: orderBy == 'price' }"
                width="6%"
                @click="setOrderByColumn('price')"
              >
                {{ trans.net }}
              </th>

              <th
                :class="{ orderBy: orderBy == 'price_with_tax' }"
                width="10%"
                @click="setOrderByColumn('price_with_tax')"
              >
                {{ trans.shipping_amount }}
              </th>
              <th
                :class="{ orderBy: orderBy == 'available_qty' }"
                width="5%"
                @click="setOrderByColumn('available_qty')"
              >
                {{ trans.available_qty }}
              </th>

              <th
                :class="{ orderBy: orderBy == 'creator_id' }"
                width="13%"
                @click="setOrderByColumn('creator_id')"
              >
                {{ trans.created_by }}
              </th>
              <th
                :class="{ orderBy: orderBy == 'created_at' }"
                width="10%"
                @click="setOrderByColumn('created_at')"
              >
                {{ trans.created_at }}
              </th>

              <th width="8%" v-text="trans.options"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in table_rows" :key="row.id">
              <td class="" v-text="row.id"></td>
              <td class="" v-text="row.user.name"></td>
              <td class=""></td>
              <td class="">
                <display-money :money="row.net" />
              </td>
              <td class="">
                <display-money :money="row.shipping_amount" />
              </td>

              <td class=""></td>
              <td class="" v-text="row.tracking_number"></td>
              <td class="" v-text="row.status"></td>
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
                    <li v-if="row.is_kit">
                      <a
                        :href="'/items/up_selling/kits/' + row.id"
                        v-text="trans.show"
                      ></a>
                    </li>
                    <li v-if="canCreate == 1 && !row.is_kit">
                      <a
                        :href="baseUrl + row.id + '/clone'"
                        v-text="trans.clone"
                      ></a>
                    </li>
                    <li v-if="!row.is_kit">
                      <a
                        :href="`/store/orders/${row.id}/create-order-transaction`"

                      >ان شاء البوليصة</a>
                    </li>
                    <li v-if="canEdit == 1 && !row.is_kit">
                      <a
                        :href="baseUrl + row.id + '/edit'"
                        v-text="trans.edit"
                      ></a>
                    </li>
                    <li v-if="row.is_need_serial == 1 && !row.is_kit">
                      <a
                        :href="baseUrl + row.id + '/view_serials'"
                        v-text="trans.view_serials"
                      ></a>
                    </li>
                    <li v-if="row.status == 'pending' && canEdit == 1">
                      <a
                        :href="baseUrl + row.id + '/activate'"
                        v-text="trans.activate"
                      ></a>
                    </li>

                    <li v-if="canDelete == 1 && canViewAccounting == 1">
                      <a
                        @click="deleteItemClicked(row)"
                        v-text="trans.delete"
                      ></a>
                    </li>
                  </ul>
                </div>
                <!-- <a
                  :href="`/store/orders/${row.id}`"
                  class="data__table__dropdown__button"
                >
                  لوحة التحكم
                </a> -->
              </td>

              <!-- <td>

              {{ row.id }}
            </td>

            <td class="text-right-with-padding" v-text="row.user.locale_name"></td>
            <td v-text="row.created_at"></td> -->
              <!--                        <td>-->

              <!-- <div class="dropdown">
                                           <button :id="'dropDownOptions'
                                           + row.id" aria-expanded="false" aria-haspopup="true"
                                                   class="btn btn-options dropdown-toggle " data-toggle="dropdown"
                                                   type="button">
                                               {{ trans.options }}
                                               <span class="caret"></span>
                                            </button>
                                           <ul :aria-labelledby="'dropDownOptions'
                                           + row.id" class="dropdown-menu CustomDropDownOptions">
                                               <li v-if="row.is_kit"><a :href="'/items/up_selling/kits/' + row.id"
                                                                        v-text="trans.show"></a></li>
                                               <li v-if="canCreate==1  && !row.is_kit"><a :href="baseUrl + row.id + '/clone'"
                                                                                           v-text="trans.clone"></a></li>
                                                <li v-if="!row.is_kit"><a :href="baseUrl + row.id +
                                                '/transactions'"
                                                                          v-text="trans.transactions"></a>
                                                </li>
                                                <li v-if="canEdit==1  && !row.is_kit"><a :href="baseUrl + row.id + '/edit'"
                                                                                         v-text="trans.edit"></a></li>
                                                <li v-if="row.is_need_serial==1  && !row.is_kit"><a
                                                        :href="baseUrl + row.id + '/view_serials'"
                                                        v-text="trans.view_serials"></a></li>
                                                <li v-if="row.status=='pending' && canEdit==1"><a :href="baseUrl + row.id +
                                                '/activate'" v-text="trans.activate"></a></li>

                                                <li v-if="canDelete==1 && canViewAccounting==1"><a @click="deleteItemClicked(row)"
                                                                                                   v-text="trans.delete"></a></li>
                                            </ul>
                                        </div>

                                    </td> -->
            </tr>
          </tbody>
        </table>
      </div>

      <tile v-show="isLoading" :loading="isLoading"></tile>
      <div class="table-paginations">
        <accounting-table-pagination-helper-layout-component
          :data="paginationResponseData"
        ></accounting-table-pagination-helper-layout-component>
        <!--              @pagePerItemsUpdated="pagePerItemsUpdated"-->
        <!--              @paginateUpdatePage="paginateUpdatePage"-->
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
    'creators'
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

    setOrderByColumn (column_name) {}
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

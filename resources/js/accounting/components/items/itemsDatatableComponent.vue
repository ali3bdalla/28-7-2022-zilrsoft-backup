<template>
  <div class="table">
    <div class="table-posistion">
      <div>
        <input
          ref="barcodeAndNameUpdated"
          v-model="filters.barcodeNameAndSerial"
          :placeholder="trans.search_barcode_sale"
          autofocus="autofocus"
          class="form-control"
          type="text"
          @focus="$event.target.select()"
          @keyup.enter="barcodeAndNameUpdated"
        />
      </div>
      <div class="table-filters">
        <div
          class="text-right search-text"
          style="cursor: pointer;"
          @click="openOrCloseSearchPanel"
        >
          <i class="fa fa-search-plus"></i>
          {{ trans.search_by_filters }}
        </div>

        <div v-show="isOpenSearchPanel">
          <div class="row">
            <div class="col-md-3">
              <VueCtkDateTimePicker
                v-model="date_range"
                :behaviour="{ time: { nearestIfDisabled: true } }"
                :custom-shortcuts="customDateShortcuts"
                :label="trans.created_at"
                :only-date="true"
                :range="true"
                locale="en"
              />
            </div>
            <div class="col-md-3">
              <input
                ref="barcodeFieldRef"
                v-model="filters.barcode"
                :placeholder="trans.barcode"
                class="form-control"
                type="text"
                @keyup.enter="pushServerRequest('barcodeFieldRef')"
              />
            </div>
            <div class="col-md-3">
              <input
                v-model="filters.price"
                :placeholder="trans.price_placeholder"
                class="form-control"
                type="text"
                @keyup="pushServerRequest"
              />
            </div>
            <div class="col-md-3">
              <select
                v-model="filters.current_status"
                class="form-control"
                @change="pushServerRequest"
              >
                <option value="all">{{ trans.status }}</option>
                <option value="active">{{ trans.active }}</option>
                <option value="pending">{{ trans.pending }}</option>
              </select>
            </div>
            <div class="col-md-3">
              <input
                v-model="filters.price_with_tax"
                :placeholder="trans.price_tax_placeholder"
                class="form-control"
                type="text"
                @keyup="pushServerRequest"
              />
            </div>
            <div class="col-md-3">
              <input
                v-model="filters.available_qty"
                :placeholder="trans.qty"
                class="form-control"
                type="text"
                @keyup="pushServerRequest"
              />
            </div>
            <div class="col-md-3">
              <accounting-multi-select-with-search-layout-component
                :options="creators"
                :placeholder="trans.creator"
                :title="trans.creator"
                default="0"
                identity="000000001"
                label_text="locale_name"
                @valueUpdated="creatorListUpdated"
              >
              </accounting-multi-select-with-search-layout-component>
            </div>
            <div class="col-md-3">
              <input
                v-model="filters.name"
                :placeholder="trans.name"
                class="form-control"
                type="text"
                @keyup="pushServerRequest"
              />
            </div>
          </div>

          <div class="table-advanced-search">
            <accounting-table-filter-search-component
              :categories="categories"
              :trans="trans"
              @filterValuesUpdated="advancedSearchUpdated"
              @selectedAttributesHasBeenUpdated="
                selectedAttributesHasBeenUpdated
              "
            ></accounting-table-filter-search-component>
          </div>
        </div>
      </div>
      <div v-show="showMultiTaskButtons" class="table-multi-task-buttons">
        <button class="btn btn-default" @click="activateListItems">
          {{ trans.activate }}
        </button>
      </div>
      <div v-show="!isLoading" class="table-content ">
        <table class="table table-striped table-bordered" width="100%">
          <thead>
            <tr>
              <th width="2%">
                <input
                  type="checkbox"
                  @click="checkAndUncheckAllRowsCheckBoxChanged"
                />
              </th>
              <th
                :class="{ orderBy: orderBy == 'is_published' }"
                width="4%"
                @click="setOrderByColumn('is_published')"
              >
                {{ trans.id }}
              </th>
              <th
                :class="{ orderBy: orderBy == 'barcode' }"
                width="13%"
                @click="setOrderByColumn('barcode')"
              >
                {{ trans.barcode }}
              </th>
              <th
                :class="{ orderBy: orderBy == 'name' }"
                @click="setOrderByColumn('name')"
              >
                {{ trans.name }}
              </th>
              <th
                :class="{ orderBy: orderBy == 'cost' }"
                width="10%"
                @click="setOrderByColumn('cost')"
              >
                {{ trans.cost }}
              </th>
              <th
                :class="{ orderBy: orderBy == 'price' }"
                width="6%"
                @click="setOrderByColumn('price_tax')"
              >
                {{ trans.price }}
              </th>

              <th
                :class="{ orderBy: orderBy == 'online_offer_price' }"
                width="10%"
                @click="setOrderByColumn('online_offer_price')"
              >
                {{ trans.online_offer_price }}
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
            <tr
              v-for="(row, index) in table_rows"
              :key="row.id"
              :class="{
                'row-has-error':
                  row.is_published &&
                  (getItemCost(row) > row.price_with_tax ||
                    getItemCost(row) > row.online_offer_price),
              }"
            >
              <td>
                <input
                  v-model="row.tb_row_selected"
                  type="checkbox"
                  @change="rowSelectCheckBoxUpdated(row)"
                />
              </td>
              <td>
                <i
                  v-if="!row.is_published"
                  class="fa fa-circle"
                  style="color:#e74c3c"
                ></i>
                <i v-else class="fa fa-circle" style="color:#2ecc71"></i>
              </td>
              <td
                style="text-align:left;cursor: pointer"
                @click="sendItemToOpenInvoice(row)"
              >
                &nbsp;<span
                  v-if="row.is_need_serial"
                  :style="{ color: primaryColor }"
                  >{{ row.barcode }}
                </span>

                <span v-else-if="row.is_kit" style="color:green">{{
                  row.barcode
                }}</span>
                <span v-else>{{ row.barcode }}</span> &nbsp;
                <i
                  v-show="row.status == 'active'"
                  :style="{ color: primaryColor }"
                  class="fa fa-check-circle pull-left"
                  style="margin-top: 3px;"
                ></i>
              </td>

              <td class="text-right-with-padding">
                {{ row.ar_name }}
                <p align="left">{{ row.name }}</p>
              </td>
              <td v-if="!row.is_kit" v-text="getItemCost(row)"></td>
              <td
                v-if="!row.is_kit"
                v-text="parseFloat(row.price_with_tax).toFixed(2)"
              ></td>
              <td v-else></td>
              <!--                        <td v-if="!row.is_kit" v-text="parseFloat(row.price_with_tax).toFixed(2)"></td>-->
              <td
                v-if="!row.is_kit"
                v-text="parseFloat(row.online_offer_price).toFixed(2)"
              ></td>
              <td v-else></td>
              <!--                        v-text="parseFloat(row.data.total).toFixed(2)"-->
              <td>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <span v-if="row.is_service" v-on="on">0</span>
                    <span v-else v-on="on">{{ row.available_qty }}</span>
                  </template>
                  <span>{{ parseFloat(row.cost).toFixed(2) }}</span>
                </v-tooltip>

                <!--                            <v-tooltip bottom>-->
                <!--                                <template v-slot:activator="{ on }">-->
                <!--                                </template>-->
                <!--                                <span>{{ row.available_qty }}</span>-->
                <!--                            </v-tooltip>-->
              </td>
              <td
                class="text-right-with-padding"
                v-text="row.creator.locale_name"
              ></td>
              <td v-text="row.created_at"></td>
              <td>
                <div class="dropdown">
                  <button
                    :id="'dropDownOptions' + row.id"
                    aria-expanded="false"
                    aria-haspopup="true"
                    class="btn btn-options dropdown-toggle "
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
                      <a :href="'/accounting/kits/' + row.id"
                        >?????? ???????????? ??????????</a
                      >
                    </li>
                    <li v-if="canCreate == 1 && !row.is_kit">
                      <a
                        :href="baseUrl + row.id + '/clone'"
                        v-text="trans.clone"
                      ></a>
                    </li>
                    <li v-if="!row.is_kit">
                      <a
                        :href="baseUrl + row.id + '/transactions'"
                        v-text="trans.transactions"
                      ></a>
                    </li>
                    <li v-if="canEdit == 1">
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
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="table-paginations">
        <accounting-table-pagination-helper-layout-component
          :data="paginationResponseData"
          @pagePerItemsUpdated="pagePerItemsUpdated"
          @paginateUpdatePage="paginateUpdatePage"
        ></accounting-table-pagination-helper-layout-component>
      </div>
    </div>
  </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import { query as ItemQuery, transfer } from "../../item";

import "vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css";
import VueCtkDateTimePicker from "vue-ctk-date-time-picker";

export default {
  components: {
    VueCtkDateTimePicker,
    Treeselect,
  },
  props: [
    "categories",
    "canEdit",
    "canDelete",
    "canCreate",
    "canViewAccounting",
    "creators",
  ],
  data: function() {
    return {
      itemsPerPage: 20,
      isOpenSearchPanel: false,
      category: null,
      baseUrl: "/items/",
      orderBy: "updated_at",
      orderType: "desc",
      yourValue: null,
      table_rows: [],

      isLoading: true,
      primaryColor: metaHelper.getContent("primary-color"),
      secondColor: metaHelper.getContent("second-color"),
      appLocate: metaHelper.getContent("app-locate"),
      trans: trans("items-page"),
      messages: trans("messages"),
      table_trans: trans("table"),
      datetimetrans: trans("datetime"),
      customDateShortcuts: [],
      date_range: null,
      showMultiTaskButtons: false,
      requestUrl: "",
      filters: {
        endDate: null,
        startDate: null,
        barcodeNameAndSerial: "",
        barcode: null,
        price: null,
        price_with_tax: null,
        available_qty: null,
        name: null,
        current_status: "all",
        categoryIds: [],
        filters: [],
        creators: [],
      },

      paginationResponseData: null,
      tableSelectionActiveMode: false,
    };
  },
  created() {
    this.initUi();
    this.pushServerRequest();
  },
  methods: {
    getItemCost(row) {
      return parseFloat(row.cost * 1.15).toFixed(2);
    },

    selectedAttributesHasBeenUpdated(event) {
      this.isLoading = true;
      const appVm = this;

      axios
        .post(getRequestUrl("items"), {
          page: 1,
          category_id: appVm.filters.categoryIds[0],
          attributes: event.selectedValues,
        })
        .then(function(response) {
          appVm.table_rows = response.data.data;
          appVm.paginationResponseData = response.data;
        })
        .catch(function(error) {
          alert(`server error : ${error}`);
        })
        .finally(function() {
          appVm.isLoading = false;
        });
    },

    barcodeAndNameUpdated(event) {
      event.target.select();
      this.pushServerRequest();
      this.$refs.barcodeAndNameUpdated.select();
    },

    initUi() {
      this.requestUrl = "/api/items";
      this.customDateShortcuts = [
        {
          key: "thisWeek",
          label: this.datetimetrans.thisWeek,
          value: "isoWeek",
        },
        {
          key: "lastWeek",
          label: this.datetimetrans.lastWeek,
          value: "-isoWeek",
        },
        {
          key: "last7Days",
          label: this.datetimetrans.last7Days,
          value: 7,
        },
        {
          key: "last30Days",
          label: this.datetimetrans.last30Days,
          value: 30,
        },
        {
          key: "thisMonth",
          label: this.datetimetrans.thisMonth,
          value: "month",
        },
        {
          key: "lastMonth",
          label: this.datetimetrans.lastMonth,
          value: "-month",
        },
        {
          key: "thisYear",
          label: this.datetimetrans.thisYear,
          value: "year",
        },
        {
          key: "lastYear",
          label: this.datetimetrans.lastYear,
          value: "-year",
        },
      ];
    },
    pushServerRequest: function(ref = null) {
      this.isLoading = true;
      const appVm = this;
      const params = appVm.filters;
      params.orderBy = this.orderBy;
      params.itemsPerPage = this.itemsPerPage;
      params.orderType = this.orderType;

      axios
        .get(this.requestUrl, {
          params: params,
        })
        .then(function(response) {
          appVm.table_rows = response.data.data;
          appVm.isLoading = false;
          appVm.paginationResponseData = response.data;
        })
        .catch(function(error) {
          alert(error);
        })
        .finally(function() {
          appVm.isLoading = false;
        });

      if (ref !== null) {
        this.$refs[ref].focus();
        this.$refs[ref].select();

        // this.$refs[ref][0].focus();
      }
    },

    setOrderByColumn(column_name) {
      if (this.orderBy == column_name) {
        // alert('hello')
        if (this.orderType == "asc") {
          this.orderType = "desc";
        } else {
          this.orderType = "asc";
        }
      } else {
        this.orderBy = column_name;
        this.orderType = "asc";
      }
      this.pushServerRequest();
    },

    sendItemToOpenInvoice(item) {
      transfer.pushToOpenInvoice(item);
      this.$toast.success({
        type: "success",
        showMethod: "lightSpeedIn",
        closeButton: false,
        timeOut: 2000,
        icon: "",
        title: this.messages.process_title,
        message: this.messages.process_done,
        progressBar: true,
        hideDuration: 1000,
      });
    },
    paginateUpdatePage(event) {
      this.requestUrl = event.link;
      this.pushServerRequest();
    },

    pagePerItemsUpdated(event) {
      this.itemsPerPage = event.items;
      this.pushServerRequest();
    },
    categoryUpdated(e) {
      this.filters.category_id = e.id;
      this.pushServerRequest();
    },

    creatorListUpdated(e) {
      this.filters.creators = db.model.pluck(e.items, "id");
      this.pushServerRequest();
    },

    openOrCloseSearchPanel() {
      this.isOpenSearchPanel = !this.isOpenSearchPanel;
    },

    advancedSearchUpdated(event) {
      this.filters.categoryIds = event.categoryIds;
      this.filters.filters = event.searchFilters;
      if (event.categoryIds === []) {
        this.filters.filters = [];
      }

      this.pushServerRequest();
    },
    checkAndUncheckAllRowsCheckBoxChanged() {
      const items = this.table_rows;
      const len = items.length;

      const new_items = [];
      for (let index = 0; index < len; index++) {
        const item = items[index];
        if (this.tableSelectionActiveMode) {
          item.tb_row_selected = false;
        } else {
          item.tb_row_selected = true;
        }
        new_items.push(item);
      }

      if (this.tableSelectionActiveMode) {
        this.showMultiTaskButtons = false;
      } else {
        this.showMultiTaskButtons = true;
      }

      this.table_rows = new_items;
      this.tableSelectionActiveMode = !this.tableSelectionActiveMode;
    },
    rowSelectCheckBoxUpdated(item) {
      this.showOrHideMultiTaskButtons();
    },
    showOrHideMultiTaskButtons() {
      const items = this.table_rows;
      const len = items.length;

      let showButtons = false;
      for (let index = 0; index < len; index++) {
        const item = items[index];
        if (item.tb_row_selected) {
          showButtons = true;
        }
      }

      this.showMultiTaskButtons = showButtons;
    },

    exportsPdf() {},
    getRowColumnIndex(index) {
      if (this.paginationResponseData != null) {
        return (
          index +
          (this.paginationResponseData.current_page - 1) *
            this.paginationResponseData.per_page
        );
      }
      return index;
    },
    activateListItems() {
      const appVm = this;
      const ids = db.model.pluck(
        this.table_rows,
        "id",
        "tb_row_selected",
        true
      );
      if (ids !== []) {
        ItemQuery.sendQueryRequestToActivateItems(ids)
          .then((response) => {
            appVm.pushServerRequest();
          })
          .catch((error) => {
            alert(error);
          });
      }
    },

    deleteItemClicked(itemData) {
      const options = {
        html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
        loader: false, // set to true if you want the dailog to show a loader after click on "proceed"
        reverse: false, // switch the button positions (left to right, and vise versa)
        okText: this.messages.ok_button_txt,
        cancelText: this.messages.close_pop_txt,
        animation: "zoom", // Available: "zoom", "bounce", "fade"
        type: "hard", // coming soon: 'soft', 'hard'
        verification: "delete",
        // for hard confirm, user will be prompted to type this to enable the proceed button
        verificationHelp: '???????? "[+:verification]" ???????????? ?????????? ?????????? ',
        // Verification help text. [+:verification] will be matched with 'options.verification' (i.e 'Type "continue" below to confirm')
        clicksCount: 3, // for soft confirm, user will be asked to click on "proceed" btn 3 times before actually proceeding
        backdropClose: false, // set to true to close the dialog when clicking outside of the dialog window, i.e. click landing on the mask
        customClass: "danger",
        // Custom class to be injected into the parent node for the current dialog instance
      };

      const appVm = this;

      this.$dialog
        .confirm(this.messages.confirm_msg, options)
        .then((dialog) => {
          axios
            .delete(appVm.baseUrl + itemData.id)
            .then(function(response) {
              window.location.reload();
            })
            .catch(function(error) {});
        })
        .catch(() => {});
    },
  },

  watch: {
    date_range: function(value) {
      if (value == null) {
        this.filters.startDate = null;
        this.filters.endDate = null;
      } else {
        this.filters.startDate = value.start;
        this.filters.endDate = value.end;
      }
      this.pushServerRequest();
    },
  },
};
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

.row-has-error {
  color: #f14668 !important;
}
</style>

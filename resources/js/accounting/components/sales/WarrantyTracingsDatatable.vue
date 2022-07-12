<template>
  <div class="table">
    <div class="table-posistion">
      <div>
        <input
            v-model="searchText"
            autofocus="autofocus"
            class="form-control"
            placeholder=" رقم الفاتورة"
            type="text"
            @change="pushServerRequest"
        />
      </div>

      <div v-show="!isLoading" class="table-content">
        <table
            :class="{ 'table-striped': !onlyQuotations }"
            class="table  table-bordered"
            width="100%"
        >
          <thead>
          <tr>
            <th width="4%" @click="setOrderByColumn('id')">
              {{ app.trans.id }}
            </th>

            <th
                :class="{ orderBy: orderBy === 'invoice_number' }"
                @click="setOrderByColumn('invoice_number')"
            >
              {{ app.trans.invoice_number }}
            </th>
            <th>
              {{ app.trans.client }}
            </th>
            <th>
              الحالة
            </th>

            <th
                :class="{ orderBy: orderBy === 'created_at' }"
                width=""
                @click="setOrderByColumn('created_at')"
            >
              {{ app.trans.created_at }}
            </th>


            <th width="">
              {{ app.trans.salesman }}
            </th>


            <th width="8%" v-text="app.trans.options"></th>
          </tr>
          </thead>
          <tbody>
          <tr
              v-for="(row, index) in table_rows"
              :key="row.id"
          >
            <td v-text="index + 1"></td>
            <td class="text-center" v-text="row.invoice_number"></td>
            <td
                class="text-center"
                v-text="
                  row.sale == null ||
                  row.user_alice_name == null ||
                  row.user_alice_name === ''
                    ? row.user
                      ? row.user.locale_name
                      : ''
                    : ''
                "
            ></td>
            <td>
              {{ row.status_label}}
            </td>
            <td v-text="row.created_at"></td>


            <td
                class="text-center"
                v-text="row.creator ? row.creator.locale_name : ''"
            ></td>
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
                  {{ app.trans.options }}
                  <span class="caret"></span>
                </button>
                <ul
                    :aria-labelledby="'dropDownOptions' + row.id"
                    class="dropdown-menu CustomDropDownOptions"
                >
                  <li>
                    <a :href="baseUrl + row.id" v-text="app.trans.view"></a>
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
import VueCtkDateTimePicker from "vue-ctk-date-time-picker";
import {math as ItemMath} from "../../item";

import "vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css";

export default {
  components: {
    VueCtkDateTimePicker,
    Treeselect,
  },
  props: [
    "onlyQuotations",
    "creator",
    "canViewAccounting",
    "canEdit",
    "canDelete",
    "canCreate",
    "creators",
    "clients",
    "departments",
  ],
  data: function () {
    return {
      itemsPerPage: 20,
      baseUrl: "",
      orderBy: "created_at",
      orderType: "desc",
      table_rows: [],
      isLoading: true,
      app: {
        trans: trans("invoices-page"),
      },
      requestUrl: "",
      searchText: "",
      paginationResponseData: null,
    };
  },
  created() {
    this.initUi();
    this.pushServerRequest();
  },
  mounted: function () {
    const appVm = this;
    if (this.creator.id === 7) {
      setInterval(function () {
        appVm.pushServerRequest();
      }, 40000);
    }
  },
  methods: {
    initUi() {
      this.requestUrl = "/api/sales/warranty_tracing/list";
      this.baseUrl = "/warranty_tracings/";
    },
    pushServerRequest: function () {
      this.isLoading = true;
      const appVm = this;
      const params = {};
      params.orderBy = this.orderBy;
      params.itemsPerPage = this.itemsPerPage;
      params.orderType = this.orderType;
      params.invoice_number = this.searchText;
      if (this.onlyQuotations === 1 || this.onlyQuotations === true) {
        params.is_draft = true;
      }
      axios
          .get(this.requestUrl, {
            params: params,
          })
          .then(function (response) {
            appVm.table_rows = response.data.data;
            appVm.isLoading = false;
            appVm.paginationResponseData = response.data;
          })
          .catch(function (error) {
            alert(error);
          })
          .finally(function () {
            appVm.isLoading = false;
          });
    },
    setOrderByColumn(column_name) {
      if (this.orderBy === column_name) {
        if (this.orderType === "asc") {
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

    paginateUpdatePage(event) {
      this.requestUrl = event.link;
      this.pushServerRequest();
    },

    pagePerItemsUpdated(event) {
      this.itemsPerPage = event.items;
      this.pushServerRequest();
    },



  },

};
</script>

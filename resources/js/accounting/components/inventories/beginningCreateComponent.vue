<template>
  <div class="">


    <div class="row">
      <div class="col-md-6">
        <button :disabled="disabledButton" class="btn btn-custom-primary" @click="pushDataToServer"><i
            class="fa fa-save"></i> {{ app.trans.save }}
        </button>
      </div>
      <div class="col-md-6">
        <a :href="'/inventory'" class="btn btn-default "><i
            class="fa fa-redo"></i> {{ app.trans.cancel }}</a>
      </div>

    </div>


    <div class="row">
      <div class="col-md-6">
        <!-- <label>client name</label> -->
        <div class="input-group">
          <span id="vendors-list" class="input-group-addon">{{ app.trans.vendor }}</span>
          <input v-model="user.locale_name" aria-describedby="time-field" class="form-control " disabled name=""
                 readonly type="text">

        </div>
      </div>
      <div class="col-md-6">
        <!-- <label>date</label> -->
        <div class="input-group">
          <span id="time-field" class="input-group-addon">{{ app.trans.date }}</span>
          <input v-model="time" aria-describedby="time-field" class="form-control" disabled name="" readonly
                 style=" direction: ltr
                            !important;" type="text">

        </div>


      </div>
    </div>


    <div class="row">
      <div class="col-md-6">
        <!-- <label>client name</label> -->
        <div class="">
          <div class="input-group">
            <span id="receivers-list" class="input-group-addon">{{ app.trans.receiver }}</span>
            <input v-model="creator.locale_name" aria-describedby="time-field" class="form-control" disabled
                   name="" type="text">

          </div>

        </div>
      </div>

      <div class="col-md-6">
        <div class="input-group">
          <span id="department-field" class="input-group-addon">{{ app.trans.department }}</span>
          <input v-model="creator.department.locale_title" aria-describedby="department-field" class="form-control"
                 disabled
                 name=""
                 readonly
                 type="text">

        </div>
      </div>

    </div>

    <!-- start search field -->
    <div class="row">
      <div class="col-md-8">
        <div id="seach_area" class="product_search">
          <div class="">
            <input ref="barcodeNameAndSerialField"
                   v-model="barcodeNameAndSerialField"
                   :placeholder="app.trans.search_barcode" class="form-control"
                   type="text"
                   @keyup.enter="sendQueryRequestToFindItems"/>
          </div>
          <div class="live-vue-search panel">
            <div v-for="item in searchResultList" :key="item.id"
                 class="panel-footer" href="#" @click="validateAndPrepareItem(item)">
              <h4 class="title has-text-white">{{ item.locale_name }}
                <small class="has-text-white">{{ item.barcode }} - {{ item.price }}</small>
              </h4>

            </div>
          </div>
        </div>
      </div>


      <div v-if="canViewItems==1" class="col-md-2">
        <a :href="app.BaseApiUrl +
                    'items?selectable=true&&is_purchase=true'" class="btn btn-custom-primary btn-lg"
           target="_blank">{{ app.trans.view_products }}</a>

      </div>
      <div v-if="canCreateItem==1" class="col-md-2">
        <a :href="app.BaseApiUrl + 'items/create'" class="btn btn-custom-primary btn-lg"
           target="_blank">{{ app.trans.create_product }}</a>
      </div>


    </div>


    <div class="panel">
      <table class="table table-bordered text-center  table-striped">
        <thead class="panel-heading">
        <tr>
          <th class="">#</th>
          <th class="">{{ app.trans.barcode }}</th>
          <th class="" width="20%">{{ app.trans.item_name }}</th>
          <th class="" width="3%">{{ app.trans.qty }}</th>
          <th class="">{{ app.trans.price }}</th>
          <th class="">{{ app.trans.total }}</th>
        </tr>
        </thead>

        <tbody class="panel-body">
        <tr v-for="(item,index) in invoiceData.items" :key="item.id">
          <th class="has-text-white">
            <button class="btn btn-danger btn-sm"
                    @click="deleteItemFromList(item)"><i
                class="fa fa-trash"></i></button>

            <a v-if="item.is_need_serial" class="btn btn-success btn-sm"
               @click="openItemSerialsModal(index,item)">السيريال &nbsp;
            </a>

          </th>
          <th>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <span v-on="on">{{ item.barcode }}</span>

              </template>
              <span>{{ parseFloat(item.cost).toFixed(2) }}</span>
            </v-tooltip>
          </th>
          <th v-text="item.locale_name"></th>
          <th width="8%">
            <input
                v-if="!item.is_need_serial"
                :ref="'itemQty_' + item.id + 'Ref'"
                v-model="item.qty"
                :placeholder="app.trans.qty"
                class="form-control"
                type="text"
                @focus="$event.target.select()"
                @change="itemQtyUpdated(item)"

            >
            <p v-else>{{ item.qty }}</p>
          </th>
          <th class="has-text-white">
            <input
                :ref="'itemPrice_' + item.id + 'Ref'"
                v-model="item.purchase_price"
                class="form-control"
                type="text"
                @change="itemPriceUpdated(item)"
                @focus="$event.target.select()">

          </th>
          <th class="has-text-white">
            <input v-model="item.total"
                   class="form-control"
                   disabled
                   type="text" @focus="$event.target.select()">
          </th>

        </tr>


        </tbody>
      </table>

    </div>

    <div class="form-group">
      <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4">
          <div class="panel">
            <div class="panel-heading">
              {{ app.trans.invoice_data }}
            </div>
            <div class="panel-body text-center">
              <div class="row">
                <div class="col-md-6"><label>{{ app.trans.total }}</label></div>
                <div class="col-md-6">
                  <input v-model="invoiceData.total"
                         :placeholder="app.trans.total"
                         class="form-control  input-xs amount-input" disabled
                         type="text">
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>


    <accounting-invoice-item-serials-list-layout-component
        :item="selectedItem"
        :item-index="selectedItemIndex"
        invoice-type="purchase"
        @panelClosed="handleItemSerialsClosed"
        @publishUpdated="handleItemSerialsUpdated"
    >

    </accounting-invoice-item-serials-list-layout-component>


  </div>


</template>

<script>


import {query as ItemQuery} from '../../item';
// import 'bulma/css/bulma.css'

export default {
  props: [
    'creator',
    'user',
    'canViewItems',
    'canCreateItem',
  ],
  data: function () {
    return {
      disabledButton: false,
      selectedItemIndex: null,
      selectedItem: null,
      codeTest: "",
      invoiceData: {
        items: [],
        total: 0,
        net: 0,
        tax: 0,
        discount: 0,
        subtotal: 0,

      },
      searchResultList: [],
      barcodeNameAndSerialField: "",
      bc: new BroadcastChannel('item_barcode_copy_to_invoice'),
      app: {
        primaryColor: metaHelper.getContent('primary-color'),
        secondColor: metaHelper.getContent('second-color'),
        appLocate: metaHelper.getContent('app-locate'),
        trans: trans('invoices-page'),
        messages: trans('messages'),
        dateTimeTrans: trans('datetime'),
        validation: trans('validation'),
        datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
        BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
        defaultVatSaleValue: 5,
        defaultVatPurchaseValue: 5,
      },
      time: new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate() + ' ' + new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds()
    };
  },
  created: function () {
    this.showActiveDateTime();

  },
  mounted: function () {
    this.itemsTabsPusherHandler();
    this.$refs.barcodeNameAndSerialField.focus();
  },

  methods: {
    itemsTabsPusherHandler() {
      let appVm = this;
      this.bc.onmessage = function (ev) {
        if (ev.isTrusted) {
          var item = JSON.parse(ev.data);
          appVm.validateAndPrepareItem(item);
        }
      }
    },
    sendQueryRequestToFindItems() {
      var appVm = this;
      ItemQuery.sendQueryRequestToFindItems(this.barcodeNameAndSerialField).then(response => {
        if (response.data.length === 1) {
          appVm.validateAndPrepareItem(response.data[0]);
          appVm.barcodeNameAndSerialField = "";
          appVm.searchResultList = [];
        } else if (response.data.length === 0) {
          appVm.$refs.barcodeNameAndSerialField.select();
          appVm.searchResultList = [];


        } else {
          appVm.searchResultList = response.data;
        }

      }).catch(error => {
      })
    },
    validateAndPrepareItem(item) {
      if (db.model.contain(this.invoiceData.items, item.id)) {
        let parent = db.model.find(this.invoiceData.items, item.id);
        if (!parent.is_need_serial) {
          parent.qty = parseInt(parent.qty) + 1;
          this.itemQtyUpdated(parent);

        }
        this.clearAndFocusOnBarcodeField();
      } else {
        var preparedItem = this.prepareDataInFirstUse(item);
        var index = this.appendItemToInvoiceItemsList(preparedItem);
        // this.invoiceData.items.reverse();
        this.clearAndFocusOnBarcodeField();
        this.focusOnQtyField(index);


      }


    },


    focusOnQtyField: function (index) {
      var item = db.model.findByIndex(this.invoiceData.items, index);

      let ref = 'itemQty_' + item.id + 'Ref';
    },
    prepareDataInFirstUse(item,) {
      item.isOpen = false;
      item.qty = 1;
      if (item.is_need_serial) {
        item.qty = 0;
        item.serials = [];
      }
      item.purchase_price = item.last_p_price;
      item.total = item.qty * item.purchase_price;
      item.discount = 0;
      item.subtotal = item.total;
      item.tax = 0;
      item.net = item.total;
      return item;

    },
    appendItemToInvoiceItemsList(item, index = null) {
      if (index != null) {
        this.invoiceData.items.splice(index, 1, item);
      } else {
        this.invoiceData.items.push(item);
      }

      this.updateInvoiceData();
      return db.model.index(this.invoiceData.items, item.id);
    },
    updateInvoiceData() {
      this.disabledButton = false;
      this.invoiceData.total = db.model.sum(this.invoiceData.items, 'total');
      this.invoiceData.discount = db.model.sum(this.invoiceData.items, 'discount');
      this.invoiceData.subtotal = db.model.sum(this.invoiceData.items, 'subtotal');
      this.invoiceData.tax = db.model.sum(this.invoiceData.items, 'tax');
      this.invoiceData.net = db.model.sum(this.invoiceData.items, 'net');
    },
    clearAndFocusOnBarcodeField() {
      this.barcodeNameAndSerialField = "";
      this.searchResultList = [];
      this.$refs.barcodeNameAndSerialField.focus();
    },
    itemQtyUpdated(item, bySerial = false) {
      if (bySerial == false) {
        var el = this.$refs['itemQty_' + item.id + 'Ref'][0];
        if (!inputHelper.validateQty(item.qty, el)) {
          return false;
        }
      }

      item = this.itemUpdater(item);
      this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
    },
    itemUpdater(item) {
      item.total = item.purchase_price * item.qty;
      item.subtotal = item.total;
      item.net = item.total;
      item.tax = 0;

      return item;
    },

    itemPriceUpdated(item) {
      var el = this.$refs['itemPrice_' + item.id + 'Ref'][0];
      if (!inputHelper.validatePrice(item.purchase_price, el)) {
        return false;
      }
      item = this.itemUpdater(item);
      this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
    },


    showActiveDateTime() {
      let vm = this;
      setInterval(function () {

        vm.time = helpers.getFullDateAndTime();
      }, 1000);
    },


    deleteItemFromList(item) {
      this.invoiceData.items = db.model.delete(this.invoiceData.items, item.id);
      this.updateInvoiceData();
    },

    openItemSerialsModal(index, item) {
      this.selectedItem = item;
      this.selectedItemIndex = index;
    },

    handleItemSerialsUpdated(e) {
      var index = e.index;
      var item = db.model.findByIndex(this.invoiceData.items, index);
      item.serials = e.serials;
      item.qty = e.serials.length;
      this.itemQtyUpdated(item, true);
    },
    handleItemSerialsClosed(e) {
      this.selectedItem = null;
      this.selectedItemIndex = null;
    },


    pushDataToServer() {
      this.disabledButton = true;
      var data = {
        items: this.invoiceData.items,
        total: this.invoiceData.total,
        tax: 0,
        discount: 0,
        discount_percent: 0,
        net: this.invoiceData.total,
        subtotal: this.invoiceData.total,
      };
      let appVm = this;
      axios.post('/api/inventory/beginning', data)
          .then(function (response) {

            window.location.reload();
          })
          .catch(function (error) {
            alert(error)
          });

    },

  },


}
</script>


<style scoped>
input {
  text-align: center
}

.product_search {
  position: relative;;
}

.live-vue-search {
  position: absolute;
  width: 100%;
  /* bottom: -46px; */
  z-index: 10000;
}

.live-vue-search a {
  text-decoration: none !important;
}

.live-vue-search div {
  background-color: black;
  border-radius: 0px;
  border-bottom: 1px solid #eeeeee;
  color: #eee;
  cursor: pointer;
}

live-vue-search div:hover {

  border-radius: 0px;

  border-bottom: 1px solid #eeeeee;
  cursor: pointer;
}


</style>



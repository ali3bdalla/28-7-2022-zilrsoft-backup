<template>
  <!-- startup box -->
  <div class="">


    <div class="row">
      <div class="col-md-6">
        <button :disabled="!everythingFineToSave" class="btn btn-custom-primary" @click="pushDataToServer"><i
            class="fa fa-save"></i> {{ app.trans.save }}
        </button>

      </div>
      <div class="col-md-6">
        <a class="btn btn-default " href="/purchases"><i
            class="fa fa-redo"></i> {{ app.trans.cancel }}</a>
      </div>

    </div>


    <div class="row">
      <div class="col-md-4">
        <accounting-select-with-search-layout-component
            :default="invoiceData.vendorId"
            :default-index="invoiceData.vendorId"
            :no_all_option="true"
            :options="vendorsList"
            :placeholder="app.trans.vendor"
            :title="app.trans.vendor"
            identity="001"
            index="001"
            label_text="locale_name"
            @valueUpdated="vendorListChanged"
        >

        </accounting-select-with-search-layout-component>

      </div>
      <div class="col-md-2">
        <a class="btn btn-custom-primary btn-lg btn-block" @click="modalsInfo.showCreateVendorModal=true">{{
            app.trans
                .create_identity
          }}</a>

      </div>
      <div class="col-md-3">
        <div class="input-group">
          <span class="input-group-addon">{{ app.trans.vendor_invoice_id }}</span>
          <input v-model="invoiceData.vendorIncCumber" aria-describedby="time-field"
                 class="form-control" type="text">

        </div>
      </div>
      <div class="col-md-3">
        <div class="input-group">
          <span class="input-group-addon">صورة الفاتورة</span>
<!--          <input v-model="invoiceData.vendorIncCumber" aria-describedby="time-field"-->
          <!--                 class="form-control" type="text">-->

          <select class="form-control h-25" v-model="purchaseDropboxSnapshot">
            <option value="">----</option>
            <option :value="pendingPurchase" v-for="(pendingPurchase,index) in pendingDropboxPurchases" :key="index">{{pendingPurchase}}</option>
          </select>


        </div>

<!--        <accounting-select-with-search-layout-component-->
<!--            :default="invoiceData.vendorId"-->
<!--            :default-index="invoiceData.vendorId"-->
<!--            :no_all_option="true"-->
<!--            :options="vendorsList"-->
<!--            :placeholder="app.trans.vendor"-->
<!--            :title="app.trans.vendor"-->
<!--            identity="001"-->
<!--            index="001"-->
<!--            label_text="locale_name"-->
<!--            @valueUpdated="vendorListChanged"-->
<!--        >-->

<!--        </accounting-select-with-search-layout-component>-->
      </div>
    </div>


    <div class="row">
      <div class="col-md-6">
        <accounting-select-with-search-layout-component
            :default-index="creator_id"
            :no_all_option="true"
            :options="receivers"
            :placeholder="app.trans.receiver"
            :title="app.trans.receiver"
            identity="002"
            index="002"
            label_text="locale_name"
            @valueUpdated="receiverListChanged"
        >

        </accounting-select-with-search-layout-component>

      </div>
      <div class="col-md-6">
        <div class="input-group">
          <span class="input-group-addon">{{ app.trans.department }}</span>
          <input v-model="creator.department.locale_title" aria-describedby="time-field" class="form-control"
                 disabled type="text">

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
          <div v-show="searchResultList.length >=1" class="live-vue-search panel">
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
        <a :href="'/items?selectable=true&&is_purchase=true'" class="btn btn-custom-primary btn-lg"
           target="_blank">{{ app.trans.view_products }}</a>

      </div>
      <div v-if="canCreateItem==1" class="col-md-2">
        <a :href="'/items/create'" class="btn btn-custom-primary btn-lg"
           target="_blank">{{ app.trans.create_product }}</a>
      </div>


    </div>


    <div class="panel panel-primary">
      <table class="table table-bordered text-center  table-striped">
        <thead class="panel-heading">
        <tr>
          <th></th>
          <th></th>
          <!-- <th class="has-text-white"></th> -->
          <th>{{ app.trans.barcode }}</th>
          <th>{{ app.trans.item_name }}</th>
          <th>{{ app.trans.qty }}</th>
          <th>{{ app.trans.sales_price }}</th>
          <th>{{ app.trans.purchase_price }}</th>
          <th>{{ app.trans.total }}</th>
          <!--<th>{{ app.trans.discount }}</th>
          <th>{{ app.trans.subtotal }}</th>-->
          <th>{{ app.trans.tax }}</th>
          <th>{{ app.trans.net }}</th>
          <th>- / +</th>

        </tr>


        </thead>

        <tbody>


        <tr v-for="(item,index) in invoiceData.items" :key="item.id">
          <td><input v-model="item.is_printable" type="checkbox"></td>
          <td>

            <button class="btn btn-danger btn-xs" @click="deleteItemFromList(item)"><i
                class="fa fa-trash"></i></button>
            <button v-if="item.is_need_serial"
                    class="btn btn-success btn-xs"
                    @click="openItemSerialsModal(index,item)"><i class="fa fa-pen"></i> &nbsp;
            </button>

          </td>
          <td v-text="item.barcode"></td>
          <td v-text="item.locale_name"></td>
          <td>
            <input
                v-if="!item.is_need_serial"
                :ref="'itemQty_' + item.id + 'Ref'"
                v-model="item.qty"
                :placeholder="app.trans.qty"
                class="form-control input-xs amount-input"
                type="text"
                @focus="$event.target.select()"
                @keyup="itemQtyUpdated(item)"
            >
            <p v-else>{{ item.qty }}</p>
          </td>


          <td>
            <input
                :ref="'itemSalePrice_' + item.id + 'Ref'"
                v-model="item.price_with_tax"
                :disabled="item.is_fixed_price"
                class="form-control input-xs amount-input"
                type="text"
                @focus="$event.target.select()">

          </td>

          <td>
            <input :ref="'itemPrice_' + item.id + 'Ref'"
                   v-model="item.purchase_price"

                   class="form-control input-xs amount-input"
                   type="text"
                   @change="itemPriceUpdated(item)"
                   @focus="$event.target.select()" @keyup.enter="clearAndFocusOnBarcodeField">

          </td>


          <td>
            <input v-model="item.total" class="form-control input-xs amount-input" disabled
                   type="text"
                   @focus="$event.target.select()">
          </td>
          <!--<td>
              <input :ref="'itemDiscount_' + item.id + 'Ref'"
                     @focus="$event.target.select()"
                     @keyup="itemDiscountUpdated(item)"
                     class="form-control input-xs amount-input" placeholder="discount" type="text"
                     v-model="item.discount">
          </td>
          <td>
              <input @focus="$event.target.select()" class="form-control input-xs amount-input" disabled=""
                     placeholder="subtotal"

                     type="text" v-model="item.subtotal">
          </td>-->

          <td>
            <input v-model="item.tax" class="form-control input-xs amount-input" disabled=""
                   placeholder="tax"
                   type="text" @focus="$event.target.select()">
          </td>
          <td>
            <input v-model="item.net"
                   class="form-control input-xs amount-input"
                   placeholder="net"

                   type="text" @change="itemNetUpdated(item)" @focus="$event.target.select()">
          </td>

          <td>
            <input v-model="item.variation"
                   :class="{'is-danger':item.variation>0,'is-primary':item.variation<=0}"
                   class="form-control input-xs amount-input"
                   disabled
                   placeholder="variation"
                   type="text">
          </td>

        </tr>

        </tbody>
      </table>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-md-3">
          <div class="panel panel-primary">
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
              <!--<div class="row">
                  <div class="col-md-6"><label>{{ app.trans.discount }}</label></div>
                  <div class="col-md-6">
                      <input :placeholder="app.trans.discount"
                             class="form-control  input-xs amount-input"
                             disabled type="text"
                             v-model="invoiceData.discount">
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-6"><label>{{ app.trans.subtotal }}</label></div>
                  <div class="col-md-6">
                      <input :placeholder="app.trans.subtotal"
                             class="form-control  input-xs amount-input"
                             disabled type="text"
                             v-model="invoiceData.subtotal">
                  </div>
              </div>-->

              <div class="row">
                <div class="col-md-6"><label>{{ app.trans.tax }}</label></div>
                <div class="col-md-6">
                  <input v-model="invoiceData.tax"
                         :placeholder="app.trans.tax"
                         class="form-control  input-xs amount-input" disabled
                         type="text">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6"><label>{{ app.trans.net }}</label></div>
                <div class="col-md-6">
                  <input v-model="invoiceData.net"
                         :placeholder="app.trans.net"
                         class="form-control  input-xs amount-input" disabled
                         type="text">
                </div>
              </div>

            </div>
          </div>
          <!--                    <textarea v-model="code_tester"></textarea>-->
          <accounting-invoice-embedded-purchase-expenses-layout
              :expenses="expensesList"
              @expenseDeIncludeInNet="expenseDeIncludeInNet"
              @expenseIncludeInNet="expenseIncludeInNet"
              @expensesUpdated="expensesListUpdated"
          >

          </accounting-invoice-embedded-purchase-expenses-layout>

        </div>
        <div class="col-md-9">
          <accounting-invoice-embedded-payments-gateway-layout
              :gateways="gateways"
              :net-amount="invoiceData.net"
              invoice-type="purchase"
              @updateGatewaysAmounts="updateGatewaysAmounts"
          >
          </accounting-invoice-embedded-payments-gateway-layout>
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


    <accounting-barcode-bulk-printer-layout-component
        :hide-btn="true"
        :invoice-id="invoiceId"
        :items='invoiceData.items'
        @CompletePrintProcess="reloadPageAfterPrintBarcode"
    >
    </accounting-barcode-bulk-printer-layout-component>


    <!--invoice Note Modal-->
    <div v-show="modalsInfo.showCreateVendorModal===true">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header ">
                  <button class="pull-left btn btn-custom-primary"
                          type="button"
                          @click="modalsInfo.showCreateVendorModal = false">
                    اغلاق
                  </button>
                  <h4 class="modal-title">انشاء هوية</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-6">
                      <toggle-button v-model="vendorModal.isIndividual" :font-size="19" :height='30' :labels="{checked:'فرد',
                                   unchecked: 'منشأة'}" :sync="true"
                                     :width='150'/>
                    </div>
                    <div class="col-md-6">
                      <toggle-button v-show="vendorModal.isIndividual" v-model="vendorModal.isMsr"
                                     :font-size="19"
                                     :height='30' :labels="{checked:'السيد',
                                   unchecked: 'السيد'}" :sync="true"
                                     :width='150'/>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <input v-model="vendorModal.vendorArName" class="form-control"
                             placeholder="اسم المورد"/>
                    </div>
                    <div class="col-md-6">
                      <input v-model="vendorModal.vendorName" class="form-control"
                             placeholder="اسم المورد (انجليزي)"/>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <input v-model="vendorModal.vendorCr" class="form-control"
                             placeholder="السجل الضريبي "/>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="btn btn-custom-primary" @click="pushVendorData">
                        حفظ
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
    <!--invoice create Modal-->


    <textarea v-if="activateTestMode" v-model="testRequestData" class="form-control"></textarea>
  </div>


</template>

<script>

import {
  accounting as ItemAccounting,
  math as ItemMath,
  query as ItemQuery,
  validator as ItemValidator
} from '../../item';

export default {
  props: ['creator', 'vendors', 'receivers', 'gateways', 'expenses', 'canViewItems',
    'canCreateItem', 'initPurchase', 'initInvoice', 'initItems', 'pendingDropboxPurchases'],
  data: function () {
    return {
      purchaseDropboxSnapshot:"",
      activateTestMode: false,
      testRequestData: "",
      code_tester: "",
      pending_purchase_id: 0,
      vendorModal: {
        vendorName: "",
        vendorArName: "",
        vendorCr: "",
        isIndividual: true,
        isMsr: true
      },
      modalsInfo: {
        showCreateVendorModal: false,
      },
      invoiceId: "",
      everythingFineToSave: false,
      selectedItem: null,
      selectedItemIndex: null,
      invoiceData: {
        remaining: 0,
        vendorIncCumber: "",
        vendorId: 0,
        receiverId: 0,
        methods: [],
        items: [],
        total: 0,
        net: 0,
        tax: 0,
        discount: 0,
        subtotal: 0,
        status: "credit"
      },
      creator_id: 0,
      vendorsList: [],
      searchResultList: [],
      expensesList: [],
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
        defaultVatSaleValue: 15,
        defaultVatPurchaseValue: 15,
      },

    };
  },
  created: function () {
    this.vendorsList = this.vendors;
    // this.initExpensesList();
    if (this.initPurchase != null) {
      this.cloneExistsInvoice();
    } else {
      this.creator_id = this.creator.id;
    }

  },

  mounted: function () {
    this.vendorsList = this.vendors;
    this.itemsTabsPusherHandler();
    this.$refs.barcodeNameAndSerialField.focus();
  },


  methods: {

    cloneExistsInvoice() {
      this.invoiceData.vendorIncCumber = this.initPurchase.vendor_invoice_id;
      this.invoiceData.vendorId = this.initPurchase.vendor_id;
      this.creator_id = this.initInvoice.creator_id;

      this.pending_purchase_id = this.initInvoice.id;
      for (let i = 0; i < this.initItems.length; i++) {
        let item = this.initItems[i];

        item.id = item.item_id;
        item.vtp = item.item.vtp;
        item.locale_name = item.item.locale_name;
        item.barcode = item.item.barcode;
        item.purchase_price = item.price;
        item.last_p_price = item.price;
        item.is_fixed_price = item.item.is_fixed_price;
        item.is_expense = item.item.is_expense;
        item.is_need_serial = item.item.is_need_serial;
        item.price_with_tax = item.item.price_with_tax;
        item.variation = 0;
        item.is_printable = item.printable;
        this.appendItemToInvoiceItemsList(item);
      }
    },
    itemNetUpdated(item) {
      item.net = parseFloat(item.net).toFixed(2);
      item.total = ItemAccounting.getSalesPriceFromSalesPriceWithTaxAndVat(item.net, item.vtp);
      item.purchase_price = parseInt(item.qty) === 0 ? 0 : parseFloat(item.total) / parseInt(item.qty);
      item.subtotal = item.total;
      item.tax = ItemAccounting.getTax(item.subtotal, item.vtp, true);
      item.discount = 0;
      this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));

    },


    pushVendorData() {

      let user_type;
      if (this.vendorModal.isIndividual) {
        user_type = 'individual';

      } else {
        user_type = 'company';
      }

      let user_title;

      if (this.vendorModal.isMr) {
        user_title = 'mr';
      } else {
        user_title = 'mis';
      }

      if (user_type == 'company') {
        user_title = 'company';
      }


      let data = {
        user_gateways: [],
        user_title: user_title,
        is_client: false,
        is_supplier: false,
        is_vendor: true,
        user_type: user_type,
        ar_name: this.vendorModal.vendorArName,
        name: this.vendorModal.vendorName,
        phone_number: "00000000",
        email: "",
        can_make_credit: false,
        user_detail_vat: "",
        user_detail_email: "",
        user_detail_cr: this.vendorModal.vendorCr,
        user_detail_address: "",
        user_detail_responser: "",
        user_detail_responser_phone: "000000"


      };

      let appVm = this;
      axios.post('/accounting/identities', data).then(response => {
        appVm.vendorsList.push(response.data);
        appVm.modalsInfo.showCreateVendorModal = false;
        appVm.invoiceData.vendorId = response.data.id;
        appVm.vendorListChanged({
          value: {
            id: response.data.id
          }
        })
      }).catch(error => {
        console.log(error.response);
        console.log(error.response.messages);
        console.log(error.message);
      });
    },
    vendorListChanged(event) {
      this.invoiceData.vendorId = event.value.id;
    },
    receiverListChanged(event) {
      this.invoiceData.receiverId = event.value.id;
    },


    itemsTabsPusherHandler() {
      let appVm = this;
      this.bc.onmessage = function (ev) {
        if (ev.isTrusted) {
          let item = JSON.parse(ev.data);
          appVm.validateAndPrepareItem(item);
        }
      }
    },
    sendQueryRequestToFindItems() {
      let appVm = this;
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
        console.log(error);
      })
    },
    validateAndPrepareItem(item) {

      if (item.is_service) {
        return;
      }
      if (db.model.contain(this.invoiceData.items, item.id)) {
        let parent = db.model.find(this.invoiceData.items, item.id);
        if (!parent.is_need_serial) {
          parent.qty = parseInt(parent.qty) + 1;
          this.itemQtyUpdated(parent);
        }
      } else {
        let preparedItem = this.prepareDataInFirstUse(item);
        this.appendItemToInvoiceItemsList(preparedItem);
      }

      this.clearAndFocusOnBarcodeField();
    },
    prepareDataInFirstUse(item) {
      item.is_printable = true;
      if (item.is_service) {
        return;
      }
      item.isOpen = false;
      item.qty = 1;
      if (item.is_need_serial) {
        item.qty = 0;
        item.serials = [];
      }
      item.purchase_price = item.last_p_price;
      item.total = item.qty * item.purchase_price;
      item.variation = item.purchase_price * item.last_p_price;
      item.discount = 0;
      item.subtotal = item.total;
      item.tax = ItemAccounting.getTax(item.subtotal, item.vtp);
      item.net = ItemAccounting.getNet(item.subtotal, item.tax);
      return item;

    },
    appendItemToInvoiceItemsList(item, index = null) {
      if (item.is_service) {
        return;
      }
      if (index != null) {
        this.invoiceData.items.splice(index, 1, item);
      } else {
        this.invoiceData.items.push(item);
      }

      this.updateInvoiceData();
    },
    updateInvoiceData() {
      this.invoiceData.total = db.model.sum(this.invoiceData.items, 'total');

      this.invoiceData.discount = db.model.sum(this.invoiceData.items, 'discount');
      this.invoiceData.subtotal = db.model.sum(this.invoiceData.items, 'subtotal');
      // console.log(this.invoiceData.subtotal);
      this.invoiceData.tax = db.model.sum(this.invoiceData.items, 'tax');
      this.invoiceData.net = db.model.sum(this.invoiceData.items, 'net');
      this.validateInvoiceData();
    },

    validateInvoiceData() {
      let everythingFineToSave = true;
      let validating = db.model.validateAmounts(this.invoiceData.items, [
        'purchase',
        'tax',
        'total',
        'discount',
        'net',
      ]);

      validating = validating && ItemValidator.validateAmount(this.invoiceData.total);
      validating = validating && ItemValidator.validateAmount(this.invoiceData.subtotal);
      validating = validating && ItemValidator.validateAmount(this.invoiceData.discount);
      validating = validating && ItemValidator.validateAmount(this.invoiceData.net);
      this.everythingFineToSave = validating;
    },
    clearAndFocusOnBarcodeField() {
      this.barcodeNameAndSerialField = "";
      this.searchResultList = [];
      this.$refs.barcodeNameAndSerialField.focus();
    },

    itemPriceMather(item) {
      let purchasePrice = item.purchase_price;
      let lastPurchasePrice = item.last_p_price;
      //  $maxExpectedPrice = ($newDBItem->last_p_price * 20) / 100 + $newDBItem->last_p_price;
      //         if ($item->price > $maxExpectedPrice) {
      //             $price = $newDBItem->last_p_price;
      //         } else {
      //             $price = $item->price;

      //         }
      if (parseFloat(purchasePrice) > parseFloat(lastPurchasePrice) * 20 / 100 + parseFloat(lastPurchasePrice)) {
        item.purchase_price = lastPurchasePrice;
      }
      // alert(`${lastPurchasePrice}`);
      return item;
    },
    itemQtyUpdated(item, bySerial = false) {

      item.qty = parseInt(item.qty);

      item = this.itemPriceMather(item);
      // item.qty = parseInt(item.qty);
      if (bySerial === false) {
        let el = this.$refs['itemQty_' + item.id + 'Ref'][0];
        if (!inputHelper.validateQty(item.qty, el)) {
          return false;
        }
      }

      // console.log('work');
      item = this.itemUpdater(item);
      this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
    },

    itemPriceUpdated(item) {
      // item.purchase_price = parseFloat(item.purchase_price).toFixed(2);
      let el = this.$refs['itemPrice_' + item.id + 'Ref'][0];
      if (!inputHelper.validatePrice(item.purchase_price, el)) {
        return false;
      }
      item = this.itemUpdater(item);
      this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
    },


    itemDiscountUpdated(item) {
      let el = this.$refs['itemDiscount_' + item.id + 'Ref'][0];
      if (!inputHelper.validateDiscount(item.discount, el)) {
        return false;
      }
      item = this.itemUpdater(item);
      this.appendItemToInvoiceItemsList(item, db.model.index(this.invoiceData.items, item.id));
    },


    itemUpdater(item) {
      item.total = ItemAccounting.getTotal(item.purchase_price, item.qty);
      item.subtotal = ItemAccounting.getSubtotal(item.total, item.discount);
      item.tax = ItemAccounting.getTax(item.subtotal, item.vtp);// this for vat purchase => vtp
      item.net = ItemAccounting.getNet(item.subtotal, item.tax);
      item.variation = ItemAccounting.getVariation(item.purchase_price, item.last_p_price);
      return item;
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
      let index = e.index;
      let item = db.model.findByIndex(this.invoiceData.items, index);
      item.serials = e.serials;
      item.qty = e.serials.length;
      this.itemQtyUpdated(item, true);
    },
    handleItemSerialsClosed(e) {
      this.selectedItem = null;
      this.selectedItemIndex = null;
    },


    updateListItemsWidgets() {
      for (let i = 0; i < this.invoiceData.items.length; i++) {
        let item = this.invoiceData.items[i];
        let itemWidget = ItemMath.dev(item.total, this.invoiceData.total);
        item.widget = itemWidget;
        this.invoiceData.items = db.model.replace(this.invoiceData.items, i, item);
      }
    },

    initExpensesList() {
      for (let i = 0; i < this.expenses.length; i++) {
        let expense = this.expenses[i];
        expense.is_open = false;
        expense.is_apended_to_net = false;
        expense.amount = 0;
        this.expensesList.push(expense);
      }
    },


    updateGatewaysAmounts(e) {
      this.invoiceData.status = e.status;
      this.invoiceData.methods = [];
      for (let i = 0; i < e.methods.length; i++) {
        let method = e.methods[i];

        if (parseFloat(method.amount) > 0) {
          this.invoiceData.methods.push(method);
        }
      }
    },


    expensesListUpdated(e) {
      this.expensesList.splice(e.index, 1, e.expense);
      this.updateNetAfterExpenses();


    },


    expenseIncludeInNet(e) {
      this.invoiceData.net = ItemMath.sum(this.invoiceData.net, e.expense.amount);
      this.expensesList.splice(e.index, 1, e.expense);
      this.updateListItemsWidgets();
    },


    expenseDeIncludeInNet(e) {
      this.invoiceData.net = ItemMath.sub(this.invoiceData.net, e.expense.amount);
      this.expensesList.splice(e.index, 1, e.expense);
      this.updateListItemsWidgets();
    },

    updateNetAfterExpenses() {

      let total = 0;
      for (let i = 0; i < this.expensesList.length; i++) {
        let expense = this.expensesList[i];
        if (expense.is_apended_to_net && parseFloat(expense.amount) > 0) {
          total = ItemMath.sum(total, expense.amount);
        }
        this.updateListItemsWidgets();
      }


      if (total > 0) {
        this.invoiceData.net = ItemMath.sum(total, db.model.sum(this.invoiceData.items, 'net'));
      } else {
        this.invoiceData.net = db.model.sum(this.invoiceData.items, 'net');
      }

    },


    pushDataToServer(event = "") {
      this.everythingFineToSave = false;
      let data = {
        pending_purchase_id: this.pending_purchase_id,
        items: this.invoiceData.items,
        vendor_id: this.invoiceData.vendorId,
        vendor_invoice_id: this.invoiceData.vendorIncCumber,
        receiver_id: this.invoiceData.receiverId,
        total: this.invoiceData.total,
        tax: this.invoiceData.tax,
        discount: this.invoiceData.discount,
        discount_percent: this.invoiceData.discount,
        net: this.invoiceData.net,
        subtotal: this.invoiceData.subtotal,
        methods: this.invoiceData.methods,
        expenses: this.expensesList,
        current_status: this.invoiceData.status,
        issued_status: this.invoiceData.status,
        remaining: this.invoiceData.remaining,
        department_id: this.creator.department_id,
        invoice_type: 'purchase',
        branch_id: this.creator.branch_id,
        creator_id: this.creator.id,
        dropbox_snapshot:this.purchaseDropboxSnapshot

      };
      this.code_tester = JSON.stringify(data);
      let appVm = this;
      if (this.activateTestMode) {
        this.testRequestData = JSON.stringify(data)
      } else {
        axios.post('/api/purchases', data)//this.
            .then(function (response) {
              console.log(response.data);
              // if (event == 'a4') {
              window.open('/accounting/printer/print_a4/' + response.data.id, '_blank');
              // }
              appVm.invoiceTitle = response.data.title;
              appVm.askUserToHandleInvoice(response.data);

              // this is user profile to handle the new quotation mode for this user profile
              // and to handle the new app with out last fake news
              // user will take every think to handle the user google account
            })
            .catch(function (error) {
              // alert(error.response);
              console.log(error);
              console.log(error.response)
            });
      }


    },

    bulkPrintComplete() {
      window.location.reload();
    },

    reloadPageAfterPrintBarcode() {
      window.location.reload();
    },
    askUserToHandleInvoice(invoice) {
      let options = {
        html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
        loader: false, // set to true if you want the dailog to show a loader after click on "proceed"
        reverse: false, // switch the button positions (left to right, and vise versa)
        okText: 'طباعة',
        cancelText: 'اغلاق',
        animation: 'zoom', // Available: "zoom", "bounce", "fade"
        type: 'soft', // coming soon: 'soft', 'hard'
        verification: 'delete',
        // for hard confirm, user will be prompted to type this to enable the proceed button
        verificationHelp: 'اكتب "[+:verification]" لتأكيد عملية الحذف ',
        // Verification help text. [+:verification] will be matched with 'options.verification' (i.e 'Type "continue" below to confirm')
        clicksCount: 1,
        // for soft confirm, user will be asked to click on "proceed" btn 3 times before actually proceeding
        backdropClose: false, // set to true to close the dialog when clicking outside of the dialog window, i.e. click landing on the mask
        // Custom class to be injected into the parent node for the current dialog instance
      };

      let appVm = this;

      this.$dialog
          .confirm('هل تريد طباعة الباركود للمنتجات في هذه الفاتورة ؟', options)
          .then(dialog => {
            appVm.invoiceId = invoice.title;
          })
          .catch(() => {
            if (appVm.pending_purchase_id == 0)
              window.location.reload();
            else
              window.location = '/purchases/create';
          });

      //
      //
    }
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

select {
  height: 45px;
}

</style>
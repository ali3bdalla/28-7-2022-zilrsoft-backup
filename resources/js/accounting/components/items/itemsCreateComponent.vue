<template>
  <div>
    <div class="row">
      <div class="col-md-6">
        <div :class="{'has-error':errorFieldName==='arName'}" class="form-group">
          <!--                  readonly=""-->
          <input v-model="itemData.arName"
                 :placeholder="app.trans.name_ar"
                 class="form-control has-error"
                 type='text'>
          <small v-show="errorFieldName==='arName'" class="text-danger">
            {{ errorFieldMessage }}
          </small>
        </div>

        <p class="help is-danger is-center" v-text="errorMessage"></p>
      </div>

      <div class="col-md-6">
        <div :class="{'has-error':errorFieldName==='enName'}" class="form-group">
          <input v-model="itemData.enName"
                 :placeholder="app.trans.name_en"
                 class="form-control has-error"
                 type='text'>
          <small v-show="errorFieldName==='enName'" class="text-danger">
            {{ errorFieldMessage }}
          </small>
        </div>

      </div>

    </div>
    <div class="row">
      <div class="col-md-4">
        <div :class="{'has-error':errorFieldName==='barcode'}" class="form-group">
          <input v-model="itemData.barcode" :class="{'is-danger':errorFieldName==='salesPrice'}"
                 :placeholder="app.trans.barcode"
                 class="form-control"
                 type='text' @blur="barcodeFieldChanged"
                 @keyup.13="barcodeFieldEnterButtonClicked">
          <small v-show="errorFieldName==='barcode'" class="text-danger">
            {{ errorFieldMessage }}
          </small>

        </div>
      </div>
      <div v-if="!openForEditing" class="col-md-2">
        <button class="btn btn-custom-primary" @click="generateBarcode">{{ app.trans.generate_barcode }}
        </button>
      </div>

      <div class="col-md-3">
        <div :class="{'has-error':errorFieldName==='salesPrice'}" class="form-group">
          <input v-model="itemData.salesPrice" :class="{'is-danger':errorFieldName==='salesPrice'}"
                 :placeholder="app.trans.price"
                 class="form-control" type='text'
                 @keyup="salesPriceFieldUpdated">
          <small v-show="errorFieldName==='salesPrice'" class="text-danger">
            {{ errorFieldMessage }}
          </small>

        </div>

      </div>

      <div class="col-md-3">

        <div :class="{'has-error':errorFieldName==='salesPriceWithTax'}" class="form-group">
          <input ref="salesPriceWithTaxFieldRef" v-model="itemData.salesPriceWithTax"
                 :class="{'is-danger':errorFieldName==='salesPriceWithTax'}"
                 :placeholder="app.trans.price_tax" class="form-control"
                 type='text'
                 @keyup="salesPriceWithTaxFieldUpdated">
          <small v-show="errorFieldName==='salesPriceWithTax'" class="text-danger">
            {{ errorFieldMessage }}
          </small>
        </div>

      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
        <toggle-button v-model="itemData.hasVatSale" :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}"
                       :sync="true" :width='70'
                       @change="vatSaleValueToggleButtonChanged"/>
        <label>{{ app.trans.has_vat_sale }}</label>
      </div>

      <div class="col-md-2">
        <toggle-button v-model="itemData.hasVatPurchase" :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}"
                       :sync="true" :width='70'
                       @change="vatPurchaseValueToggleButtonChanged"/>
        <label>{{ app.trans.has_vat_purchase }}</label>
      </div>

      <div class="col-md-2">
        <toggle-button v-model="itemData.isNeedSerial" :font-size="14" :height='30'
                       :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}" :sync="true" :width='70'
                       @change="isNeedSerialToggleButtonChanged"/>
        <label>{{ app.trans.has_serial_number }}</label>
      </div>

      <div class="col-md-2">
        <toggle-button v-model="itemData.hasFixedPrice" :font-size="14" :height='30' :labels="{checked: reusable_translator.unchecked,
                                   unchecked: reusable_translator.radio_checked}"
                       :sync="true" :width='70'/>
        <label>{{ app.trans.is_fixed_price }}</label>
      </div>

      <div class="col-md-2">
        <toggle-button v-model="itemData.isService" :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}"
                       :sync="true" :width='70' @change="isServiceToggleButtonChanged"/>
        <label>{{ app.trans.is_service }}</label>
      </div>

      <div class="col-md-2">
        <toggle-button v-model="itemData.isExpense" :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}"
                       :sync="true" :width='70' @change="isExpenseToggleButtonChanged"/>
        <label>{{ app.trans.is_expense }}</label>
      </div>

    </div>

    <div class="row">
      <div class="col-md-2">
        <input v-model="itemData.vts"
               :disabled="!itemData.hasVatSale"
               :placeholder="app.trans.vat_sale"
               class="form-control"
               type='text'
               @keydown="vatSaleFieldUpdated">

      </div>
      <div class="col-md-2">

        <input v-model="itemData.vtp" :disabled="!itemData.hasVatPurchase"
               :placeholder="app.trans.vat_purchase"
               class="form-control"
               type='text'
               @keydown="vatPurchaseFieldUpdated">

      </div>
      <div class="col-md-2">
        <select v-model="itemData.warranty_subscription_id" class="form-control ">
          <option value="0">من غير ضمان</option>
          <option v-for="warranty in warranty_subscriptions" :key="warranty.id" :value="warranty.id">
            {{ warranty.locale_name }}
          </option>
        </select>
        <!--                <accounting-select-with-search-layout-component-->
        <!--                        :options="warranty_subscriptions"-->
        <!--                        placeholder="الضمان" :no_all_option="true"-->
        <!--                        :default_id="itemData.warranty_subscription_id"-->
        <!--                        title="الضمان"-->
        <!--                        label_text="locale_name"-->
        <!--                ></accounting-select-with-search-layout-component>-->
      </div>
      <div class="col-md-2">

      </div>
      <div class="col-md-2">

      </div>
      <div class="col-md-2">
        <div v-show="itemData.isExpense" :class="{'has-error':errorFieldName==='expenseVendorId'}"
             class="form-group">
          <accounting-select-with-search-layout-component
              :identity="10023749872394"
              :no_all_option="true"
              :options="vendors"
              default="0"
              label_text="locale_name"
              placeholder="المورد"
              title="المورد"
              @valueUpdated="vendorListHasBeenUpdated"
          >
          </accounting-select-with-search-layout-component>
          <small v-show="errorFieldName==='expenseVendorId'" class="text-danger">-->
            {{ errorFieldMessage }}
          </small>

        </div>

      </div>

    </div>

    <div v-show="!itemData.hasVatSale || !itemData.hasVatPurchase" class="row">
      <div class="col-md-12">
        <label>ضريبة في الطباعة</label>
      </div>
      <div class="col-md-2">
        <input v-show="!itemData.hasVatSale"
               v-model="itemData.vts_for_print"
               :placeholder="app.trans.vat_sale"
               class="form-control"
               type='text'>

      </div>
      <div class="col-md-2">

        <input
            v-show="!itemData.hasVatPurchase"
            v-model="itemData.vtp_for_print" :placeholder="app.trans.vat_purchase"
            class="form-control"
            type='text'>

      </div>

    </div>

    <div class="row">

      <div class="col-md-2 label-txt  col-md-offset-1">
        <b>{{ app.trans.categories }}</b>
      </div>
      <div class="col-md-6">
        <div>
          <div :dir="app.appLocate==='ar' ? 'rtl' : 'ltr'">
            <treeselect
                v-model="itemData.categoryId"
                :disable-branch-nodes="false"
                :disabled="cloningItem===true"
                :load-options="loadCategoriesList"
                :options="categories"
                :placeholder="app.trans.category"
                :show-count="true"
                :value="itemData.categoryId"
                @select="categoryListUpdated"
            ></treeselect>
          </div>
        </div>
      </div>

      <div class="col-md-1">
        &nbsp;<toggle-button v-model="categoryNameShouldBeInItemName"
                             :font-size="14" :height='30'
                             :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}"
                             :sync="true"
                             :width='70'
                             @change="categoryNameShouldBeInItemNameToggleUpdated">
      </toggle-button>
      </div>

      <div class="col-md-1  text-center">
        <a v-if="canCreateCategory===1" :href="app.BaseApiUrl + 'categories/create'" target="_blank">
          <button class="btn btn-custom-primary btn-sm"><i class="fa fa-plus-circle"></i></button>
        </a>
      </div>

    </div>

    <div v-for='(filter,index) in filterList' v-bind:key="index">
      <accounting-filter-select-with-search-component
          :app-trans="app.trans"
          :can-create="canCreateFilter"
          :can-edit="canEditFilter"
          :default="selectedFilterValue.get(filter.id)"
          :filter="filter"
          :index="index"
          :messages="messages"
          :options="filter.values"
          :reusable_translator="reusable_translator"
          @updateItemName="rebuildItemName"
          v-on:valueUpdated="filterValueListChanged">
      </accounting-filter-select-with-search-component>
    </div>

    <div style="margin-top: 35px;padding:5px">

      <toggle-button v-model="itemData.isAvailableOnline" :font-size="14" :height='30' :labels="{checked: 'متاح اونلاين',
                                   unchecked: 'غير متاح اونلاين'}"
                     :sync="true" :width='150'/>

      <div>
        <div class="row">

          <div class="col-md-3">
            <div :class="{'has-error':errorFieldName==='onlinePrice'}" class="form-group">
              <input v-model="itemData.onlinePrice" :class="{'is-danger':errorFieldName==='onlinePrice'}"
                     class="form-control"
                     placeholder="سعر الاونلاين" type='text'
              >
              <small v-show="errorFieldName==='onlinePrice'" class="text-danger">
                {{ errorFieldMessage }}
              </small>

            </div>

          </div>

          <div class="col-md-3">
            <div :class="{'has-error':errorFieldName==='onlineOfferPrice'}" class="form-group">
              <input v-model="itemData.onlineOfferPrice" :class="{'is-danger':errorFieldName==='onlineOfferPrice'}"
                     class="form-control"
                     placeholder="سعر العرض" type='text'>
              <small v-show="errorFieldName==='onlineOfferPrice'" class="text-danger">
                {{ errorFieldMessage }}
              </small>

            </div>

          </div>

          <div class="col-md-3">
            <div :class="{'has-error':errorFieldName==='weight'}" class="form-group">
              <input v-model="itemData.weight" :class="{'is-danger':errorFieldName==='weight'}"
                     class="form-control"
                     placeholder="الوزن" type='text'/>
              <small v-show="errorFieldName==='weight'" class="text-danger">
                {{ errorFieldMessage }}
              </small>

            </div>

          </div>

          <div class="col-md-3">

            <div :class="{'has-error':errorFieldName==='shippingDiscount'}" class="form-group">
              <input v-model="itemData.shippingDiscount"
                     :class="{'is-danger':errorFieldName==='shippingDiscount'}"
                     class="form-control" placeholder="خصم الشحن"
                     type='text'>
              <small v-show="errorFieldName==='shippingDiscount'" class="text-danger">
                {{ errorFieldMessage }}
              </small>
            </div>

          </div>
        </div>

        <div class="row">
           <vue-tags-input
            v-model="tag"
            :tags="itemData.tags"
            @tags-changed="newTags => itemData.tags = newTags"
          />
        </div>

      </div>

    </div>

<!--    <div class="row">-->
<!--      <div v-for="image in attachments" :key="image.id">-->
<!--        {{ image.url}}-->
<!--      </div>-->
<!--    </div>-->
    <!--        <div class="row">-->
    <!--            <attachments-preview-component :attachments="itemData.attachments"-->
    <!--                                           :new_attachment_link="new_attachment_link"></attachments-preview-component>-->
    <!--        </div>-->
    <!--    <Images :attachments="[]" :item="itemData"></Images>-->
    <br>
    <br>
    <br>
    <div class="form-group text-center">

      <button v-if="editingItem!=true ||
            cloningItem===1" class="btn btn-custom-primary" @click="sendDataToServer('clone')"><i
          class="fa fa-check-circle"></i> &nbsp;&nbsp;{{ app.trans.save_clone }}
      </button>
      &nbsp;&nbsp;
      &nbsp;
      &nbsp;
      &nbsp;
      <button class="btn btn-custom-primary" @click="sendDataToServer('exit')"><i class="fa fa-door-open"></i>&nbsp;&nbsp;
        {{ app.trans.save_exit }}
      </button>
      &nbsp;
      &nbsp;
      &nbsp;
      &nbsp;
      &nbsp;
      <a :href="app.BaseApiUrl + 'items'" class="btn btn-custom-default"><i class="fa fa-undo-alt"></i>
        &nbsp;&nbsp;{{ app.trans.cancel }}</a>

    </div>

  </div>

</template>

<script>

import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import PreviewComponent from '../attachments/previewComponent'
import { accounting as ItemAccounting, validator as ItemValidator } from '../../item'
import Images from '../../../components/BackEnd/Product/Images'

import VueTagsInput from '@johmun/vue-tags-input'

export default {

  components: {
    VueTagsInput,
    Images,
    Treeselect,
    'attachments-preview-component': PreviewComponent

  },
  props: ['categories', 'cloningItem', 'editingItem', 'editedItemFilters', 'editedItemCategory', 'editedItemData',
    'vendors', 'canCreateCategory', 'canEditFilter', 'canCreateFilter'],
  data: function () {
    return {
      new_attachment_link: null,
      openForEditing: false,
      errorFieldName: '',
      errorFieldMessage: '',
      warranty_subscriptions: [],
      tag: '',
      tags: [],
      app: {
        primaryColor: metaHelper.getContent('primary-color'),
        secondColor: metaHelper.getContent('second-color'),
        appLocate: metaHelper.getContent('app-locate'),
        trans: trans('items-page'),
        messages: trans('messages'),
        dateTimeTrans: trans('datetime'),
        validation: trans('validation'),
        datatableBaseUrl: metaHelper.getContent('datatableBaseUrl'),
        BaseApiUrl: metaHelper.getContent('BaseApiUrl'),
        defaultVatSaleValue: 15,
        defaultVatPurchaseValue: 15
      },
      itemData: {
        tags: [],
        attachments: [],
        warranty_subscription_id: 0,
        arName: '',
        enName: '',
        barcode: '',
        salesPrice: '',
        salesPriceWithTax: '',
        hasVatSale: true,
        isAvailableOnline: false,
        hasVatPurchase: true,
        isNeedSerial: false,
        hasFixedPrice: false,
        isService: false,
        isExpense: false,
        vts: 15,
        vtp: 15,
        onlinePrice: 0,
        onlineOfferPrice: 0,
        weight: 0,
        shippingDiscount: 0,
        vts_for_print: 0,
        vtp_for_print: 0,
        expenseVendorId: 0,
        categoryId: null
      },
      requestHelperData: {
        itemsWithSameBarcode: []
      },
      categoryNameShouldBeInItemName: true,
      reusable_translator: null,
      messages: '',
      disableCategorySelection: false,
      parent_item: null,
      error: false,
      errorMessage: '',
      categoriesList: [],
      filterList: [],
      selectedCategory: null,
      selectedFilterValue: new Map(),
      isLoading: true

    }
  },
  created: function () {
    this.reusable_translator = JSON.parse(window.reusable_translator)
    this.local_config = JSON.parse(window.config)
    if (this.editingItem != null && this.editingItem) {
      this.itemData.warranty_subscription_id = this.editedItemData.warranty_subscription_id

      this.itemData.arName = this.editedItemData.ar_name
      this.itemData.isAvailableOnline = this.editedItemData.is_available_online
      this.itemData.enName = this.editedItemData.name
      this.itemData.barcode = this.editedItemData.barcode
      this.itemData.salesPrice = this.editedItemData.price
      this.itemData.salesPriceWithTax = this.editedItemData.price_with_tax

      this.itemData.onlinePrice = this.editedItemData.online_price
      this.itemData.onlineOfferPrice = this.editedItemData.online_offer_price
      this.itemData.weight = this.editedItemData.weight
      this.itemData.shippingDiscount = this.editedItemData.shipping_discount
      this.editedItemData.tags.forEach((item) => {
        this.itemData.tags.push({
          text: item
        })
      })

      this.itemData.hasVatSale = this.editedItemData.is_has_vts
      this.itemData.has_vat_purchase = this.editedItemData.is_has_vtp
      this.itemData.isNeedSerial = this.editedItemData.is_need_serial
      this.itemData.hasFixedPrice = this.editedItemData.is_fixed_price
      this.itemData.isService = this.editedItemData.is_service
      this.itemData.isExpense = this.editedItemData.is_expense
      this.itemData.vtp = this.editedItemData.vtp
      this.itemData.vts = this.editedItemData.vts
      this.itemData.vts_for_print = this.editedItemData.vts_for_print
      this.itemData.vtp_for_print = this.editedItemData.vtp_for_print
      this.itemData.expenseVendorId = this.editedItemData.expense_vendor_id
      this.itemData.categoryId = this.editedItemData.category_id
      this.itemData.attachments = this.editedItemData.attachments
      this.new_attachment_link = '/items/' + this.editedItemData.id + '/attachments'

      if (!this.editedItemData.name.includes(this.editedItemCategory.name)) {
        this.categoryNameShouldBeInItemName = false
      }

      if (this.cloningItem != null && this.cloningItem === 1) {
        this.itemData.barcode = ''
        this.itemData.salesPrice = 0
        this.itemData.salesPriceWithTax = 0
      }
      this.categoryListUpdated(this.editedItemCategory, null)
    }

    this.messages = window.messages

    this.loadWarrantySubscriptions()
  },
  methods: {

    loadWarrantySubscriptions () {
      const appVm = this
      // warranty_subscriptions
      axios.get('/accounting/warranty_subscriptions').then(response => {
        appVm.warranty_subscriptions = response.data
      }).catch(error => {
        alert(error)
      })
    },
    // barcode events
    barcodeFieldChanged (e) {
      this.validateBarcode(e.target.value)
    },
    validateBarcode (barcode) {
      const appVm = this
      axios.get(appVm.app.BaseApiUrl + 'items/helper/validate_barcode', {
        params: {
          barcode: barcode
        }
      })
        .then(function (response) {
          if (appVm.errorFieldName === 'barcode') { appVm.errorFieldName = '' }
        })
        .catch(function (error) {
          appVm.errorFieldName = 'barcode'
          appVm.errorFieldMessage = error.response.data.message
          appVm.itemData.barcode = ''
        })
    },
    barcodeFieldEnterButtonClicked () {
      if (this.itemData.barcode != '') {
        if (this.errorFieldName != 'barcode') {
          this.itemData.salesPriceWithTax = ''
          this.$refs.salesPriceWithTaxFieldRef.focus()
        }
      }
    },
    // sales price and sales price with tax fields events
    salesPriceFieldUpdated (e) {
      if (this.errorFieldName === 'salesPrice' || this.errorFieldName === 'salesPriceWithTax') { this.errorFieldName = '' }
      const val = e.target.value
      if (ItemValidator.validatePriceValue(val)) { this.itemData.salesPriceWithTax = ItemAccounting.getSalesPriceWithTaxFromSalesPriceAndVat(val, this.itemData.vts) } else { this.itemData.salesPriceWithTax = '' }
    },
    salesPriceWithTaxFieldUpdated (e) {
      if (this.errorFieldName === 'salesPrice' || this.errorFieldName === 'salesPriceWithTax') { this.errorFieldName = '' }
      const val = this.itemData.salesPriceWithTax
      if (ItemValidator.validatePriceValue(val)) { this.itemData.salesPrice = ItemAccounting.getSalesPriceFromSalesPriceWithTaxAndVat(val, this.itemData.vts) } else { this.itemData.salesPrice = '' }
    },
    // toggle buttons events
    vatSaleValueToggleButtonChanged (e) {
      if (this.itemData.hasVatSale === false) {
        this.itemData.vts = 0
      } else {
        if (this.itemData.hasVatSale === 0) {
          this.itemData.vts = this.app.defaultVatSaleValue
        }
      }
    },
    vatPurchaseValueToggleButtonChanged (e) {
      if (this.itemData.hasVatPurchase === false) {
        this.itemData.vtp = 0
      } else {
        if (this.itemData.hasPurchaseSale === 0) {
          this.itemData.vtp = this.app.defaultVatPurchaseValue
        }
      }
    },
    isNeedSerialToggleButtonChanged (e) {
      if (this.itemData.isNeedSerial === true) {
        this.itemData.isService = false
        this.itemData.isExpense = false
      }
    },
    isServiceToggleButtonChanged (e) {
      if (this.itemData.isService === true) {
        this.itemData.isNeedSerial = false
        this.itemData.hasVatPurchase = false
        this.itemData.salesPriceWithTax = 40
      } else {
        this.itemData.hasVatPurchase = true
        this.itemData.salesPriceWithTax = 0
      }
      this.salesPriceWithTaxFieldUpdated({})
    },
    isExpenseToggleButtonChanged (e) {
      if (this.itemData.isExpense === true) {
        this.itemData.isNeedSerial = false
      }
    },
    categoryNameShouldBeInItemNameToggleUpdated () {
      this.rebuildItemName()
    },
    // vats fields events
    vatSaleFieldUpdated (e) {
      if (parseFloat(e.target.value) > 0) {
        this.itemData.hasVatSale = true
      }
    },
    vatPurchaseFieldUpdated (e) {
      if (parseFloat(e.target.value) > 0) {
        this.itemData.hasVatPurchase = true
      }
    },
    // vendor => expense
    vendorListHasBeenUpdated (e) {
      this.itemData.expenseVendorId = e.value.id
    },
    categoryListUpdated (node, instanceId) {
      if (this.errorFieldName === 'category') {
        this.error = ''
      }
      this.selectedFilterValue.clear()
      const appVm = this
      this.selectedCategory = node
      this.itemData.categoryId = node.id

      axios.post(this.app.BaseApiUrl + 'categories/view/filters', {
        categories_ids: [node.id]
      })
        .then(function (response) {
          const filters = response.data
          const data = []
          for (let i = 0; i < filters.length; i++) {
            const filter = filters[i]
            filter.is_checked = true
            data.push(filter)
          }
          appVm.filterList = data

          if (appVm.editingItem != null && appVm.editingItem) {
            appVm.updateSelectedValuesFromParentItem()
          } else {
            appVm.rebuildItemName()
          }
        })
        .catch(function (error) {
          console.log(error.response)
        })
    },
    loadCategoriesList (e) {
    },
    rebuildItemName () {
      let arName = ''
      let enName = ''
      if (this.categoryNameShouldBeInItemName) {
        arName = this.selectedCategory.ar_name
        enName = this.selectedCategory.name
      }

      const len = this.filterList.length
      for (let i = 0; i < len; i++) {
        const filter = this.filterList[i]
        if (filter.is_checked) {
          if (this.selectedFilterValue.has(filter.id)) {
            const value_id = this.selectedFilterValue.get(filter.id)
            if (value_id !== 0) {
              const value_data = helpers.getDataFromArrayById(filter.values, value_id)
              enName = enName.concat(' ' + value_data.name)
              arName = arName.concat(' ' + value_data.ar_name)
            }
          }
        }
      }

      this.itemData.arName = arName
      this.itemData.enName = enName
    },
    filterValueListChanged (data) {
      const filterData = this.filterList[data.index]
      this.selectedFilterValue.set(filterData.id, data.value)
      this.rebuildItemName()
    },
    generateBarcode () {
      const barcode = helpers.generateRandomNumberWithSize()
      this.itemData.barcode = barcode

      this.validateBarcode(barcode)
    },
    validateAllField () {
      if (this.itemData.barcode === '' || this.itemData.barcode.length < 4) {
        this.errorFieldName = 'barcode'
        this.errorFieldMessage = this.validation.item.barcode_required
        return false
      }

      if (this.itemData.enName === '') {
        this.errorFieldName = 'enName'
        this.errorFieldMessage = this.validation.item.name_required
        return false
      }

      if (this.itemData.arName === '') {
        this.errorFieldName = 'arName'
        this.errorFieldMessage = this.validation.item.name_required
        return false
      }

      if (this.itemData.salesPriceWithTax <= 0) {
        this.errorFieldName = 'salesPriceWithTax'
        this.errorFieldMessage = this.validation.item.price_with_tax_required
        return false
      }

      if (this.itemData.salesPrice <= 0) {
        this.errorFieldName = 'salesPrice'
        this.errorFieldMessage = this.validation.item.price_required
        return false
      }

      if (this.itemData.categoryId === null) {
        this.errorFieldName = 'categoryId'
        this.errorFieldMessage = this.validation.item.category_required
        return false
      }

      return true
    },
    extractAllSelectedFiltersAsKeyValueArray () {
      const data = []
      this.selectedFilterValue.forEach((value, index) => {
        if (value !== 0) {
          data[index] = value
        }
      })
      return data
    },
    // when submit form
    sendDataToServer (redirect_to) {
      if (!this.validateAllField()) {
        return
      }

      const Tags = []

      this.itemData.tags.forEach(element => {
        Tags.push(element.text)
      })

      const filters_values = this.extractAllSelectedFiltersAsKeyValueArray()
      const data = {
        expense_vendor_id: this.itemData.expenseVendorId,
        warranty_subscription_id: this.itemData.warranty_subscription_id,
        is_expense: this.itemData.isExpense,
        name: this.itemData.enName,
        ar_name: this.itemData.arName,
        barcode: this.itemData.barcode,
        is_has_vts: this.itemData.hasVatSale,
        is_has_vtp: this.itemData.hasVatPurchase,
        is_need_serial: this.itemData.isNeedSerial,
        is_fixed_price: this.itemData.hasFixedPrice,
        is_service: this.itemData.isService,
        price: this.itemData.salesPrice,
        price_with_tax: this.itemData.salesPriceWithTax,
        category_id: this.itemData.categoryId,
        vts: this.itemData.vts,
        vtp: this.itemData.vtp,
        vtp_for_print: this.itemData.vtp_for_print,
        online_price: this.itemData.onlinePrice,
        online_offer_price: this.itemData.onlineOfferPrice,
        weight: this.itemData.weight,
        shipping_discount: this.itemData.shippingDiscount,
        is_available_online: this.itemData.isAvailableOnline,
        tags: Tags,
        filters: filters_values
      }
      const loader = this.$loading.show({
        container: this.fullPage ? null : this.$refs.formContainer
      })
      const appVm = this

      if (this.editingItem != null && this.editingItem && this.cloningItem != true) {
        axios.put(this.app.BaseApiUrl + 'items/' + this.editedItemData.id, data)
          .then(function (response) {
            loader.hide()
            location.href = '/items'
          })
          .catch(function (error) {
            console.log(error.response)
            loader.hide()
          })

        // simulate AJAX
        setTimeout(() => {
          loader.hide()
        }, 5000)
      } else {
        axios.post(this.app.BaseApiUrl + 'items', data)
          .then(function (response) {
            loader.hide()
            appVm.itemData.barcode = ''
            appVm.itemData.salesPrice = 0
            appVm.itemData.salesPriceWithTax = 0

            if (redirect_to === 'clone') {
              appVm.showPopSuccessMessage()
            } else {
              location.href = appVm.app.BaseApiUrl + 'items'
            }
          })
          .catch(function (error) {
            console.log(error.response)
            console.log(error.data)
            loader.hide()
            // }
          })

        setTimeout(() => {
          loader.hide()
        }, 5000)
      }
    },
    updateSelectedValuesFromParentItem () {
      const filterLen = this.editedItemFilters.length
      // console.log(filterLen)
      for (let i = 0; i < filterLen; i++) {
        const filter_and_value = this.editedItemFilters[i]

        this.selectedFilterValue.set(filter_and_value.filter_id, filter_and_value.filter_value)
      }

      this.updateFiltersToggleButtons()
    },
    updateFiltersToggleButtons () {
      const data = []
      const len = this.filterList.length
      for (let i = 0; i < len; i++) {
        const fit = this.filterList[i]
        if (this.selectedFilterValue.has(fit.id)) {
          const vid = this.selectedFilterValue.get(fit.id)// value id
          const value = helpers.getDataFromArrayById(fit.values, vid)
          if (this.itemData.enName.includes(value.name)) {
            fit.is_checked = true
          } else {
            fit.is_checked = false
          }
        } else {
          fit.is_checked = false
        }
        data.push(fit)
      }

      this.filterList = data
    },
    showPopSuccessMessage () {
      const options = {
        html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
        loader: false, // set to true if you want the dailog to show a loader after click on "proceed"
        reverse: false, // switch the button positions (left to right, and vise versa)
        okText: this.app.messages.ok_button_txt,
        cancelText: this.app.messages.close_pop_txt,
        animation: 'zoom', // Available: "zoom", "bounce", "fade"
        clicksCount: 3, // for soft confirm, user will be asked to click on "proceed" btn 3 times before actually proceeding
        backdropClose: false, // set to true to close the dialog when clicking outside of the dialog window, i.e. click landing on the mask
        customClass: ''
        // Custom class to be injected into the parent node for the current dialog instance
      }

      this.$dialog
        .alert(this.app.messages.item_has_been_saved, options)
        .then(dialog => {

        })
        .catch(() => {

        })
    }

  }

}
</script>

<style scoped>
input {
  text-align: center !important;
}

.label-txt {
  vertical-align: middle;
  text-align: right;
  margin-top: 9px;
}

.vue-treeselect div, .vue-treeselect span {
  padding: 1px !important;
  font-size: 16px !important;
}

.vue-tags-input {
  max-width: 100% !important;
}
</style>

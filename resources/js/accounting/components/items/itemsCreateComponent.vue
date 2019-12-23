<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div :class="{'has-error':errorFieldName=='arName'}" class="form-group">
                    <input :placeholder="app.trans.name_ar"
                           class="form-control has-error"
                           readonly="" type='text'
                           v-model="itemData.arName">
                    <small class="text-danger" v-show="errorFieldName=='arName'">
                        {{ errorFieldMessage}}
                    </small>
                </div>

                <p class="help is-danger is-center" v-text="errorMessage"></p>
            </div>

            <div class="col-md-6">
                <div :class="{'has-error':errorFieldName=='enName'}" class="form-group">
                    <input :placeholder="app.trans.name_en"
                           class="form-control has-error"
                           readonly="" type='text'
                           v-model="itemData.enName">
                    <small class="text-danger" v-show="errorFieldName=='enName'">
                        {{ errorFieldMessage}}
                    </small>
                </div>

            </div>


        </div>
        <div class="row">
            <div class="col-md-4">
                <div :class="{'has-error':errorFieldName=='barcode'}" class="form-group">
                    <input :class="{'is-danger':errorFieldName=='salesPrice'}" :placeholder="app.trans.barcode"
                           @blur="barcodeFieldChanged"
                           @keyup.13="barcodeFieldEnterButtonClicked"
                           class="form-control" type='text'
                           v-model="itemData.barcode">
                    <small class="text-danger" v-show="errorFieldName=='barcode'">
                        {{ errorFieldMessage}}
                    </small>

                </div>
            </div>
            <div class="col-md-2" v-if="!openForEditing">
                <button @click="generateBarcode" class="btn btn-custom-primary">{{app.trans.generate_barcode}}
                </button>
            </div>


            <div class="col-md-3">
                <div :class="{'has-error':errorFieldName=='salesPrice'}" class="form-group">
                    <input :class="{'is-danger':errorFieldName=='salesPrice'}" :placeholder="app.trans.price"
                           @keyup="salesPriceFieldUpdated"
                           class="form-control" type='text'
                           v-model="itemData.salesPrice">
                    <small class="text-danger" v-show="errorFieldName=='salesPrice'">
                        {{ errorFieldMessage}}
                    </small>

                </div>

            </div>

            <div class="col-md-3">

                <div :class="{'has-error':errorFieldName=='salesPriceWithTax'}" class="form-group">
                    <input :class="{'is-danger':errorFieldName=='salesPriceWithTax'}" :placeholder="app.trans.price_tax"
                           @keyup="salesPriceWithTaxFieldUpdated"
                           class="form-control" ref="salesPriceWithTaxFieldRef"
                           type='text'
                           v-model="itemData.salesPriceWithTax">
                    <small class="text-danger" v-show="errorFieldName=='salesPriceWithTax'">
                        {{ errorFieldMessage}}
                    </small>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}" :sync="true"
                               :width='70' @change="vatSaleValueToggleButtonChanged"
                               v-model="itemData.hasVatSale"/>
                <label>{{ app.trans.has_vat_sale }}</label>
            </div>


            <div class="col-md-2">
                <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}" :sync="true"
                               :width='70' @change="vatPurchaseValueToggleButtonChanged"
                               v-model="itemData.hasVatPurchase"/>
                <label>{{ app.trans.has_vat_purchase }}</label>
            </div>


            <div class="col-md-2">
                <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}"
                               :sync="true" :width='70' @change="isNeedSerialToggleButtonChanged"
                               v-model="itemData.isNeedSerial"/>
                <label>{{ app.trans.has_serial_number }}</label>
            </div>


            <div class="col-md-2">
                <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.unchecked,
                                   unchecked: reusable_translator.radio_checked}" :sync="true"
                               :width='70' v-model="itemData.hasFixedPrice"/>
                <label>{{ app.trans.is_fixed_price }}</label>
            </div>

            <div class="col-md-2">
                <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}" :sync="true"
                               :width='70' @change="isServiceToggleButtonChanged" v-model="itemData.isService"/>
                <label>{{ app.trans.is_service }}</label>
            </div>

            <div class="col-md-2">
                <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}" :sync="true"
                               :width='70' @change="isExpenseToggleButtonChanged" v-model="itemData.isExpense"/>
                <label>{{ app.trans.is_expense }}</label>
            </div>


        </div>
        <div class="row">
            <div class="col-md-2">
                <input :placeholder="app.trans.vat_sale"
                       @keydown="vatSaleFieldUpdated"
                       class="form-control"
                       type='text'
                       v-model="itemData.vts">

            </div>
            <div class="col-md-2">

                <input :placeholder="app.trans.vat_purchase" @keydown="vatPurchaseFieldUpdated"
                       class="form-control"
                       type='text'
                       v-model="itemData.vtp">

            </div>
            <div class="col-md-2">

            </div>
            <div class="col-md-2">

            </div>
            <div class="col-md-2">

            </div>
            <div class="col-md-2">
                <div :class="{'has-error':errorFieldName=='expenseVendorId'}" class="form-group"
                     v-show="itemData.isExpense">
                    <accounting-select-with-search-layout-component
                            :identity="10023749872394"
                            :no_all_option="true"
                            :options="vendors"
                            @valueUpdated="vendorListHasBeenUpdated"
                            default="0"
                            label_text="name"
                            placeholder="المورد"
                            title="المورد"
                    >
                    </accounting-select-with-search-layout-component>
                    <small class="text-danger" v-show="errorFieldName=='expenseVendorId'">-->
                        {{ errorFieldMessage}}
                    </small>

                </div>

            </div>

        </div>


        <div class="row">
            <div class="col-md-2 label-txt  col-md-offset-1">
                <b>{{ app.trans.categories}}</b>
            </div>
            <div class="col-md-6">
                <div>
                    <div :dir="app.appLocate=='ar' ? 'rtl' : 'ltr'">
                        <treeselect
                                :disable-branch-nodes="true"
                                :load-options="loadCategoriesList"
                                :options="categories"
                                :disabled="cloningItem==true"
                                :placeholder="app.trans.category"
                                :show-count="true"
                                :value="itemData.categoryId"
                                @select="categoryListUpdated"
                                v-model="itemData.categoryId"
                        ></treeselect>
                    </div>
                </div>
            </div>

            <div class="col-md-1">
                &nbsp;<toggle-button :font-size="14"
                                     :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}"
                                     :sync="true"
                                     :width='70'
                                     @change="categoryNameShouldBeInItemNameToggleUpdated"
                                     v-model="categoryNameShouldBeInItemName">
            </toggle-button>
            </div>

            <div class="col-md-1  text-center">
                <a :href="app.BaseApiUrl + 'categories/create'" target="_blank" v-if="canCreateCategory==1">
                    <button class="btn btn-custom-primary btn-sm"><i class="fa fa-plus-circle"></i></button>
                </a>
            </div>

        </div>


        <div v-bind:key="index" v-for='(filter,index) in filterList'>
            <accounting-filter-select-with-search-component
                    :app.trans="app.trans"
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


        <br>
        <br>
        <div class="form-group text-center">


            <button @click="sendDataToServer('clone')" class="btn btn-custom-primary" v-if="editingItem!=true ||
            cloningItem==1"><i
                    class="fa fa-check-circle"></i> &nbsp;&nbsp;{{ app.trans.save_clone}}
            </button>
            &nbsp;&nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            <button @click="sendDataToServer('exit')" class="btn btn-custom-primary"><i class="fa fa-door-open"></i>&nbsp;&nbsp;
                {{ app.trans.save_exit}}
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


    import {accounting as ItemAccounting, validator as ItemValidator} from '../../item';


    export default {
        components: {
            Treeselect
        },
        props: ["categories", "cloningItem", "editingItem", "editedItemFilters", 'editedItemCategory', 'editedItemData',
            'vendors', 'canCreateCategory', 'canEditFilter', 'canCreateFilter'],
        data: function () {
            return {
                openForEditing: false,
                errorFieldName: "",
                errorFieldMessage: "",
                app: {
                    primaryColor: metaHelper.getContent('primary-color'),
                    secondColor: metaHelper.getContent('second-color'),
                    appLocate: metaHelper.getContent('app-locate'),
                    trans: trans('items-page'),
                    messages: trans('messages'),
                    dateTimeTrans: trans('datetime'),
                    validation: trans('validation'),
                    datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
                    BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
                    defaultVatSaleValue: 5,
                    defaultVatPurchaseValue: 5,
                },
                itemData: {
                    arName: "",
                    enName: "",
                    barcode: "",
                    salesPrice: "",
                    salesPriceWithTax: "",
                    hasVatSale: true,
                    hasVatPurchase: true,
                    isNeedSerial: false,
                    hasFixedPrice: false,
                    isService: false,
                    isExpense: false,
                    vts: 5,
                    vtp: 5,
                    expenseVendorId: 0,
                    categoryId: null
                },
                requestHelperData: {
                    itemsWithSameBarcode: []
                },
                categoryNameShouldBeInItemName: true,
                reusable_translator: null,
                messages: "",
                disableCategorySelection: false,
                parent_item: null,
                error: false,
                errorMessage: '',
                categoriesList: [],
                filterList: [],
                selectedCategory: null,
                selectedFilterValue: new Map(),
                isLoading: true,

            };
        },
        created: function () {
            this.reusable_translator = JSON.parse(window.reusable_translator);
            this.local_config = JSON.parse(window.config);
            if (this.editingItem != null && this.editingItem) {
                this.itemData.arName = this.editedItemData.ar_name;
                this.itemData.enName = this.editedItemData.name;
                this.itemData.barcode = this.editedItemData.barcode;
                this.itemData.salesPrice = this.editedItemData.price;
                this.itemData.salesPriceWithTax = this.editedItemData.price_with_tax;
                this.itemData.hasVatSale = this.editedItemData.is_has_vts;
                this.itemData.has_vat_purchase = this.editedItemData.is_has_vtp;
                this.itemData.isNeedSerial = this.editedItemData.is_need_serial;
                this.itemData.hasFixedPrice = this.editedItemData.is_fixed_price;
                this.itemData.isService = this.editedItemData.is_service;
                this.itemData.isExpense = this.editedItemData.is_expense;
                this.itemData.vtp = this.editedItemData.vtp;
                this.itemData.vts = this.editedItemData.vts;
                this.itemData.expenseVendorId = this.editedItemData.expense_vendor_id;
                this.itemData.categoryId = this.editedItemData.category_id;

                    if (!this.editedItemData.name.includes(this.editedItemCategory.name))
                    {
                        this.categoryNameShouldBeInItemName = false;
                    }
                    //editedItemCategory

                if (this.cloningItem != null && this.cloningItem == 1) {
                    this.itemData.barcode = "";
                    this.itemData.salesPrice = 0;
                    this.itemData.salesPriceWithTax = 0;
                }
                this.categoryListUpdated(this.editedItemCategory, null);
            }
            this.messages = JSON.parse(window.messages);
        },
        methods: {
            // barcode events
            barcodeFieldChanged(e) {

                this.validateBarcode(e.target.value);

            },
            validateBarcode(barcode) {
                var appVm = this;
                axios.get(appVm.app.BaseApiUrl + 'items/helper/validate_barcode', {
                    params: {
                        barcode: barcode
                    }
                })
                    .then(function (response) {
                        if (appVm.errorFieldName == 'barcode')
                            appVm.errorFieldName = '';


                    })
                    .catch(function (error) {
                        appVm.errorFieldName = 'barcode';
                        appVm.errorFieldMessage = error.response.data.message;
                        appVm.itemData.barcode = '';
                    });
            },
            barcodeFieldEnterButtonClicked() {
                if (this.itemData.barcode != "") {
                    if (this.errorFieldName != "barcode") {
                        this.itemData.salesPriceWithTax = '';
                        this.$refs.salesPriceWithTaxFieldRef.focus();
                    }
                }
            },
            //sales price and sales price with tax fields events
            salesPriceFieldUpdated(e) {
                if (this.errorFieldName == 'salesPrice' || this.errorFieldName == 'salesPriceWithTax')
                    this.errorFieldName = '';
                var val = e.target.value;
                if (ItemValidator.validatePriceValue(val))
                    this.itemData.salesPriceWithTax = ItemAccounting.getSalesPriceWithTaxFromSalesPriceAndVat(val, this.itemData.vts);
                else
                    this.itemData.salesPriceWithTax = '';

            },
            salesPriceWithTaxFieldUpdated(e) {
                if (this.errorFieldName == 'salesPrice' || this.errorFieldName == 'salesPriceWithTax')
                    this.errorFieldName = '';
                var val = e.target.value;
                if (ItemValidator.validatePriceValue(val))
                    this.itemData.salesPrice = ItemAccounting.getSalesPriceFromSalesPriceWithTaxAndVat(val, this.itemData.vts);
                else
                    this.itemData.salesPrice = '';

            },
            // toggle buttons events
            vatSaleValueToggleButtonChanged(e) {
                if (this.itemData.hasVatSale == false) {
                    this.itemData.vts = 0;
                } else {

                    if (this.itemData.hasVatSale == 0) {
                        this.itemData.vts = this.app.defaultVatSaleValue;
                    }
                }
            },
            vatPurchaseValueToggleButtonChanged(e) {
                if (this.itemData.hasVatPurchase == false) {
                    this.itemData.vtp = 0;
                } else {

                    if (this.itemData.hasPurchaseSale == 0) {
                        this.itemData.vtp = this.app.defaultVatPurchaseValue;
                    }
                }
            },
            isNeedSerialToggleButtonChanged(e) {
                if (this.itemData.isNeedSerial == true) {
                    this.itemData.isService = false;
                    this.itemData.isExpense = false;
                }

            },
            isServiceToggleButtonChanged(e) {
                if (this.itemData.isService == true) {
                    this.itemData.isNeedSerial = false;
                }
            },
            isExpenseToggleButtonChanged(e) {
                if (this.itemData.isExpense == true) {
                    this.itemData.isNeedSerial = false;
                }
            },
            categoryNameShouldBeInItemNameToggleUpdated() {
                this.rebuildItemName();
            },
            // vats fields events
            vatSaleFieldUpdated(e) {
                if (parseFloat(e.target.value) > 0) {
                    this.itemData.hasVatSale = true;
                }
            },
            vatPurchaseFieldUpdated(e) {
                if (parseFloat(e.target.value) > 0) {
                    this.itemData.hasVatPurchase = true;
                }
            },
            // vendor => expense
            vendorListHasBeenUpdated(e) {
                this.itemData.expenseVendorId = e.value.id;
            },
            categoryListUpdated(node, instanceId) {
                if (this.errorFieldName == 'category') {
                    this.error = '';
                }
                this.selectedFilterValue.clear();
                var appVm = this;
                this.selectedCategory = node;
                this.itemData.categoryId = node.id;

                axios.post(this.app.BaseApiUrl + "categories/view/filters", {
                    categories_ids: [node.id]
                })
                    .then(function (response) {

                        var filters = response.data;
                        var data = [];
                        for (var i = 0; i < filters.length; i++) {
                            var filter = filters[i];
                            filter.is_checked = true;
                            data.push(filter);

                        }
                        appVm.filterList = data;


                        if (appVm.editingItem != null && appVm.editingItem) {
                            appVm.updateSelectedValuesFromParentItem();
                        } else {
                            appVm.rebuildItemName();
                        }
                    })
                    .catch(function (error) {

                    });
            },
            loadCategoriesList(e) {
            },
            rebuildItemName() {
                var arName = "";
                var enName = "";
                if (this.categoryNameShouldBeInItemName) {
                    arName = this.selectedCategory.ar_name;
                    enName = this.selectedCategory.name;
                }

                var len = this.filterList.length;
                for (var i = 0; i < len; i++) {
                    var filter = this.filterList[i];
                    if (filter.is_checked) {
                        if (this.selectedFilterValue.has(filter.id)) {
                            var value_id = this.selectedFilterValue.get(filter.id);
                            if (value_id != 0) {
                                var value_data = helpers.getDataFromArrayById(filter.values, value_id);
                                enName = enName.concat(" " + value_data.name);
                                arName = arName.concat(" " + value_data.ar_name);
                            }

                        }

                    }
                }


                this.itemData.arName = arName;
                this.itemData.enName = enName;


            },
            filterValueListChanged(data) {
                var filterData = this.filterList[data.index];
                this.selectedFilterValue.set(filterData.id, data.value);
                this.rebuildItemName();
            },
            generateBarcode() {
                var barcode = helpers.generateRandomNumberWithSize();
                this.itemData.barcode = barcode;

                this.validateBarcode(barcode);
            },
            validateAllField() {

                if (this.itemData.barcode == "" || this.itemData.barcode.length < 4) {
                    this.errorFieldName = 'barcode';
                    this.errorFieldMessage = this.validation.item.barcode_required;
                    return false;
                }


                if (this.itemData.enName == "") {
                    this.errorFieldName = 'enName';
                    this.errorFieldMessage = this.validation.item.name_required;
                    return false;
                }


                if (this.itemData.arName == "") {
                    this.errorFieldName = 'arName';
                    this.errorFieldMessage = this.validation.item.name_required;
                    return false;
                }


                if (this.itemData.salesPriceWithTax <= 0) {
                    this.errorFieldName = 'salesPriceWithTax';
                    this.errorFieldMessage = this.validation.item.price_with_tax_required;
                    return false;
                }


                if (this.itemData.salesPrice <= 0) {
                    this.errorFieldName = 'salesPrice';
                    this.errorFieldMessage = this.validation.item.price_required;
                    return false;
                }


                if (this.itemData.categoryId == null) {
                    this.errorFieldName = 'categoryId';
                    this.errorFieldMessage = this.validation.item.category_required;
                    return false;
                }


                return true;
            },
            extractAllSelectedFiltersAsKeyValueArray() {
                var data = [];
                this.selectedFilterValue.forEach((value, index) => {
                    if (value != 0) {
                        data[index] = value;
                    }
                });
                return data;
            },
            // when submit form
            sendDataToServer(redirect_to) {
                if (!this.validateAllField()) {
                    return;
                }
                var filters_values = this.extractAllSelectedFiltersAsKeyValueArray();
                var data = {
                    expense_vendor_id: this.itemData.expenseVendorId,
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
                    filters: filters_values
                };
                let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                });
                var appVm = this;

                if (this.editingItem != null && this.editingItem && this.cloningItem != true) {

                    axios.put(this.app.BaseApiUrl + "items/" + this.editedItemData.id, data)
                        .then(function (response) {
                            loader.hide();
                            location.href = appVm.app.BaseApiUrl + 'items';
                        })
                        .catch(function (error) {
                            loader.hide();

                        });

                    // simulate AJAX
                    setTimeout(() => {
                        loader.hide()
                    }, 5000);

                } else {


                    axios.post(this.app.BaseApiUrl + 'items', data)
                        .then(function (response) {
                            loader.hide();
                            appVm.itemData.barcode = "";
                            appVm.itemData.salesPrice = 0;
                            appVm.itemData.salesPriceWithTax = 0;

                            if (redirect_to == 'clone') {
                                appVm.showPopSuccessMessage();
                            } else {
                                location.href = appVm.app.BaseApiUrl + 'items';
                            }

                        })
                        .catch(function (error) {

                            console.log(error);
                            loader.hide();
                            // }
                        });

                    setTimeout(() => {
                        loader.hide()
                    }, 5000);

                }


            },
            updateSelectedValuesFromParentItem() {
                var filterLen = this.editedItemFilters.length;
                for (var i = 0; i < filterLen; i++) {
                    var filter_and_value = this.editedItemFilters[i];
                    this.selectedFilterValue.set(filter_and_value.filter_id, filter_and_value.filter_value);
                }

                this.updateFiltersToggleButtons();

            },
            updateFiltersToggleButtons() {
                var data = [];
                var len = this.filterList.length;
                for (var i = 0; i < len; i++) {
                    var fit = this.filterList[i];
                    if (this.selectedFilterValue.has(fit.id)) {
                        var vid = this.selectedFilterValue.get(fit.id);//value id
                        var value = helpers.getDataFromArrayById(fit.values, vid);
                        if (this.itemData.enName.includes(value.name)) {
                            fit.is_checked = true;
                        } else {
                            fit.is_checked = false;
                        }
                    } else {
                        fit.is_checked = false;
                    }
                    data.push(fit);
                }

                this.filterList = data;
            },
            showPopSuccessMessage() {
                let options = {
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
                };

                this.$dialog
                    .alert(this.app.messages.item_has_been_saved, options)
                    .then(dialog => {


                    })
                    .catch(() => {

                    });
            },


        },


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

</style>
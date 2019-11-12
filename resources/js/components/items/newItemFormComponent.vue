<template>
    <div>
        <div class="message-body">

            <div class="columns">
                <div class="column">
                    <div class="field">
                        <div class="control">
                            <input :class="{'is-danger':error=='item_en_name'}" :placeholder="translator.name_en"
                                   class="input" readonly=""
                                   type='text' v-model="item_en_name">
                        </div>
                        <p class="help is-danger is-center" v-show="error=='item_en_name'" v-text="errorMessage"></p>
                    </div>

                </div>
                <div class="column">
                    <div class="field">
                        <div class="control">
                            <input :class="{'is-danger':error=='item_ar_name'}" :placeholder="translator.name_ar"
                                   class="input"
                                   readonly="" style='direction: rtl;' type='text'
                                   v-model="item_ar_name">
                        </div>
                        <p class="help is-danger is-center" v-show="error=='item_ar_name'" v-text="errorMessage"></p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="columns">
                    <div class="column is-one-quarter">
                        <div class="field">
                            <div class="control">
                                <input :class="{'is-danger':error=='barcode'}" :disabled="isEdit"
                                       :placeholder="translator.barcode" @blur="checkBarcodeIfItAlreadyUsed"
                                       class="input"
                                       type='text'
                                       v-model="item_barcode" v-on:keyup.13="barcodeItemInterClicked">
                            </div>
                            <p class="help is-danger is-center" v-show="error=='barcode'" v-text="errorMessage"></p>
                        </div>
                    </div>
                    <div class="column is-one-fifth" v-if="!isEdit">
                        <button @click="generateBarcode" class="button is-info">{{translator.generate_barcode}}</button>
                    </div>


                    <div class="column">
                        <div class="field">
                            <div class="control">
                                <input :class="{'is-danger':error=='sales_price'}" :placeholder="translator.price"
                                       @keyup="updateSalesPrice"
                                       class="input" type='text'
                                       v-model="sales_price">
                            </div>
                            <p class="help is-danger is-center" v-show="error=='sales_price'" v-text="errorMessage"></p>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <div class="control">
                                <input :class="{'is-danger':error=='sales_inc_price'}"
                                       :placeholder="translator.price_tax" @keyup="updateSalesPriceWithTax"
                                       class="input"
                                       ref="sales_inc_price" type='text'
                                       v-model="sales_inc_price">
                            </div>
                            <p class="help is-danger is-center" v-show="error=='sales_inc_price'"
                               v-text="errorMessage"></p>
                        </div>
                    </div>


                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}" :sync="true"
                                   :width='70' @change="hasVatSaleChanged"
                                   v-model="has_vat_sale"/>
                    <label>{{ translator.has_vat_sale }}</label>
                </div>

                <div class="column">
                    <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}" :sync="true"
                                   :width='70' @change="hasVatPurchaseChanged" v-model="has_vat_purchase"/>
                    <label>{{ translator.has_vat_purchase }}</label>
                </div>


                <div class="column">
                    <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}"
                                   :sync="true" :width='70' @change="hasSerialNumberChanged"
                                   v-model="has_serial_number"/>
                    <label>{{ translator.has_serial_number }}</label>
                </div>


                <div class="column">
                    <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}" :sync="true"
                                   :width='70' @change="hasFixedPriceChanged" v-model="has_fixed_price"/>
                    <label>{{ translator.is_fixed_price }}</label>
                </div>

                <div class="column">
                    <toggle-button :font-size="14" :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}" :sync="true"
                                   :width='70' @change="isServiceChanged" v-model="is_service"/>
                    <label>{{ translator.is_service }}</label>
                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <div class="field">
                        <div class="control">
                            <input :placeholder="translator.vat_sale" @keydown="vatSaleInputUpdated" class="input"
                                   type='text'
                                   v-model="vat_sale">
                        </div>
                        <p class="help is-danger is-center" v-show="error=='vat_sale'" v-text="errorMessage"></p>
                    </div>

                </div>
                <div class="column">
                    <div class="field">
                        <div class="control">
                            <input :placeholder="translator.vat_purchase" @keydown="vatPurchaseInputUpdated"
                                   class="input"
                                   type='text'
                                   v-model="vat_purchase">
                        </div>
                        <p class="help is-danger is-center" v-show="error=='vat_purchase'" v-text="errorMessage"></p>
                    </div>
                </div>
                <div class="column is-three-fifths">
                </div>
            </div>


            <!---->

            <div class="columns">
                <div class="column is-one-fifth">
                    <div class="label has-text-dark" style="font-size: 16px !important;">
                        {{ translator.categories}}
                    </div>

                </div>
                <div class="column is-three-fifths">
                    <div :dir="rtl ? 'rtl' : 'ltr'">
                        <!--                        :show-count="true"-->

                        <treeselect
                                :disabled="disableCategorySelection"
                                :options="categories"
                                :placeholder="translator.category"
                                :value="initCategoryId"
                                @select="categoryUpdated"
                        ></treeselect>
                    </div>

                </div>


                <div class="column">
                    <div class="lable">
                        <toggle-button :font-size="14"
                                       :height='30' :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}"
                                       :sync="true"
                                       :width='70' @change="categoryNameAppearOnItemNameUpdated"
                                       v-model="isCategoryNameEmbededOnItemName">
                        </toggle-button>
                    </div>
                </div>


                <div class="column">
                    <div class="lable">
                        <a class="button is-info" href="/management/categories/create" target="_blank"><i
                                class="fa fa-plus-circle"></i></a>

                    </div>
                </div>

            </div>
            <!---->


            <div v-bind:key="index" v-for='(filter,index) in filterList'>
                <filter-select-with-search-component
                        :default="selectedFilterValue.get(filter.id)"
                        :filter="filter"
                        :index="index"
                        :messages="messages"
                        :options="filter.values"
                        :reusable_translator="reusable_translator"
                        :translator="translator"
                        @updateItemName="updateItemName"
                        v-on:valueUpdated="filterValueListChanged">
                </filter-select-with-search-component>
            </div>


            <hr>
            <div class="form-group">
                <button @click="sendDataToServer('clone')" class="button is-info"><i
                        class="fa fa-check-circle"></i> &nbsp;&nbsp;{{ translator.save_clone}}
                </button>
                &nbsp;
                <button @click="sendDataToServer('exit')" class="button is-link"><i class="fa fa-door-open"></i>&nbsp;&nbsp;
                    {{ translator.save_exit}}
                </button>
                &nbsp;

                <a class="button is-right pull-right" href="/management/items"><i class="fa fa-undo-alt"></i>
                    &nbsp;&nbsp;{{ translator.cancel }}</a>
            </div>


        </div>


    </div>
</template>


<script>

    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'


    export default {
        components: {
            Treeselect
        },
        props: ["categories", "isCloned", "item", "itemFilters", 'itemCategory', 'isEdited'],
        data: function () {
            return {

                rtl: true,
                messages: null,
                translator: null,
                reusable_translator: null,
                item_id: 0,
                disableCategorySelection: false,
                initCategoryId: 'اختر الفئة',
                parent_item: null,
                error: false,
                errorMessage: '',
                item_en_name: null,
                item_ar_name: '',
                item_barcode: '',
                sales_price: '',
                sales_inc_price: '',
                has_vat_sale: true,
                has_vat_purchase: true,
                has_serial_number: false,
                has_fixed_price: false,
                is_service: false,
                main_vat_sale: config.vts,
                main_vat_purchase: config.vts,
                vat_sale: config.vts,
                vat_purchase: config.vts,
                categoriesList: [],
                category: null,
                filterList: [],
                selectedCategory: null,
                isCategoryNameEmbededOnItemName: true,
                selectedFilterValue: new Map(),
                isLoading: true,
                dataRec: null,
                isEdit: false,

            };
        },
        created: function () {

            // alert(this.item.price_with_tax)
            this.translator = JSON.parse(window.translator);
            this.reusable_translator = JSON.parse(window.reusable_translator);
            this.messages = JSON.parse(window.messages);
            if (this.isEdited) {
                this.isEdit = true;
            }

        },


        mounted() {
            if (this.isCloned) {
                this.disableCategorySelection = true;
                this.initCloneProccess();
            }

        }
        ,
        methods: {

            initCloneProccess() {

                if (this.isEdit) {
                    this.item_barcode = this.item.barcode;
                    this.sales_price = this.item.price;
                    this.item_id = this.item.id;
                    // this.sales_inc_price = this.item.price_with_tax;
                }

                //
                this.item_en_name = this.item.name;
                this.item_ar_name = this.item.ar_name;
                this.has_vat_purchase = this.item.is_has_vtp;
                this.has_vat_sale = this.item.is_has_vts;
                this.has_fixed_price = this.item.is_fixed_price;
                this.is_service = this.item.is_service;
                this.has_serial_number = this.item.is_need_serial;

                this.vat_sale = this.item.vts;
                this.vat_purchase = this.item.vtp;
                // console.log(this.categoriesList);
                //var dataRec = null;
                this.selectedCategory = this.itemCategory;
                // console.log(this.selectedCategory);
                // console.log(helpers.getDataFromArrayById(this.categoriesList,1));
                this.initCategoryId = this.item.category_id;
                var vm = this;
                axios.get('/management/categories/filters/' + this.initCategoryId)
                    .then(function (response) {
                        vm.dataRec = response.data;
                        // console.log(vm.dataRec);
                        vm.filterList = response.data;
                        vm.selectFilterValuesOnCloneMode();
                        vm.putOnAndOFBUTTONoNSELECTmode();

                    })
                    .catch(function (error) {

                    });


            },

            barcodeItemInterClicked() {

                if (this.item_barcode != "") {
                    if (this.error != "barcode") {
                        this.sales_inc_price = '';
                        this.$refs.sales_inc_price.focus();
                    }
                }


                // alert('hello
            },
            selectFilterValuesOnCloneMode() {
                // console.log('..selectFilterValuesOnCloneMode ' + this.itemFilters.length  )
                var itemfilterlen = this.itemFilters.length;
                for (var i = 0; i < itemfilterlen; i++) {
                    var filter_and_value = this.itemFilters[i];
                    this.selectedFilterValue.set(filter_and_value.filter_id, filter_and_value.filter_value);

                }

                // console.log(this.selectedFilterValue);

            },
            putOnAndOFBUTTONoNSELECTmode() {
                var data = [];
                var len = this.filterList.length;
                for (var i = 0; i < len; i++) {

                    var fit = this.filterList[i];
                    // console.log(fit);
                    if (this.selectedFilterValue.has(fit.id)) {
                        var vid = this.selectedFilterValue.get(fit.id);//value id

                        var value = helpers.getDataFromArrayById(fit.values, vid);
                        // console.log(value);
                        // console.log(this.item_en_name.includes(value.name));
                        if (this.item_en_name.includes(value.name)) {
                            fit.isChecked = true;
                        } else {
                            fit.isChecked = false;
                        }


                    }

                    data.push(fit);
                }
                this.filterList = data;
            },


            isServiceChanged() {
                if (this.is_service == true) {
                    this.has_serial_number = !this.is_service;
                }
            },

            hasSerialNumberChanged(e) {
                if (this.has_serial_number == true) {
                    this.is_service = !this.has_serial_number;
                }

            },


            hasVatSaleChanged() {
                if (this.has_vat_sale == false) {
                    this.vat_sale = 0;
                } else {

                    if (this.vat_sale == 0) {
                        this.vat_sale = this.main_vat_sale;
                    }
                }
            },


            hasVatPurchaseChanged() {
                if (this.has_vat_purchase == false) {
                    this.vat_purchase = 0;
                } else {

                    if (this.vat_purchase == 0) {
                        this.vat_purchase = this.main_vat_purchase;
                    }
                }
            },

            hasFixedPriceChanged() {

            },

            vatSaleInputUpdated(e) {


                if (e.target.value > 0) {
                    this.has_vat_sale = true;
                }
            },


            vatPurchaseInputUpdated(e) {
                if (e.target.value > 0) {
                    this.has_vat_purchase = true;
                }
            },


            generateBarcode() {
                var barcode = helpers.generateRandomNumberWithSize();
                // console.log(barcode);
                var vm = this;


                if (this.error == 'barcode') {

                    this.error = '';
                }
                axios.post('/management/items/check_barcode_if_exists', {
                    barcode: barcode,
                })
                    .then(function (response) {
                        if (vm.error == 'barcode') {
                            vm.error = '';
                        }
                    })
                    .catch(function (error) {
                        if (error.response.status == 403) {
                            alert('you have no permession to create item');
                        } else {
                            vm.generateBarcode();
                        }
                        //
                        // // console.log(error.response.data.message);
                    });

                vm.item_barcode = barcode;
                if (this.error == 'barcode') {
                    this.error = '';
                }
            },


            updateSalesPrice(e) {
                if (this.error == 'sales_price' || this.error == 'sales_inc_price')
                    this.error = '';

                var val = e.target.value;
                if (!validate.isEmpty(val)) {
                    if (validate.isNumber(parseFloat(val))) {
                        this.sales_inc_price = counting.calcPriceWithTaxFromPrice(val, this.vat_sale);
                    }

                } else {
                    this.sales_inc_price = '';
                }

            },

            updateSalesPriceWithTax(e) {
                if (this.error == 'sales_price' || this.error == 'sales_inc_price')
                    this.error = '';


                var val = e.target.value;
                if (!validate.isEmpty(val)) {
                    if (validate.isNumber(parseFloat(val))) {
                        this.sales_price = counting.calcPriceFromPriceWithTax(val, this.vat_sale);

                    }

                } else {
                    this.sales_price = '';
                }

            },


            checkBarcodeIfItAlreadyUsed(e) {
                var vm = this;
                axios.post('/management/items/check_barcode_if_exists', {
                    barcode: e.target.value,
                })
                    .then(function (response) {
                        if (vm.error == 'barcode')
                            vm.error = '';
                    })
                    .catch(function (error) {
                        vm.error = 'barcode';
                        vm.errorMessage = error.response.data.message;
                        vm.item_barcode = '';
                    });


            },


            categoryUpdated(node, instanceId) {
                if (this.error == 'category') {
                    this.error = '';
                }
                this.selectedFilterValue.clear();
                var vm = this;
                this.selectedCategory = node;
                this.updateItemName();

                // console.log('/management/categories/filters/' + node.id);
                axios.get('/management/categories/filters/' + node.id)
                    .then(function (response) {
                        vm.filterList = response.data;
                    })
                    .catch(function (error) {

                    });

                // $('.select2').select2();

            },


            categoryNameAppearOnItemNameUpdated() {

                this.updateItemName();
            },
            updateItemName() {
                var ar_item_name = "";
                var en_item_name = "";
                if (this.isCategoryNameEmbededOnItemName) {
                    ar_item_name = this.selectedCategory.ar_name;
                    en_item_name = this.selectedCategory.name;
                }
                var len = this.filterList.length;
                for (var i = 0; i < len; i++) {
                    var filter = this.filterList[i];
                    if (filter.isChecked) {
                        if (this.selectedFilterValue.has(filter.id)) {
                            var value_id = this.selectedFilterValue.get(filter.id);
                            if (value_id != 0) {
                                var value_data = helpers.getDataFromArrayById(filter.values, value_id);
                                //// console.log(en_item_name.concat(" " + value_data.name));
                                en_item_name = en_item_name.concat(" " + value_data.name);
                                ar_item_name = ar_item_name.concat(" " + value_data.ar_name);
                            }

                        }

                    }

                }


                // console.log(en_item_name);

                this.item_en_name = en_item_name;
                this.item_ar_name = ar_item_name;


            },


            filterValueListChanged(data) {

                var filterData = this.filterList[data.index];
                this.selectedFilterValue.set(filterData.id, data.value);
                this.updateItemName();
            },


            validateAllField() {

                if (this.item_barcode == "") {
                    this.error = 'barcode';
                    this.errorMessage = 'barcode is required fields please fill it';
                    return false;
                }


                if (this.item_ar_name == "") {
                    this.error = 'item_ar_name';
                    this.errorMessage = 'name is required fields please fill it';
                    return false;
                }


                if (this.item_en_name == "") {
                    this.error = 'item_en_name';
                    this.errorMessage = 'name is required fields please fill it';
                    return false;
                }


                if (this.sales_inc_price <= 0) {
                    this.error = 'sales_inc_price';
                    this.errorMessage = 'sales price is required fields please fill it';
                    return false;
                }


                if (this.sales_price <= 0) {
                    this.error = 'sales_price';
                    this.errorMessage = 'price is required fields please fill it';
                    return false;
                }


                if (this.selectedCategory == null) {
                    this.error = 'category';
                    this.errorMessage = 'category is required fields please fill it';
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
                // return
            },

            // when submit form
            sendDataToServer(redirect_to) {
                if (!this.validateAllField()) {
                    return;
                }

                var filters_values = this.extractAllSelectedFiltersAsKeyValueArray();

                var data = {
                    name: this.item_en_name,
                    ar_name: this.item_ar_name,
                    barcode: this.item_barcode,
                    is_has_vts: this.has_vat_sale,
                    is_has_vtp: this.has_vat_purchase,
                    is_need_serial: this.has_serial_number,
                    is_fixed_price: this.has_fixed_price,
                    is_service: this.is_service,
                    price: this.sales_price,
                    price_with_tax: this.sales_inc_price,
                    category_id: this.selectedCategory.id,
                    vts: this.vat_sale,
                    vtp: this.vat_purchase,
                    filters: filters_values
                };
                let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                });
                var vm = this;

                if (this.isEdit) {

                    axios.put("/management/items/" + this.item_id + "/store/update", data)
                        .then(function (response) {
                            loader.hide();

                            if (redirect_to == 'clone') {
                                vm.item_barcode = "";
                                vm.sales_price = "";
                                vm.sales_inc_price = "";
                                //.vm.showPopSuccessMessage();
                            } else {

                               location.href = '/management/items';
                            }

                            // console.log(response.data);
                        })
                        .catch(function (error) {
                            console.log(error.response.data.errors);
                            if (error.response.data.errors.barcode != null) {

                                loader.hide();
                                // vm.error = 'barcode';
                                // vm.errorMessage = error.response.data.errors.barcode[0];

                            }
                        });

                    // simulate AJAX
                    setTimeout(() => {
                        loader.hide()
                    }, 5000);

                } else {
                    axios.patch("/management/items/store/save", data)
                        .then(function (response) {
                            loader.hide();
                            vm.item_barcode = "";
                            vm.sales_price = "";
                            vm.sales_inc_price = "";
                            if (redirect_to == 'clone') {
                                //.vm.showPopSuccessMessage();
                            } else {

                                location.href = '/management/items';
                            }

                            // console.log(response.data);
                        })
                        .catch(function (error) {
                            console.log(error.response.data.errors);
                            if (error.response.data.errors.barcode != null) {

                                loader.hide();
                                // vm.error = 'barcode';
                                // vm.errorMessage = error.response.data.errors.barcode[0];

                            }
                        });

                    // simulate AJAX
                    setTimeout(() => {
                        loader.hide()
                    }, 5000);

                }


            },

            updateCategoryList() {
                window.Echo.channel('categories')
                    .listen('CategoryCreatedEvent', ({category}) => {
                        //categoriesList
                        var vm = this;
                        axios.get('/management/categories/categories/list/update')
                            .then(function (response) {
                                // console.log(response.data);
                                vm.categoriesList = response.data;
                            })
                            .catch(function (error) {
                                // console.log(error.response.data);
                            });


                    });


            },


            showPopSuccessMessage() {
                this.$dialog
                    .confirm("item has been created successfully", {
                        // And a dialog object will be passed to the then() callback
                    })
                    .then(dialog => {


                    })
                    .catch(() => {

                    });
            },


        },

        watch: {
            vat_sale: function (val, oldVal) {
                if (this.error == 'sales_price' || this.error == 'sales_inc_price')
                    this.error = '';


                if (!validate.isEmpty(val)) {
                    if (validate.isNumber(parseFloat(val))) {
                        if (this.isEdit) {
                            this.sales_inc_price = this.item.price_with_tax;
                            // this.sales_inc_price = 40;
                        } else {
                            this.sales_inc_price = counting.calcPriceWithTaxFromPrice(this.sales_price, val);
                        }

                    }

                } else {

                }
            },

            isCategoryNameEmbededOnItemName: function () {

                //// console.log(this.isCategoryNameEmbededOnItemName);
            }
        }
    }
</script>


<style scoped>
    .vue-treeselect {
        text-align: right !important;
    }
</style>
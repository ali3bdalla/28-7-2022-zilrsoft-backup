<template>
    <div>
        <button class="toggleButton" @click="showFiltersLayout">فلاتر </button>

        <modal :scrollable="true" name="filtersLayoutModal" class="filtersLayoutModal" :adaptive="true" width="100%"
               height="100%">
            <div class="closeBtnClass">
                <i @click="quiteModel" class="fa fa-close"></i> Selected Filters ({{selectedValues.length}})
            </div>
            <div class="container-fluid filters-layout-modal loading-progress" v-if="isSendingApiRequest">
                <circle-spin class="loading" v-show="isSendingApiRequest"></circle-spin>
            </div>
            <div class="container-fluid filters-layout-modal" v-else>
                <!--                <div class="filter-widget" v-if="subcategories.length >= 1">-->
                <!--                    <div class="fw-tags">-->
                <!--                        <a @click="toggleSubCategory(subCategory)" class="hand-mouse"-->
                <!--                           v-for="subCategory in subcategories"-->
                <!--                           :class="{'bg-primary text-white':selectedSubCategoryId === subCategory.id}"-->
                <!--                           :key="subCategory.id"> {{ subCategory.locale_name }}</a>-->
                <!--                    </div>-->
                <!--                </div>-->


                <div class="row">

                    <div class="col-md-6" v-for="filter in filters">
                        <div class="filter-widget">
                            <h4 class="fw-title">
                                <!--                                <input @change="toggleFilterChildrenLayoutAvailability(filter)" type="checkbox"-->
                                <!--                                       :checked="selectedFilters.includes(filter.id)">-->
                                {{ filter.locale_name }}</h4>


                            <!--                            v-show="selectedFilters.includes(filter.id)"-->
                            <div class="fw-brand-check">
                                <div class="row">
                                    <div class="col-md-6 col-6" v-for="val in filter.values" :key="val.id">
                                        <div class="bc-item">
                                            <label :for="'value_' + val.id">
                                                {{ val.locale_name}}
                                                <!--                                                ({{ val.items_count}})-->
                                                <!--                                                @change="toggleSelectedAttributes(val,attr)"-->
                                                <input type="checkbox"
                                                       :id="'value_' + val.id"
                                                       :checked="selectedValues.includes(val.id)"
                                                       @change="toggleValueAvailability(val)"
                                                >
                                                <!--                                                :checked="isInArray(JSON.stringify(getJsonObj(val,attr)))"-->
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6 col-6">
                        <button @click="applyFilters" class="btn btn-primary btn-block applyBtn">Apply</button>

                    </div>
                    <!--                    <div class="col-md-1"></div>-->
                    <div class="col-md-6  col-6">
                        <button @click="resetFilters" class="btn btn-default btn-block resetBtn">Reset</button>

                    </div>
                </div>
                <div class="form-group">
                </div>
            </div>
        </modal>
    </div>
</template>

<script>


    export default {
        props: ['categoryId'],
        name: "searchFiltersComponent",

        data: function () {
            return {
                isSendingApiRequest: false,
                filters: [],
                isSubCategoriesPanelOpen: false,
                selectedSubCategoryId: 0,
                subcategories: [],
                isFilterLayoutOpen: false,
                selectedFilters: [],
                selectedValues: [],
            }
        },
        created() {
            // this.getSubCategories();
            this.getApiFilters();
        },
        methods: {

            quiteModel() {
                this.resetFilters();
                this.closeModel();
            },
            applyFilters() {
                this.$emit('selectedAttributesHasBeenUpdated', {
                    selectedValues: this.selectedValues
                })
                this.closeModel()
            },
            resetFilters() {
                this.selectedSubCategoryId = 0;
                this.selectedValues = [];
                this.selectedFilters = [];
                this.getApiFilters();
            },
            closeModel() {
                this.$modal.hide('filtersLayoutModal');
            },
            toggleSubCategoriesPanel() {
                this.isSubCategoriesPanelOpen = !this.isSubCategoriesPanelOpen;
            },
            getSelectedCategory() {
                return this.selectedSubCategoryId === 0 ? this.categoryId : this.selectedSubCategoryId;
            },

            getSelectedFiltersMap() {
                return this.selectedFilters;
            },


            getSelectedValues() {
                return this.selectedValues;
            },

            getApiFilters() {
                this.isSendingApiRequest = true;
                let appVm = this;
                console.log({
                    'filters': this.getSelectedFiltersMap(),
                    'values': this.getSelectedValues(),
                    'category_id': this.getSelectedCategory()
                });
                axios.post(getRequestUrl(`filters`), {
                    'filters': this.getSelectedFiltersMap(),
                    'values': this.getSelectedValues(),
                    'category_id': this.getSelectedCategory()
                }).then(function (response) {
                    appVm.handleGetFiltersResponse(response.data);

                }).catch(function (error) {
                    alert(`server error : ${error}`);
                }).finally(function () {
                    appVm.isSendingApiRequest = false;
                });
            },
            handleGetFiltersResponse(data = []) {
                let tempSelectedValuesArray = [];
                this.filters = data;
                for (let i = 0; i < data.length; i++) {
                    let filter = data[i];
                    for (let j = 0; j < filter.values.length; j++) {
                        if (this.selectedValues.includes(filter.values[j].id))
                            tempSelectedValuesArray.push(filter.values[j].id);
                    }
                }


                this.selectedValues = tempSelectedValuesArray;
            },

            async toggleFilterChildrenLayoutAvailability(filter) {
                let filterId = filter.id;
                if (!this.selectedFilters.includes(filterId)) {
                    await this.selectedFilters.push(filterId);

                } else {
                    await this.selectedFilters.splice(this.selectedFilters.indexOf(filterId), 1);
                }

            },


            async toggleValueAvailability(value) {
                let valueId = value.id;
                if (!this.selectedValues.includes(valueId)) {
                    await this.selectedValues.push(valueId);

                } else {
                    await this.selectedValues.splice(this.selectedFilters.indexOf(valueId), 1);
                }

                this.getApiFilters();
            },


            showFiltersLayout() {
                this.$modal.show('filtersLayoutModal');
            },

            toggleSubCategory(subCategory) {
                if (subCategory.id === this.selectedSubCategoryId)
                    this.selectedSubCategoryId = 0;
                else
                    this.selectedSubCategoryId = subCategory.id;


                this.$emit('subCategoryHasBeenUpdated', {
                    selectedSubCategoryId: this.selectedSubCategoryId
                });
                this.getApiFilters();
            },
            getSubCategories() {
                let appVm = this;
                axios.get(getRequestUrl(`subcategories/${this.categoryId}`)).then(function (response) {
                    appVm.subcategories = response.data;
                }).catch(function (error) {
                    alert(`server error : ${error}`);
                }).finally(function () {
                });
            },

        }
    }
</script>



<style>
    .v--modal {
        overflow: scroll !important;
    }
</style>
<style scoped>



    .filter-widget {
        margin-bottom: 45px;
    }

    .filter-widget .fw-title {
        color: #252525;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 26px;
    }

    .filter-widget .filter-catagories li {
        list-style: none;
    }

    .filter-widget .filter-catagories li a {
        display: inline-block;
        color: #636363;
        font-size: 16px;
        line-height: 39px;
    }

    .filter-widget .fw-brand-check .bc-item {
        margin-bottom: 3px;
    }

    .filter-widget .fw-brand-check .bc-item label {
        position: relative;
        cursor: pointer;
        padding-left: 28px;
    }

    .filter-widget .fw-brand-check .bc-item label input {
        position: absolute;
        visibility: hidden;
    }

    .filter-widget .fw-brand-check .bc-item label input:checked~span {
        background: #e7ab3c;
        border-color: #e7ab3c;
    }

    .filter-widget .fw-brand-check .bc-item label .checkmark {
        position: absolute;
        left: 0;
        top: 5px;
        height: 15px;
        width: 15px;
        border: 2px solid #ebebeb;
        border-radius: 2px;
    }

    .filter-widget .fw-brand-check .bc-item label .checkmark:after {
        left: 0;
        top: 0;
        width: 10px;
        height: 8px;
        border: solid white;
        border-width: 3px 3px 0px 0px;
        -webkit-transform: rotate(127deg);
        -ms-transform: rotate(127deg);
        transform: rotate(127deg);
    }

    .filter-widget .filter-range-wrap {
        margin-bottom: 40px;
    }

    .filter-widget .filter-range-wrap .range-slider {
        margin-bottom: 25px;
    }

    .filter-widget .filter-range-wrap .range-slider .price-input {
        position: relative;
    }

    .filter-widget .filter-range-wrap .range-slider .price-input:after {
        position: absolute;
        left: 58px;
        top: 13px;
        height: 1px;
        width: 17px;
        background: #ebebeb;
        content: "";
    }

    .filter-widget .filter-range-wrap .range-slider .price-input input {
        font-size: 16px;
        color: #252525;
        max-width: 20%;
        text-align: center;
        border: 1px solid #ebebeb;
        border-radius: 2px;
    }

    .filter-widget .filter-range-wrap .range-slider .price-input input:nth-child(1) {
        margin-right: 28px;
    }

    .filter-widget .filter-range-wrap .price-range {
        border-radius: 0;
    }

    .filter-widget .filter-range-wrap .price-range.ui-widget-content {
        border: none;
        background: #ebebeb;
        height: 3px;
    }

    .filter-widget .filter-range-wrap .price-range.ui-widget-content .ui-slider-handle {
        height: 16px;
        width: 16px;
        border-radius: 50%;
        background: #ffffff;
        border: none;
        -webkit-box-shadow: 1px 6px 14px -4px #425c6d;
        box-shadow: 1px 6px 14px -4px #425c6d;
        outline: none;
    }

    .filter-widget .filter-range-wrap .price-range .ui-slider-range {
        background: #ebebeb;
        border-radius: 0;
    }

    .filter-widget .filter-range-wrap .price-range .ui-slider-range.ui-corner-all.ui-widget-header:last-child {
        background: #e7ab3c;
    }

    .filter-widget .filter-btn {
        font-size: 14px;
        color: #ffffff;
        font-weight: 700;
        background: #e7ab3c;
        padding: 7px 20px 5px;
        border-radius: 2px;
        display: inline-block;
        text-transform: uppercase;
    }

    .filter-widget .fw-color-choose .cs-item {
        width: 50%;
        float: left;
        margin-bottom: 4px;
    }

    .filter-widget .fw-color-choose .cs-item input {
        position: absolute;
        visibility: hidden;
    }

    .filter-widget .fw-color-choose .cs-item label {
        cursor: pointer;
        position: relative;
        padding-left: 33px;
        font-size: 16px;
        color: #636363;
    }

    .filter-widget .fw-color-choose .cs-item label.cs-violet:before {
        background: #8230E3;
    }

    .filter-widget .fw-color-choose .cs-item label.cs-blue:before {
        background: #2773BE;
    }

    .filter-widget .fw-color-choose .cs-item label.cs-yellow:before {
        background: #EEEE21;
    }

    .filter-widget .fw-color-choose .cs-item label.cs-red:before {
        background: #DC3232;
    }

    .filter-widget .fw-color-choose .cs-item label.cs-green:before {
        background: #81D742;
    }

    .filter-widget .fw-color-choose .cs-item label:before {
        position: absolute;
        left: 0;
        top: 4px;
        height: 18px;
        width: 18px;
        background: #252525;
        border-radius: 50%;
        content: "";
    }

    .filter-widget .fw-size-choose .sc-item {
        display: inline-block;
        margin-right: 5px;
    }

    .filter-widget .fw-size-choose .sc-item:last-child {
        margin-right: 0;
    }

    .filter-widget .fw-size-choose .sc-item input {
        position: absolute;
        visibility: hidden;
    }

    .filter-widget .fw-size-choose .sc-item label {
        font-size: 16px;
        color: #252525;
        font-weight: 700;
        height: 40px;
        width: 47px;
        border: 1px solid #ebebeb;
        text-align: center;
        line-height: 40px;
        text-transform: uppercase;
        cursor: pointer;
    }

    .filter-widget .fw-size-choose .sc-item label.active {
        background: #252525;
        color: #ffffff;
    }

    .filter-widget .fw-tags a {
        display: inline-block;
        font-size: 16px;
        color: #636363;
        padding: 5px 15px;
        border: 1px solid #ebebeb;
        margin-right: 5px;
        margin-bottom: 9px;
        border-radius: 2px;
    }


    .v--modal-overlay {
        z-index: 110000;
    }

    .filters-layout-modal {
        padding-top: 20px;
    }


    .toggleButton {
        font-size: 20px;
        color: #888888;
        margin-right: 28px;
        position: relative;
        background: none;
        border: none;
        /*text-decoration: underline;*/
        display: flex;
        padding-top: 4px;

    }

    .applyBtn {
        height: 55px;
        border-radius: 17px;
        box-shadow: 1px 5px 7px #c1baba;
    }

    .resetBtn {
        height: 55px;
        border-radius: 17px;
        box-shadow: 2px 1px 7px #c1baba;
    }

    .fw-title {
        /*color: #777;*/
        color: #252424;


        font-size: 16px;
        text-transform: lowercase;
        border-bottom: 1px solid #e8e0e0;
        padding-bottom: 10px;
        margin-bottom: 10px;
        margin-top: 20px;
    }

    .fw-brand-check {
        padding-left: 20px;
    }

    .container-fluid .filters-layout-modal .filter-widget {
        margin-bottom: 10px
    }

    .loading-progress {
        padding-top: 10%;
    }

    .closeBtnClass {
        padding: 17px;
        padding-bottom: 0px;
        font-size: 19px;
        font-weight: bold;
    }

    .closeBtnClass i {
        font-size: 22px;
        color: #777;
        margin: 6px;
        border: 1px solid #eee;
        padding: 8px;
        border-radius: 50%;
        box-shadow: 0px 2px 5px #ddd;
        cursor: pointer;
    }


</style>


<!--                <div class="filter-widget">-->
<!--                    <h4 class="fw-title">Price</h4>-->
<!--                    <div class="filter-range-wrap">-->
<!--                        <div class="range-slider">-->
<!--                            <div class="price-input">-->
<!--                                <input type="text" id="minamount">-->
<!--                                <input type="text" id="maxamount">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" data-min="33" data-max="98">-->
<!--                            <div class="ui-slider-range ui-corner-all ui-widget-header"></div>-->
<!--                            <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 0%;"></span>-->
<!--                            <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 100%;"></span>-->
<!--                            <div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 0%; width: 100%;"></div></div>-->
<!--                    </div>-->
<!--                    <a href="#" class="filter-btn">Filter</a>-->
<!--                </div>-->
<!--                    <div class="col-md-6" v-if="subcategories.length >= 1">-->
<!--                        <div class="filter-widget">-->
<!--                            <h4 class="fw-title">-->
<!--                                <input  type="checkbox" @change="toggleSubCategoriesPanel" :checked="isSubCategoriesPanelOpen"> Types</h4>-->

<!--                            <div class="fw-brand-check" v-show="isSubCategoriesPanelOpen">-->
<!--                                <div class="row">-->
<!--                                    <div class="col-md-6 col-6" v-for="subCategory in subcategories" :key="subCategory.id">-->
<!--                                        <div class="bc-item">-->
<!--                                            <label :for="'category_value_' + subCategory.id">-->
<!--                                                {{ subCategory.locale_name}}-->
<!--                                                <input @click="toggleSubCategory(subCategory)" type="checkbox"-->
<!--                                                       :id="'category_value_' + subCategory.id"-->
<!--                                                       :checked="selectedSubCategoryId === subCategory.id">-->
<!--                                                <span class="checkmark"></span>-->
<!--                                            </label>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                        </div>-->
<!--                    </div>-->
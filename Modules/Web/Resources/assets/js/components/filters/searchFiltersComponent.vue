<template>
    <div>
        <button class="toggleButton" @click="showFiltersLayout">Filters</button>


        <modal :scrollable="true" name="filtersLayoutModal" class="filtersLayoutModal" :adaptive="true" width="95%"
               height="90%">
            <div class="container-fluid filters-layout-modal">


                <div class="filter-widget" v-if="subcategories.length >= 1">
                    <div class="fw-tags">
                        <a @click="toggleSubCategory(subCategory)" class="hand-mouse"
                           v-for="subCategory in subcategories"
                           :class="{'bg-primary text-white':selectedSubCategoryId === subCategory.id}"
                           :key="subCategory.id"> {{ subCategory.locale_name }}</a>
                    </div>
                </div>


                <div class="row">

                    <div class="col-md-6" v-for="attr in attributes" v-if="isLoaded">
                        <div class="filter-widget">
                            <h4 class="fw-title">
                                <input @change="toggleFilterChildrenLayoutAvailability(attr)" type="checkbox"
                                       :checked="attr.openChildrenLayout">
                                {{ attr.locale_name }}</h4>

                            <div class="fw-brand-check" v-show="attr.openChildrenLayout ">
                                <circle-spin class="loading" v-show="isCompleteLoadFilterValues && loadingFilterId==attr.id"></circle-spin>
                                <div class="row" v-show="!isCompleteLoadFilterValues">
                                    <div class="col-md-6 col-6" v-for="val in attr.values" :key="val.id" v-show="val.items_count >= 1">
                                        <div class="bc-item">
                                            <label :for="'value_' + val.id">
                                                {{ val.locale_name}} ({{ val.items_count}})
                                                <input @change="toggleSelectedAttributes(val,attr)" type="checkbox"
                                                       :id="'value_' + val.id"
                                                       :checked="isInArray(JSON.stringify(getJsonObj(val,attr)))">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <button @click="closeModel" class="btn btn-primary btn-block applyBtn">Apply</button>
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
                loadingFilterId:0,
                isCompleteLoadFilterValues:false,
                isSubCategoriesPanelOpen: false,
                isFilterLayoutOpen: false,
                selectedSubCategoryId: 0,
                subcategories: [],
                isLoaded: true,
                selectedAttributes: [],
                attributes: [],
                selectedFiltersMap:Map,
            }
        },
        created() {
            this.getFilters();
            this.getSubCategories();
        },
        methods: {

            closeModel()
            {
                this.$modal.hide('filtersLayoutModal');
            },
            toggleSubCategoriesPanel()
            {
                this.isSubCategoriesPanelOpen = !this.isSubCategoriesPanelOpen;
            },
            toggleFilterChildrenLayoutAvailability(filter) {
                this.isCompleteLoadFilterValues = !filter.openChildrenLayout;
                let freshFilter = filter;
                this.loadingFilterId = filter.id;
                filter.openChildrenLayout = !filter.openChildrenLayout;
                this.attributes.splice(window.getIndex(freshFilter, this.attributes), 1, filter)
                // if(filter.openChildrenLayout)
                //   this.getFilterValues(filter);
            },

            getSelectedFilters()
            {
                console.log(this.selectedAttributes);
                return this.selectedAttributes;
            },
            getFilterValues(filter)
            {
                let freshFilter = filter;
                let appVm = this;
                axios.post(getRequestUrl(`filters/values`), {
                    'category_id': this.getCategoryId(),
                    'filters': this.getSelectedFilters()
                }).then(function (response) {
                    console.log(response.data);
                    // filter.values = response.data;
                    // appVm.attributes.splice(window.getIndex(freshFilter, appVm.attributes), 1, filter)
                    appVm.isCompleteLoadFilterValues = !filter.openChildrenLayout;

                }).catch(function (error) {
                    alert(`server error : ${error}`);
                }).finally(function () {
                });
            },
            showFiltersLayout() {
                this.$modal.show('filtersLayoutModal');
            },


            getCategoryId()
            {
               return  this.selectedSubCategoryId === 0 ? this.categoryId : this.selectedSubCategoryId;
            },
            toggleSubCategory(subCategory) {
                if (subCategory.id === this.selectedSubCategoryId)
                    this.selectedSubCategoryId = 0;
                else
                    this.selectedSubCategoryId = subCategory.id;


                this.$emit('subCategoryHasBeenUpdated', {
                    selectedSubCategoryId: this.selectedSubCategoryId
                });
                this.getFilters(this.selectedSubCategoryId);
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
            getJsonObj(value, attr) {
                return {
                    filter_id: attr.id,
                    value_id: value.id,
                }
            },
            isInArray(obj) {
                return window.inArray(obj, this.selectedAttributes);
            },
            getFilters(categoryId = 0) {
                categoryId = categoryId === 0 ? this.categoryId : categoryId;
                let appVm = this;
                axios.get(getRequestUrl(`filters?category_id=${categoryId}`)).then(function (response) {
                    appVm.attributes = response.data;
                }).catch(function (error) {
                    alert(`server error : ${error}`);
                }).finally(function () {
                });
            },

            toggleSelectedAttributes(value, attr) {
                let obj = this.getJsonObj(value, attr);
                this.isLoaded = false;


                if (this.isInArray(JSON.stringify(obj))) {
                    let index = window.getIndex(JSON.stringify(obj), this.selectedAttributes);
                    this.selectedAttributes.splice(index, 1);
                } else {
                    this.selectedAttributes.push(JSON.stringify(obj));
                }


                this.$emit('selectedAttributesHasBeenUpdated', {
                    selectedAttributes: this.selectedAttributes
                });

                this.isLoaded = true;
            }
        }
    }
</script>

<style scoped>
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

    .fw-title {
        color: #777;
        font-size: 16px;
        text-transform: lowercase;
    }

    .container-fluid .filters-layout-modal .filter-widget {
        margin-bottom:10px
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
<template>
    <section class="product-shop spad">
        <div class="">
            <div class="row">

<!--                <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1 produts-sidebar-filter">-->
<!--                    <search-filters-component  @subCategoryHasBeenUpdated="subCategoryHasBeenUpdated" :category-id="categoryId" @selectedAttributesHasBeenUpdated="selectedAttributesHasBeenUpdated" @priceFilterRangeHasBeenUpdated="priceFilterRangeHasBeenUpdated"></search-filters-component>-->
<!--                </div>-->

<!--                -->
                <!--                order-1 order-lg-2-->
                <div class="col-lg-12">
                    <div class="product-show-option">
                        <div class="row">
                            <div class="col-4 text-left">
                                <search-filters-component  @subCategoryHasBeenUpdated="subCategoryHasBeenUpdated" :category-id="categoryId" @selectedAttributesHasBeenUpdated="selectedAttributesHasBeenUpdated" @priceFilterRangeHasBeenUpdated="priceFilterRangeHasBeenUpdated"></search-filters-component>
                            </div>
                            <div class="col-8 text-right">
                                <p>Show 01- 09 Of 36 Product</p>
                            </div>
                        </div>
                    </div>
                    <div class="product-list">
                        <div class="row">
                            <div class="col-md-4 col-sm-12 col-12 col-xs-12" v-for="item in items" :key="item.id" >
                                <cell-item-component :item="item"></cell-item-component>
                            </div>
                        </div>
                    </div>
                    <div class="loading-more" v-show="showLoading">
                        <circle-spin class="loading"></circle-spin>
<!--                        <a @click="getNextPage">-->
<!--                            Loading More-->
<!--                        </a>-->
                    </div>
                </div>



            </div>
        </div>
    </section>
</template>

<script>
    const qs = require('qs');

    export default {
        props:['categoryId'],
        name: "gridItemsCollectionComponent",
        data:function() {
          return {
                selectedSubCategoryId:0,
                priceRange:{},
                currentPage:1,
                lastPage:1,
                showLoading:false,
                items:[],
                attributes:[]
          };
        },
        created:function() {
            this.getItems();
        },

        methods: {
            getNextPage()
            {
                if(this.currentPage < this.lastPage)
                {
                    this.currentPage++;
                    this.getItems();
                }
            },
            getItems()
            {
                if(!this.showLoading)
                {
                    let categoryId = this.selectedSubCategoryId === 0 ? this.categoryId : this.selectedSubCategoryId;
                    this.showLoading = true;
                    let appVm = this;

                    console.log(this.attributes);
                    axios.post(getRequestUrl('items'),{
                        page:this.currentPage,
                        category_id: categoryId,
                        attributes: this.attributes,
                    }).then(function(response){
                        let data = response.data.data;
                        data.forEach(function (item) {
                            appVm.items.push(item);
                        })
                        appVm.lastPage = response.data.last_page;
                        appVm.currentPage = response.data.current_page;
                    }).catch(function(error) {
                        alert(`server error : ${error}`);
                    }).finally(function () {
                        appVm.showLoading = false;
                    });
                }

            },
            selectedAttributesHasBeenUpdated(event)
            {
                this.items = [];
                this.attributes = event.selectedValues;
                this.getItems();
            },

            priceFilterRangeHasBeenUpdated(event)
            {
                this.items = [];
                this.priceRange = event.priceRange;
                this.getItems();
            },


            subCategoryHasBeenUpdated(event)
            {
                this.selectedSubCategoryId = event.selectedSubCategoryId;
                this.items = [];
                this.getItems();
            }

        }
    }
</script>

<style scoped>
    .product-shop {
        padding-top: 10px;
    }
</style>
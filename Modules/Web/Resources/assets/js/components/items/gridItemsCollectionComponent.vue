<template>
    <section class="product-shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1 produts-sidebar-filter">
                    <search-filters-component :category-id="categoryId" @selectedAttributesHasBeenUpdated="selectedAttributesHasBeenUpdated" @priceFilterRangeHasBeenUpdated="priceFilterRangeHasBeenUpdated"></search-filters-component>
                </div>
                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="product-show-option">
                        <div class="row">
                            <div class="col-lg-7 col-md-7">
                                <div class="select-option">
                                    <select class="sorting" style="display: none;">
                                        <option value="">Default Sorting</option>
                                    </select><div class="nice-select sorting" tabindex="0"><span class="current">Default Sorting</span><ul class="list"><li data-value="" class="option selected">Default Sorting</li></ul></div>
                                    <select class="p-show" style="display: none;">
                                        <option value="">Show:</option>
                                    </select><div class="nice-select p-show" tabindex="0"><span class="current">Show:</span><ul class="list"><li data-value="" class="option selected">Show:</li></ul></div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 text-right">
                                <p>Show 01- 09 Of 36 Product</p>
                            </div>
                        </div>
                    </div>
                    <div class="product-list">
                        <div class="row">
                            <cell-item-component v-for="item in items" :key="item.id" :item="item"></cell-item-component>
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
                    this.showLoading = true;
                    let appVm = this;

                    axios.post(getRequestUrl('items'),{
                        page:this.currentPage,
                        category_id: this.categoryId,
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
                this.attributes = event.selectedAttributes;
                this.getItems();
            },

            priceFilterRangeHasBeenUpdated(event)
            {
                this.items = [];
                this.priceRange = event.priceRange;
                this.getItems();
            },


        }
    }
</script>

<style scoped>

</style>
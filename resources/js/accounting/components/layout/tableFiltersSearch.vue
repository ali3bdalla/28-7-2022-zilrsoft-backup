<template>
    <div class="">
        <a @click="openOrClosePanel" style="cursor: pointer">
            بحث متقدم <i class="fa fa-arrow-down" v-show="isOpened==false"></i>
            <i class="fa fa-arrow-up" v-show="isOpened==true"></i>
        </a>
        <div class="card" v-show="isOpened">
            <div class="row">

                <div class="col-md-3">
                    <accounting-select-with-search-layout-component
                            :identity="10023749872394"
                            :options="categories"
                            :placeholder="trans.category"
                            :title="trans.category"
                            @valueUpdated="categoryValueUpdated"
                            default="0"
                            label_text="locale_name"
                    >
                    </accounting-select-with-search-layout-component>

                </div>

                <div :key="filter.id" class="col-md-3" v-for="filter in filters">


                    <accounting-select-with-search-layout-component
                            :helper-value="filter.id"
                            :identity="filter.id"
                            :options="filter.values"
                            :placeholder="filter.locale_name"
                            :title="filter.locale_name"
                            @valueUpdated="filterValueUpdated"
                            default="0"
                            label_text="locale_name"
                    >
                    </accounting-select-with-search-layout-component>

                </div>


            </div>
        </div>
    </div>
</template>
<script>

    export default {
        props: ["categories", "trans"],
        data: function () {
            return {
                filtersListLen: 0,
                isOpened: false,
                filters: [],
                categoryId: 0,
                BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
            };
        },
        methods: {
            openOrClosePanel() {
                this.isOpened = !this.isOpened;
            },
            sendServerRequest() {

                if (this.categoryId == 0) {
                    this.filters = [];
                    this.notifyTableWithValuesUpdate();
                    return;
                }
                var appVm = this;
                appVm.filters = [];
                axios.post(this.BaseApiUrl + 'categories/view/filters', {
                    categories_ids: [this.categoryId]
                })
                    .then(function (response) {
                        var filters = response.data;
                        appVm.filtersListLen = filters.length;
                        for (var i = 0; i < appVm.filtersListLen; i++) {
                            var filter = filters[i];
                            filter.value_id = 0;
                            appVm.filters.push(filter);

                        }
                        appVm.notifyTableWithValuesUpdate();

                    })
                    .catch(function (error) {
                    });
            },

            notifyTableWithValuesUpdate() {
                var searchFilters = [];
                for (var i = 0; i < this.filtersListLen; i++) {
                    var filterRow = this.filters[i];
                    if (filterRow.value_id >= 1) {
                        var search_filter = {
                            filter_id: filterRow.id,
                            value_id: filterRow.value_id,
                        };
                        searchFilters.push(search_filter);

                    }
                }


                this.$emit("filterValuesUpdated", {
                    searchFilters: searchFilters,
                    categoryId: this.categoryId
                });
            },
            filterValueUpdated(e) {
                var new_filters = [];

                if (e.value.id == 0) {
                    var filter_id = e.helperValue;
                } else {
                    var filter_id = e.value.filter_id;
                }
                for (var i = 0; i < this.filtersListLen; i++) {
                    var filterRow = this.filters[i];
                    if (filterRow.id == filter_id) {
                        filterRow.value_id = e.value.id;
                    }

                    new_filters.push(filterRow);
                }

                this.filters = new_filters;
                this.notifyTableWithValuesUpdate();

            },
            categoryValueUpdated(e) {
                this.categoryId = e.value.id;
                this.sendServerRequest();
            }

        }
    }
</script>

<style>


    input[type=text],
    input[type=number],
    select {
        height: 42px;
    }
</style>
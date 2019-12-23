<template>
    <div class="">
        <a @click="openOrClosePanel" style="cursor: pointer">
            بحث متقدم <i class="fa fa-arrow-down" v-show="isOpened==false"></i>
            <i class="fa fa-arrow-up" v-show="isOpened==true"></i>
        </a>
        <div class="card" v-show="isOpened">
            <div class="row">

                <div class="col-md-3">
                    <accounting-multi-select-with-search-layout-component
                            :options="categories"
                            :placeholder="trans.category"
                            :title="trans.category"
                            @valueUpdated="categoryValueUpdated"
                            default="0"
                            identity="000000000"
                            label_text="locale_name"
                    >
                    </accounting-multi-select-with-search-layout-component>

                </div>

                <div :key="filter.id" class="col-md-3" v-for="filter in filters">


                    <accounting-multi-select-with-search-layout-component
                            :helper-value="filter.id"
                            :identity="filter.id"
                            :options="filter.values"
                            :placeholder="filter.locale_name"
                            :title="filter.locale_name"
                            @valueUpdated="filterMultiValueUpdated"
                            default="0"
                            label_text="locale_name"
                    >
                    </accounting-multi-select-with-search-layout-component>

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
                isOpened: true,
                filtersIds: [],
                filters: [],
                categoryIds: [],
                BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
            };
        },
        methods: {
            openOrClosePanel() {
                this.isOpened = !this.isOpened;
            },
            sendServerRequest() {

                if (this.categoryIds == []) {
                    this.filters = [];
                    this.notifyTableWithValuesUpdate();
                    return;
                }
                var appVm = this;
                appVm.filters = [];
                axios.post(this.BaseApiUrl + 'categories/view/filters', {
                    categories_ids: this.categoryIds
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

                this.$emit("filterValuesUpdated", {
                    searchFilters: this.filtersIds,
                    categoryIds: this.categoryIds
                });
            },
            filterMultiValueUpdated(e) {
                var fresh_data = [];
                for (var i = 0; i < e.items.length; i++) {
                    var filterRow = e.items[i];
                    fresh_data.push({
                        filter_id: filterRow.filter_id,
                        value_id: filterRow.id,
                    });
                }

                this.filtersIds = fresh_data;
                this.notifyTableWithValuesUpdate();
            },

            categoryValueUpdated(e) {
                var items = [];
                for (var i = 0; i < e.items.length; i++) {
                    items.push(e.items[i].id);

                }
                this.categoryIds = items;
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
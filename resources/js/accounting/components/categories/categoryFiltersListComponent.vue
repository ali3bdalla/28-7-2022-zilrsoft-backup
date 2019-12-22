<template>
    <div class="panel">
        <div class="panel-heading">
            <button @click="sendDataToServer" class="btn btn-custom-primary"><i
                    class='fa fa-save'></i>&nbsp; {{app.trans.update}}
            </button>
            &nbsp;
            <a :href="app.BaseApiUrl + 'filters/create'" class="btn btn-default" target="_blank" v-if="canCreateFilter">
                <i class='fa fa-plus-circle'></i>
                &nbsp;
                {{app.trans.create_filter}}</a>
        </div>
        <div class="panel-body">
            <div class="text-center">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input :placeholder="app.trans.search_on_it_filters" @keyup="searchOnCategoryFilter"
                                   class="form-control"
                                   type="text" v-model="search_keyword_in_category_only">
                        </div>
                        <draggable v-model="category_filters">

                            <transition-group class='group'>
                                <div :key="filter.id" @click="removeFromCategoryFilters(filter,index)"
                                     class="single-item-group has-background-light"
                                     v-for="(filter,index) in category_filters">
                                    {{filter.locale_name}}
                                </div>
                            </transition-group>
                        </draggable>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input
                                    :placeholder="app.trans.search_on_all"
                                    @keyup="searchOnAllFilter" class="form-control"
                                    type="text"
                                    v-model='search_keyword_in_all'>
                        </div>
                        <draggable v-model="all_filters">

                            <transition-group class='group'>
                                <div :key="filter.id"
                                     @click="addFromCategoryFilters(filter,index)"
                                     class="single-item-group has-background-light"
                                     v-for="(filter,index) in all_filters">
                                    {{filter.locale_name}}
                                </div>
                            </transition-group>
                        </draggable>

                    </div>
                </div>
            </div>

        </div>
    </div>
</template>


<script type="text/javascript">
    import draggable from 'vuedraggable'

    export default {
        components: {
            draggable,
        },
        props: ['sys_filters', 'cat_filters', 'category', 'canCreateFilter'],
        data: function () {
            return {
                app: {
                    primaryColor: metaHelper.getContent('primary-color'),
                    secondColor: metaHelper.getContent('second-color'),
                    appLocate: metaHelper.getContent('app-locate'),
                    trans: trans('categories-page'),
                    messages: trans('messages'),
                    dateTimeTrans: trans('datetime'),
                    datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
                    BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
                },
                search_keyword_in_all: '',
                search_keyword_in_category_only: '',
                category_filters: [],
                all_filter: []
            };
        },
        created: function () {
            this.category_filters = this.cat_filters;
            this.all_filters = this.sys_filters;
        },
        methods: {

            searchOnCategoryFilter() {
                this.category_filters = this.cat_filters.filter(item => {
                    return item['name'].indexOf(this.search_keyword_in_category_only) > -1 || item['ar_name'].indexOf(this.search_keyword_in_category_only) > -1;
                });

            },
            searchOnAllFilter() {
                if (this.search_keyword_in_all == "") {
                    this.all_filters = this.sys_filters;
                    return;
                }
                this.all_filters = this.sys_filters.filter(item => {
                    return item['name'].indexOf(this.search_keyword_in_all) > -1 || item['ar_name'].indexOf(this.search_keyword_in_all) > -1;
                });

                // console.log(this.category_filters);
            },
            removeFromCategoryFilters(filter, index) {
                // console.log(index);
                this.category_filters.splice(index, 1);
                this.all_filters.push(filter);
            },


            addFromCategoryFilters(filter, index) {
                // console.log(index);
                // this.all_filters.splice(this.all_filters.indexOf(filter),1);
                this.all_filters.splice(index, 1);
                this.category_filters.push(filter);
                // console.log(this.category_filters);
            },


            sendDataToServer() {

                var vm = this;
                axios.patch(this.app.BaseApiUrl + 'categories/' + this.category.id + '/filters', this.category_filters)
                    .then(function (response) {
                        vm.showPopMessage('تم تحديث البيانات بنجاح');

                    })
                    .catch(function (error) {

                    });

            },


            showPopMessage(message) {

                let options = {
                    html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
                    loader: false, // set to true if you want the dailog to show a loader after click on "proceed"
                    reverse: false, // switch the button positions (left to right, and vise versa)
                    okText: this.app.messages.ok_button,
                    cancelText: 'Close',
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                    verification: 'continue', // for hard confirm, user will be prompted to type this to enable the proceed button
                    verificationHelp: 'Type "[+:verification]" below to confirm', // Verification help text. [+:verification] will be matched with 'options.verification' (i.e 'Type "continue" below to confirm')
                    clicksCount: 3, // for soft confirm, user will be asked to click on "proceed" btn 3 times before actually proceeding
                    backdropClose: false, // set to true to close the dialog when clicking outside of the dialog window, i.e. click landing on the mask
                    customClass: '' // Custom class to be injected into the parent node for the current dialog instance
                };


                this.$dialog
                    .alert(message, options)
                    .then(dialog => {


                    })
                    .catch(() => {

                    });
            },
        }
    }
</script>

<style>
    .column > div:nth-child(2) {
        height: 500px;
        overflow: scroll;
    }

    .single-item-group {
        padding: 5px;
        margin-bottom: 2px;
        border-bottom: 1px solid #eee;
        cursor: pointer;

    }
</style>
<template>
    <div>
        <!--    <input type="text" v-model="by_date"  class="input" :placeholder="translator.search"/>-->

        <div class="table-container">
            <table class="table is-small table-borderd is-bordered">
                <thead class="has-background-dark has-text-white">
                <tr>
                    <th class="has-text-white" searchable="false">#</th>
                    <th class="has-text-white">{{ translator.barcode }}</th>
                    <th class="has-text-white">{{ translator.name }}</th>
                    <th class="has-text-white">{{ translator.price }}</th>
                    <th class="has-text-white">{{ translator.price_tax }}</th>
                    <th class="has-text-white">{{ translator.qty }}</th>
                    <th class="has-text-white">{{ translator.date }}</th>
                    <th class="has-text-white">{{ translator.creator }}</th>
                    <th class="has-text-white" width="4%">{{ translator.actions }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><input class="input" type="text" v-model="by_id"/></td>
                    <td><input :placeholder="translator.search" @keyup.13='searchWithBarcode' class="input"
                               ref="barcode_search_field"
                               type="text" v-model="by_barcode"/></td>
                    <td><input :placeholder="translator.search" class="input" type="text" v-model="by_name"/></td>
                    <td><input :placeholder="translator.search" class="input" type="text" v-model="by_price"/></td>
                    <td><input :placeholder="translator.search" class="input" type="text" v-model="by_price_inc"/></td>
                    <td><input :placeholder="translator.search" class="input" type="text" v-model="by_qty"/></td>
                    <td>
                        <custom-date-field-component @updated="dateHasBeenUpdated"></custom-date-field-component>
                    </td>
                    <td>
                        <multi-select-component :items="creators"
                                                @listUpdated="creatorsSelected"></multi-select-component>
                    </td>
                    <td></td>
                </tr>
                <tr :class="{'danger':item.is_expense}" :key="item.id" v-for="(item,index) in items">
                    <th v-text="index+1" width="4%"></th>

                    <th class="barcode" style="text-align:left !important"
                        v-if="selectable"
                        width="13%">
                        <span :class="{'has-text-primary':item.is_need_serial}"
                              v-if="item.available_qty<=0 && is_ask_for_purchase==0 && !item.is_kit">{{item
							.barcode}}</span>
                        <span :class="{'has-text-primary':item.is_need_serial}" @click="copyBarCodeToInvoice(item)"
                              href=""
                              style="    cursor: pointer;"
                              v-else>{{item
							.barcode}}</span>

                    </th>
                    <th class="barcode"
                        style="text-align:left !important"
                        v-else
                        width="13%">
                        <span :class="{'has-text-primary':item.is_need_serial}">{{item.barcode}}</span>
                    </th>


                    <th
                            :class="{'item_name_class':translator.locale=='ar','item_name_class_en':translator.locale!='ar'}"
                            style="text-align: right !important;">
                        {{ item.locale_name }}
                    </th>
                    <th v-text='item.price' width="6%"></th>
                    <th v-text='item.price_with_tax' width="6%"></th>
                    <th v-text='item.available_qty' width="5%"></th>
                    <th v-text='item.created_at' width="8%"></th>
                    <th v-text='item.creator.name' width="13%"></th>
                    <th class="option-buttons" width="4%">

                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true" class="button is-small is-primary"
                                    data-toggle="dropdown" id="optionsMenu $item->id}}" type="button">
                                <i class="fa fa-cogs"></i> &nbsp; {{ translator.actions }}
                            </button>
                            <div aria-labelledby="optionsMenu $item->id}}" class="dropdown-menu"
                                 style="transform: translate3d(80px, 41px, 0px) !important;">
                                <a :href="'/management/items/clone/' + item.id " class="dropdown-item"><i
                                        class="fa fa-copy"></i> &nbsp; {{ translator.clone }}</a>
                                <a :href="'/management/items/' + item.id " class="dropdown-item"><i
                                        class="fa fa-eye"></i>
                                    &nbsp; {{ translator.show }}</a>
                                <a :href="'/management/items/flow/' + item.id + '/list' " class="dropdown-item"><i
                                        class="fab fa-stack-overflow"></i>
                                    &nbsp; {{ translator.flow }}</a>
                                <a :href="'/management/items/' + item.id + '/edit'" class="dropdown-item"><i
                                        class="fa fa-edit"></i> &nbsp; {{ translator.edit }}</a>
                                <!--                            <delete-button-component class=" dropdown-item"-->
                                <!--                                :href="'/management/items/' + item.id + '/delete'"><i-->
                                <!--                                class="fa fa-trash"></i> &nbsp; delete</delete-button-component>-->

                                <a :href="'/management/items/' +
                             item.id  + '/activate'" class="dropdown-item" v-if="loadType=='pending'"><i
                                        class="fa fa-clipboard-check"></i> &nbsp; {{
                                    translator.activate }}</a>
                            </div>
                        </div>

                    </th>

                </tr>


                </tbody>
            </table>


        </div>
        <div class="text-center">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li :class="{'active has-text-white':page==current_page}" :key="page" v-for="page in last_page"><a
                            @click="paginate(page)" v-text="page"></a></li>
                </ul>
            </nav>
        </div>
    </div>
</template>
<script type="text/javascript">


    export default {
        props: ['loadType', 'creators', 'linkable', 'is_ask_for_purchase'],
        components: {},
        data: function () {
            return {
                bc: new BroadcastChannel('item_barcode_copy_to_invoice'),
                roundNumber: null,


                creator_ids: [],
                selectable: false,
                messages: null,
                translator: null,
                by_id: '',
                by_name: '',
                by_barcode: '',
                by_price: '',
                by_price_inc: '',
                by_qty: '',
                by_date: null,
                by_creator: '',
                items: [],
                by_creators: [],
                last_page: 0,
                path: '',
                current_page: '1',

            }
        },
        created: function () {
            this.loadData();

            if (this.linkable == 1) {
                this.selectable = true;
            }


            this.translator = JSON.parse(window.translator);
            this.messages = JSON.parse(window.messages);
            // console.log(this.loadType + ' app');
        },


        methods: {

            // this is used to used all text in the field
            searchWithBarcode() {
                this.$refs.barcode_search_field.select();
                this.loadData();
            },


            dateHasBeenUpdated(e) {
                this.by_date = e;

                this.loadData();
            },
            creatorsSelected(e) {
                ///
                this.creator_ids = e.ids;
                this.loadData();
            },


            getSearchParams() {


                var data = {};


                if (this.by_barcode != '') {
                    data.barcode = this.by_barcode;
                }

                if (this.by_name != '') {
                    data.name = this.by_name;
                }


                if (this.by_id != '') {
                    data.id = this.by_id;
                }


                if (this.by_qty != '') {
                    data.qty = this.by_qty;
                }


                if (this.by_price != '') {
                    data.price = this.by_price;
                }

                if (this.by_price_inc != '') {
                    data.price_with_tax = this.by_price_inc;
                }


                if (this.by_qty != '') {
                    data.qty = this.by_qty;
                }


                if (this.by_date != null) {
                    data.startDate = this.by_date.start;
                    data.endDate = this.by_date.end;
                }


                if (this.creator_ids.length >= 1) {
                    data.creators = this.creator_ids;
                }

                return data;
            },


            copyBarCodeToInvoice(item) {
                this.bc.postMessage(JSON.stringify(item));

                this.$toast.success({
                    type: 'success',
                    showMethod: 'lightSpeedIn',
                    closeButton: false,
                    timeOut: 2000,
                    icon: '',
                    title: this.messages.process_title,
                    message: this.messages.process_done,
                    progressBar: true,
                    hideDuration: 1000
                })

                /*if(item.available_qty>=1)
                {

                }else
                {

                }*/


                // var gettingCurrent = browser.tabs.getCurrent()
                // gettingCurrent.then((index) => {
                //     console.log(index);
                // }));

            },


            loadData() {

                // this.flashSuccess('Data loaded');


                var vm = this;

                var params = this.getSearchParams();
                // console.log(params);
                axios.post('/management/items/datatable/index?page=' + this.current_page + '&loadType=' + this.loadType, params)
                    .then(function (response) {
                        // handle success
                        // console.log(response.data);
                        vm.items = response.data.data;
                        vm.last_page = response.data.last_page;
                        vm.path = response.data.path;
                        vm.current_page = response.data.current_page;

                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .finally(function () {
                        // always executed
                    });

            },


            paginate(page) {
                this.current_page = page;
                this.loadData();
            }

        },

        watch: {

            by_id: function (value) {
                this.loadData();
            },
            by_name: function (value) {
                this.loadData();
            },
            by_price: function (value) {
                this.loadData();
            },
            by_price_inc: function (value) {
                this.loadData();
            },
            by_date: function (value) {
                this.loadData();
            },
            by_creator: function (value) {
                this.loadData();
            },
            by_qty: function (value) {
                this.loadData();
            }
        }
    }
</script>

<!--<link rel="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />-->
<!--<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>-->

<style scoped>
    input {
        text-align: center !important;
    }

    a {
        text-decoration: none !important
    }

    th {
        vertical-align: middle !important;
        padding: 0px !important;
        margin: 0px !important;
    }

    .item_name_class {
        text-align: right !important;
        padding-right: 13px !important
    }


    .item_name_class_en {
        text-align: left !important;
        padding-left: 13px !important
    }

    .dropdown-menu {
        transform: translate3d(80px, 41px, 0px) !important;
    }


    .barcode {
        text-align: left !important;
        padding-left: 5px !important;
    }
</style>




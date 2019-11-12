<template>
    <div class="inline">
        <button @click="showModal" class="button is-small is-primary"
                style="margin: 10px"><i class="fa fa-star"></i></button>


        <v-dialog
                :dark="true"
                :fullscreen="true"
                @keyup.esc="doCloseWithESC"
                v-model="dialog"
                width="500"
        >

            <v-card>


                <v-card-title
                        class="headline grey lighten-2"
                        primary-title
                >
                    {{ kit.name }}
                </v-card-title>

                <!--                </v-card>-->


                <v-card style="margin:10px">
                    <table class="table table-dark">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">السيريال</th>
                        </tr>
                        </thead>
                        <tbody v-for="(item,index) in items">
                        <tr>
                            <td>{{ item.locale_name }}</td>
                            <td>{{ item.is_need_serial }}</td>

                        </tr>


                        <tr v-for="x in kit_qty" v-if="item.is_need_serial">
                            <!--                            -->
                            <td colspan="2">
                                <input :rel="'serial_'+ index" @keyup="clearField"
                                       @keyup.enter="checkSerial($event,'serial_'+ index +'_'+ x,item)"
                                       class="input"
                                       placeholder="ادخل السيريال "
                                       type="text">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </v-card>


                <v-divider></v-divider>


                <v-card-actions v-show="showButtons">
                    <v-spacer></v-spacer>
                    <v-btn
                            class="button is-primary"
                            @click="dialog = false"
                            color="primary"
                            text
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>
</template>
<script>

    export default {
        components: {},
        props: ["kit", "index", "qty"],
        data: function () {
            return {
                dialog: true,
                items: [],
                is_required_to_add_data: false,
                has_serials_error: true,
                kit_qty: 0,
                showButtons:false
            };
        },
        created: function () {

            this.kit_qty = this.kit.qty;
            this.checkItem();

        },


        methods: {


            doCloseWithESC() {
                this.dialog = true;
            },
            showModal() {
                this.dialog = true;
            },

            checkItem() {
                var len = this.kit.items.length;
                for (var i = 0; i < len; i++) {

                    var item = this.kit.items[i];

                    if (item.item.is_need_serial) {
                        this.is_required_to_add_data = true;
                        item.item.is_need_serial = true;
                        item.item.serials = [];


                    }

                    item.item.qty = item.qty;
                    item.item.total = item.total;
                    item.item.discount = item.discount;
                    item.item.subtotal = item.subtotal;
                    item.item.price = item.price;
                    item.item.net = item.net;
                    item.item.tax = item.tax;


                    this.items.push(item.item);
                    if(i+1==len)
                    {
                        this.checkItemsData();
                    }
                }


            },


            clearField(event) {
                $(event.target).removeClass("has-background-danger");
            },

            checkItemsData() {
                this.has_serials_error = false;
                var len = this.items.length;
                for (var i = 0; i < len; i++) {

                    var item = this.items[i];
                    // console.log(item);
                    if (item.is_need_serial) {
                        if (item.serials.length < parseInt( this.kit_qty)) {
                            this.has_serials_error = true;
                        }
                    }
                }


                if(!this.has_serials_error)
                {
                    this.pushEvent();
                    this.showButtons = true;
                }else
                {
                    this.showButtons = false;
                }

            },


            pushEvent() {

                var kit = this.kit;
                kit.items = this.items;
                this.$emit("kitUpdated", {
                    index: this.index,
                    kit: kit,
                    has_serials_error: this.has_serials_error
                });
            },


            checkSerial(event, ref, item) {

                var intered_serial = event.target.value;


                if (!item.serials.includes(this.intered_serial)) {
                    var vm = this;
                    axios.get('/management/items/serials/checkserialforsale?serial=' + intered_serial + '&item_id=' +
                        item.id)
                        .then(function (response) {

                            item.serials.push(intered_serial);
                            $(event.target).removeClass("has-background-danger");
                            $(event.target).attr("disabled", "disabled");

                            vm.checkItemsData();
                           vm.clearField(event);

                        })
                        .catch(function (error) {
                            event.target.focus();
                            event.target.select();
                            $(event.target).addClass("has-background-danger");
                        });
                } else {
                    $(event.target).addClass("has-background-danger");
                    event.target.focus();
                    event.target.select();
                }



            },

        },

        watch: {
            qty: function (value) {


                this.kit_qty = value;
                var subitems=  [];
                var len = this.items.length;
                for (var i = 0; i < len; i++) {

                    var item = this.items[i];

                    subitems.push(item);
                }

                this.items = subitems;
                this.dialog = true;
               // console.log(value);

            }
        }

    }
</script>

<style>
    .v-dialog__content {
        z-index: 37498732942 !important;
    }
</style>
<template>
    <div  data-app>
        <button @click="showModal" class="btn btn-success btn-xs"><i class="fa fa-star"></i></button>
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
                    {{ kit.locale_name }}
                </v-card-title>

                <v-card style="margin:10px">
                    <table class="table table-dark">
                        <thead>
                        <tr>
                            <th scope="col  text-center">الباركود</th>
                            <th scope="col  text-center">اسم المنتج</th>
                            <th scope="col  text-center">الكمية</th>
                            <th scope="col  text-center"> السعر</th>
                            <th scope="col  text-center">المجموع</th>
                            <th scope="col  text-center"> الخصم</th>
                            <th scope="col  text-center">الصافي</th>
                            <th scope="col  text-center">الضريبة</th>
                            <th scope="col  text-center">النهائي</th>
                            <th scope="col  text-center">يحتاج سيريال</th>
                        </tr>
                        </thead>
                        <tbody v-for="(item,index) in items" :key="index">
                            <tr>
                                <td>{{ item.barcode }}</td>
                                <td>{{ item.locale_name }}</td>
                                <td>{{ item.qty }}</td>
                                <td>{{ item.price }}</td>
                                <td>{{ item.total }}</td>
                                <td>{{ item.discount }}</td>
                                <td>{{ item.subtotal }}</td>
                                <td>{{ item.tax }}</td>
                                <td>{{ item.net }}</td>


                                <td v-if="item.is_need_serial">نعم</td>
                                <td v-else>لا</td>

                            </tr>


                        <tr v-for="x in kit_qty" v-if="item.is_need_serial" :key="x">
                            <td colspan="10">
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
                            @click="dialog = false"
                            class="button is-primary btn-block"
                            color="primary"
                            text
                    >
                        اغلاق
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>
</template>
<script>
    import {
        accounting as ItemAccounting,
        math as ItemMath,
        query as ItemQuery,
        validator as ItemValidator
    } from '../../item';


    export default {
        components: {},
        props: ["kit", "index", "qty"],
        data: function () {
            return {
                dialog: false,
                items: [],
                is_required_to_add_data: false,
                has_serials_error: true,
                kit_qty: 0,
                showButtons: false
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
                for (let i = 0; i < len; i++) {

                    let item = this.kit.items[i];

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
                    if (i + 1 == len) {
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
                        if (item.serials.length < parseInt(this.kit_qty)) {
                            this.has_serials_error = true;
                        }
                    }
                }


                if (!this.has_serials_error) {
                    this.pushEvent();
                    this.showButtons = true;
                } else {
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
                    ItemQuery.sendValidateReturnSaleSerialRequest({
                        'item_id':item.id,
                        'serials':[intered_serial]
                    }).then(function (response) {
                        if (response.data.length >= 1) {
                            item.serials.push(intered_serial);
                            $(event.target).removeClass("has-background-danger");
                            $(event.target).attr("disabled", "disabled");

                            vm.checkItemsData();
                            vm.clearField(event);
                        } else {
                            event.target.focus();
                            event.target.select();
                            $(event.target).addClass("has-background-danger");
                        }
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
                var subitems = [];
                var len = this.items.length;
                for (var i = 0; i < len; i++) {
                    var item = this.items[i];
                    subitems.push(item);
                }

                this.items = subitems;

            }
        }

    }
</script>


<style scoped src='bulma/css/bulma.css'>

</style>

<style>
    .v-dialog__content {
        z-index: 37498732942 !important;
    }
</style>
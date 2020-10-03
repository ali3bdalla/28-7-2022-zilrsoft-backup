<template>
    <div>
        <div v-if="itemData!=null">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header ">
                                    <button @click="panelClosed" class="pull-left btn btn-custom-primary" type="button">
                                        اغلاق
                                    </button>
                                    <h4 class="modal-title">{{ itemData.locale_name }}</h4>
                                </div>
                                <div class="modal-body">

                                    <div class="table-response">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                            <th>#</th>
                                            <th></th>
                                            <th></th>
                                            </thead>
                                            <tbody>
                                            <tr :key="index" v-for="(serial,index) in serials">
                                                <td class="text-center" v-text="index + 1"></td>
                                                <td class="text-center" v-text="serial.serial"></td>
                                                <td class="text-center">
                                                    <button @click="validateSerial(index,serial)" class="btn
                                                            btn-custom-primary"
                                                            v-if="allowedType.includes(serial.status)">ارجاع
                                                    </button>
                                                    <span v-else-if="serial.status == 'sold'">تم بيعه</span>
                                                    <span v-else-if="serial.status == 'return_purchase'"> مرتج مشتريات</span>
                                                    <span v-else-if="serial.status == 'return_sale'"> مرتج مبيعات</span>
                                                    <span v-else-if="serial.status == 'purchase'"> مشتريات</span>
                                                </td>
                                            </tr>


                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
    //

    export default {


        props: ['item', 'itemIndex', 'invoiceType'],
        data: function () {
            return {
                serialInput: "",
                itemData: null,
                index: -1,
                serials: [],
                publishSerial: [],
                allowedType: []
            };

        },

        created: function () {
            if (this.invoiceType == 'return_purchase') {
                this.allowedType = [
                    'in_stock', 'return_sale'
                ];
            } else {
                this.allowedType = [
                    'sold'
                ];
            }
        },

        // mounted: function () {
        //     this.updateReturnedQty();
        // },

        methods: {


            updateReturnedQty() {
                let canBeReturned = 0;
                for (let i = 0; i < this.serials.length; i++) {
                    let serial = this.serials[i];
                    if (this.allowedType.includes(serial.status)) {
                        canBeReturned = canBeReturned + 1;
                    }
                }

                this.$emit('canBeReturnedSerialCount', {
                    index: this.itemIndex,
                    count: canBeReturned
                });

                // console.log('works')
            },
            validateSerial(index, serial) {
                if (this.allowedType.includes(serial.status)) {
                    serial.first_return = true;
                    serial.status = this.invoiceType;
                    this.serials.splice(index, 1, serial);
                    this.updateReturnSerialList();
                }

            },


            updateReturnSerialList() {
                let list = [];
                for (let i = 0; i < this.serials.length; i++) {
                    let serial = this.serials[i];
                    if (serial.status == this.invoiceType && serial.first_return) {
                        list.push(serial.serial);
                    }
                }
                this.$emit('publishUpdated', {
                    index: this.index,
                    serials: list
                });
            },

            panelClosed() {
                this.$emit('panelClosed', {});
            },
            handleOpen(value) {
                if (value != null) {
                    this.itemData = value;
                    this.index = this.itemIndex;
                    this.serials = value.serials;
                    let appVm = this;
                    setTimeout(function () {
                    }, 500);

                } else {
                    this.itemData = null;
                    this.index = -1;

                }


            }
        },
        watch: {
            item: function (value) {
                this.handleOpen(value);
                this.updateReturnedQty();
            }
        }

    }
</script>


<style scoped src='bulma/css/bulma.css'>

</style>

<style>
    /*.modal {*/
    /*z-index: 923890423 !important;*/
    /*}*/
    .modal-mask {
        position: fixed;
        z-index: 932849080;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .8);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }
</style>
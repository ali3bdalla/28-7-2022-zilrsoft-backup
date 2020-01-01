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
                                                            v-if="serial.current_status!=invoiceType">ارجاع
                                                    </button>
                                                    <span v-else>مسترجع</span>
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
    import {query as ItemQuery} from '../../item';
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
            };

        },


        methods: {

            validateSerial(index, serial) {
                serial.current_status = this.invoiceType;
                this.serials.splice(index, 1, serial);
                this.publishUpdated();
            },


            publishUpdated() {
                let list = [];
                for (let i = 0; i < this.serials.length; i++) {
                    var serial = this.serials[i];
                    if (serial.current_status == this.invoiceType) {
                        list.push(serial);
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
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input @keyup.enter="validateSerial" class="form-control"
                                                   placeholder="السيريال"
                                                   ref="mainFieldId"
                                                   type="text"
                                                   v-model="serialInput"
                                            >
                                        </div>
                                        <!--                                        <div class="col-md-6">-->
                                        <!--                                            <input class="form-control" name="" type="text">-->
                                        <!--                                        </div>-->
                                    </div>
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
                                                <td class="text-center" v-text="serial"></td>
                                                <td class="text-center">
                                                    <button @click="deleteSerialFromList(index)"
                                                            class="btn btn-danger btn-sm">حذف
                                                    </button>
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
            };

        },


        methods: {
            validateSerial() {
                if (this.serialInput == "") {
                    this.itemData = null;
                    this.index = -1;

                    return;
                }
                if (this.invoiceType == 'purchase') {
                    var appVm = this;
                    ItemQuery.sendValidatePurchaseSerialRequest(
                        [this.serialInput]
                    ).then(response => {
                        appVm.serials = db.model.createUnique(appVm.serials, appVm.serialInput);
                        appVm.serialInput = "";
                        appVm.publishUpdated();
                    }).catch(error => {
                        alert(error);

                    });

                } else if (this.invoiceType == 'sale') {
                    var appVm = this;
                    ItemQuery.sendValidateSaleSerialRequest(this.item.id, [
                        this.serialInput
                    ]).then(response => {
                        if (response.data.length <= 0) {
                            alert('هذا السيريال غير متوفر');
                            appVm.$refs.serialInput.focus();
                            appVm.$refs.serialInput.select();
                        } else {
                            appVm.serials = db.model.createUnique(appVm.serials, appVm.serialInput);
                            appVm.serialInput = "";
                            appVm.publishUpdated();
                        }
                    }).catch(error => {
                        alert(error);

                    });

                } else if (this.invoiceType == 'r_sale') {
                    var appVm = this;
                    ItemQuery.sendValidateReturnSaleSerialRequest(this.item.id, [
                        this.serialInput
                    ]).then(response => {
                        if (response.data.length <= 0) {
                            alert('هذا السيريال غير متوفر');
                            appVm.$refs.serialInput.focus();
                            appVm.$refs.serialInput.select();
                        } else {
                            appVm.serials = db.model.createUnique(appVm.serials, appVm.serialInput);
                            appVm.serialInput = "";
                            appVm.publishUpdated();
                        }
                    }).catch(error => {
                        alert(error);

                    });

                } else if (this.invoiceType == 'r_purchase') {
                    var appVm = this;
                    ItemQuery.sendValidateReturnPurchaseSerialRequest(this.item.id, [
                            this.serialInput
                        ]
                    ).then(response => {
                        if (response.data.length <= 0) {
                            alert('هذا السيريال غير متوفر');
                            appVm.$refs.serialInput.focus();
                            appVm.$refs.serialInput.select();
                        } else {
                            appVm.serials = db.model.createUnique(appVm.serials, appVm.serialInput);
                            appVm.serialInput = "";
                            appVm.publishUpdated();
                        }
                    }).catch(error => {
                        alert(error);

                    });

                }


            },
            deleteSerialFromList(index) {
                this.serials = db.model.deleteByIndex(this.serials, index);
                this.publishUpdated();
            },
            publishUpdated() {
                this.$emit('publishUpdated', {
                    index: this.index,
                    serials: this.serials
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
                        appVm.$refs.mainFieldId.focus();
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
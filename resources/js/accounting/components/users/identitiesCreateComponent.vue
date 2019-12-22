<template>
    <div class="">

        <div class="">
            <div class="row">

                <div class="col-md-2 col-md-offset-1 label-txt">
                    <!-- <div class="label has-text-dark"> -->
                    <label>{{ app.trans.identityType}} </label>
                    <!-- </div> -->
                </div>


                <div class="col-md-3">
                    <toggle-button :font-size="19" :height='30' :labels="{checked: app.reusableTranslator.radio_checked,
                                   unchecked: app.reusableTranslator.unchecked}" :sync="true" :width='70'
                                   v-model="identityData.isIndividual"/>
                    &nbsp;
                    <label>{{ app.trans.individual}}</label>
                </div>

                <div class="col-md-3">
                    <toggle-button :font-size="19" :height='30' :labels="{checked: app.reusableTranslator.radio_checked,
                                   unchecked: app.reusableTranslator.unchecked}" :sync="true" :width='70'
                                   v-model="identityData.isCompany"/>
                    &nbsp;
                    <label>{{ app.trans.company}}</label>
                </div>


            </div>

            <div class="row">

                <div class="col-md-2 col-md-offset-1 label-txt">
                    <label>{{ app.trans.title}}</label>
                </div>


                <div class="col-md-3">
                    <toggle-button :font-size="19" :height='30' :labels="{checked: app.reusableTranslator.radio_checked,
                                   unchecked: app.reusableTranslator.unchecked}" :sync="true" :width='70'
                                   v-model="identityData.isMr"/>
                    &nbsp;
                    <label>{{ app.trans.mr}}</label>
                </div>

                <div class="col-md-3">
                    <toggle-button :font-size="19" :height='30' :labels="{checked: app.reusableTranslator.radio_checked,
                                   unchecked: app.reusableTranslator.unchecked}" :sync="true" :width='70'
                                   v-model="identityData.isMis"/>
                    &nbsp;
                    <label>{{ app.trans.mis}}</label>
                </div>

                <div class="col-md-3">
                    <toggle-button :font-size="19" :height='30' :labels="{checked: app.reusableTranslator.radio_checked,
                                   unchecked: app.reusableTranslator.unchecked}" :sync="true" :width='70'
                                   v-model="identityData.isCompany"/>
                    &nbsp;
                    <label>{{ app.trans.company_title}}</label>
                </div>


            </div>


            <div class="row">

                <div class="col-md-2 col-md-offset-1 label-txt">
                    <!-- <div class="label has-text-dark"> -->
                    <label>{{ app.trans.identitySubscriptions}}</label>
                    <!-- </div> -->
                </div>


                <div class="col-md-3">
                    <toggle-button :font-size="19" :height='30' :labels="{checked: app.reusableTranslator.radio_checked,
                                   unchecked: app.reusableTranslator.unchecked}" :sync="true" :width='70'
                                   v-model="identityData.isClient"/>
                    &nbsp;
                    <label>{{ app.trans.client}}</label>
                </div>

                <div class="col-md-3">
                    <toggle-button :font-size="19" :height='30' :labels="{checked: app.reusableTranslator.radio_checked,
                                   unchecked: app.reusableTranslator.unchecked}" :sync="true" :width='70'
                                   v-model="identityData.isVendor"/>
                    &nbsp;
                    <label>{{ app.trans.vendor}}</label>
                </div>


                <div class="col-md-3">
                    <toggle-button :font-size="19" :height='30' :labels="{checked: app.reusableTranslator.radio_checked,
                                   unchecked: app.reusableTranslator.unchecked}" :sync="true" :width='70'
                                   v-model="identityData.isSupplier"/>
                    &nbsp;
                    <label>{{ app.trans.supplier}}</label>
                </div>


            </div>


            <div class="row">

                <div class="col-md-2 col-md-offset-1 label-txt">
                    <!-- <div class="label has-text-dark"> -->
                    <label>{{ app.trans.credit_invoices}}</label>
                    <!-- </div> -->
                </div>


                <div class="col-md-3">
                    <toggle-button :font-size="19" :height='30' :labels="{checked: app.reusableTranslator.radio_checked,
                                   unchecked: app.reusableTranslator.unchecked}" :sync="true" :width='70'
                                   v-model="identityData.identityHasAbilityToMakeCreditInvoices"/>
                    &nbsp;
                </div>


            </div>


            <div class="row">
                <div class="col-md-4">
                    <div :class="{'has-error':errorFieldName=='arName'}" class="form-group">
                        <input :placeholder="app.trans.name_ar" class="form-control arabic-input"
                               ref="identityArName"
                               type="text" v-model="identityData.arName">
                        <small class="text-danger" v-show="errorFieldName=='arName'">
                            {{ errorFieldMessage}}
                        </small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div :class="{'has-error':errorFieldName=='enName'}" class="form-group">
                        <input :placeholder="app.trans.name" class="form-control"
                               ref="identityenName"
                               type="text" v-model="identityData.enName">
                        <small class="text-danger" v-show="errorFieldName=='enName'">
                            {{ errorFieldMessage}}
                        </small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div :class="{'has-error':errorFieldName=='phoneNumber'}" class="form-group">
                        <input :placeholder="app.trans.phone_number" class="form-control"
                               ref="identityenName"
                               type="text" v-model="identityData.phoneNumber">
                        <small class="text-danger" v-show="errorFieldName=='phoneNumber'">
                            {{ errorFieldMessage}}
                        </small>
                    </div>
                </div>


            </div>


            <div v-show="identityData.isClient || identityData.isVendor">

                <div class="row">
                    <div class="col-md-4">
                        <div :class="{'has-error':errorFieldName=='email'}" class="form-group">
                            <input :placeholder="app.trans.email_address" class="form-control"
                                   ref="identityenName"
                                   type="email" v-model="identityData.email">
                            <small class="text-danger" v-show="errorFieldName=='email'">
                                {{ errorFieldMessage}}
                            </small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div :class="{'has-error':errorFieldName=='workManagerName'}" class="form-group">
                            <input :placeholder="app.trans.work_manager_name" class="form-control"
                                   ref="identityenName"
                                   type="text" v-model="identityData.workManagerName">
                            <small class="text-danger" v-show="errorFieldName=='workManagerName'">
                                {{ errorFieldMessage}}
                            </small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div :class="{'has-error':errorFieldName=='workPhoneNumber'}" class="form-group">
                            <input :placeholder="app.trans.work_phone_number" class="form-control"
                                   ref="identityenName"
                                   type="text" v-model="identityData.workPhoneNumber">
                            <small class="text-danger" v-show="errorFieldName=='workPhoneNumber'">
                                {{ errorFieldMessage}}
                            </small>
                        </div>
                    </div>


                </div>
                <div class="row">


                    <div class="col-md-4">
                        <div :class="{'has-error':errorFieldName=='address'}" class="form-group">
                            <input :placeholder="app.trans.address" class="form-control"
                                   ref="identityenName"
                                   type="text" v-model="identityData.address">
                            <small class="text-danger" v-show="errorFieldName=='address'">
                                {{ errorFieldMessage}}
                            </small>
                        </div>
                    </div>


                    <div class="col-md-4" v-show="identityData.isVendor">
                        <div :class="{'has-error':errorFieldName=='crNumber'}" class="form-group">
                            <input :placeholder="app.trans.cr_number" class="form-control"
                                   ref="crNumber"
                                   type="text" v-model="identityData.crNumber">
                            <small class="text-danger" v-show="errorFieldName=='crNumber'">
                                {{ errorFieldMessage}}
                            </small>
                        </div>
                    </div>

                    <div class="col-md-4" v-show="identityData.isVendor">
                        <div :class="{'has-error':errorFieldName=='vatNumber'}" class="form-group">
                            <input :placeholder="app.trans.vat_number" class="form-control"
                                   ref="vatNumber"
                                   type="text" v-model="identityData.vatNumber">
                            <small class="text-danger" v-show="errorFieldName=='vatNumber'">
                                {{ errorFieldMessage}}
                            </small>
                        </div>
                    </div>


                </div>


            </div>


            <div class="" v-show="identityData.isVendor || identityData.isClient">
                <accounting-append-gateways-layout-component
                        :accounts="banks"
                        :init-gateways="ini_user_gateways"
                        @publishUpdate="updateUserGateways">
                </accounting-append-gateways-layout-component>
            </div>


            <div class="row">
                <div class="col-md-2 col-md-offset-3">
                    <button @click="validateData" class="btn btn-custom-primary" type="submit"><i
                            class="fa fa-save"></i>
                        <span v-if="editingIdentity != null && editingIdentity"> {{ app.trans.edit}}</span>
                        <span v-else> {{ app.trans.create }}</span>
                    </button>
                </div>
                <div class="col-md-3 col-md-offset-3">
                    <a :href="app.BaseApiUrl + 'identities'" class="btn btn-default">
                        <i class="fa fa-undo"></i>
                        {{ app.reusableTranslator.cancel}}
                    </a>
                </div>


            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['banks', 'editingIdentity', 'identityDetails', 'identityGateways', 'identity'],
        data: function () {
            return {
                ini_user_gateways: [],
                app: {
                    primaryColor: metaHelper.getContent('primary-color'),
                    secondColor: metaHelper.getContent('second-color'),
                    appLocate: metaHelper.getContent('app-locate'),
                    trans: trans('users-page'),
                    messages: trans('messages'),
                    reusableTranslator: trans('reusable-translator'),
                    datetimetrans: trans('datetime'),
                    datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
                    BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
                },
                errorFieldName: "",
                errorFieldMessage: "",

                identityData: {
                    isIndividual: true,
                    workManagerName: "",
                    vatNumber: "",
                    crNumber: "",
                    email: "",
                    arName: "",
                    enName: "",
                    workEmail: "",
                    workPhoneNumber: "",
                    phoneNumber: "",
                    address: "",
                    identityHasAbilityToMakeCreditInvoices: false,
                    isMr: true,
                    isMis: false,
                    isCompany: false,
                    isClient: true,
                    isVendor: false,
                    isSupplier: false,
                },

            };
        },
        created: function () {
            if (this.editingIdentity != null && this.editingIdentity) {
                this.initEditingIdentity();
            }

        },
        methods: {

            initEditingIdentity() {
                this.identityData.isVendor = this.identity.is_vendor;
                this.identityData.isClient = this.identity.is_client;
                this.identityData.isSupplier = this.identity.is_supplier;
                this.identityData.arName = this.identity.name_ar;
                this.identityData.enName = this.identity.name;
                this.identityData.phoneNumber = this.identity.phone_number;
                this.identityData.email = this.identityDetails.email_address;
                this.identityData.isIndividual = this.identity.user_type == 'individual' ? true : false;
                this.identityData.isCompany = this.identity.user_type == 'individual' ? false : true;
                this.identityData.isMr = this.identity.user_title == 'mr' ? true : false;
                this.identityData.isMis = this.identity.user_title == 'mis' ? true : false;
                this.identityData.isMis = this.identity.user_title == 'mis' ? true : false;
                this.identityData.workManagerName = this.identityDetails.responsible_name;
                this.identityData.crNumber = this.identityDetails.cr;
                this.identityData.workPhoneNumber = this.identityDetails.responsible_phone_number;
                this.identityData.vatNumber = this.identityDetails.vat;
                this.identityData.address = this.identityDetails.address;
                this.ini_user_gateways = this.identityGateways;
            },
            updateUserGateways(e) {
                this.user_gateways = e.gateways;
            },


            validateData() {
                // if (this.user_name == "") {
                //     this.error = 'user_name';
                //     this.errorMessage = 'please put user name';
                //     return;
                // }
                //
                //
                // if (this.user_phone_number == "") {
                //     this.error = 'user_phone_number';
                //     return;
                // }
                //
                //
                // if (this.is_manager) {
                //
                //
                //     if (this.user_email == "") {
                //         this.error = 'user_email';
                //         return;
                //     }
                //
                //     // console.log(validate.isEmail(this.user_email));
                //     // if(!validate.isEmail(this.user_email)){
                //     // 	this.error = 'user_email';
                //     // 	return;
                //     // }
                //
                //
                //     if (this.user_pin_code == "") {
                //         this.error = 'user_pin_code';
                //         return;
                //     }
                //
                //
                //     if (this.branch == null) {
                //         this.error = 'branch';
                //         return;
                //     }
                //
                //     if (this.department == null) {
                //         this.error = 'department';
                //         return;
                //     }
                //
                //
                //     if (this.user_password == '') {
                //         this.error = 'user_password';
                //         return;
                //     }
                //
                //     if (this.user_password.length <= 7) {
                //         this.error = 'user_password';
                //         return;
                //     }
                //
                //
                //     if (this.user_password_confirmation == '') {
                //         this.error = 'user_password_confirmation';
                //         return;
                //     }
                //
                //
                //     if (this.user_password_confirmation != this.user_password) {
                //         this.error = 'user_password_confirmation';
                //         return;
                //     }
                //
                //
                //     this.department_id = this.department.id;
                //     this.branch_id = this.branch.id;
                //
                // }
                //
                // if (!this.is_manager && !this.is_vendor && !this.is_client && !this.is_supplier) {
                //     this.error = 'user_type';
                //     return;
                // }
                //
                //
                // this.error = '';


                if (this.editingIdentity != null && this.editingIdentity) {
                    this.sendUpdatedRequest();
                } else {
                    this.sendCreateRequest();
                }


            },

            sendCreateRequest() {

                var appVm = this;

                var data = this.getRequestData();
                axios.post(this.app.BaseApiUrl + 'identities', data)
                    .then(function (response) {
                        location.href = appVm.app.BaseApiUrl + 'identities';
                    })
                    .catch(function (error) {
                        alert(error);


                    });
            },

            sendUpdatedRequest() {

                var appVm = this;

                var data = this.getRequestData();
                axios.patch(this.app.BaseApiUrl + 'identities/' + this.identity.id, data)
                    .then(function (response) {
                        location.href = appVm.app.BaseApiUrl + 'identities';
                    })
                    .catch(function (error) {
                        alert(error);


                    });
            },
            getRequestData() {
                var user_type;
                if (this.identityData.isCompany) {
                    user_type = 'company';
                } else {
                    user_type = 'individual';
                }

                var user_title;

                if (this.is_title_mr) {
                    user_title = 'mr';
                } else if (this.is_title_mis) {
                    user_title = 'mis';
                } else {
                    user_title = 'company';
                }

                if (user_type == 'company') {
                    user_title = 'company';
                }


                var data = {
                    user_gateways: this.user_gateways,
                    user_title: user_title,
                    is_client: this.identityData.isClient,
                    is_supplier: this.identityData.isSupplier,
                    is_vendor: this.identityData.isVendor,
                    user_type: user_type,
                    ar_name: this.identityData.arName,
                    name: this.identityData.enName,
                    phone_number: this.identityData.phoneNumber,
                    email: this.identityData.email,
                    can_make_credit: this.identityData.identityHasAbilityToMakeCreditInvoices,
                    user_detail_vat: this.identityData.vatNumber,
                    user_detail_email: this.identityData.workEmail,
                    user_detail_cr: this.identityData.crNumber,
                    user_detail_address: this.identityData.address,
                    user_detail_responser: this.identityData.workManagerName,
                    user_detail_responser_phone: this.identityData.workPhoneNumber


                };


                return data;
            }


        },


    }
</script>

<style scoped>
    .label-txt {
        vertical-align: middle;
        text-align: right;
    }

    .row {
        margin-top: 10px;
    }

    .form-group {
        margin-bottom: -20px;
    }
</style>

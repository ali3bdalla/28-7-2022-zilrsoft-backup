<template>
    <div class="message is-primary has-text-dark">
        <div class="message-header">
        </div>
        <div class="message-body">
            <div class="columns">

                <div class="column text-center">
                    <!-- <div class="label has-text-dark"> -->
                    <p class="subtitle">نوع الهوية</p>
                    <!-- </div> -->
                </div>


                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""
                                   v-model="is_individual"/>
                    &nbsp;
                    <label>فرد</label>
                </div>

                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""
                                   v-model="is_company"/>
                    &nbsp;
                    <label>منشأة</label>
                </div>


            </div>
            <hr>


            <div class="columns">

                <div class="column text-center">
                    <!-- <div class="label has-text-dark"> -->
                    <p class="subtitle">عنوان الاسم</p>
                    <!-- </div> -->
                </div>


                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""
                                   v-model="is_title_mr"/>
                    &nbsp;
                    <label>السيد</label>
                </div>

                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""
                                   v-model="is_title_mis"/>
                    &nbsp;
                    <label>السيدة</label>
                </div>

                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""
                                   v-model="is_title_company"/>
                    &nbsp;
                    <label>السادة</label>
                </div>


            </div>
            <hr>


            <div class="columns">

                <div :class="{'has-background-danger':error=='user_type'}" class="column text-center  ">
                    <!-- <div class="label has-text-dark"> -->
                    <p class="subtitle">الهوية</p>
                    <!-- </div> -->
                </div>


                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels="" v-model="is_client"/>
                    &nbsp;
                    <label>عميل</label>
                </div>

                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels="" v-model="is_vendor"/>
                    &nbsp;
                    <label>مورد</label>
                </div>

                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""
                                   v-model="is_manager"/>
                    &nbsp;
                    <label>مستخدم</label>
                </div>


                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""
                                   v-model="is_supplier"/>
                    &nbsp;
                    <label>مزود خدمات</label>
                </div>


            </div>
            <hr>


            <div class="columns">

                <div class="column text-center">
                    <!-- <div class="label has-text-dark"> -->
                    <p class="subtitle"> آجل</p>
                    <!-- </div> -->
                </div>


                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""
                                   v-model="has_credit_invoice"/>
                    &nbsp;
                    <!--					<label>نعم</label>-->
                </div>

                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""
                                   v-model="has_no_credit_invoice"/>
                    &nbsp;
                    <!--					<label>لا</label>-->
                </div>


            </div>
            <hr>


            <div class="columns">

                <div class="column">
                    <div class="field">
                        <input :class="{'is-danger':error=='user_name'}" class="form-control" placeholder="الاسم"
                               ref="user_name"
                               type="text" v-model="user_name">
                    </div>
                </div>

                <div class="column">
                    <div class="field">
                        <input :class="{'is-danger':error=='user_phone_number'}" class="input"
                               placeholder="رقم الهاتف "
                               ref="user_phone_number" type="text" v-model="user_phone_number">
                    </div>
                </div>


            </div>


            <div v-show="is_vendor || is_client">


                <div class="columns">
                    <div class="column">
                        <input :class="{'is-danger':error=='user_detail_email'}" class="input"
                               placeholder="البريد الاكتروني"
                               ref="user_detail_email"
                               type="text" v-model="user_detail_email">
                    </div>
                    <div class="column">
                        <input :class="{'is-danger':error=='user_detail_responser'}" class="input"
                               placeholder="المسؤل"
                               ref="user_detail_responser"
                               type="text" v-model="user_detail_responser">
                    </div>


                    <div class="column">
                        <input :class="{'is-danger':error=='user_detail_responser_phone'}" class="input"
                               placeholder="جوال المسؤل"
                               ref="user_detail_responser_phone"
                               type="text" v-model="user_detail_responser_phone">
                    </div>
                </div>


                <div class="columns">
                    <div class="column">
                        <input :class="{'is-danger':error=='user_detail_address'}" class="input"
                               placeholder="العنوان"
                               ref="user_detail_address"
                               type="text" v-model="user_detail_address">
                    </div>
                </div>

                <div class="columns" v-show="is_vendor">


                    <div class="column">
                        <input :class="{'is-danger':error=='user_detail_vat'}" class="input"
                               placeholder="الرقم الضريبي"
                               ref="user_detail_vat"
                               type="text" v-model="user_detail_vat">
                    </div>


                    <div class="column">
                        <input :class="{'is-danger':error=='user_detail_cr'}" class="input"
                               placeholder="السجل التجاري"
                               ref="user_detail_cr"
                               type="text" v-model="user_detail_cr">
                    </div>


                </div>


            </div>

            <div v-show="is_manager">
                <div class="columns">

                    <div class="column">
                        <div class="field">
                            <input :class="{'is-danger':error=='user_email'}" class="input"
                                   placeholder="البريد الاكتروني"
                                   ref="user_email"
                                   type="text" v-model="user_email">
                        </div>
                    </div>

                    <div class="column">
                        <div class="field">
                            <input :class="{'is-danger':error=='user_pin_code'}" class="input"
                                   placeholder="رمز الدخول"
                                   ref="user_pin_code" type="text" v-model="user_pin_code">
                        </div>
                    </div>


                </div>


                <div class="columns">

                    <div class="column">
                        <div :class="{'is-danger':error=='branch'}" class="select is-fullwidth">

                            <select @change="updateBrachsList" class="" v-model="branch">
                                <option :key="branch.id" :value="branch" v-for="branch in branchs"
                                        v-text="branch.name"></option>
                            </select>
                        </div>
                    </div>

                    <div class="column">
                        <div :class="{'is-danger':error=='department'}" class="select is-fullwidth">
                            <select @change="updateDepartmentsList" class="" v-model="department">
                                <option :key="department.id" :value="department" v-for="department in departments"
                                        v-text="department.title"></option>
                            </select>

                        </div>
                    </div>


                </div>


                <div class="columns">

                    <div class="column">
                        <div class="field">
                            <input :class="{'is-danger':error=='user_password'}" autocomplete="new-password"
                                   class="input" placeholder="كلمة المرور" ref="user_password" type="password"
                                   v-model="user_password">
                        </div>
                    </div>

                    <div class="column">
                        <div class="field">
                            <input :class="{'is-danger':error=='user_password_confirmation'}"
                                   autocomplete="new-password"
                                   class="input" placeholder="تاكيد كلمة المرور" ref="user_password_confirmation"
                                   type="password" v-model="user_password_confirmation">
                        </div>
                    </div>


                </div>


                <manager-gateways-component :gateways="gateways" @gatewaysUpdated="gatewaysUpdated"></manager-gateways-component>
            </div>


            <div class="columns">
                <div class="column">
                    <button @click="validateData" class="button is-primary" type="submit"><i class="fa fa-save"></i>
                        حفظ البيانات
                    </button>
                </div>


                <div class="column text-left">
                    <button class="button" type="submit"><i class="fa fa-undo"></i>
                        الغاء
                    </button>
                </div>


            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['branchs','gateways'],
        data: function () {
            return {

                user_detail_vat: "",
                user_detail_email: "",
                user_detail_cr: "",
                user_detail_address: "",
                user_detail_responser: "",
                user_detail_responser_phone: "",

                manager_gateways:[],

                can_make_credit: false,
                has_credit_invoice: false,
                has_no_credit_invoice: true,
                is_title_mr: true,
                is_title_mis: false,
                is_title_company: false,
                error: '',
                errorMessage: '',
                branch: null,
                branch_id: 0,
                department: null,
                department_id: 0,
                departments: [],
                is_individual: true,
                is_company: false,
                is_client: true,
                is_manager: false,
                is_supplier: false,
                is_vendor: false,
                user_email: '',
                user_name: '',
                user_phone_number: '',
                user_pin_code: '',
                user_password: '',
                user_password_confirmation: '',

            };
        },
        methods: {

            updateBrachsList() {
                this.department = null;
                this.departments = this.branch.departments;
            },


            updateDepartmentsList() {
            },

            gatewaysUpdated(e)
            {
              this.manager_gateways = e.gateways;
            },

            validateData() {
                if (this.user_name == "") {
                    this.error = 'user_name';
                    this.errorMessage = 'please put user name';
                    return;
                }


                if (this.user_phone_number == "") {
                    this.error = 'user_phone_number';
                    return;
                }


                if (this.is_manager) {


                    if (this.user_email == "") {
                        this.error = 'user_email';
                        return;
                    }

                    // console.log(validate.isEmail(this.user_email));
                    // if(!validate.isEmail(this.user_email)){
                    // 	this.error = 'user_email';
                    // 	return;
                    // }


                    if (this.user_pin_code == "") {
                        this.error = 'user_pin_code';
                        return;
                    }


                    if (this.branch == null) {
                        this.error = 'branch';
                        return;
                    }

                    if (this.department == null) {
                        this.error = 'department';
                        return;
                    }


                    if (this.user_password == '') {
                        this.error = 'user_password';
                        return;
                    }

                    if (this.user_password.length <= 7) {
                        this.error = 'user_password';
                        return;
                    }


                    if (this.user_password_confirmation == '') {
                        this.error = 'user_password_confirmation';
                        return;
                    }


                    if (this.user_password_confirmation != this.user_password) {
                        this.error = 'user_password_confirmation';
                        return;
                    }


                    this.department_id = this.department.id;
                    this.branch_id = this.branch.id;

                }

                if (!this.is_manager && !this.is_vendor && !this.is_client && !this.is_supplier) {
                    this.error = 'user_type';
                    return;
                }


                this.error = '';


                this.sendDataToServer();

            },

            sendDataToServer() {

                var user_type;
                if (this.is_company) {
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


                var data_to = {
                    gateways:this.manager_gateways,
                    user_title: user_title,
                    is_manager: this.is_manager,
                    is_client: this.is_client,
                    is_supplier: this.is_supplier,
                    is_vendor: this.is_vendor,
                    user_type: user_type,
                    name: this.user_name,
                    phone_number: this.user_phone_number,
                    email: this.user_email,
                    pin_code: this.user_pin_code,
                    branch_id: this.branch_id,
                    department_id: this.department_id,
                    can_make_credit: this.can_make_credit,
                    password: this.user_password,
                    password_confirmation: this.user_password_confirmation,
                    user_detail_vat: this.user_detail_vat,
                    user_detail_email: this.user_detail_email,
                    user_detail_cr: this.user_detail_cr,
                    user_detail_address: this.user_detail_address,
                    user_detail_responser: this.user_detail_responser,
                    user_detail_responser_phone: this.user_detail_responser_phone


                };

                console.log(data_to);
                var vm = this;
                axios.post('/management/users', data_to)
                    .then(function (response) {
                        location.href = '/management/users';
                        console.log(response.data);
                    })
                    .catch(function (error) {
                        if (error.response.data.errors.phone_number.length >= 1) {
                            vm.error = 'user_phone_number';
                            vm.errorMessage = error.response.data.errors.phone_number[0];
                        } else if (error.response.data.errors.email.length >= 1) {
                            vm.error = 'user_email';
                            vm.errorMessage = error.response.data.errors.email[0];
                        }


                    });
            }
        },

        watch: {


            user_name: function (value) {
                this.user_detail_responser = value;
            },
            is_title_mr: function (value) {
                if (value == true) {
                    this.is_title_mis = false;
                    this.is_title_company = false
                }
            },

            is_title_company: function (value) {
                if (value == true) {
                    this.is_title_mr = false;
                    this.is_title_mis = false;
                }
            },

            is_title_mis: function (value) {
                if (value == true) {
                    this.is_title_mr = false;
                    this.is_title_company = false;
                }

            },


            is_individual: function (value) {
                this.is_company = !value;
            },
            is_company: function (value) {
                if (value) {
                    this.is_manager = false;
                    this.is_title_company = true;
                }
                this.is_individual = !value;
            },
            is_manager: function (value) {
                if (value) {
                    this.is_company = false;
                }
            },
            has_credit_invoice: function (value) {
                if (value) {

                    this.has_no_credit_invoice = false;
                }

                this.can_make_credit = value;

            },
            has_no_credit_invoice: function (value) {
                if (value) {
                    this.has_credit_invoice = false;
                }
            }

        }
    }
</script>


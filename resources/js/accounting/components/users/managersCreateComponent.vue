<template>
    <div class="">
        <div class="row">
            <div class="col-md-6">
                <div :class="{'has-error':errorFieldName=='branchId'}" class="form-group">
                    <accounting-select-with-search-layout-component
                            :default="managerData.branchId"
                            :default-index="managerData.branchId"
                            :identity="1"
                            :index="1"
                            :no_all_option="true"
                            :options="branches"
                            :placeholder="app.trans.branch"
                            :title="app.trans.branch"
                            @valueUpdated="branchHasBeenUpdated"
                            label_text="locale_name"
                    ></accounting-select-with-search-layout-component>

                    <small class="text-danger" v-show="errorFieldName=='branchId'">
                        {{errorFieldMessage}}
                    </small>
                </div>
            </div>
            <div class="col-md-6" v-if="departments.length>=1">
                <div :class="{'has-error':errorFieldName=='departmentId'}" class="form-group">
                    <accounting-select-with-search-layout-component
                            :default="managerData.departmentId"
                            :default-index="managerData.departmentId"
                            :identity="2"
                            :index="2"
                            :no_all_option="true"
                            :options="departments"
                            :placeholder="app.trans.department"
                            :title="app.trans.department"
                            @valueUpdated="departmentHasBeenUpdated"

                            label_text="locale_title"
                            v-show="departments.length>=1"
                    ></accounting-select-with-search-layout-component>
                    <small class="text-danger" v-show="errorFieldName=='departmentId'">
                        {{errorFieldMessage}}
                    </small>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div :class="{'has-error':errorFieldName=='arName'}" class="form-group">
                    <input :placeholder="app.trans.name_ar"
                           class="form-control arabic-input"
                           type="text"
                           v-model="managerData.arName"
                    >
                    <small class="text-danger" v-show="errorFieldName=='arName'">
                        {{errorFieldMessage}}
                    </small>

                </div>
            </div>
            <div class="col-md-6">
                <div :class="{'has-error':errorFieldName=='enName'}" class="form-group">
                    <input :placeholder="app.trans.name"
                           class="form-control "
                           type="text"
                           v-model="managerData.enName"
                    >

                    <small class="text-danger" v-show="errorFieldName=='enName'">
                        {{errorFieldMessage}}
                    </small>

                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div :class="{'has-error':errorFieldName=='email'}" class="form-group">
                    <input :placeholder="app.trans.email_address"
                           class="form-control arabic-input"
                           type="email"
                           v-model="managerData.email"
                    >
                    <small class="text-danger" v-show="errorFieldName=='email'">
                        {{errorFieldMessage}}
                    </small>

                </div>
            </div>
            <div class="col-md-6">
                <div :class="{'has-error':errorFieldName=='phoneNumber'}" class="form-group">
                    <input :placeholder="app.trans.phone_number"
                           class="form-control "
                           type="text"
                           v-model="managerData.phoneNumber"
                    >

                    <small class="text-danger" v-show="errorFieldName=='phoneNumber'">
                        {{errorFieldMessage}}
                    </small>

                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div :class="{'has-error':errorFieldName=='password'}" class="form-group">
                    <input :placeholder="app.trans.password"
                           class="form-control arabic-input"
                           type="text"
                           v-model="managerData.password"
                    >
                    <small class="text-danger" v-show="errorFieldName=='password'">
                        {{errorFieldMessage}}
                    </small>

                </div>

            </div>
            <div class="col-md-6">
                <div :class="{'has-error':errorFieldName=='confirmPassword'}" class="form-group">
                    <input :placeholder="app.trans.confirm_password"
                           class="form-control "
                           type="text"
                           v-model="managerData.confirmPassword"
                    >
                    <small class="text-danger" v-show="errorFieldName=='confirmPassword'">
                        {{errorFieldMessage}}
                    </small>
                </div>
            </div>
        </div>


        <accounting-managers-permissions-and-roles-component
                :init-permissions="managerPermissions"
                @pushPermissionsUpdated="pushPermissionsUpdated"
                v-if="editingManager!==true || managerUser.is_system_user!==true && managerUser.is_supervisor!==true"
        >
        </accounting-managers-permissions-and-roles-component>


        <accounting-managers-gateways-component
                :gateways='gateways'
                :init-gateways="managerGateways"
                @gatewaysUpdated="gatewaysUpdated"
        >

        </accounting-managers-gateways-component>
        <div class="row">
            <div class="col-md-2 col-md-offset-3">
                <button @click="validateData" class="btn btn-custom-primary" type="submit"><i
                        class="fa fa-save"></i>
                    <span v-if="editingManager==null"> {{ app.trans.create }}</span>
                    <span v-else> {{ app.trans.edit }}</span>
                </button>
            </div>
            <div class="col-md-3 col-md-offset-3">
                <a :href="app.BaseApiUrl + 'managers'" class="btn btn-default">
                    <i class="fa fa-undo"></i>
                    {{ app.reusableTranslator.cancel}}
                </a>
            </div>


        </div>
    </div>
</template>

<script>

    export default {
        props: ["branches", 'editingManager', 'manager', 'managerBranch', 'managerDepartment',
            'managerPermissions', 'managerUser', 'gateways', 'managerGateways'],
        data: function () {
            return {
                errorFieldName: "",
                errorFieldMessage: "",
                departments: [],
                app: {
                    reusableTranslator: trans('reusable-translator'),
                    primaryColor: metaHelper.getContent('primary-color'),
                    secondColor: metaHelper.getContent('second-color'),
                    appLocate: metaHelper.getContent('app-locate'),
                    trans: trans('users-page'),
                    messages: trans('messages'),
                    validation: trans('validation'),
                    table_trans: trans('table'),
                    datetimetrans: trans('datetime'),
                    datatableBaseUrl: metaHelper.getContent("datatableBaseUrl"),
                    BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
                },
                managerData: {
                    branchId: 0,
                    departmentId: 0,
                    arName: "",
                    enName: "",
                    email: "",
                    password: "",
                    confirmPassword: "",
                    pinCode: "",
                    phoneNumber: "",
                    gateways: [],
                    permissions: [],
                }
            };
        },

        created: function () {
            if (this.editingManager != null && this.editingManager == true) {
                this.initEditManager();
            }
        },
        methods: {

            initEditManager() {
                this.managerData.branchId = this.manager.branch_id;
                this.departments = this.managerBranch.departments;
                this.managerData.departmentId = this.manager.department_id;
                this.managerData.permissions = this.managerPermissions;
                this.managerData.enName = this.manager.name;
                this.managerData.arName = this.manager.name_ar;
                this.managerData.email = this.manager.email;
                this.managerData.phoneNumber = this.managerUser.phone_number;
            },
            gatewaysUpdated(e) {
                this.managerData.gateways = e.gateways;
            },

            branchHasBeenUpdated(event) {
                this.managerData.branchId = event.value.id;
                this.departments = event.value.departments;
            },
            departmentHasBeenUpdated(event) {
                this.managerData.departmentId = event.value.id;
            },
            pushPermissionsUpdated(e) {
                this.managerData.permissions = e.permissions;
            },
            pushCreationRequest() {
                var data = {
                    email: this.managerData.email,
                    password: this.managerData.password,
                    password_confirmation: this.managerData.confirmPassword,
                    branch_id: this.managerData.branchId,
                    department_id: this.managerData.departmentId,
                    phone_number: this.managerData.phoneNumber,
                    name_ar: this.managerData.arName,
                    name: this.managerData.enName,
                    permissions: this.managerData.permissions,
                    gateways: this.managerData.gateways,
                };


                var appVm = this;
                axios.post(this.app.BaseApiUrl + 'managers', data)
                    .then(function (response) {
                        location.href = appVm.app.BaseApiUrl + 'managers';
                    })
                    .catch(function (error) {
                        alert(error.response);


                    });

            },
            pushUpdateRequest() {
                var data = {
                    email: this.managerData.email,
                    id: this.manager.id,
                    password: this.managerData.password,
                    password_confirmation: this.managerData.confirmPassword,
                    branch_id: this.managerData.branchId,
                    department_id: this.managerData.departmentId,
                    phone_number: this.managerData.phoneNumber,
                    name_ar: this.managerData.arName,
                    name: this.managerData.enName,
                    permissions: this.managerData.permissions,
                    gateways: this.managerData.gateways,
                };


                var appVm = this;
                axios.patch(this.app.BaseApiUrl + 'managers/' + this.manager.id, data)
                    .then(function (response) {
                        location.href = appVm.app.BaseApiUrl + 'managers';
                    })
                    .catch(function (error) {
                        alert(error.response);


                    });

            },

            validateData() {
                if (this.managerData.branchId <= 0) {
                    this.errorFieldName = 'branchId';
                    this.errorFieldMessage = this.app.validation.required;
                    return false;
                }

                if (this.managerData.departmentId <= 0) {
                    this.errorFieldName = 'departmentId';
                    this.errorFieldMessage = this.app.validation.required;
                    return false;
                }


                if (this.managerData.arName == "") {
                    this.errorFieldName = 'arName';
                    this.errorFieldMessage = this.app.validation.required;
                    return false;
                }

                if (this.managerData.arName.length <= 2) {
                    this.errorFieldName = 'arName';
                    this.errorFieldMessage = 'يجب ان لا يقل الاسم عن ثلاثة احرف';
                    return false;
                }

                if (this.managerData.enName == "") {
                    this.errorFieldName = 'enName';
                    this.errorFieldMessage = this.app.validation.required;
                    return false;
                }

                if (this.managerData.enName.length <= 2) {
                    this.errorFieldName = 'enName';
                    this.errorFieldMessage = 'يجب ان لا يقل الاسم عن ثلاثة احرف';
                    return false;
                }


                if (this.managerData.email == "") {
                    this.errorFieldName = 'email';
                    this.errorFieldMessage = this.app.validation.required;
                    return false;
                }

                if (this.managerData.email.length <= 2) {
                    this.errorFieldName = 'email';
                    this.errorFieldMessage = 'يجب ان لا يقل البريد عن ثلاثة احرف';
                    return false;
                }


                if (this.editingManager === null) {
                    if (this.managerData.password == "") {
                        this.errorFieldName = 'password';
                        this.errorFieldMessage = this.app.validation.required;
                        return false;
                    }

                    if (this.managerData.password.length <= 6) {
                        this.errorFieldName = 'password';
                        this.errorFieldMessage = 'يجب ان لا تقل كل المرور عن ستة احرف ';
                        return false;
                    }


                }

                if (this.managerData.password != this.managerData.confirmPassword) {
                    this.errorFieldName = 'confirmPassword';
                    this.errorFieldMessage = 'لا تطابق كلمة المرور';
                    return false;
                }


                if (this.editingManager !== null && this.editingManager === true) {
                    this.pushUpdateRequest();
                } else {
                    this.pushCreationRequest();

                }

            }
        }
    }
</script>
<style scoped>
    input[type=text],
    input[type=email],
    input[type=password] {
        text-align: center !important;
        height: 43px !important;
    }
</style>
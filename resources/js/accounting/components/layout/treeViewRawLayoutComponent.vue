<template>
    <div class='raw-child'>
        <div>
            <div class="row">
                <div class="col-md-3">
                    <button @click="showAndHideChildren" v-show="hasChildren && !showChildren"><i
                            class="fa fa-arrow-left icon"></i></button>
                    <button @click="showAndHideChildren" v-show="hasChildren && showChildren"><i
                            class="fa fa-arrow-down icon"></i></button>

                    <span>{{ itemData.locale_name}}</span>
                </div>
                <div class="col-md-4">
                    <a :href="app.BaseApiUrl + 'categories/create?parent_id='+ itemData.id"
                       class="btn btn-sm btn-default"
                       v-if="canCreate"><i
                            class="fa fa-plus-circle"></i> {{ app.trans.create}}
                    </a>
                    <a :href="app.BaseApiUrl + 'categories/' + itemData.id + '/filters'" class="btn btn-sm btn-default"
                       v-if="canEdit" v-show="!hasChildren"><i class="fa fa-bars"></i> {{
                        app.trans.edit_filters}}
                    </a>
                    <a :href="app.BaseApiUrl + 'categories/' + itemData.id + '/clone'" class="btn btn-sm btn-default"
                       v-if="canCreate"><i class="fa fa-copy"></i> {{
                        app.trans.copy}}
                    </a>

                    <a :href="app.BaseApiUrl + 'categories/'+ itemData.id + '/edit'" class="btn btn-sm btn-default"
                       v-if="canEdit"><i class="fa fa-edit"></i> {{ app.trans.edit}}</a>
                    <a @click="deleteItemClicked" class="btn btn-sm btn-danger"
                       v-if="canDelete"><i class="fa fa-trash"></i> {{ app.trans.delete}}</a>
                </div>
            </div>
        </div>
        <div :key="childItem.id" v-for="childItem in itemData.children" v-show="showChildren">
            <accounting-treeview-raw-layout-component
                    :can-create="canCreate"
                    :can-delete="canDelete"
                    :can-edit="canEdit"
                    :can-view="canView"
                    :item="childItem"
                    :key="childItem.id"
            ></accounting-treeview-raw-layout-component>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["item", 'canCreate', 'canView', 'canEdit', 'canDelete'],
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

                hasChildren: false,
                itemData: null,
                showChildren: false
            };
        },
        created() {
            this.itemData = this.item;
            this.hasChildren = this.itemData.children != null && this.itemData.children.length >= 1 ? true : false;
        },
        methods: {
            showAndHideChildren() {
                this.showChildren = !this.showChildren;

            },
            deleteItemClicked() {


                let options = {
                    html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
                    loader: false, // set to true if you want the dailog to show a loader after click on "proceed"
                    reverse: false, // switch the button positions (left to right, and vise versa)
                    okText: this.app.messages.ok_button_txt,
                    cancelText: this.app.messages.close_pop_txt,
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'hard', // coming soon: 'soft', 'hard'
                    verification: 'delete',
                    // for hard confirm, user will be prompted to type this to enable the proceed button
                    verificationHelp: 'اكتب "[+:verification]" لتأكيد عملية الحذف ',
                    // Verification help text. [+:verification] will be matched with 'options.verification' (i.e 'Type "continue" below to confirm')
                    clicksCount: 3, // for soft confirm, user will be asked to click on "proceed" btn 3 times before actually proceeding
                    backdropClose: false, // set to true to close the dialog when clicking outside of the dialog window, i.e. click landing on the mask
                    customClass: 'danger'
                    // Custom class to be injected into the parent node for the current dialog instance
                };

                var appVm = this;

                this.$dialog
                    .confirm(this.app.messages.confirm_msg, options)
                    .then(dialog => {

                        axios.delete(appVm.app.BaseApiUrl + "categories/" + appVm.itemData.id)
                            .then(function (response) {
                                window.location.reload();
                            })
                            .catch(function (error) {

                            });

                    })
                    .catch(() => {

                    });
            },

        }
    }
</script>
<style>
    .raw-child {
        font-weight: bold;
        font-size: 18px;
        padding: 6px;
        padding-right: 50px;
        background-color: white;
        border-top: 1px solid #f7f4f4;
        margin-bottom: 5px;
    }

    .icon {
        border-radius: 50%;
        border: 1px solid;
        padding: 3px;
        font-size: 19px;
        margin-left: 8px;
        background: #0883e3;
        color: white;

    }
</style>
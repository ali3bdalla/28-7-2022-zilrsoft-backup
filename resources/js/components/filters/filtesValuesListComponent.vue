<template>


    <div class="">
        <div class="message">
            <div class="message-header">
                <a href="/management/filters" class="button"><i class="fa fa-undo"></i>&nbsp; {{ reusable_translator
                    .cancel}}</a>
            </div>

            <div class="message-body">
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <input class="input" name="input" ref="filter_en_name" type="text"
                                   v-model="filter.name">
                        </div>

                    </div>

                    <div class="column">
                        <div class="field">
                            <input class="input rtl" name="input" ref="filter_ar_name" type="text"
                                   v-model="filter.ar_name">
                        </div>
                    </div>
                    <div class="column is-one-fifth">
                        <button @click="updateFilterData" class="button is-primary is-fullwidth"><i
                                class='fa fa-save'></i> &nbsp; &nbsp{{ translator.update_name }}
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <div class="message">


            <div class="card">
                <div class="card-content has-background-dark">
                    <div class="columns">
<!--                        <div class="column">-->
<!--&lt;!&ndash;                            <span class="subtitle has-text-white"><i class='fa fa-bars'></i> {{&ndash;&gt;-->
<!--&lt;!&ndash;                                translator.manage_values&ndash;&gt;-->
<!--&lt;!&ndash;                                }}</span>&ndash;&gt;-->
<!--                        </div>-->
                        <div class="column">
                            <button @click="createNewFilterValue" class="button is-primary"><i
                                    class="fa fa-plus-circle"></i>&nbsp; {{ translator.create_value }}
                            </button>
                        </div>
                        <div class="column">
                            <input :placeholder="translator.search_on_values" @keyup="searchOnValues" class="input"
                                   type="text"
                                   v-model="search">
                        </div>
                    </div>
                </div>


            </div>
            <div class="message-body">
                <table class="table text-center table-hover table-striped table-bordered ">
                    <thead class="dark-head">
                    <th>{{ translator.id }}</th>
                    <th>{{ translator.date }}</th>
                    <th>{{ translator.creator }}</th>
                    <th>{{ translator.name }}</th>
                    <th>{{ translator.ar_name }}</th>
                    <th>{{ translator.update }}</th>
                    <th>{{ translator.delete }}</th>
                    <th>{{ translator.copy }}</th>
                    </thead>
                    <tbody>
                    <tr :key="value.id" v-for="(value,index) in values">
                        <td v-text="value.id"></td>
                        <td style="direction: ltr !important;" v-text="value.created_at"></td>
                        <td v-text="">{{ value.creator == null ? '' : value.creator.name}}</td>
                        <td>
                            <div class="field">
                                <input :ref="'en_' + value.id" @keyup="startTyping" class="input" name="input"
                                       placeholder="english value" type="text" v-model="value.name">
                            </div>
                        </td>
                        <td>
                            <div class="field">
                                <input :ref="'ar_' + value.id" @keyup="startTyping" class="input rtl" name="input"
                                       placeholder="arabic value" type="text" v-model="value.ar_name">
                            </div>
                        </td>
                        <td>
                            <button @click="updateValue(value,index)" class="button is-primary"><i
                                    class='fa fa-edit'></i> &nbsp; &nbsp;{{translator.update}}
                            </button>
                        </td>
                        <td>
                            <button @click="deleteValue(value)" class="button is-danger"><i class='fa fa-trash'></i>
                                &nbsp;{{translator.delete}}
                            </button>
                        </td>
                        <td>
                            <button @click="cloneValue(value)" class="button is-info"><i class='fa fa-copy'></i>
                                &nbsp; &nbsp;{{translator.copy}}
                            </button>
                        </td>

                    </tr>
                    </tbody>
                </table>

            </div>

            <!-- dialog -->
            <div class="text-center">
                <v-dialog
                        :dark="true"
                        :eager="true"
                        :full-width="true"
                        :hide-overlay="true"
                        :is-active="isPopOpened"
                        :smAndDown="true"
                        v-model="showCreateValueDialog"
                        width="700"
                >

                    <v-card>
                        <v-card-title
                                class="headline grey lighten-2"
                                primary-title
                        >
                            {{ translator.create_value }} ({{ filter.locale_name }})
                        </v-card-title>

                        <v-card-text>
                            <div class='columns'>
                                <div class="column">
                                    <div class="field is-fullwidth">
                                        <input :placeholder='translator.name' class="input is-fullwidth"
                                               style="direction:ltr"
                                               v-model="valueEnName"/>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="field is-fullwidth">
                                        <input :placeholder='translator.ar_name' class="input is-fullwidth"
                                               style="direction:rtl"
                                               v-model="valueArName"/>
                                    </div>

                                </div>
                            </div>
                        </v-card-text>

                        <v-divider></v-divider>

                        <v-card-actions>
                            <div class="flex-grow-1"></div>
                            <v-btn
                                    @click="showCreateValueDialog = false"
                                    class="button"
                                    text
                            >
                                {{ reusable_translator.cancel }}
                            </v-btn>
                            &nbsp;
                            &nbsp;
                            <v-btn
                                    @click="createNewFilterValueRequest"
                                    class="button is-primary is-right has-text-light"
                                    text

                            >
                                {{ reusable_translator.create }}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </div>

        </div>
    </div>

</template>
<script type="text/javascript">
    export default {
        props: ["values_list", 'obj_filter'],
        data: function () {
            return {
                messages:null,
                reusable_translator:null,
                translator: null,
                search: "",
                values: [],
                filter: null,
                valueArName: "",
                valueEnName: "",
                filterDataToCreateValueFor: null,
                showCreateValueDialog: false
            };
        },
        created: function () {
            this.values = this.values_list;
            this.filter = this.obj_filter;

            this.translator = JSON.parse(window.translator);
            this.messages = JSON.parse(window.messages);
            this.reusable_translator = JSON.parse(window.reusable_translator);
        },

        methods: {
            isPopOpened() {

            },
            updateValue(obj, index) {


                if (obj.name == "") {

                    this.$refs['en_' + obj.id][0].focus();
                    this.$refs['en_' + obj.id][0].classList.add("is-danger");
                    return;
                }

                if (obj.ar_name == "") {
                    this.$refs['ar_' + obj.id][0].focus();
                    this.$refs['ar_' + obj.id][0].classList.add("is-danger");
                    return;
                }


                this.sendUpdateValueRequest(obj);
            },

            startTyping(e) {
                e.srcElement.classList.remove('is-danger');
            },

            sendUpdateValueRequest(value) {
                var vm = this;
                axios.patch('/management/filters/values/update', value)
                    .then(function (response) {
                        vm.showPopMessage(value.name + ' has been updated');
                    })
                    .catch(function (error) {

                    })
                    .then(function () {
                        // always executed
                    });

            },

            searchOnValues() {
                if (this.search == "") {
                    this.values = this.values_list;
                } else {
                    var data = [];
                    this.values = this.values_list.filter(item => {
                        return item['name'].indexOf(this.search) > -1 || item['ar_name'].indexOf(this.search) > -1;
                    });


                }

            },

            showPopMessage(message) {

                let options = {
                    html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
                    loader: false, // set to true if you want the dailog to show a loader after click on "proceed"
                    reverse: false, // switch the button positions (left to right, and vise versa)
                    okText: this.messages.ok_button,
                    cancelText: 'Close',
                    animation: 'zoom', // Available: "zoom", "bounce", "fade"
                    type: 'basic', // coming soon: 'soft', 'hard'
                    verification: 'continue', // for hard confirm, user will be prompted to type this to enable the proceed button
                    verificationHelp: 'Type "[+:verification]" below to confirm', // Verification help text. [+:verification] will be matched with 'options.verification' (i.e 'Type "continue" below to confirm')
                    clicksCount: 3, // for soft confirm, user will be asked to click on "proceed" btn 3 times before actually proceeding
                    backdropClose: false, // set to true to close the dialog when clicking outside of the dialog window, i.e. click landing on the mask
                    customClass: '' // Custom class to be injected into the parent node for the current dialog instance
                };


                this.$dialog
                    .alert(message,options)
                    .then(dialog => {


                    })
                    .catch(() => {

                    });
            },

            createNewFilterValue() {
                this.showCreateValueDialog = true;

            },

            deleteValue(value) {

                var vm = this;
                this.$dialog
                    .confirm('are you sure ?', {
                        // And a dialog object will be passed to the then() callback
                    })
                    .then(dialog => {

                        axios.delete('/management/filters/values/delete/' + value.id)
                            .then(function (response) {
                                vm.values_list.splice(vm.values_list.indexOf(value), 1);
                                console.log(response.data);

                            })
                            .catch(function (error) {
                                loader.hide();
                                alert(error.response.data.message);
                                // console.log(error.response);
                            });


                    })
                    .catch(() => {

                    });

            },

            cloneValue(value) {
                this.valueEnName = value.name;
                this.valueArName = value.ar_name;
                this.showCreateValueDialog = true;

            },

            updateFilterData() {
                if (this.filter.name == "") {

                    this.$refs.filter_en_name.focus();
                    this.$refs.filter_en_name.classList.add("is-danger");
                    return;
                } else {
                    this.$refs.filter_en_name.classList.remove("is-danger");
                }

                if (this.filter.ar_name == "") {
                    this.$refs.filter_ar_name.focus();
                    this.$refs.filter_ar_name.classList.add("is-danger");
                    return;
                } else {
                    this.$refs.filter_ar_name.classList.remove("is-danger");
                }

                var vm = this;

                axios.patch('/management/filters/' + this.filter.id, this.filter)
                    .then(function (response) {
                        // var index = vm.filterList.indexOf(vm.filterDataToCreateValueFor);
                        // console.log(index);

                        // vm.values.push(response.data);
                        vm.filter = response.data;
                        vm.showPopMessage(vm.messages.updated);
                        // console.log(response.data);
                    })
                    .catch(function (error) {
                        loader.hide();
                        alert(error.response.data.message);
                        // console.log(error.response);
                    });

            },

            createNewFilterValueRequest() {
                // console.log('click..');
                var vm = this;
                let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                });

                axios.post('/management/filters/values/create', {
                    filter_id: this.filter.id,
                    name: this.valueEnName,
                    ar_name: this.valueArName,
                })
                    .then(function (response) {
                        // var index = vm.filterList.indexOf(vm.filterDataToCreateValueFor);
                        // console.log(index);
                        vm.showCreateValueDialog = false;
                        vm.valueArName = "";
                        vm.valueEnName = "";
                        // // console.log(response.data);
                        loader.hide();
                        vm.values_list.push(response.data);
                        // vm.values.push(response.data);

                        // console.log(response.data);
                    })
                    .catch(function (error) {
                        loader.hide();
                        alert(error.response.data.message);
                        // console.log(error.response);
                    });


            }


        }
    }
</script>
<style scoped>
    th {
        text-align: center !important
    }

    .rtl {
        direction: rtl;
        text-align: right
    }

    .single-item {
        padding: 5px;
        border-bottom: 1px solid #eee;
    }

</style>

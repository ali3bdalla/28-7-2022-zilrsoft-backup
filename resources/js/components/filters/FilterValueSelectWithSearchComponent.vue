<template>
    <div class="columns">
        <div class="column is-one-fifth">
            <div class="label has-text-dark" style="font-size: 16px !important;"  v-text="filter.locale_name">
            </div>

        </div>
        <div class="column is-three-fifths">
            <div class="filter_area">
                <div class="label" v-show="isListOpened">
                    <button @click="hideTheList" class="button is-danger delete danger"></button>
                </div>
                <div class="">

                    <input
                            :disabled="viewOnly"
                            style="font-size: 16px;font-widget:bold"
                            @blur="blurFromField" @focus="focusOnField" @keyup="searchOnList" autocomplete=""
                           class="form-control" type="text" v-model="search"/>
                </div>
                <div :id="'filter_result_' + filter.id" class="result_list">
                    <ul class="list-group">
                        <li :class="{'active':selected==item.id}" :key="item.id" :value="item.id" @click="setFilterValue(item.id)"
                            class="list-group-item" v-for="item in items"
                            v-text="item.locale_name"></li>

                    </ul>


                </div>
            </div>

        </div>


        <div class="column" v-if="!viewOnly">
            <div class="lable">
                <toggle-button :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}"   :filter-index="index" :font-size="14"
                               :height='30' :sync="true" :width='70' @change="filterNameAppearOnItemNameUpdated"
                                v-model="filter.isChecked">
                </toggle-button>
            </div>
        </div>


        <div class="column" v-if="!viewOnly">
            <div class="lable">
                <button @click="createNewFilterValue" class="button is-info"><i class="fa fa-plus-circle"></i></button>
            </div>
        </div>



        <!-- dialog -->
        <div justify="center">
            <v-dialog
                    :dark="true"
                    :eager="true"
                    :full-width="true"
                    :hide-overlay="true"
                    :isActive="activeList"
                    smAndDown="true"
                    v-model="showCreateValueDialog"
                    width="700"
            >

                <v-card>
                    <v-card-title
                            class="headline grey lighten-2"
                            primary-title
                    >
                        ({{ filterDataToCreateValueFor.name }})
                    </v-card-title>

                    <v-card-text>
                        <div class='columns'>
                            <div class="column">
                                <div class="field is-fullwidth">
                                    <input @keyup.13="createNewFilterValueRequest"
                                           class="input is-fullwidth"
                                           style="direction:ltr"

                                          :placeholder='reusable_translator.name' v-model="valueEnName"/>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field is-fullwidth">
                                    <input

                                            @keyup.13="createNewFilterValueRequest"
                                            class="input is-fullwidth" :placeholder='reusable_translator.ar_name'
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
                            {{reusable_translator.cancel}}
                        </v-btn>
                        &nbsp;
                        &nbsp;
                        <v-btn
                                @click="createNewFilterValueRequest"
                                class="button is-primary is-right has-text-light"
                                text>
                            {{reusable_translator.create}}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>


    </div>
</template>

<script>
    export default {
        props: ["options", "default", 'index', 'filter', 'translator','messages','reusable_translator','disabled'],
        data: function () {
            return {
                viewOnly:false,
                isListOpened: false,
                isActive: true,
                search: '',
                old_search: '',
                selected: 0,
                allItems: [],
                showCreateValueDialog: false,
                filterDataToCreateValueFor: null,
                defaultValueObj: null,
                items: null,
                valueArName: "",
                valueEnName: "",
            };

        },
        created: function () {
            if(this.disabled)
            {
                this.viewOnly= true;
            }
            this.selected = this.default;
            this.items = this.options;
            this.allItems = this.options;
            this.filterDataToCreateValueFor = this.filter;
            this.defaultValueObj = helpers.getDataFromArrayById(this.options, this.default);
            if (this.defaultValueObj != null) {
                this.search = this.defaultValueObj.locale_name;
            }

            if(this.options.length==1)
            {
               this.setFilterValue(this.options[0].id);
                // this.selected = this.options[0].id;
                // this.search = this.options[0].locale_name;
            }
            // console.log('selected for ' + this.filter.id + '= ' + this.default);
            // console.log('data of the value ' + this.defaultValueObj.name);

        },
        methods: {
            activeList(e) {

            },
            setFilterValue(id) {
                this.isListOpened = false;
                this.selected = id;
                this.defaultValueObj = helpers.getDataFromArrayById(this.options, id);
                this.search = this.defaultValueObj.locale_name;
                this.items = this.options;
                // console.log(this.value);
                this.$emit('valueUpdated', {
                    value: id,
                    index: this.index
                });
                // console.log('consolle..')
                this.hideList();
            },

            filterNameAppearOnItemNameUpdated() {

                this.$emit('updateItemName');

            },

            createNewFilterValue() {
                this.showCreateValueDialog = true;
                this.filterDataToCreateValueFor = this.filter;

            },

            hideTheList() {
                this.isListOpened = false;
                // this.selected = id;
                // this.defaultValueObj = helpers.getDataFromArrayById(this.options,id);
                this.search = this.old_search;
                // this.items = this.options;
                // console.log(this.value);
                // this.$emit('valueUpdated',{
                //     value:id,
                //     index:this.index
                // });
                // console.log('consolle..')
                this.hideList();

            },
            createNewFilterValueRequest() {
                // console.log('click..');
                var vm = this;
                let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                });

                axios.post('/management/filters/values/create', {
                    filter_id: this.filterDataToCreateValueFor.id,
                    name: this.valueEnName,
                    ar_name: this.valueArName,
                })
                    .then(function (response) {
                        // console.log(response.data);
                        // var index = vm.filterList.indexOf(vm.filterDataToCreateValueFor);
                        // // console.log(index);
                        // vm.selectedFilterValue.set(vm.filterDataToCreateValueFor.id,response.data.id);
                        vm.showCreateValueDialog = false;
                        vm.valueArName = "";
                        vm.valueEnName = "";
                        // console.log(response.data);
                        loader.hide();
                        vm.items.push(response.data);
                        // vm.allItems.push(response.data);
                        vm.search = response.data.locale_name;
                        vm.setFilterValue(response.data.id);
                        // console.log(response.data);
                    })
                    .catch(function (error) {
                        loader.hide();
                        alert(error.response.data.message);
                        // console.log(error.response);
                    });


            },


            searchOnList() {
                if (this.search == '') {
                    this.items = this.allItems;
                } else {
                    this.items = helpers.searchInArrayByArOrEnName(this.search, this.allItems);
                }


                // console.lo/g(this.search);
            },


            focusOnField() {
                this.isListOpened = true;
                this.old_search = this.search;
                this.search = '';
                $('.result_list').css('display', 'none');
                $('#filter_result_' + this.filter.id).css('display', 'block');
            },
            hideList() {
                $('#filter_result_' + this.filter.id).css('display', 'none');
            },
            blurFromField() {

            },


        }
    }
</script>


<style scoped>
    .result_list {
        background: rebeccapurple;
        overflow: scroll;
        background-color: white;
        max-height: 300px;
        min-height: 50px;
        z-index: 1000;
        position: absolute;
        width: 100%;
        display: none;
    }

    .result_list ul {
        margin-left: 0;
        margin-top: 0;
    }

    .result_list ul li {
        cursor: pointer;
    }

    .filter_area {
        position: relative;
    }
</style>

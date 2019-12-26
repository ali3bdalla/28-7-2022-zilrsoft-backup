<template>
    <div class="column">
        <div class="filter_area">

            <div class="label" v-show="isListOpened">
                <span @click="hideTheList" class="button is-danger delete danger"></span>
            </div>

            <div class="input-group">

                    <span :class="defaultTitleClass" class="input-group-addon">
                        {{title}}</span>
                <input :placeholder="placeholder" @focus="focusOnField" @keyup="searchOnList"
                       autocomplete="search_field"
                       class="form-control" type="text" v-model="search"/>
            </div>
            <div :id="'filter_result_'+identity" class="result_list">
                <ul class="list-group">
                    <li :class="{'active':selected==0}" :key="0" @click="valueUpdated(emptyObject)"
                        class="list-group-item"
                        v-if="no_all_option!=true" value="0">
                        الكل
                    </li>

                    <li :class="{'active':selected==item.id}" :key="item.id" :value="item.id"
                        @click="valueUpdated(item)" class="list-group-item" v-for="item in items">
                        {{ item[label] }}
                    </li>
                </ul>

            </div>
        </div>

    </div>

</template>

<script>
    export default {
        props: ["options", "default", 'index', 'placeholder', 'identity', 'title', 'label_text',
            'title_class', 'no_all_option',
            'defaultIndex', 'helperValue'],
        data: function () {
            return {
                emptyObject: {
                    id: 0,
                    locale_name: 'الكل',
                },
                isListOpened: false,
                search: '',
                old_search: '',
                selected: 1,
                label: 'name',
                defaultTitleClass: 'has-background-primary has-text-white',
            };

        },
        mounted: function () {
            if(this.defaultIndex!=null)
                this.someoneUpdateId(this.defaultIndex);
        },
        created: function () {


            if (this.title_class != null) {
                this.defaultTitleClass = this.title_class;
            }


            if (this.label_text != null) {
                this.label = this.label_text;
            }


            this.id = this.identity;
            this.items = this.options;

            //


        },
        methods: {

            hideTheList() {
                this.isListOpened = false;
                this.search = this.old_search;

                this.hideList();

            },

            someoneUpdateId(id) {
                var item = helpers.getDataFromArrayById(this.items, id);
                if (item != null) {
                    this.valueUpdated(item);
                }

            },


            valueUpdated(item) {
                this.isListOpened = false;
                this.selected = item.id;
                this.search = item[this.label];
                this.items = this.options;
                this.$emit('valueUpdated', {
                    value: item,
                    helperValue: this.helperValue
                });

                this.hideList();
            },
            //
            //


            searchOnList() {
                if (this.search == '') {
                    this.items = this.options;
                } else {
                    this.items = helpers.searchInArrayByArOrEnName(this.search, this.options);
                }
            },


            focusOnField() {
                this.isListOpened = true;
                this.old_search = this.search;
                this.search = '';
                this.$emit('listFocused');
                $('.result_list').css('display', 'none');
                $('#filter_result_' + this.id).css('display', 'block');
            },
            hideList() {
                $('#filter_result_' + this.id).css('display', 'none');
            },


        },

        watch: {
            default: function (value) {
                if (value != null && value) {
                    this.selected = this.value;
                    this.someoneUpdateId(value);
                }

            }
        }
    }
</script>


<style scoped>
    .result_list {
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

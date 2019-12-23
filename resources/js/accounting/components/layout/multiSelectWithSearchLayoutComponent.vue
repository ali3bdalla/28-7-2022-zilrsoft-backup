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

                    <li :class="{'active':selectedList.includes(item)}" :key="item.id" :value="item.id"
                        @click="selectOrDeselectItem(item,index)" class="list-group-item" v-for="(item,index) in items">
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

                selectedList: [],
                isListOpened: false,
                search: '',
                label: 'name',
                defaultTitleClass: 'has-background-primary has-text-white',
            };

        },
        mounted: function () {
            // this.selected = this.default;
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

            selectOrDeselectItem(item, index) {
                if (this.selectedList.includes(item)) {
                    this.selectedList.splice(this.selectedList.indexOf(item), 1);
                } else {
                    this.selectedList.push(item);

                }
                this.updateLabelTitle();
                this.valueUpdated();
            },


            updateLabelTitle() {
                var title = "";
                var len = this.selectedList.length;
                for (var i = 0; i < len; i++) {
                   title =  title.concat(" , " +  this.selectedList[i][this.label]);
                }

                this.search = title;

            },


            valueUpdated() {
                this.isListOpened = false;
                this.items = this.options;
                this.$emit('valueUpdated', {
                    items: this.selectedList,
                });

                this.hideList();
            },

            searchOnList() {
                if (this.search == '') {
                    this.items = this.options;
                } else {
                    this.items = helpers.searchInArrayByArOrEnName(this.search, this.options);
                }
            }
            ,


            focusOnField() {
                this.isListOpened = true;
                this.old_search = this.search;
                this.search = '';
                this.$emit('listFocused');
                $('.result_list').css('display', 'none');
                $('#filter_result_' + this.id).css('display', 'block');
            }
            ,
            hideList() {
                $('#filter_result_' + this.id).css('display', 'none');
            }
            ,


        },

        watch: {}
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

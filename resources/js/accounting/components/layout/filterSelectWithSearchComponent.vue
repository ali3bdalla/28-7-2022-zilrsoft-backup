<template>
    <div class="row">
        <div class="col-md-2 label-txt col-md-offset-1">
            <b>{{filter.locale_name}}</b>
        </div>
        <div class="col-md-6">
            <div class="filter_area">
                <div class="label" v-show="isListOpened">
                    <button @click="hideTheList" class="button is-danger delete danger"></button>
                </div>
                <div class="">

                    <input
                            :disabled="viewOnly"
                            @blur="blurFromField"
                            @focus="focusOnField" @keyup="searchOnList" autocomplete="" class="form-control"
                            style="font-size: 16px;font-widget:bold" type="text" v-model="search"/>
                </div>
                <div :id="'filter_result_' + filter.id" class="result_list">
                    <ul class="list-group">
                        <li :class="{'active':selected==0}" @click="setFilterValue(0,-1)" class="list-group-item"
                            value="0">
                            {{ noThing}}
                        </li>
                        <li :class="{'active':selected==item.id}" :key="item.id" :value="item.id"
                            @click="setFilterValue(item.id,index)"
                            class="list-group-item" v-for="(item,index) in items"
                            v-text="item.locale_name"></li>

                    </ul>
                </div>
            </div>

        </div>

        <div class="col-md-1" v-if="!viewOnly">
            <div class="label">
                <toggle-button :filter-index="index" :font-size="14" :height='30'
                               :labels="{checked: reusable_translator.radio_checked,
                                   unchecked: reusable_translator.unchecked}" :sync="true" :width='70'
                               @change="filterNameAppearOnItemNameUpdated"
                               v-model="filter.is_checked">
                </toggle-button>
            </div>
        </div>
        <div class="col-md-1 text-center" v-if="!viewOnly">
            <div class="label">
                <button @click="createNewFilterValue" class="btn btn-custom-primary  btn-sm" v-if="canCreate"><i
                        class="fa fa-plus-circle"></i></button>

            </div>

        </div>
        <div class="col-md-1 text-center" v-if="!viewOnly">
            <div class="label">
                <button @click="editFilterValue(index)" class="btn btn-custom-primary btn-sm" v-if="canEdit"
                        v-show="selected>=0"><i
                        class="fa fa-edit"></i></button>

            </div>

        </div>
        <div class="modal" role="dialog" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Modal body text goes here.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button">Save changes</button>
                        <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div justify="center" data-app>
            <v-dialog

                    v-model="showCreateValueDialog"
                     fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
      dark
                    >

                <v-card>
                    <v-card-title class="headline grey lighten-2" primary-title>
                        ({{ filterDataToCreateValueFor.locale_name }})
                    </v-card-title>

                    <v-card-text>
                        <div class='row'>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input
                                            :placeholder='reusable_translator.ar_name'
                                            @keyup.13="createNewFilterValueRequest" class="form-control"
                                            style="direction:rtl"
                                            v-model="valueArName"/>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input :placeholder='reusable_translator.name'
                                           @keyup.13="createNewFilterValueRequest"
                                           class="form-control"
                                           style="direction:ltr" v-model="valueEnName"/>
                                </div>
                            </div>

                        </div>
                    </v-card-text>

                    <v-card-actions>

                        <div class="row">
                            <div class="col-md-6 text-center">
                                <button
                                        @click="createNewFilterValueRequest"
                                        class="btn btn-custom-primary">
                                    {{reusable_translator.create}}
                                </button>
                            </div>
                            <div class="col-md-6 text-center">
                                <v-btn
                                        @click="showCreateValueDialog = false"
                                        class="btn btn-default"
                                        text>
                                    {{reusable_translator.cancel}}
                                </v-btn>
                            </div>
                        </div>
                    </v-card-actions>
                </v-card>
            </v-dialog>

        </div>
    </div>
</template>

<script>

export default {

  props: ['options', 'default', 'index', 'filter', 'translator', 'messages', 'reusable_translator',
    'disabled', 'canEdit', 'canCreate'],
  data: function () {
    return {
      app: {
        primaryColor: metaHelper.getContent('primary-color'),
        secondColor: metaHelper.getContent('second-color'),
        appLocate: metaHelper.getContent('app-locate'),
        trans: trans('items-page'),
        messages: trans('messages'),
        dateTimeTrans: trans('datetime'),
        datatableBaseUrl: metaHelper.getContent('datatableBaseUrl'),
        BaseApiUrl: metaHelper.getContent('BaseApiUrl'),
        defaultVatSaleValue: 5,
        defaultVatPurchaseValue: 5
      },
      activeIndex: -1,
      noThing: 'الغاء الاختيار',
      viewOnly: false,
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
      valueArName: '',
      valueEnName: '',
      filterValueUpdateMode: 'edit'
    }
  },
  created: function () {
    // console.log(this.$props)
    if (this.disabled) {
      this.viewOnly = true
    }
    this.selected = this.default
    this.items = this.options
    this.allItems = this.options
    this.filterDataToCreateValueFor = this.filter
    this.defaultValueObj = helpers.getDataFromArrayById(this.options, this.default)
    if (this.defaultValueObj != null) {
      this.search = this.defaultValueObj.locale_name
    }

    if (this.options.length === 1) {
      this.setFilterValue(this.options[0].id)
    }

    // http://196.202.134.90/SMSbulk/webacc.aspx
  },
  methods: {
    activeList (e) {

    },
    setFilterValue (id, index) {
      this.isListOpened = false
      this.selected = id
      this.activeIndex = index !== -1 ? db.model.index(this.allItems, id) : index

      if (index === -1) {
        this.$emit('valueUpdated', {
          value: id,
          index: this.index
        })
      } else {
        this.defaultValueObj = helpers.getDataFromArrayById(this.options, id)
        this.search = this.defaultValueObj.locale_name
        this.items = this.options
        this.$emit('valueUpdated', {
          value: id,
          index: this.index
        })
      }

      this.hideList()
    },

    filterNameAppearOnItemNameUpdated () {
      this.$emit('updateItemName')
    },

    createNewFilterValue () {
      this.filterValueUpdateMode = 'create'
      this.showCreateValueDialog = true
      this.valueArName = ''
      this.valueEnName = ''
      this.filterDataToCreateValueFor = this.filter
    },

    editFilterValue () {
      const value = db.model.find(this.allItems, this.selected)

      console.log(value)
      this.valueArName = value.ar_name
      this.valueEnName = value.name
      this.filterValueUpdateMode = 'edit'
      this.showCreateValueDialog = true
      this.filterDataToCreateValueFor = this.filter
    },

    hideTheList () {
      this.isListOpened = false
      this.search = this.old_search
      this.hideList()
    },
    createNewFilterValueRequest () {
      const vm = this
      const appVm = this
      const loader = this.$loading.show({
        container: this.fullPage ? null : this.$refs.formContainer
      })

      if (this.filterValueUpdateMode == 'create') {
        axios.post(this.app.BaseApiUrl + 'filter_values', {
          filter_id: this.filterDataToCreateValueFor.id,
          name: this.valueEnName,
          ar_name: this.valueArName
        })
          .then(function (response) {
            vm.showCreateValueDialog = false
            vm.valueArName = ''
            vm.valueEnName = ''
            // console.log(response.data);
            loader.hide()
            vm.items.push(response.data)
            // vm.allItems.push(response.data);
            vm.search = response.data.locale_name
            vm.setFilterValue(response.data.id, appVm.activeIndex)
            // console.log(response.data);
          })
          .catch(function (error) {
            loader.hide()
            alert(error.response.data.message)
            // console.log(error.response);
          })
      } else {
        axios.put(this.app.BaseApiUrl + 'filter_values/' + this.selected,
          {
            name: this.valueEnName,
            ar_name: this.valueArName,
            filter_id: this.filterDataToCreateValueFor.id
          })
          .then(function (response) {
            // var
            vm.showCreateValueDialog = false
            loader.hide()
            const value = response.data
            value.name = appVm.valueEnName
            value.ar_name = appVm.valueArName
            // console.log(value);
            appVm.items.splice(appVm.activeIndex, 1, value)
            appVm.setFilterValue(appVm.selected, appVm.activeIndex)
            appVm.valueArName = ''
            appVm.valueEnName = ''
          })
          .catch(function (error) {
            loader.hide()
            alert(error.response.data.message)
            console.log(error.response)
          })
      }
    },

    searchOnList () {
      if (this.search == '') {
        this.items = this.allItems
      } else {
        this.items = helpers.searchInArrayByArOrEnName(this.search, this.allItems)
      }

      // console.lo/g(this.search);
    },

    focusOnField () {
      this.isListOpened = true
      this.old_search = this.search
      this.search = ''
      $('.result_list').css('display', 'none')
      $('#filter_result_' + this.filter.id).css('display', 'block')
    },
    hideList () {
      $('#filter_result_' + this.filter.id).css('display', 'none')
    },
    blurFromField () {

    }

  }
}
</script>

<style scoped>
    input {
        text-align: right !important;
    }

    .label-txt {
        vertical-align: middle;
        text-align: right;
        margin-top: 9px;
    }

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

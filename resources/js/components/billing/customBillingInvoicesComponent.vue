<template>
    <div class="column">
        <div class="filter_area">

            <div class="label" v-show="isListOpened">
                <button class="button  is-small is-primary"  @click="hideTheList">
                    <i class="fa fa-check-square"></i>
                </button>

            </div>

            <div class="input-group" >

                <span class="input-group-addon has-background-primary has-text-white">{{title}}</span>
                <input  autocomplete="search_field" type="text"  class="form-control"
                        v-model="search"
                        @keyup="searchOnList" @focus="focusOnField" :placeholder="placeholder"/>
            </div>
            <div class="result_list" :id="'filter_result_'+identity">
                <ul class="list-group">
                    <li class="list-group-item"  v-for="(item,index) in items" :key="item.id"
                        :value="item.id"  @click="valueUpdated(item,index)" :class="{'active':item.isChecked}">
                        <div class="columns">
                            <div class="column is-three-quarters">
                                {{ item.prefix + item.id }}
                                 <div class='text-right text-center'>
                                    <div class="columns">
                                        <div class="column"> القيمة الكلية
                                         :    <strong>({{item.invoice.net}}$)</strong></div>
                                        <div class="column">المتبقي :
                                            <strong>({{item.invoice.remaining }}$)</strong></div>
                                    </div>
                                 </div>

                            </div>
                            <div class="column text-left"><input type="checkbox" :checked="item.isChecked"> </div>
                        </div>
                    </li>
                </ul>

            </div>
        </div>

    </div>

</template>

<script>
    export default {
        props:["default",'index','placeholder','identity','title','user','type'],
        data:function(){
            return {
                allItems:[],
                items:[],
                isChecked:true,
                isListOpened:false,
                search:'',
                old_search:'',
                selected:0,
                options:[],
                api_link:'',
            };

        },
        created:function(){
            this.id = this.identity;
            console.log(this.type);
            console.log('finish work');
           this.initData();
        },
        methods:{
            initData()
            {
                if(this.type=='sale')
                {
                    if(this.user!=null)
                    {
                        this.api_link = '/management/sales/list/unpaid/' + this.user.id + '/list';
                    }else
                    {
                        this.api_link = '/management/sales/list/unpaid/all';
                    }


                }else

                {
                    if(this.user!=null)
                    {
                        this.api_link = '/management/purchases/list/unpaid/' + this.user.id + '/list';
                    }else
                    {
                        this.api_link = '/management/purchases/list/unpaid/all';

                    }

                }
                this.loadData();
            },
            loadData()
            {
                var vm = this;

                  axios.get(this.api_link).then((response)=>{
                      var len = response.data.length;
                      vm.allItems = [];
                      vm.items = [];
                      for (var i =0 ; i < len;i++)
                      {
                          var item = response.data[i];
                          item.isChecked = false;
                          vm.allItems.push(item)

                          // vm.search = '';
                      }
                      vm.handelData();
                  });
            },

            handelData()
            {

                this.items = this.allItems;
            },

            hideTheList()
            {
                this.updateFieldName();
               this.isListOpened = false;
                // this.selected = id;
                // this.defaultValueObj = helpers.getDataFromArrayById(this.options,id);
                // this.search = this.old_search;
                // this.items = this.options;
                // console.log(this.value);
                // this.$emit('valueUpdated',{
                //     value:id,
                //     index:this.index
                // });
                // console.log('consolle..')

                this.hideList();

            },

            updateFieldName()
            {
                var len = this.items.length;
                for(var i = 0;i<len;i++)
                {

                    var one = this.items[i];
                    if(one.isChecked)
                    {

                        this.search+=one.prefix + one.id + ' , ';
                    }


                }
            },

            valueUpdated(item,index) {





                var index2 = this.allItems.indexOf(item);
                if (item.isChecked) {
                    item.isChecked = false;
                } else
                {
                    item.isChecked = true;
                }

                this.items.splice(index,1,item);
                this.allItems.splice(index2,1,item);



                if(this.user == null)
                {
                    var user;
                    if(this.type=='sale')
                    {
                        user=this.items[0].client;
                    }else
                    {
                        user=this.items[0].vendor;
                    }

                    this.items = [];

                    this.items.push(item);
                    this.$emit('invoicesUpdated',{
                              invoices:this.items,
                        user:user
                     });
                    this.hideTheList();
                    // this.hideList();

                }else{




                    this.$emit('invoicesUpdated',{
                        invoices:this.items,

                    });

                }

                //


            },
            //






            searchOnList(){
                if(this.search==''){
                    this.items = this.allItems;
                }else   {
                    this.items =this.searchInItems(this.search);
                }
            },

            searchInItems( name ) {
                var result = [];
                var arr = this.allItems;
                var len = arr.length, str = name.toLowerCase();
                for ( var i = 0; i < len; i++ ) {
                    // console.log(arr[i].name.toLowerCase());
                    // console.log(name.toLowerCase());
                    var full_name = arr[i].prefix.toLowerCase().concat(String(arr[i].id));

                    if (String(arr[i].id).toLowerCase().match(str) || arr[i].prefix.toLowerCase().match(str) || full_name.toLowerCase().match(str) )
                    {
                        result.push(arr[i]);
                    }
                }

                return result;
            },

            focusOnField(){
                this.isListOpened = true;
                this.old_search = this.search;
                this.search = '';
                $('.result_list').css('display','none');
                $('#filter_result_'+ this.id).css('display','block');
            },
            hideList(){
                $('#filter_result_' + this.id).css('display','none');
            },



        },
        watch:{
            user:function (value) {
                if(value!=null)
                {
                    this.loadData();
                }
                //
            }
        }
    }
</script>



<style scoped>
    .result_list {
        overflow:scroll;
        background-color:white;
        max-height: 300px;
        min-height:50px;
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

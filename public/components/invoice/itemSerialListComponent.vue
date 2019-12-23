<template>
    <div v-if="item.is_need_serial" :id="'pop_' + item_index">
        <!-- template for the modal component -->
        <modal :name="'serialList_' + index"  :id="'serialList_' + index" :scrollable="true" width="70%" height="70%">
            <div class="box has-background-dark has-text-white">
                <div class="" >


                    <div class="columns">
                        <div class="column" v-show="!is_range">
                            <input type="text" ref="serial_field_ref" :id="'serial_field_' + item_index"
                                   :class="{'is-danger':errorMessage!=''}"
                                   class="input"
                                   v-model="serial_field"

                                   @keyup="clearFieldFromData"
                                   @keyup.13="createNewSerialNumber"
                                   :placeholder="translator.add_serial"/>
                            <p class="help is-danger is-center" v-show="errorMessage!=''"
                               v-text="errorMessage"></p>
                        </div>


                        <div class="column" v-show="is_range">
                            <input type="text" ref="range_start_serial_ref"
                                   class="input"
                                   v-model="range_start_serial"
                                   @keyup.13="startRangeClikced"
                                   placeholder="first serial here"/>
                        </div>
                        <div class="column" v-show="is_range">
                            <input type="text"
                                   ref="range_end_serial_ref"
                                   class="input"
                                   @keyup.enter="endRangeClikced"
                                   v-model="range_end_serial"
                                   placeholder="last serial number here"/>

                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column has-text-white text-center">
                        {{ item.name }}
                    </div>
                    <div class="column text-center">
                        {{ serials.length }} {{ translator.serials_count }}
                    </div>

                </div>
                <div class="box" style="overflow: scroll;    max-height: 250px;">
                    <table class="table table-bordered">
                        <thead>
                            <th>#</th>
                            <th>{{translator.serial}}</th>
                            <th>{{translator.options}}</th>
                        </thead>
                        <tbody>
                        <tr v-for="(serial,index) in serials" :key="index" >
                            <td v-text="index + 1"></td>
                            <td v-text="serial"></td>
                            <td><a @click="deleteSerialFromList(index)" class="delete"></a> </td>
                        </tr>


                        </tbody>

                    </table>
                </div>

                <div class="text-center">
                    <button @click="hidePanel" class="button"><i class="fa  fa-window-close"></i>
                        &nbsp;
                        &nbsp;
                        {{translator.close_panel}}</button>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    export default  {


        props:['item','item_index','init_item_serial_list','invoicetype','updatehock','isOpen'],
        data:function(){
            return {
                index:0,
                range_end_serial:'',
                range_start_serial:'',
                is_range:false,
                serial_field:'',
                serials:[],
                serials_returned:[],
                showModal: false,
                errorMessage:'',
                translator:null,
            };

        },

        mounted:function() {
            //console.log('has been updated') // Logs the counter value every second, before the DOM updates.
        },

        created:function(){



            var all  = JSON.parse(window.reusable_translator);
            this.translator = all.serials;
            this.serials = this.init_item_serial_list;
            this.index = this.item_index;
        },
        methods: {


            hidePanel()
            {
                // console.log('updated every time');
                this.$modal.hide('serialList_' + this.item_index);
            },
            findSerialInSerialsList(){
                var len= this.serials.length;
                for(var i=0; i < len; i++) {
                    if (this.serials[i].serial == this.serial_field) {
                        return this.serials[i];
                    }
                }

                return  null;
            },

            returnSerialNumber(){
                var item = this.findSerialInSerialsList();
                if(item==null){
                    this.errorMessage = 'this different serial';
                    this.$refs.serial_field_ref2.select();
                    return;
                }

                if(item.current_status=='paid' || item.current_status=='returned')
                {
                    this.errorMessage = 'this serial is already paid or retured';
                    this.$refs.serial_field_ref2.select();
                    return;
                }



                this.serials_returned.push(this.serial_field);
                var index = helpers.getItemIndex(this.serials,item);
                item.current_status = 'returned';
                this.serials.splice(index,1,item);
                this.serial_field = '';
                this.updateInvoice()


              // console.log(this.serial_field);
            },

            startRangeClikced(){
                if(this.checkIsAlreadyUsedSerial(this.range_start_serial)){
                   this.range_end_serial = '';
                   this.$refs.range_end_serial_ref.focus();
                }
            },

            endRangeClikced(){
                if(!this.checkIsAlreadyUsedSerial(this.range_start_serial)){
                   this.$refs.range_start_serial_ref.classList.add('is-danger');
                   this.$refs.range_start_serial_ref.focus();

                   return;
                }

                if(!this.checkIsAlreadyUsedSerial(this.range_end_serial)){
                    this.$refs.range_end_serial_ref.classList.add('is-danger');
                    this.$refs.range_end_serial_ref.focus();
                    return;
                }


                var range_len = parseInt(this.range_end_serial) - parseInt(this.range_start_serial);
                    range_len = Math.abs(range_len);
                for (var i=1;i<=range_len;i++){
                     var data = parseInt(this.range_end_serial) + i;
                    if(this.checkIsAlreadyUsedSerial(data)){
                        this.serials.push(data);
                    }
                }

               this.updateInvoice();
                this.range_end_serial ='';
                this.range_start_serial ='';
                this.$refs.range_start_serial_ref.focus();

            },
            checkIsAlreadyUsedSerial(data){

              if(this.serials.includes(data))
              {
                  this.errorMessage = 'this serial has been used in serial ';
                  this.$refs.serial_field_ref.select();
                  return false;
              }

              return true;

            },


            createNewSerialNumber(){



                if(this.serial_field==''){
                    this.$modal.hide('serialList_' + this.index);
                }else{

                    this.serial_field = this.serial_field.trim();
                    if(this.invoicetype=='sale'){
                        if(this.checkIsAlreadyUsedSerial(this.serial_field)){
                            var vm  = this;
                            axios.get('/management/items/serials/checkserialforsale?serial=' + this.serial_field + '&item_id=' +
                                this.item.id)
                                .then(function (response) {
                                    vm.serials.push(vm.serial_field);
                                    vm.serial_field = '';
                                    vm.updateInvoice();

                                    vm.serials = vm.serials.reverse();
                                })
                                .catch(function (error) {
                                    vm.errorMessage = error.response.data.errors.serial[0];
                                    vm.$refs.serial_field_ref.select();
                                });
                        }
                    }else{
                        if(this.checkIsAlreadyUsedSerial(this.serial_field)){

                            var vm  = this;
                            axios.get('/management/items/serials/check?serial=' + this.serial_field)
                                .then(function (response) {
                                    vm.serials.push(vm.serial_field);
                                    vm.serial_field = '';
                                    vm.updateInvoice();
                                    vm.serials = vm.serials.reverse();
                                })
                                .catch(function (error) {
                                    vm.errorMessage = error.response.data.errors.serial[0];
                                    vm.$refs.serial_field_ref.select();
                                });

                        }
                    }

                }




            },


            updateInvoice(){

                    this.$emit('changed',{
                        index:this.item_index,
                        item:this.item,
                        serials:this.serials
                    })


            },


            clearFieldFromData(){
                this.errorMessage = '';
                console.log(this.$refs);
                // this.$refs.serial_field_ref.focus();
            },

            deleteSerialFromList(index){
                this.serials.splice(index,1);
                this.updateInvoice();
                this.$refs.serial_field_ref.focus();
            },

            focusOnField(){
                console.log(this.$refs.serial_field_ref);
                // this.$refs.serial_field_ref.$el.focus()
                // this.$refs.serial_field_ref.el.focus();

            }
        },
        watch:{
            updatehock:function () {
                this.focusOnField();
            },
            isOpen:function(value)
            {
                console.log($("#serial_field_" + this.item_index));

                // console.log($(this).find("#serial_field_" + this.item_index).focus());

                $("#pop_" + this.item_index).hide();
                $("#pop_"+ this.item_index).fadeIn('fast', function() {
                    $("#serial_field_0").focus();
                });



                // this.clearFieldFromData();
            }
        }

    }
</script>


<style scoped>
    button {
        margin-left: 8px;
        padding: 10px;
        padding-top: 4px;
        padding-right: 7px;
    }
</style>

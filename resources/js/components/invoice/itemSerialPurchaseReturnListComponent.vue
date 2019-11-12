<template>
    <div v-if="item.is_need_serial">
        <button @click="show(itemindex)" class="button is-primary is-small" style="margin:10px">
            <i class="fa fa-plus"></i> &nbsp;
        </button>
        <!-- template for the modal component -->
        <modal :name="'serialList_' + index"  :id="'serialList_' + index" :scrollable="true" width="70%" height="70%">
            <div class="box">
                <div class="">

                    <div class="columns">


                        <div class="column">
                            <input type="text" ref="serial_field_ref2" :class="{'is-danger':errorMessage!=''}"
                                   class="input"
                                   v-model="serial_field"
                                   @keyup.13="returnSerialNumber"
                                   placeholder="scan the serial to return it"/>
                            <p class="help is-danger is-center" v-show="errorMessage!=''"
                               v-text="errorMessage"></p>
                        </div>



                    </div>
                </div>

                <div class="columns">
                    <div class="column has-text-dark text-center">
                        {{ item.name }}
                    </div>
                    <div class="column text-center">
                        {{ serials.length }} serial
                    </div>
                    <div class="column text-center">
                        {{ serials_returned.length }} returned
                    </div>

                </div>
                <div class="box">
                    <table class="table table-bordered">
                        <thead>
                        <th>#</th>
                        <th>serial</th>
                        <th>options</th>
                        </thead>
                        <tbody>
                        <tr v-for="(serial,index) in serials" :key="index" >
                            <td v-text="index + 1"></td>
                            <td v-text="serial.serial"></td>
                            <td>
                                    <span class="tag is-primary" v-show="serial.current_status=='available' ||
                                    serial.current_status=='r_sale'">
                                        available
                                    </span>

                                <span class="tag is-danger" v-show="serial.current_status=='saled'">
                                        paid
                                    </span>

                                <span class="tag is-warning" v-show="serial.current_status=='r_purchase'">
                                        returned
                                    </span>

                            </td>
                        </tr>


                        </tbody>

                    </table>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    export default  {
        props:['item','itemindex','seriallist'],
        data:function(){
            return {
                index:0,
                serial_field:'',
                serials:[],
                serials_returned:[],
                showModal: false,
                errorMessage:''
            };

        },

        created:function(){
            this.serials = this.seriallist;
            this.index = this.itemindex;

            console.log(this.serials);
        },
        methods: {
            show (index) {
                this.$modal.show('serialList_' + index);
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
                }else if(item.current_status=='saled' || item.current_status=='r_purchase')
                {
                    this.errorMessage = 'this serial is already paid or retured';
                    this.$refs.serial_field_ref2.select();
                    return;
                }else{
                    this.serials_returned.push(this.serial_field);
                    var index = helpers.getItemIndex(this.serials,item);
                    item.current_status = 'r_purchase';
                    this.serials.splice(index,1,item);
                    this.serial_field = '';
                    this.errorMessage = '';
                    this.updateInvoice()
                }





            },



            updateInvoice(){

                this.$emit('changed',{
                    index:this.index,
                    serials:this.serials,
                    item:this.item,
                    returned_qty:this.serials_returned.length,
                })


            },


            clearFieldFromData(){
                this.errorMessage = '';
            },


        },

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

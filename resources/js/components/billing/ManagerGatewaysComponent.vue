<template>
    <div>
        <h5>وسائل الدفع </h5>
        <div class="row">
            <div class="col-md-3" v-for="gateway in gateways_list">
                <div class="card" style="padding:5px;padding-top: 8px">
                    <div class="columns">
                        <div class="column">{{gateway.locale_name}}</div>
                        <div class="column text-left">
                            <toggle-button
                                    @change="updatedList"
                                    :font-size="19" :height='30' :labels="{checked: 'نعم', unchecked: 'لا'}"
                                           v-model="gateway.is_selected"
                                           :async="true"
                                           :width='70'></toggle-button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['gateways'],
        name: "ManagerGatewaysComponent.vue",
        data:function()
        {
            return {
              gateways_list:[]
            };
        },
        created:function() {
            var len = this.gateways.length;

            for (var i =0; i < len;i++)
            {
                var gateway = this.gateways[i];
                gateway.is_seleted = false;
                this.gateways_list.push(gateway);
            }
        },
        methods:{
            updatedList()
            {
                var len = this.gateways_list.length;

                var checked_gateways = [];
                for (var i =0; i < len;i++)
                {
                    var gateway = this.gateways_list[i];

                    if(gateway.is_selected)
                    {
                        checked_gateways.push(gateway);
                    }
                }

                this.$emit("gatewaysUpdated",{
                   gateways:checked_gateways
                });
            }
        }
    }
</script>

<style scoped>

</style>
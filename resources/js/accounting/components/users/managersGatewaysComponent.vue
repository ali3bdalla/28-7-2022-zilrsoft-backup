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
                                    :async="true"
                                    :font-size="19" :height='30' :labels="{checked: 'نعم', unchecked: 'لا'}"
                                    :width='70'
                                    @change="updatedList"
                                    v-model="gateway.is_selected"></toggle-button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['gateways', 'initGateways'],
        name: "ManagerGatewaysComponent.vue",
        data: function () {
            return {
                is_selected: true,
                gateways_list: []
            };
        },
        created: function () {
            let len = this.gateways.length;
            console.log('works');
            console.log(this.initGateways);
            for (let i = 0; i < len; i++) {
                let gateway = this.gateways[i];
                if (this.initGateways != null) {
                    gateway['is_selected'] = db.model.in_array(this.initGateways, gateway['id'], 'int') === true;
                    console.log(gateway['is_selected']);
                } else {
                    gateway['is_selected'] = false;
                }


                this.gateways_list.push(gateway);
            }

            if (this.initGateways != null) {
                this.updatedList();
            }
        },
        methods: {
            updatedList() {
                let len = this.gateways_list.length;

                let checked_gateways = [];
                for (var i = 0; i < len; i++) {
                    var gateway = this.gateways_list[i];

                    if (gateway.is_selected) {
                        checked_gateways.push(gateway);
                    }
                }

                this.$emit("gatewaysUpdated", {
                    gateways: checked_gateways
                });
            }
        }
    }
</script>

<style scoped src='bulma/css/bulma.css'>

</style>
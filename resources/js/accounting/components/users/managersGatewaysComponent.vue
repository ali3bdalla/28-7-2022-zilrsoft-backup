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
        props: ['initGateways', 'arName', 'name'],
        name: "ManagerGatewaysComponent.vue",
        data: function () {
            return {
                gateways: [],
                is_selected: true,
                gateways_list: []
            };
        },
        created: function () {

            this.loadGateways();
        },
        methods: {
            loadGateways() {
                var appVm = this;

                // {
                //     params: {
                //         name: this.name,
                //             ar_name: this.arName,
                //     }
                // }
                axios.get('/accounting/gateways/get_gateways_like_to_manager_name',).then(function (response) {
                    appVm.gateways = response.data;
                    appVm.showResultGateways();
                }).catch(function (error) {
                    alert(error)
                }).finally(function () {
                    // appVm.isLoading = false;
                });


            },

            showResultGateways() {

                this.gateways_list = [];

                let len = this.gateways.length;
                for (let i = 0; i < len; i++) {
                    let gateway = this.gateways[i];
                    if (this.initGateways != null) {
                        gateway['is_selected'] = db.model.in_array(this.initGateways, gateway['id'], 'int') === true;
                    } else {
                        gateway['is_selected'] = false;
                    }


                    this.gateways_list.push(gateway);
                }

                if (this.initGateways != null) {
                    this.updatedList();
                }
            },
            updatedList() {
                let len = this.gateways_list.length;

                let checked_gateways = [];
                for (let i = 0; i < len; i++) {
                    let gateway = this.gateways_list[i];
                    if (gateway.is_selected) {
                        checked_gateways.push(gateway);
                    }
                }

                this.$emit("gatewaysUpdated", {
                    gateways: checked_gateways
                });
            },

        }
        ,
        watch: {
            name: function (value) {
                // console.log(value);
                // this.loadGateways();
            },
            arName: function (value) {
                // this.loadGateways();
            }
        }
    }
</script>

<style scoped src='bulma/css/bulma.css'>

</style>
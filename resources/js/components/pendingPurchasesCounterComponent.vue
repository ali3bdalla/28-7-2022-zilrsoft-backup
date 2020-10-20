<template>
    <span>
        <span class="counter-t" v-text="count" :class="{'notification-t':count>0}"></span>
        <i class="fa fa-heart"></i>
    </span>
</template>
<script>
    export  default  {
        name:"PendingPurchasesCounterComponent",
        data:function () {
            return {
                count:0
            }
        },
        created() {
            let vm = this;
            axios.get(window.routes.purchases + "statistics/get_pending_counts").then(res => {
                vm.count = res.data;
            })

        } 
    }
</script>
<style scoped>
    .counter-t {
        font-size: 20px;
        border-radius: 30%;
        padding: 2px 5px;
        box-shadow: 1px 1px 3px 0px;
    }

    .notification-t {
        background-color: red;
    }
</style>
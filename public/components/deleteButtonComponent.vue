<template>
    <a @click="alertConfirm" ><slot></slot></a>
</template>

<script>
    export  default  {
        props:['href'],
        methods:{
            alertConfirm(){
                var vm = this;
                this.$dialog
                    .confirm("are you sure you want to delete this item ?", {
                        // And a dialog object will be passed to the then() callback

                    })
                    .then(dialog => {

                        location.href =vm.href;

                    })
                    .catch(() => {

                    });
            }
        }
    }
</script>

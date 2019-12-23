<template>
    <div v-text="time" class="datedirection"></div>
</template>

<script>
    export  default  {

        data:function()
        {
            return {
                time: new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate() + ' ' + new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds()
            };
        },
        created() {
            var vm = this;
            setInterval(function () {

                vm.time = helpers.getFullDateAndTime();

            }, 1000);


        }

    }
</script>
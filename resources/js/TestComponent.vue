<template>
    <div class="container">
        
    </div>
</template>

<script>
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'
    import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';


    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';


    export default {
        name: "TestComponent.vue",
        components:{VueCtkDateTimePicker},
        data: function () {
            return {
                day:null,
                start_time:null,
                end_time:null,
            }
        }
    }
</script>

<style scoped>

</style>
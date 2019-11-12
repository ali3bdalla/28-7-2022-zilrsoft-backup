<template>
    <div class="select is-fullwidth">
<!--        multiple="multiple"-->
        <select class="" v-model="user"  @change="updated">
            <option v-for="item in items" :value="item.id" v-model="user">{{ item.name }}</option>
        </select>
    </div>
</template>

<script>

    export default {
        components:{
        },
        props:['items'],
        data:function(){
            return {
                arr:[],
                user:'',
            };
        },
        created:function(){
            // console.log('hello')
        },
        methods:{
            updated()
            {
                this.arr = [];
                this.arr.push(this.user);
                this.$emit('listUpdated',{
                    ids:this.arr
                });
                // console.log(this.arr);
            }
        }
    }
</script>

<style scoped>
    .select2-container {
        width: 100% !important;
    }
</style>

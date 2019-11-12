<template>
    <div class="container">
        <div class='upper-header'>
            <img src="/template/images/zilrtech-g.png"/>
            <div class="upper-organization-deatil">
                <ul>
                    <li>securty</li>
                    <li>trainding</li>
                    <li>half</li>
                </ul>
            </div>
        </div>

        <div class="header">

        </div>


    </div>
</template>

<script>
    export default {
        props:['invoice'],
        methods:{

        },
        created:function(){
            // console.log(this.invoice)
        }
    }
</script>

<style scoped>
    .upper-header{
        background:white;
    }

    .upper-organization-deatil {

    }
    .upper-header img {
        height: 100px;
        padding: 10px;
        padding-bottom: 0px;
    }

    .upper-organization-deatil ul {
        display:flex;
        align-items: center;
        vertical-align: middle;
        justify-items: center;
    }

    .upper-organization-deatil ul  li{
        list-style:none;

        padding: 5px;
        padding-right:10px;
        border-right: 1px solid;
    }

    .upper-organization-deatil ul  li:last-child {
        border-right:none
    }


    .header {
        background-color:#f0f;
        height:100px
    }
</style>

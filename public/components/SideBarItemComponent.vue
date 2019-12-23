<template>
    <li>
        <a v-bind:href="url"><i class='sidebar-item-icon fa' v-bind:class="icon"></i>
            <span class='nav-label'>
                {{title}}
                <span v-show="hasBadge" class='pull-right badge' :class="badgeClass" v-text="badgeCount"></span>

            </span>
        </a>
    </li>
</template>








<script>
    export default {
        data:function(){
          return {

          };
        },
        props: ['title','url','icon','hasBadge','badgeCount','badgeClass'],

    }
</script>

<style scoped>
    li {

        border-bottom: 1px solid #777;
        padding: 0px;
        /* background: #3a3737; */
        font-size: 19px;
        font-weight: bold;
        /*font-family: fantasy;*/
    }

    li a {

        text-decoration: none;
    }


</style>

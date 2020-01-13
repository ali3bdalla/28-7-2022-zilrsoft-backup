<template>
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-bell" style="font-size: 18px;
margin-bottom: -10px;"></i>
            <span class="label label-warning">10</span>
        </a>
        <ul class="dropdown-menu">
            <li class="header">You have 10 notifications</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <li>
                        <a href="#">
                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                    </li>
                    <li v-for="transaction in pendingTransactions">
                        <a href="#">
                            <i class="fa fa-warning text-yellow"></i> تحويل من {{ transaction.creator.locale_name }} ،
                            بمبلغ {{
                            transaction.amount }}
                        </a>
                    </li>

                </ul>
            </li>
            <li class="footer"><a href="#">View all</a></li>
        </ul>
    </li>
</template>


<script>
    export default {
        props:['pendingTransactions']
    }
</script>
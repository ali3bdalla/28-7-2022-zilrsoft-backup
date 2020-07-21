<template>
    <header class="main-header " style="    margin-bottom: 50px;">
        <a :href="baseUrl" :style="{'background-color':primaryColor}" class="logo">
            <span class="logo-lg"><b>{{ appName }}</b></span>
        </a>
        <a class="sidebar-toggle" data-toggle="push-menu" href="#" role="button">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-bars"></i>

        </a>
<!--        :style="{'backgroundColor':primaryColor}"-->
        <nav  class="navbar navbar-fixed-top" style="margin-bottom: 50px
        !important;">
            <div class="right" style="">
                <ul class="nav navbar-nav">

                    <li v-if="canConfirmPendingPurchases">
                        <a class="dropdown-toggle" href="/accounting/purchases/pending/list">
                            <i class="fa fa-bars" style="font-size: 19px;
margin-bottom: -10px;"></i>
                            <span class="label label-danger">{{ pendingPurchases}}</span>
                        </a>
                    </li>
                    <li class="dropdown notifications-menu">
                        <a aria-expanded="true" class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-bell" style="font-size: 19px;
margin-bottom: -10px;"></i>
                            <span class="label label-danger">{{ pendingTransactions.length}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">({{ pendingTransactions.length}}) عملية تحويل منتظرة</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu" style="    background: #eee;    max-height: 500px;">

                                    <li v-for="transaction in pendingTransactions">
                                        <div class="">
                                            <div class="panel-body">
                                                <i class="fa fa-warning text-yellow"></i> تحويل من {{
                                                transaction.creator.locale_name }} ،
                                                بمبلغ
                                                <span class="text-primary" style="    font-weight: bold;">{{
                                                    parseFloat(transaction.amount).toFixed(2)
                                                    }}</span>
                                            </div>
                                            <div class="panel-footer">
                                                <a
                                                        :href="'/accounting/reseller_daily/'+transaction.id+'/confirm_transaction'"
                                                        class="btn btn-custom-primary pull-left">موافق</a>
                                                <a :href="'/accounting/reseller_daily/'+transaction.id+
                                                   '/delete_transaction'"
                                                   class="btn btn-custom-default">الغاء </a>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </li>
                                                        <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>



                    <li class="dropdown user user-menu  dropdown-menu-right pull-right">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <img alt="User Image" class="user-image"
                                 src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg">
                            <span class="hidden-xs"></span>
<!--                            {{ username }}-->
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img alt="User Image" class="img-circle"
                                     src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg">

                                <p>
                                </p>
                            </li>

                            <li class="user-footer">
                                <div class="pull-left">
                                    <a class="btn btn-default
                            btn-flat" href="">البروفايل</a>
                                </div>
                                <div class="pull-right">
                                    <form :action="BaseApiUrl + 'logout'" method="post">
                                        <input :value="csrf" name="_token" type="hidden">
                                        <button class="btn btn-default btn-flat" type="submit">تسجيل
                                            خروج
                                        </button>
                                    </form>

                                </div>
                            </li>
                        </ul>
                    </li>
                    <li><a :href="routes.configuration" v-text="lang.layouts.configuration"></a></li>
                    <li><a href="/accounting/printer/printers"></a></li>
                    <li><a href="/accounting/statistics"></a></li>
                    <li><a href="/accounting/items"> المنتجات</a></li>
                    <li><a href="/accounting/sales/create">فاتورة مبيعات</a></li>
                    <li><a href="/accounting/purchases/create">فاتورة مشتريات</a></li>
                    <li><a href="/accounting/reseller_daily/account_close_list">انهاء الوردية</a></li>
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
    export default {
        name: "headerPageComponent.vue",
        data:function () {
            return {
                routes:window.routes,
                lang:window.lang
            }
        }

    }
</script>

<style scoped>

</style>
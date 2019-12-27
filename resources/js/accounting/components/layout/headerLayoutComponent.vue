<template>

    <header class="main-header ">
        <a :href="baseUrl" :style="{'background-color':primaryColor}" class="logo">
            <span class="logo-lg"><b>{{ appName }}</b></span>
        </a>
        <a class="sidebar-toggle" data-toggle="push-menu" href="#" role="button">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-bars"></i>

        </a>
        <nav :style="{'backgroundColor':primaryColor}" class="navbar">
            <div class="right" style="">
                <ul class="nav navbar-nav">


                    <li class="dropdown user user-menu  dropdown-menu-right pull-right">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <img alt="User Image" class="user-image"
                                 src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg">
                            <span class="hidden-xs"></span>
                            {{ username }}
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
                                        <input type="hidden" name="_token" :value="csrf">
                                        <button type="submit" class="btn btn-default btn-flat">تسجيل
                                            خروج
                                        </button>
                                    </form>

                                </div>
                            </li>
                        </ul>
                    </li>
                    <li><a href="/accounting/settings" v-text="trans.settings"></a></li>
                    <li><a href="/accounting/statistics" v-text="trans.statistics"></a></li>


                </ul>
            </div>
        </nav>
    </header>
</template>
<script>
    export default {
        props: ["csrf","username"],
        data: function () {
            return {
                appName: metaHelper.getContent('app-name'),
                baseUrl: metaHelper.getContent('app-base-url'),
                primaryColor: metaHelper.getContent('primary-color'),
                secondColor: metaHelper.getContent('second-color'),
                appLocate: metaHelper.getContent('app-locate'),
                BaseApiUrl: metaHelper.getContent('BaseApiUrl'),
                trans: trans('sidebar')
            };
        },
        created() {
        }
    }
</script>

<style scoped type="text/css">
    .navbar {
        box-shadow: 0px 3px 6px 0px #777;
        background-color: rgb(44, 62, 80);
    }


    .navbar-nav > li > .dropdown-menu {
        background-color: #2c3e50 !important;
        background-color: white;
        margin-top: 15px;
        border-radius: 4px;
        margin-right: 18px;
        box-shadow: 0px 1px 10px #dbdbdb;
    }

    .navbar-nav > li > .dropdown-menu .user-header {
        background-color: inherit;
    }

    .sidebar-toggle {
        color: white;
        /* background: rebeccapurple; */
        position: absolute;
        z-index: 3849382432;
    }
</style>

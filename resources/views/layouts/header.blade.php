<a href="{{ route('management.dashboard') }}" class="logo">
    ZilrSoft
</a>
<nav class="navbar" style="margin-left:0px !important;">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <i class="fa fa-bars"></i>
        {{--        <span class="sr-only">Toggle navigation</span>--}}
        {{--        <span class="icon-bar"></span>--}}
        {{--        <span class="icon-bar"></span>--}}
        {{--        <span class="icon-bar"></span>--}}
    </a>

    <div class="right" style="">
        <ul class="nav navbar-nav">

            <li class="dropdown user user-menu  dropdown-menu-right pull-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" class="user-image"
                         alt="User Image">
                    <span class="hidden-xs">{{Auth::user()->name}}</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-header">
                        <img src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle"
                             alt="User Image">

                        <p>
                            {{Auth::user()->email}}
                        </p>
                    </li>

                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="{{ route('management.users.index') }}" class="btn btn-default
                            btn-flat">البروفايل</a>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('management.users.signout') }}" class="btn btn-default btn-flat">تسجيل
                                خروج
                            </a>
                        </div>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</nav>

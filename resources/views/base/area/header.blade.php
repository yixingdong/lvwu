<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="{{url('/')}}" class="logo"><b>L</b> awood</a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"></span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @if(Auth::check())
                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">你有 1 条通知消息</li>
                        <li>
                            <!-- Inner Menu: contains the notifications -->
                            <ul class="menu">
                                <li><!-- start notification -->
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 你有一条客户咨询
                                    </a>
                                </li><!-- end notification -->
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{URL::asset('/')}}images/user2-160x160.jpg" class="user-image" alt="User Image"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">某某某</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{URL::asset('/')}}images/user2-160x160.jpg" class="img-circle" alt="User Image" />
                            <p>
                                XXX 你好
                                <small>Member since Mar. 2016</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            @if(is_null(Auth::user()->wx_id))
                            <div class="col-xs-4">
                                <a class="btn btn-warning" href="{{url('wx/bind')}}">绑定微信</a>
                            </div>
                            @endif
                            @if(!Auth::user()->email_active))
                            <div class="col-xs-4 col-xs-offset-3">
                                <a class="btn btn-warning" href="{{url('bind/email')}}">绑定邮箱</a>
                            </div>
                            @endif
                            @if(is_null(Auth::user()->phone))
                            <div class="col-xs-4 text-center col-xs-offset-3">
                                <a class="btn btn-warning" href="{{url('bind/select')}}">绑定手机</a>
                            </div>
                            @endif
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">档案</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{URL('logout')}}" class="btn btn-default btn-flat">登出</a>
                            </div>
                        </li>
                    </ul>
                </li>
                @else
                    <li><a href="{{URL('chose')}}">注册</a></li>
                    <li><a href={{URL('login')}}>登录</a></li>
                    <li><a href={{URL('wx/login')}}>扫码登注</a></li>
                @endif
            </ul>
        </div>
    </nav>
</header>
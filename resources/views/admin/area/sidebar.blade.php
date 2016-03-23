<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{URL::asset('/')}}images/user2-160x160.jpg" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>sanmingzhi</p>
                <!-- Status -->
                <a href="#"><i class="icon-camera-retro"></i> 在线</a>
            </div>
        </div>
        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                  <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">个人工作区</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active">
                <a href="{{url('admin/calendar')}}">
                    <i class="fa fa-calendar"></i> <span>任务日历</span>
                    <small class="label pull-right bg-red">3</small>
                </a>
            </li>
            <li><a href="#"><span>时间轴线</span></a></li>
            <li class="treeview">
                <a href="#"><span>用户管理</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{url('role/create')}}">创建角色</a></li>
                    <li><a href="#">角色管理</a></li>
                    <li><a href="#">权限管理</a></li>
                    <li><a href="#">用户列表</a></li>
                </ul>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
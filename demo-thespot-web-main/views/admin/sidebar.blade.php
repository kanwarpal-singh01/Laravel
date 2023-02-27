<?php $url  = Request::segments(); ?>

<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar navbar-dark menu-item-icon-style4 icon-colored">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo" style="background:#fc6e26;font-weight:900">
            <a href="{{route('admin_dashboard')}}" class="b-brand">

                <span class="b-title" style="font-weight:900">The Spot</span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item pcoded-menu-caption">
                    <label>Menus</label>
                </li>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php echo isset($url[1]) && $url[1] == 'dashboard'? 'active':''; ?>">
                    <a href="{{url('admin/dashboard')}}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                </li>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php echo isset($url[1]) && $url[1] == 'users'? 'active':''; ?>">
                    <a href="{{url('admin/users')}}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-user"></i></span><span class="pcoded-mtext">Users Management</span></a>
                </li>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php echo isset($url[1]) && $url[1] == 'emailtemplate'? 'active':''; ?>">
                    <a href="{{ url('admin/emailtemplate') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-envelope"></i></span><span class="pcoded-mtext">Email Management</span></a>
                </li>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php echo isset($url[1]) && $url[1] == 'template_list'? 'active':''; ?>">
                    <a href="{{ route('template_list') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-bell"></i></span><span class="pcoded-mtext">Notification Template</span></a>
                </li>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php echo isset($url[1]) && $url[1] == 'notifications'? 'active':''; ?>">
                    <a href="{{ route('notifications') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-bell"></i></span><span class="pcoded-mtext">Chat Management</span></a>
                </li>

                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php echo isset($url[1]) && $url[1] == 'report-user-list'? 'active':''; ?>">
                    <a href="{{ route('reportUserList') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-file"></i></span><span class="pcoded-mtext">Reports Management</span></a>
                </li>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php echo isset($url[1]) && $url[1] == 'subscriptions'? 'active':''; ?>">
                    <a href="{{ route('subscription') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-bullhorn"></i></span><span class="pcoded-mtext">Subscription Management</span></a>
                </li>
                <li class="nav-item <?php echo isset($url[1]) && in_array($url[1],['update-cmspage','cms-pages'])? 'active':''; ?>">
                    <a href="{{ route('cmspages') }}" class="nav-link "><span class="pcoded-micon"><i class="fas fa-tasks"></i></span><span class="pcoded-mtext">Content Management</span></a>
                </li>
                <!-- <li class="nav-item <?php echo isset($url[1]) && in_array($url[1],['chats'])? 'active':''; ?>">
                    <a href="{{ route('fakeChat') }}" class="nav-link "><span class="pcoded-micon"><i class="fas fa-tasks"></i></span><span class="pcoded-mtext">Fake User Chat</span></a>
                </li> -->
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php echo isset($url[1]) && $url[1] == 'faq'? 'active':''; ?>">
                    <a href="{{ url('admin/faq') }}" class="nav-link "><span class="pcoded-micon"><i class="fa fa-question-circle"></i></span><span class="pcoded-mtext">FAQ Management</span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

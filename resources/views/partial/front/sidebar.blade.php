<aside class="main-sidebar sidebar-dark-primary elevation-4 customer-sidebar">
    <!-- Brand Logo -->
    <a href="{{ url('home') }}" class="brand-link">
        <img src="{{ url('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ url('/').'/assets/dist/img/user4-128x128.jpg' }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Welcome {{ Auth::user()->fname.' '.Auth::user()->lname }}!</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{ url('customer/dashboard') }}" class="nav-link {{ (isset($module) && $module == 'dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ url('customer/my-bundles') }}" class="nav-link {{ (isset($module) && $module == 'mybundles') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>My Bundles</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ url('customer/my-devices') }}" class="nav-link {{ (isset($module) && $module == 'mydevices') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-people-carry"></i>
                        <p>My Devices</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ url('customer/profile') }}" class="nav-link {{ (isset($module) && $module == 'profile') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>My Profile</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
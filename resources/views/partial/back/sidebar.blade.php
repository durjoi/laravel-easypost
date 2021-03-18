<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('home') }}" class="brand-link">
        <img src="{{ url('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ (Auth::user() != null) ? Auth::user()->photo : '' }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{ url('home') }}" class="nav-link {{ (isset($module) && $module == 'dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ url('admin/orders') }}" class="nav-link {{ (isset($module) && $module == 'order') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-people-carry"></i>
                        <p>Orders</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ url('admin/customers') }}" class="nav-link {{ (isset($module) && $module == 'customer') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Customers</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ url('admin/pagebuilder') }}" class="nav-link {{ (isset($module) && $module == 'page') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Pages</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ url('admin/products') }}" class="nav-link {{ (isset($module) && $module == 'product') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ url('admin/settings/users') }}" class="nav-link {{ (isset($module) && $module == 'user') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item has-treeview {{ ($module == 'config' || $module == 'menu' || $module == 'brand' || $module == 'status') ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ ($module == 'config' || $module == 'menu' || $module == 'brand' || $module == 'status') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings<i class="fas fa-angle-left right"></i></p>
                    </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ url('admin/settings/config') }}" class="nav-link {{ (isset($module) && $module == 'config') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Edit Config</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/settings/menus') }}" class="nav-link {{ (isset($module) && $module == 'menu') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Menus</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/settings/brands') }}" class="nav-link {{ (isset($module) && $module == 'brand') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Brands</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/settings/status') }}" class="nav-link {{ (isset($module) && $module == 'status') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Status</p>
                    </a>
                </li>
            </ul>
            <!-- </li>
            </ul> -->
        </nav>
    </div>
</aside>
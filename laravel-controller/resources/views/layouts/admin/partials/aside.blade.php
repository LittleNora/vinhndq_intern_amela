<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                     alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                       aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                @foreach(config('menus') as $menu)
                    <li class="nav-item menu-open">
                        <a
                                href="{{ !empty($menu['children']) ? '#' : route($menu['route']) }}"
                                class="nav-link @if(request()->route()->getName() == $menu['route']) active @endif"
                        >
                            <i class="nav-icon {{ $menu['icon'] }}"></i>
                            <p>
                                {{ $menu['name'] }}
                                @if(!empty($menu['children']))
                                    <i class="right fas fa-angle-left"></i>
                                @endif
                            </p>
                        </a>
                        @if(!empty($menu['children']))
                            @foreach($menu['children'] as $submenu)
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a
                                            href="{{ route($submenu['route']) }}"
                                            class="nav-link @if(request()->route()->getName() == $submenu['route']) active @endif">
                                            <i class="{{ $submenu['icon'] }} nav-icon"></i>
                                            <p>{{ $submenu['name'] }}</p>
                                        </a>
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                    </li>
                @endforeach
                <li class="nav-item menu-open">
                    <form
                        class="nav-link bg-danger d-flex align-items-center"
                        action="{{ route('logout') }}"
                        method="post"
                        style="cursor: pointer"
                    >
                        @csrf
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <button type="submit" class="btn p-0 border-0 text-white">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

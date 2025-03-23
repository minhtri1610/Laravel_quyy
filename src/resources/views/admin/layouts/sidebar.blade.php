<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/logo-sm.png') }}" alt="logo"></a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li class="{{ request()->routeIs('admin.quyy.index') || request()->routeIs('admin.quyy.create') ? 'active' : '' }}">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-dashboard"></i><span>QL Quy Y</span></a>
                        <ul class="collapse">
                            <li class="{{ request()->routeIs('admin.quyy.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.quyy.index') }}">Danh Sách Phật Tử</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.quyy.list') ? 'active' : '' }}">
                                <a href="{{ route('admin.quyy.list') }}">Danh Sách Chờ Duyệt</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.quyy.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.quyy.create') }}">Thêm Phật tử</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
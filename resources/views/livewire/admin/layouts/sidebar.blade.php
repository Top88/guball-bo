<div>
    <!-- BEGIN scrollbar -->
    <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
        <!-- BEGIN menu -->
        <div class="menu">
            <div class="menu-profile">
                <a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
                    <div class="menu-profile-cover with-shadow"></div>
                    <div class="menu-profile-image menu-profile-image-icon bg-gray-900 text-gray-600">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="menu-profile-info">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                {{ $fullName }}
                            </div>
                            {{-- <div class="menu-caret ms-auto"></div> --}}
                        </div>
                        <small>{{ $role }}</small>
                    </div>
                </a>
            </div>
            <div class="menu-header">เมนู</div>
            <div class="menu-item">
                <a href="{{ route('index') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa-solid fa-web-awesome"></i>
                    </div>
                    <div class="menu-text">หน้าลูกค้า</div>
                </a>
            </div>
            <div class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa-solid fa-gauge"></i>
                    </div>
                    <div class="menu-text">แดชบอร์ด</div>
                </a>
            </div>

            @can('view-manage-role')
                <div class="menu-item {{ request()->routeIs('admin.roles') ? 'active' : '' }}">
                    <a href="{{ route('admin.roles') }}" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div class="menu-text">จัดการสิทธิ์</div>
                    </a>
                </div>
            @endcan

            @can('view-manage-user')
                <div class="menu-item {{ request()->routeIs('admin.user-management') ? 'active' : '' }}">
                    <a href="{{ route('admin.user-management') }}" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="menu-text">{{__('website.general.manage_user')}}</div>
                    </a>
                </div>
            @endcan

            @can('view-manage-league')
            <div class="menu-item {{ request()->routeIs('admin.league-management') ? 'active' : '' }}">
                <a href="{{ route('admin.league-management') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <div class="menu-text">{{__('website.general.manage_league')}}</div>
                </a>
            </div>
            @endcan

            @can('view-manage-team')
            <div class="menu-item {{ request()->routeIs('admin.team-management') ? 'active' : '' }}">
                <a href="{{ route('admin.team-management') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa-brands fa-font-awesome"></i>
                    </div>
                    <div class="menu-text">{{__('website.general.manage_team')}}</div>
                </a>
            </div>
            @endcan
            @can('view-manage-match')
            <div class="menu-item {{ request()->routeIs('admin.match-management') ? 'active' : '' }}">
                <a href="{{ route('admin.match-management') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa-sharp-duotone fa-solid fa-futbol"></i>
                    </div>
                    <div class="menu-text">{{__('website.general.manage_match')}}</div>
                </a>
            </div>
            @endcan
            @can('view-exchange-credit')
            <div class="menu-item {{ request()->routeIs('admin.credit-exchange-management') ? 'active' : '' }}">
                <a href="{{ route('admin.credit-exchange-management') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa-sharp-duotone fa-solid fa-futbol"></i>
                    </div>
                    <div class="menu-text">{{__('website.credit-exchange-management')}}</div>
                </a>
            </div>
            @endcan

            @can('view-setting')
            <div class="menu-item {{ request()->routeIs('admin.setting') ? 'active' : '' }}">
                <a href="{{ route('admin.setting') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa-sharp-duotone fa-solid fa-futbol"></i>
                    </div>
                    <div class="menu-text">{{__('website.general.setting')}}</div>
                </a>
            </div>
            @endcan

            <!-- BEGIN minify-button -->
            <div class="menu-item d-flex">
                <a href="javascript:;" class="app-sidebar-minify-btn ms-auto d-flex align-items-center text-decoration-none" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
            </div>
            <!-- END minify-button -->
        </div>
        <!-- END menu -->
    </div>
    <!-- END scrollbar -->
</div>

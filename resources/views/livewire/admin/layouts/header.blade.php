<div>
    <!-- BEGIN #header -->
    <div id="header" class="app-header">
        <!-- BEGIN navbar-header -->
        <div class="navbar-header">
            <a href="{{route('admin.dashboard')}}" class="navbar-brand"><span class="navbar-logo"></span> {{config('app.name')}}</a>
            <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- END navbar-header -->

        <!-- BEGIN header-nav -->
        <div class="navbar-nav">
            <div class="navbar-item dropdown">
                <a href="#" data-bs-toggle="dropdown" class="navbar-link dropdown-toggle fs-14px">
                    <i class="fa fa-bell"></i>
                    <span class="badge">0</span>
                </a>
                <div class="dropdown-menu media-list dropdown-menu-end">
                    <div class="dropdown-header">NOTIFICATIONS (0)</div>
                    <div class="text-center w-300px py-3">
                        No notification found
                    </div>
                </div>
            </div>
            <div class="navbar-item navbar-user dropdown">
                <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" id="headerDropdown" aria-expanded="false">
                    <div class="image image-icon bg-gray-800 text-gray-600">
                        <i class="fa fa-user"></i>
                    </div>
                    <span class="d-none d-md-inline">{{$fullName}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end me-1" aria-labelledby="headerDropdown">
                    <a href="javascript:;" class="dropdown-item">{{__('website.button.change_password')}}</a>
                    {{-- <a href="javascript:;" class="dropdown-item">{{__('website.button.edit_profile')}}</a> --}}
                    <div class="dropdown-divider"></div>
                    <a wire:click='logout' class="dropdown-item">{{__('website.button.logout')}}</a>
                </div>
            </div>
        </div>
        <!-- END header-nav -->
    </div>
    <!-- END #header -->
</div>

@section('title', 'People')

@include('layouts.sections.head')

<body class="sidebar-mini layout-footer-fixed">
    <div class="wrapper">

        @include('layouts.sections.nav')
        
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4 sidebar--tecc sidebar--{{ config('global.site_color_people') }}">
            
            <div class="dropdown show">
                <a class="brand-link navbar-white" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ config('global.site_icon_people') }}" alt="" class="brand-image">
                    <span class="brand-text font-weight-light">People</span>
                    <i class="nav-icon material-icons icon--list ml-5 pl-4 text-gray">arrow_drop_down</i>
                </a>
                <div class="dropdown-menu w-100 border-0 shadow" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item px-3" href="/">
                        <i class="icon--list m-1 material-icons nav-icon">apps</i>
                        <div class="d-inline-block mb-1 ml-2">All</div>
                    </a>
                    @if (in_array(config('global.apps')[0], explode(',', Auth::user()->apps)))
                        <a class="dropdown-item px-3" href="{{ config('global.dashboard_leaves') }}">
                            <img src="{{ config('global.site_icon_leaves') }}" alt="" class="img-size-32 mr-2">
                            Leaves
                        </a>
                    @endif
                    @if (in_array(config('global.apps')[1], explode(',', Auth::user()->apps)))
                        <a class="dropdown-item px-3" href="{{ config('global.dashboard_sequence') }}">
                            <img src="{{ config('global.site_icon') }}" alt="" class="img-size-32 mr-2">
                            Sequence
                        </a>
                    @endif
                </div>
            </div>
    
            <!-- Sidebar -->
            <div class="sidebar os-host os-theme-light os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden os-host-scrollbar-vertical-hidden">
                <div class="os-padding">
                    <div class="os-viewport os-viewport-native-scrollbars-invisible">
                        <div class="os-content">
                            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                                <div class="image">
                                    <img src="/storage/public/images/users/{{ Auth::user()->avatar }}" class="img-circle mt-2" alt="User Image">
                                </div>
                                <div class="info">
                                    <a href="/my-account" class="d-block">{{ Auth::user()->name }}</a>
                                    <span class="small text-secondary font-weight-bold">{{ Auth::user()->role->name }} {{ Auth::user()->is_smt ? ' - SMT' : '' }}</span>
                                </div>
                            </div>
    
                            <!-- Sidebar Menu -->
                            <nav class="mt-2">
                                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                    <li class="nav-item">
                                        <a href="{{ config('global.dashboard_people') }}" class="nav-link {{ Route::currentRouteName() == 'people-dashboard' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">dashboard</i><p>Dashboard</p>
                                        </a>
                                    </li>
                                    
                                    @if (Auth::user()->role_id == 1)
                                        <li class="nav-header">USER ACCESS</li>
                                        <li class="nav-item">
                                            <a href="/ua-route" class="nav-link {{ Route::currentRouteName() == 'uaroute' ? 'active' : '' }}">
                                                <i class="nav-icon material-icons icon--list">route</i><p>Route</p>
                                            </a>
                                            <a href="/ua-level" class="nav-link {{ Route::currentRouteName() == 'ualevel' ? 'active' : '' }}">
                                                <i class="nav-icon material-icons icon--list">leaderboard</i><p>Level</p>
                                            </a>
                                            <a href="/ua-level-route" class="nav-link {{ Route::currentRouteName() == 'ualevelroute' ? 'active' : '' }}">
                                                <i class="nav-icon material-icons icon--list">rule</i><p>Level Route</p>
                                            </a>
                                        </li> 
                                        <li class="nav-header">ADMINISTRATOR</li>
                                        <li class="nav-item">
                                            <a href="/user" class="nav-link {{ Route::currentRouteName() == 'user' ? 'active' : '' }}">
                                                <i class="nav-icon material-icons icon--list">face</i><p>User</p>
                                            </a>
                                        </li>                                            
                                        <li class="nav-item">
                                            <a href="/people-settings" class="nav-link {{ Route::currentRouteName() == 'people-settings' ? 'active' : '' }}">
                                                <i class="nav-icon material-icons icon--list">settings</i><p>Settings</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/activity-log" class="nav-link {{ Route::currentRouteName() == 'activitylog' ? 'active' : '' }}">
                                                <i class="nav-icon material-icons icon--list">history</i><p>Activity Log</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/db-backups" class="nav-link {{ Route::currentRouteName() == 'dbbackups' ? 'active' : '' }}">
                                                <i class="nav-icon material-icons icon--list">storage</i><p>Database</p>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @include('flashmessage')                
            @yield('content')
        </div>
        
        @include('shared.admin.bread')
    </div>
    <!-- </div> -->
    <div id="app"></div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    @yield('script')
    @yield('script2')

    <script>
        $(function() {            
            // PREVENT MULTIPLE SUBMIT
            preventClass = 'form.jsPreventMultiple'
            if($(preventClass).length) {
                $(preventClass).on('submit', function() {
                    $(preventClass+' [type="submit"]').attr('disabled', 'true')
                })
            }
        })
    </script>

    @yield('footer')
</body>
</html>

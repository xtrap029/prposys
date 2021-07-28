<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'People')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('style')
</head>
<body class="@guest @else sidebar-mini layout-footer-fixed @endguest">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="@guest @else main-header @endguest navbar navbar-expand @yield('nav_class', 'navbar-light') border-bottom-0 text-sm">
            @guest
                <!-- Brand Logo -->
                <a href="/" class="brand-link">
                    <img src="{{ config('global.site_icon_people') }}" alt="" class="brand-image" style="opacity: .8">
                    @guest @else <span class="brand-text font-weight-light">{{ config('app.name', 'People') }}</span> @endguest
                </a>
            @else
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#"><i class="material-icons icon--list">menu</i></a>
                    </li>                    
                </ul>
                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <div class="dropdown show">
                            <a class="nav-link pr-2 btn text-gray outline-0" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="nav-icon material-icons icon--list">apps</i>
                            </a>
                          
                            <div class="dropdown-menu mt-2" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item px-3" href="{{ config('global.dashboard_sequence') }}">
                                    <img src="{{ config('global.site_icon') }}" alt="" class="img-size-32 mr-2">
                                    Sequence
                                </a>
                                <a class="dropdown-item px-3" href="{{ config('global.dashboard_leaves') }}">
                                    <img src="{{ config('global.site_icon_leaves') }}" alt="" class="img-size-32 mr-2">
                                    Leaves
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link pl-2" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="nav-icon material-icons icon--list">exit_to_app</i>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            @endguest
        </nav>
        <!-- /.navbar -->
        
        @guest  
        @else
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar elevation-4 sidebar--tecc sidebar--{{ config('global.site_color_people') }}">
                <!-- Brand Logo -->
                <a href="/" class="brand-link navbar-white">
                    <img src="{{ config('global.site_icon_people') }}" alt="" class="brand-image">
                    <span class="brand-text font-weight-light">People</span>
                </a>
        
                <!-- Sidebar -->
                <div class="sidebar os-host os-theme-light os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden os-host-scrollbar-vertical-hidden">
                    <div class="os-padding">
                        <div class="os-viewport os-viewport-native-scrollbars-invisible">
                            <div class="os-content">
                                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                                    @guest
                                    @else
                                        <div class="image">
                                            <img src="/storage/public/images/users/{{ Auth::user()->avatar }}" class="img-circle mt-2" alt="User Image">
                                        </div>
                                        <div class="info">
                                            <a href="/my-account" class="d-block" target="_blank">{{ Auth::user()->name }}</a>
                                            <span class="small text-secondary font-weight-bold">{{ Auth::user()->role->name }}</span>
                                        </div>
                                    @endguest
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
        @endguest

        <!-- Content Wrapper. Contains page content -->
        <div class="@guest @else content-wrapper @endguest">
            @include('flashmessage')                
            @yield('content')
        </div>
        
        @guest @else
        @include('shared.admin.bread')
        @endguest
    </div>
    <!-- </div> -->
    <div id="app"></div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    @yield('script')

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

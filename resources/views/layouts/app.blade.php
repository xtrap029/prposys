<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sequencing')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="@guest @else sidebar-mini layout-footer-fixed @endguest">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="@guest @else main-header @endguest navbar navbar-expand @yield('nav_class', 'navbar-light') border-bottom-0 text-sm">
            @guest
                <!-- Brand Logo -->
                <a href="/" class="brand-link">
                    <img src="{{ config('global.site_icon') }}" alt="" class="brand-image" style="opacity: .8">
                    @guest @else <span class="brand-text font-weight-light">{{ config('app.name', 'Sequencing') }}</span> @endguest
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
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                            <i class="nav-icon material-icons icon--list ml-1">exit_to_app</i>
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
            <aside class="main-sidebar elevation-4 sidebar--tecc">
                <!-- Brand Logo -->
                <a href="/" class="brand-link navbar-white">
                    <img src="{{ config('global.site_icon') }}" alt="" class="brand-image" style="opacity: .8">
                    <span class="brand-text font-weight-light">{{ config('app.name', 'Sequencing') }}</span>
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
                                            <a href="/my-account" class="d-block">{{ Auth::user()->name }}</a>
                                            <span class="small text-secondary font-weight-bold">{{ Auth::user()->role->name }}</span>
                                        </div>
                                    @endguest
                                </div>
        
                                <!-- Sidebar Menu -->
                                <nav class="mt-2">
                                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                        <li class="nav-item">
                                            <a href="/" class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                                                <i class="nav-icon material-icons icon--list">dashboard</i><p>Dashboard</p>
                                            </a>
                                        </li>
                                        
                                        @if (in_array(Auth::user()->role_id, [1, 2, 3]))
                                            <li class="nav-item">
                                                <a href="/transaction/prpo/{{ Auth::user()->company_id }}" class="nav-link {{ isset($trans_page) ? in_array($trans_page, ['prpo', 'pc', 'prpo-form', 'pc-form', 'prpo-liquidation', 'pc-liquidation']) ? 'active' : '' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">toll</i><p>Transactions</p>
                                                </a>
                                            </li>
                                            {{-- <li class="nav-header">TRANSACTION</li>
                                            <li class="nav-item">
                                                <a href="/transaction/prpo/{{ Auth::user()->company_id }}" class="nav-link {{ isset($trans_page) ? in_array($trans_page, ['prpo', 'pc', 'prpo-form', 'pc-form', 'prpo-liquidation', 'pc-liquidation']) ? 'active' : '' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">list_alt</i>
                                                    <p>Gen. / Summary</p>
                                                </a>
                                            </li> --}}
                                            {{-- <li class="nav-item has-treeview {{ isset($trans_page) ? in_array($trans_page, ['prpo', 'pc']) ? 'menu-open' : '' : '' }}">
                                                <a href="#" class="nav-link {{ isset($trans_page) ? in_array($trans_page, ['prpo', 'pc']) ? 'active' : '' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">list_alt</i>
                                                    <p>Gen. / Summary</p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="/transaction/prpo/{{ Auth::user()->company_id }}" class="nav-link ml-1 pl-5 {{ isset($trans_page) ? $trans_page == 'prpo' ? 'active' : '' : '' }}">
                                                            <p>Pr / Po</p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/transaction/pc/{{ Auth::user()->company_id }}" class="nav-link ml-1 pl-5 {{ isset($trans_page) ? $trans_page == 'pc' ? 'active' : '' : '' }}">
                                                            <p>Petty Cash</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li> --}}
                                            {{-- <li class="nav-item">
                                                <a href="/transaction-form/prpo/{{ Auth::user()->company_id }}" class="nav-link {{ isset($trans_page) ? in_array($trans_page, ['prpo-form', 'pc-form']) ? 'active' : '' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">list_alt</i>
                                                    <p>Make Forms / Issued</p>
                                                </a>
                                            </li> --}}
                                            {{-- <li class="nav-item has-treeview {{ isset($trans_page) ? in_array($trans_page, ['prpo-form', 'pc-form']) ? 'menu-open' : '' : '' }}">
                                                <a href="#" class="nav-link {{ isset($trans_page) ? in_array($trans_page, ['prpo-form', 'pc-form']) ? 'active' : '' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">list_alt</i>
                                                    <p>Make Forms / Issued</p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="/transaction-form/prpo/{{ Auth::user()->company_id }}" class="nav-link ml-1 pl-5 {{ isset($trans_page) ? $trans_page == 'prpo-form' ? 'active' : '' : '' }}">
                                                            <p>Pr / Po</p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/transaction-form/pc/{{ Auth::user()->company_id }}" class="nav-link ml-1 pl-5 {{ isset($trans_page) ? $trans_page == 'pc-form' ? 'active' : '' : '' }}">
                                                            <p>Petty Cash</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li> --}}
                                            {{-- <li class="nav-item">
                                                <a href="/transaction-liquidation/prpo/{{ Auth::user()->company_id }}" class="nav-link {{ isset($trans_page) ? in_array($trans_page, ['prpo-liquidation', 'pc-liquidation']) ? 'active' : '' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">list_alt</i>
                                                    <p>Clearing / Liq.</p>
                                                </a>
                                            </li> --}}
                                            {{-- <li class="nav-item has-treeview {{ isset($trans_page) ? in_array($trans_page, ['prpo-liquidation', 'pc-liquidation']) ? 'menu-open' : '' : '' }}">
                                                <a href="#" class="nav-link {{ isset($trans_page) ? in_array($trans_page, ['prpo-liquidation', 'pc-liquidation']) ? 'active' : '' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">list_alt</i>
                                                    <p>Clearing / Liq.</p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="/transaction-liquidation/prpo/{{ Auth::user()->company_id }}" class="nav-link ml-1 pl-5 {{ isset($trans_page) ? $trans_page == 'prpo-liquidation' ? 'active' : '' : '' }}">
                                                            <p>Pr / Po</p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/transaction-liquidation/pc/{{ Auth::user()->company_id }}" class="nav-link ml-1 pl-5 {{ isset($trans_page) ? $trans_page == 'pc-liquidation' ? 'active' : '' : '' }}">
                                                            <p>Petty Cash</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li> --}}
                                            <li class="nav-item">
                                                <a href="/transaction/report-all?from={{ date('Y-m-01') }}&to={{ date('Y-m-t') }}" class="nav-link {{ Route::currentRouteName() == 'transactionreport' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">assessment</i><p> REPORTS</p>
                                                </a>
                                            </li>
                                        @endif
                                            
                                        @if (in_array(Auth::user()->role_id, [1, 2]))
                                            <li class="nav-header">ACCOUNTING</li>
                                            <li class="nav-item">
                                                <a href="/bank" class="nav-link {{ Route::currentRouteName() == 'bank' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">account_balance</i><p> Bank</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/coa-tagging" class="nav-link {{ Route::currentRouteName() == 'coatagging' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">poll</i><p> COA Tagging</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/expense-type" class="nav-link {{ Route::currentRouteName() == 'expensetype' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">payments</i><p>Expense Type</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/particular" class="nav-link {{ Route::currentRouteName() == 'particular' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">description</i><p>Particulars</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/released-by" class="nav-link {{ Route::currentRouteName() == 'releasedby' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">person_pin</i><p>Released By</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/vat-type" class="nav-link {{ Route::currentRouteName() == 'vattype' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">receipt</i><p>Tax Type</p>
                                                </a>
                                            </li>
                                            {{-- <li class="nav-item">
                                                <a href="/report-column" class="nav-link {{ Route::currentRouteName() == 'reportcolumns' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">view_column</i><p>Report Column</p>
                                                </a>
                                            </li> --}}
                                            <li class="nav-item">
                                                <a href="/report-template" class="nav-link {{ Route::currentRouteName() == 'reporttemplates' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">extension</i><p>Report Template</p>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        @if (Auth::user()->role_id == 1)
                                            <li class="nav-header">ADMINISTRATOR</li>
                                            <li class="nav-item">
                                                <a href="/user" class="nav-link {{ Route::currentRouteName() == 'user' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">face</i><p>User</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/role" class="nav-link {{ Route::currentRouteName() == 'role' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">lock</i><p>Role</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/company" class="nav-link {{ Route::currentRouteName() == 'company' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">business</i><p>Company</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/settings" class="nav-link {{ Route::currentRouteName() == 'settings' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">settings</i><p>Settings</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/activity-log" class="nav-link {{ Route::currentRouteName() == 'activitylog' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">history</i><p>Activity Log</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/control-panel/db-backups" class="nav-link {{ Route::currentRouteName() == 'dbbackups' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">storage</i><p>Database</p>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        @if (Auth::user()->role_id == 1)
                                            <li class="nav-header">CONTROL PANEL</li>
                                            <li class="nav-item">
                                                <a href="/control-panel/revert-status" class="nav-link {{ Route::currentRouteName() == 'revertstatus' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">history_edu</i><p>Revert Status</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/control-panel/force-cancel" class="nav-link {{ Route::currentRouteName() == 'forcecancel' ? 'active' : '' }}">
                                                    <i class="nav-icon material-icons icon--list">cancel</i><p>Force Cancel</p>
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

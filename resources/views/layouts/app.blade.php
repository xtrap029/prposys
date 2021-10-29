@section('title', 'Sequence')

@include('layouts.sections.head')

<body class="sidebar-mini layout-footer-fixed">
    <div class="wrapper">

        @include('layouts.sections.nav')
        <?php $ua = (new \App\Helpers\UAHelper)->get(); ?>
        <?php $non = config('global.ua_none'); ?>
        
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4 sidebar--tecc sidebar--{{ config('global.site_color') }}">
            
            <div class="dropdown show">
                <a class="brand-link navbar-white" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ config('global.site_icon') }}" alt="" class="brand-image">
                    <span class="brand-text font-weight-light">Sequence</span>
                    <i class="nav-icon material-icons icon--list ml-5 pl-4 text-gray">arrow_drop_down</i>
                </a>
                <div class="dropdown-menu w-100 border-0 shadow" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item px-3" href="/">
                        <i class="icon--list m-1 material-icons nav-icon">apps</i>
                        <div class="d-inline-block mb-1 ml-2">All</div>
                    </a>
                    @if (in_array(config('global.apps')[2], explode(',', Auth::user()->apps)))
                        <a class="dropdown-item px-3" href="{{ config('global.dashboard_people') }}">
                            <img src="{{ config('global.site_icon_people') }}" alt="" class="img-size-32 mr-2">
                            People
                        </a>
                    @endif
                    @if (in_array(config('global.apps')[0], explode(',', Auth::user()->apps)))
                        <a class="dropdown-item px-3" href="{{ config('global.dashboard_leaves') }}">
                            <img src="{{ config('global.site_icon_leaves') }}" alt="" class="img-size-32 mr-2">
                            Leaves
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
                                    <a href="/my-account" class="d-block" target="_blank">{{ Auth::user()->name }}</a>
                                    <span class="small text-secondary font-weight-bold">{{ Auth::user()->ualevel->name }}</span>
                                </div>
                            </div>
    
                            <!-- Sidebar Menu -->
                            <nav class="mt-2">
                                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                    <li class="nav-item">
                                        <a href="{{ config('global.dashboard_sequence') }}" class="nav-link {{ Route::currentRouteName() == 'sequence-dashboard' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">dashboard</i><p>Dashboard</p>
                                        </a>
                                    </li>
                                    
                                    @if (in_array(Auth::user()->role_id, [1, 2, 3]))
                                        <li class="nav-item {{ $ua['trans_view'] == $non ? 'd-none' : '' }}">
                                            <a href="/transaction/prpo/{{ Auth::user()->company_id }}" class="nav-link {{ isset($trans_page) ? in_array($trans_page, ['prpo', 'pc', 'prpo-form', 'pc-form', 'prpo-liquidation', 'pc-liquidation']) ? 'active' : '' : '' }}">
                                                <i class="nav-icon material-icons icon--list">toll</i><p>Transactions</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/transaction/report-all?from={{ date('Y-m-01') }}&to={{ date('Y-m-t') }}" class="nav-link {{ Route::currentRouteName() == 'transactionreport' ? 'active' : '' }}">
                                                <i class="nav-icon material-icons icon--list">assessment</i><p> REPORTS</p>
                                            </a>
                                        </li>
                                    @endif
                                        
                                    <li class="nav-header">CONTROL PANEL</li>  
                                    <li class="nav-item {{ $ua['seq_bank'] == $non ? 'd-none' : '' }}">
                                        <a href="/bank" class="nav-link {{ Route::currentRouteName() == 'bank' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">account_balance</i><p> Bank</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['seq_coa'] == $non ? 'd-none' : '' }}">
                                        <a href="/coa-tagging" class="nav-link {{ Route::currentRouteName() == 'coatagging' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">poll</i><p> COA Tagging</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['seq_expense'] == $non ? 'd-none' : '' }}">
                                        <a href="/expense-type" class="nav-link {{ Route::currentRouteName() == 'expensetype' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">payments</i><p>Expense Type</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['seq_particulars'] == $non ? 'd-none' : '' }}">
                                        <a href="/particular" class="nav-link {{ Route::currentRouteName() == 'particular' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">description</i><p>Particulars</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['seq_rel_by'] == $non ? 'd-none' : '' }}">
                                        <a href="/released-by" class="nav-link {{ Route::currentRouteName() == 'releasedby' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">person_pin</i><p>Released By</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['seq_vat'] == $non ? 'd-none' : '' }}">
                                        <a href="/vat-type" class="nav-link {{ Route::currentRouteName() == 'vattype' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">receipt</i><p>Tax Type</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['seq_rep_temp'] == $non ? 'd-none' : '' }}">
                                        <a href="/report-template" class="nav-link {{ Route::currentRouteName() == 'reporttemplates' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">extension</i><p>Report Template</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['seq_role'] == $non ? 'd-none' : '' }}">
                                        <a href="/role" class="nav-link {{ Route::currentRouteName() == 'role' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">lock</i><p>Role</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['seq_comp'] == $non ? 'd-none' : '' }}">
                                        <a href="/company" class="nav-link {{ Route::currentRouteName() == 'company' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">business</i><p>Company</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['seq_settings'] == $non ? 'd-none' : '' }}">
                                        <a href="/settings" class="nav-link {{ Route::currentRouteName() == 'settings' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">settings</i><p>Settings</p>
                                        </a>
                                    </li>                               
                                    <li class="nav-item {{ $ua['seq_rev_stat'] == $non ? 'd-none' : '' }}">
                                        <a href="/control-panel/revert-status" class="nav-link {{ Route::currentRouteName() == 'revertstatus' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">history_edu</i><p>Revert Status</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['seq_force_cancel'] == $non ? 'd-none' : '' }}">
                                        <a href="/control-panel/force-cancel" class="nav-link {{ Route::currentRouteName() == 'forcecancel' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">cancel</i><p>Force Cancel</p>
                                        </a>
                                    </li>
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

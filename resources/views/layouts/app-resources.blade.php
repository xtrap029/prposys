@section('title', 'Resources')

@include('layouts.sections.head')

<body class="sidebar-mini layout-footer-fixed">
    <div class="wrapper">

        @include('layouts.sections.nav')
        <?php $ua = (new \App\Helpers\UAHelper)->get(); ?>
        <?php $non = config('global.ua_none'); ?>
        
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4 sidebar--tecc sidebar--{{ config('global.site_color_resources') }}">
            
            <div class="dropdown show">
                <a class="brand-link navbar-white" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ config('global.site_icon_resources') }}" alt="" class="brand-image">
                    <span class="brand-text font-weight-light">Resources</span>
                    <i class="nav-icon material-icons icon--list ml-5 pl-4 text-gray">arrow_drop_down</i>
                </a>
                <div class="dropdown-menu w-100 border-0 shadow" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item px-3" href="/">
                        <i class="icon--list m-1 material-icons nav-icon">apps</i>
                        <div class="d-inline-block mb-1 ml-2">All</div>
                    </a>
                    @if (in_array(config('global.apps')[0], explode(',', Auth::user()->apps)))
                        {{-- <a class="dropdown-item px-3" href="{{ config('global.dashboard_leaves') }}">
                            <img src="{{ config('global.site_icon_leaves') }}" alt="" class="img-size-32 mr-2">
                            Leaves
                        </a> --}}
                    @endif
                    @if (in_array(config('global.apps')[1], explode(',', Auth::user()->apps)))
                        <a class="dropdown-item px-3" href="{{ config('global.dashboard_sequence') }}">
                            <img src="{{ config('global.site_icon') }}" alt="" class="img-size-32 mr-2">
                            Sequence
                        </a>
                    @endif
                    @if (in_array(config('global.apps')[2], explode(',', Auth::user()->apps)))
                        <a class="dropdown-item px-3" href="{{ config('global.dashboard_people') }}">
                            <img src="{{ config('global.site_icon_people') }}" alt="" class="img-size-32 mr-2">
                            People
                        </a>
                    @endif
                    @foreach (config('global.site_app_externals') as $item)
                        <a class="dropdown-item px-3" href="{{ $item->url }}" target="_blank">
                            <img src="/storage/public/images/app-externals/{{ $item->icon }}" alt="" class="img-size-32 mr-2">
                            {{ $item->name }}
                        </a>
                    @endforeach
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
                                    <span class="small text-secondary font-weight-bold">
                                        <form action="/my-account" method="post" id="formLevel">
                                            @csrf
                                            @method('put')
                                            <select name="ua_level_id" class="form-control form-control-sm" id="selectLevel" onchange="this.form.submit()">
                                                <option value="{{ Auth::user()->ualevel->id }}">{{ Auth::user()->ualevel->name }}</option>
                                                @if (Auth::user()->ua_levels != '' && Auth::user()->ua_levels != null)
                                                    <optgroup label="Switch Level">
                                                        @foreach (explode(',',Auth::user()->ua_levels) as $level)
                                                            <option value="{{ $level }}">{{ App\UaLevel::where('id', $level)->first()->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            </select>
                                        </form>
                                    </span>
                                </div>
                            </div>
    
                            <!-- Sidebar Menu -->
                            <nav class="mt-2">
                                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                    <li class="nav-item">
                                        <a href="{{ config('global.dashboard_resources') }}" class="nav-link {{ Route::currentRouteName() == 'resources-dashboard' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">dashboard</i><p>Dashboard</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/faqs" class="nav-link {{ Route::currentRouteName() == 'faq' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">quiz</i><p>FAQs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/forms" class="nav-link {{ Route::currentRouteName() == 'form' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">history_edu</i><p>Forms</p>
                                        </a>
                                    </li>
                                    <li class="nav-item py-3"></li>
                                    <li class="nav-item {{ $ua['res_faq_manage'] == $non ? 'd-none' : '' }}">
                                        <a href="/faqs-manage" class="nav-link {{ Route::currentRouteName() == 'faqmanage' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">quiz</i><p>Manage FAQs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $ua['res_form_manage'] == $non ? 'd-none' : '' }}">
                                        <a href="/forms-manage" class="nav-link {{ Route::currentRouteName() == 'formmanage' ? 'active' : '' }}">
                                            <i class="nav-icon material-icons icon--list">history_edu</i><p>Manage Forms</p>
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

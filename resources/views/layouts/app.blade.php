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
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/images/logo.png" alt="" class="thumb--xxs mr-1">
                    {{ config('app.name', 'Sequencing') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item d-none">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item d-none">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img src="/storage/public/images/users/{{ Auth::user()->avatar }}" alt="" class="thumb--xxs rounded">
                                    <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <div class="dropdown-item">{{ Auth::user()->name }}</div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-3 container">
            @include('flashmessage')

            <div class="row">
                @guest  
                    <div class="col-md-12">
                        @yield('content')
                    </div>
                @else
                    <div class="col-md-3 mb-5">
                        <div class="list-group mb-4">
                            <a class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" href="/"><i class="material-icons icon--list">dashboard</i> Dashboard</a>
                            
                            @if (Auth::user()->role_id == 1)
                            <a class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'user' ? 'active' : '' }}" href="/user"><i class="material-icons icon--list">face</i> User</a>
                            <a class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'role' ? 'active' : '' }}" href="/role"><i class="material-icons icon--list">lock</i> Role</a>
                            <a class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'company' ? 'active' : '' }}" href="/company"><i class="material-icons icon--list">business</i> Company</a>                                  
                            @endif                        
                        </div>

                        @if (in_array(Auth::user()->role_id, [1, 2]))
                        <div class="list-group mb-4">
                            <a class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'coatagging' ? 'active' : '' }}" href="/coa-tagging"><i class="material-icons icon--list">account_balance</i> COA Tagging</a>
                            <a class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'expensetype' ? 'active' : '' }}" href="/expense-type"><i class="material-icons icon--list">payments</i> Expense Type</a>
                            <a class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'particular' ? 'active' : '' }}" href="/particular"><i class="material-icons icon--list">description</i> Particulars</a>
                        </div>
                        @endif

                        @if (Auth::user()->role_id == 1)
                        <div class="list-group">
                            <a class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'settings' ? 'active' : '' }}" href="/settings"><i class="material-icons icon--list">settings</i> Settings</a>
                            <a class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'activiyLog' ? 'active' : '' }}" href="/activity-log"><i class="material-icons icon--list">history</i> Activity Log</a>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        @yield('content')
                    </div>
                @endguest
            </div>
        </main>
    </div>
</body>
</html>

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

    @yield('style')
</head>
<body style="@yield('bodystyle', '')">
    
    <div class="wrapper">
        @include('flashmessage')                
        @yield('content')
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

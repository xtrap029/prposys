@extends('layouts.app-center')

@section('content')
<div class="vh-100 banner-cover" style="background-image: url('{{ config('global.site_banner_home_driveid') != '' ? 'http://drive.google.com/uc?export=view&id='.config('global.site_banner_home_driveid') : config('global.site_banner_home') }}');">
    <div class="h-100 row m-0 align-items-end">
        <div class="col text-center">
            <div class="nav-ios-icon">
                @if (in_array(config('global.apps')[1], explode(',', Auth::user()->apps)))
                    <a href="{{ config('global.dashboard_sequence') }}" class="text-bold text-gray-dark text-decoration-none d-inline-block my-1">
                        <span>Sequence</span>                  
                        <img src="{{ config('global.site_icon') }}" class="img-size-64 img-size-64--100 pb-2" alt="">
                    </a>
                @endif
                @if (in_array(config('global.apps')[2], explode(',', Auth::user()->apps)))
                    <a href="{{ config('global.dashboard_people') }}" class="text-bold text-gray-dark text-decoration-none d-inline-block my-1">
                        <span>People</span>                 
                        <img src="{{ config('global.site_icon_people') }}" class="img-size-64 img-size-64--100 pb-2" alt="">  
                    </a>
                @endif
                @if (in_array(config('global.apps')[3], explode(',', Auth::user()->apps)))
                    <a href="{{ config('global.dashboard_resources') }}" class="text-bold text-gray-dark text-decoration-none d-inline-block my-1">
                        <span>Resources</span>                 
                        <img src="{{ config('global.site_icon_resources') }}" class="img-size-64 img-size-64--100 pb-2" alt="">  
                    </a>
                @endif
                @if (count($app_externals))
                    <div class="nav-ios-icon__divider"></div>
                    @foreach ($app_externals as $item)
                        <a href="{{ $item->url }}" target="_blank" class="text-bold text-gray-dark text-decoration-none d-inline-block my-1">
                            <span>{{ $item->name }}</span>  
                            <img src="/storage/public/images/app-externals/{{ $item->icon }}" class="img-size-64 img-size-64--100 pb-2" alt="">
                        </a>
                    @endforeach
                @endif
                <div class="nav-ios-icon__divider"></div>
                <a href="{{ route('logout') }}" target="_blank" class="text-bold text-gray-dark text-decoration-none d-inline-block my-1"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"
                >
                    <span>Logout</span>                   
                    <img src="{{ config('global.site_icon_logout') }}" class="img-size-64 img-size-64--100 pb-2" alt="">  
                </a>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
      </div>
</div>
@endsection
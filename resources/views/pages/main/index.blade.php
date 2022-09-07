@extends('layouts.app-center')

@section('content')
<div class="vh-100 banner-cover" style="background-image:url('{{ config('global.site_banner_home') }}');">
    <div class="h-100 row m-0 align-items-end">
        <div class="col text-center">
            <div class="nav-ios-icon">
                <a href="{{ config('global.dashboard_sequence') }}" class="text-bold text-gray-dark text-decoration-none d-inline-block my-1">
                    <span>Sequence</span>                  
                    <img src="{{ config('global.site_icon') }}" class="img-size-64 img-size-64--100 pb-2" alt="">
                </a>
                <a href="{{ config('global.dashboard_people') }}" class="text-bold text-gray-dark text-decoration-none d-inline-block my-1">
                    <span>People</span>                 
                    <img src="{{ config('global.site_icon_people') }}" class="img-size-64 img-size-64--100 pb-2" alt="">  
                </a>
                <a href="{{ config('global.leaves_link') }}" target="_blank" class="text-bold text-gray-dark text-decoration-none d-inline-block my-1">
                    <span>Leaves</span>  
                    <img src="{{ config('global.site_icon_leaves') }}" class="img-size-64 img-size-64--100 pb-2" alt="">
                </a>
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
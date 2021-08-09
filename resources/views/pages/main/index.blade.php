@extends('layouts.app-center')

@section('content')
<div class="vh-100">
    <div class="h-100 row m-0 align-items-center">
        <div class="col text-center">
            <h4>Choose App</h4>
            <div class="m-3">
                <a href="{{ config('global.dashboard_sequence') }}" class="text-bold text-gray-dark text-decoration-none d-inline-block m-3">
                    <img src="{{ config('global.site_icon') }}" class="img-size-64 pb-2" alt=""><br>
                    Sequence
                </a>
                <a href="{{ config('global.dashboard_leaves') }}" class="text-bold text-gray-dark text-decoration-none d-inline-block m-3">
                    <img src="{{ config('global.site_icon_leaves') }}" class="img-size-64 pb-2" alt=""><br>
                    Leaves
                </a>
                <a href="{{ config('global.dashboard_people') }}" class="text-bold text-gray-dark text-decoration-none d-inline-block m-3">
                    <img src="{{ config('global.site_icon_people') }}" class="img-size-64 pb-2" alt=""><br>
                    People
                </a>
            </div>
            <a class="btn btn-default btn-sm d-inline-block m-3" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
      </div>
</div>
@endsection

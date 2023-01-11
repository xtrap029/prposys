@extends('layouts.app-travels')

@section('content')
<section class="content-header pb-0">
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg--tecc">
                        <h3 class="widget-user-username">{{ $user->name }}</h3>
                        <h6 class="widget-user-desc">{{ $user->role->name }} {{ $user->is_smt ? ' - SMT' : '' }}</h6>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="/storage/public/images/users/{{ $user->avatar }}" alt="User Avatar">
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row">
                            <div class="col-sm-6 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">Email</h5>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="description-block">
                                    <h5 class="description-header">Hired</h5>
                                    <span>  
                                        @if ($user->e_hire_date)
                                            {{ Carbon::parse($user->e_hire_date)->diffInDays(Carbon::now()) >= 1 ? Carbon::parse($user->e_hire_date)->format('Y-m-d') : Carbon::parse($user->e_hire_date)->diffForHumans() }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-8 d-flex">
                @include('layouts.sections.slick')
            </div>
        </div>
    </div>
</section>
@endsection
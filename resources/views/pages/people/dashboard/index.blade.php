@extends('layouts.app-people')

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
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget">
                    <div class="card-header pb-2 bg-dark">
                        <h3 class="card-title">
                            <i class="nav-icon material-icons icon--list mr-2 text-info">face</i>
                            Profile Info
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table m-0">
                            <tr>
                                <td class="border-top-0">Employee No.</td>
                                <td class="border-top-0">{{ $user->e_emp_no ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>{{ $user->e_emp_status ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td>Position</td>
                                <td>{{ $user->e_position ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td>Rank</td>
                                <td>{{ $user->e_rank ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>{{ $user->e_department ?: '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-8">
                <div class="card card-widget">
                    <div class="card-header pb-2 bg-dark">
                        <h3 class="card-title">
                            <i class="nav-icon material-icons icon--list mr-2 text-white">swipe</i>
                            Recent Activities
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            @foreach ($activity as $item)
                                <tr>
                                    <td class="text-nowrap">{{ strtoupper($item->description) }}</td>
                                    <td class="text-nowrap">{{ $item->log_name }}</td>
                                    <td class="text-nowrap">{{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->created_at->format('Y-m-d') : $item->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget">
                    <div class="card-header pb-2 bg-dark">
                        <h3 class="card-title">
                            <i class="nav-icon material-icons icon--list mr-2 text-pink">celebration</i>
                            {{ Carbon::now()->format('F') }} Celebrants
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            @foreach ($user_bday as $item)
                                <tr>
                                    <td><img class="img-circle img-size-32" src="/storage/public/images/users/{{ $item->avatar }}" alt="User Avatar"></td>
                                    <td>
                                        <span class="font-weight-bold">{{ $item->name }}</span><br>
                                        {{ $item->e_department ?: '-' }}
                                    </td>
                                    <td>
                                        {{ Carbon::parse($item->e_dob)->format('M d') }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget">
                    <div class="card-header pb-2 bg-dark">
                        <h3 class="card-title">
                            <i class="nav-icon material-icons icon--list mr-2 text-success">add_reaction</i>
                            Newest Members
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            @foreach ($user_newest as $item)
                                <tr>
                                    <td><img class="img-circle img-size-32" src="/storage/public/images/users/{{ $item->avatar }}" alt="User Avatar"></td>
                                    <td>
                                        <span class="font-weight-bold">{{ $item->name }}</span><br>
                                        {{ $item->e_department ?: '-' }}
                                    </td>
                                    <td>
                                        {{ Carbon::parse($item->e_hire_date)->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget">
                    <div class="card-header pb-2 bg-dark">
                        <h3 class="card-title">
                            <i class="nav-icon material-icons icon--list mr-2 text-warning">workspace_premium</i>
                            Longest Members
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small m-0">
                            @foreach ($user_oldest as $item)
                                <tr>
                                    <td><img class="img-circle img-size-32" src="/storage/public/images/users/{{ $item->avatar }}" alt="User Avatar"></td>
                                    <td>
                                        <span class="font-weight-bold">{{ $item->name }}</span><br>
                                        {{ $item->e_department ?: '-' }}
                                    </td>
                                    <td>
                                        {{ Carbon::parse($item->e_hire_date)->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@extends('layouts.app-leaves')

@section('content')
<section class="content-header pb-0">
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg--tecc">
                        <h3 class="widget-user-username">{{ $user->name }}</h3>
                        <h6 class="widget-user-desc">
                            @foreach ($user->departmentuser as $item)
                                <span class="px-2">{{ $item->department->name }}</span>
                            @endforeach
                        </h6>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="/storage/public/images/users/{{ $user->avatar }}" alt="User Avatar">
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row">
                            <div class="col-sm-6 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">Hired</h5>
                                    <span>  
                                        @if ($user->e_hire_date)
                                            {{ Carbon::parse($user->e_hire_date)->diffInDays(Carbon::now()) >= 1 ? Carbon::parse($user->e_hire_date)->format('Y-m-d') : $user->e_hire_date->diffForHumans() }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="description-block">
                                    <h5 class="description-header">Regularization</h5>
                                    <span>  
                                        @if ($user->e_reg_date)
                                            {{ Carbon::parse($user->e_reg_date)->diffInDays(Carbon::now()) >= 1 ? Carbon::parse($user->e_reg_date)->format('Y-m-d') : $user->e_reg_date->diffForHumans() }}
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
            <div class="col-md-4 d-none">
                <div class="card">
                    <div class="card-header pb-2">
                        <h3 class="card-title">Leave Balances <i class="text-danger small">(test data only)</i></h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table">
                            <tr>
                                <td>YTD Leaves</td>
                                <td class="text-center">{{ $user->leavesytd ? $user->leavesytd->leaves_ytd : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Carry Over Leaves</td>
                                {{-- deduct prev used and prev adjustment --}}
                                <td class="text-center">{{ $user->leavesytdpast ? $user->leavesytdpast->leaves_ytd : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Leave Adjustments</td>
                                <td class="text-center">{{ $user->leavesadjustmenttotal ? $user->leavesadjustmenttotal->adjustment : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Used Leaves</td>
                                <td class="text-center">0</td>
                            </tr>
                            <tr>
                                <td class="bg-gray-light">Total Leaves</td>
                                <td class="bg-gray-light text-center">0</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
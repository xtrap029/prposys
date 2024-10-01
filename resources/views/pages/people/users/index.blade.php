@extends('layouts.app-people')

@section('title', 'Users')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Users</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/user" method="GET" class="row">
                <div class="mb-2 col-6 col-md-2 col-xl-1">
                    <a href="/user/create" class="btn btn-success btn-block p-0"><i class="material-icons mt-1 text-white">add</i></a>
                </div> 
                <div class="mb-2 col-md-5 col-xl-3">
                    <input type="text" class="form-control" name="s" value="{{ app('request')->input('s') }}" placeholder="Name, Email, Employee Number">
                </div>
                <div class="mb-2 col-md-6 col-xl-2">
                    <select name="status" class="form-control">
                        <option value="1" {{ app('request')->input('status') == 1 ? 'selected' : '' }}>All</option>
                        <option value="" {{ app('request')->input('status') == "" || empty(app('request')->input('status')) ? 'selected' : '' }}>Active</option>
                        <option value="2" {{ app('request')->input('status') == 2 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="mb-2 col-md-6 col-xl-2">
                    <select name="level" class="form-control">
                        <option value="">All Levels</option>
                        @foreach ($levels as $item)
                            <option value="{{ $item->id }}" {{ app('request')->input('level') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option> 
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 col-md-6 col-xl-2">
                    <select name="is_accounting" class="form-control">
                        <option value="">All Users</option>
                        <option value="1" {{ app('request')->input('is_accounting') == 1 ? 'selected' : '' }}>Accounting</option>
                        <option value="2" {{ app('request')->input('is_accounting') == 2 ? 'selected' : '' }}>Not Accounting</option>
                    </select>
                </div>
                <div class="mb-2 col-6 col-md-2 col-xl-1">
                    <button class="btn btn-primary btn-block p-0" type="submit"><i class="material-icons mt-1">search</i></button>
                </div>
                <div class="mb-2 col-6 col-md-2 col-xl-1">
                    <a href="/user" class="btn btn-secondary btn-block p-0" type="submit"><i class="material-icons mt-1 text-white">clear</i></a>
                </div>
            </form>
            <table class="table table-striped table-responsive-sm">
                <thead>
                    <tr>
                        <th colspan="2">List</th>
                        <th style="min-width: 150px">Status</th>
                        <th>Roles</th>
                        <th class="text-right text-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $item)
                        <tr>
                            <td class="align-middle"><img src="storage/public/images/users/{{ $item->avatar }}" alt="" class="img-circle thumb--xs"></td>
                            <td class="align-middle text-nowrap">
                                <div class="font-weight-bold">{{ $item->name }}</div>
                                <div class="text-info">{{ $item->email }}</div>
                                <div><span class="badge badge-pill p-1 small bg-white">{{ $item->e_position }}</span></div>
                            </td>
                            <td class="align-middle">
                                <div>
                                    @if ($item->e_emp_status == "Regular" && $item->ua_level_id != config('global.ua_inactive'))
                                        <span class="status-dot status-online"></span> <small>Regular</small>
                                    @endif
                                    @if ($item->e_emp_status == "Probationary" && $item->ua_level_id != config('global.ua_inactive'))
                                        <span class="status-dot status-pending"></span> <small>Probation</small>
                                    @endif
                                    @if ($item->ua_level_id == config('global.ua_inactive'))
                                        <span class="status-dot status-offline"></span> <small>Inactive</small> 
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small>Level</small> &nbsp;
                                    <span class="badge badge-pill p-1 small bg-gray">{{ $item->ualevel->name }}</span>
                                    @foreach ($levels as $level)
                                        @if ($item->ualevel->id != $level->id && in_array($level->id, explode(',',$item->ua_levels)))
                                            <span class="badge badge-pill p-1 small bg-gray">{{ $level->name }}</span>
                                        @endif
                                    @endforeach
                                </div>
                                <div>
                                    <small>Dept.</small> &nbsp;
                                    @foreach ($item->departmentuser as $deptartmentuser)
                                        <span class="badge badge-pill p-1 small bg-gray">{{ $deptartmentuser->department->name }}</span>
                                    @endforeach
                                </div>
                                <div>
                                    <small>Other</small> &nbsp;
                                    @if ($item->is_accounting)
                                        <span class="badge badge-pill p-1 small bg-info" style="color: white !important">Accounting</span>  
                                    @endif
                                    @if ($item->is_accounting_head)
                                        <span class="badge badge-pill p-1 small bg-primary">Accounting - Head</span>  
                                    @endif
                                    @if ($item->is_external)
                                        <span class="badge badge-pill p-1 small bg-gray-dark">External</span>  
                                    @endif
                                </div>
                            </td>                            
                            <td class="align-middle text-right">
                                <a href="/user/{{ $item->id }}" class="btn btn-link btn-sm">View</a>
                                <a href="/user/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="overflow-auto position-relative text-center">
                <div class="d-inline-block">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('style')
    <style>
        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-online {
            background-color: #28a745; /* green */
        }
        .status-offline {
            background-color: #dc3545; /* red */
        }
        .status-pending {
            background-color: #ffc107; /* yellow */
        }
    </style>
@endsection
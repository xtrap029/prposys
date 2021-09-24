@extends('layouts.app-leaves')

@section('title', 'Department')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $department->name }} Department</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid table-responsive">
            <table class="table">
                <tbody>
                    @foreach ($department->departmentuserapprover as $item)
                        <tr class="shadow-sm">
                            <td><img src="/storage/public/images/users/{{ $item->user->avatar }}" alt="" class="img-circle thumb--xs"></td>
                            <td class="text-nowrap vlign--middle">{{ $item->user->name }}</td>
                            <td class="text-nowrap vlign--middle">{{ $item->user->email }}</td>
                            <td class="text-nowrap vlign--middle">Approver</td>
                        </tr>
                    @endforeach
                    @foreach ($department->departmentusermember as $item)
                        <tr>
                            <td><img src="/storage/public/images/users/{{ $item->user->avatar }}" alt="" class="img-circle thumb--xs"></td>
                            <td class="text-nowrap vlign--middle">{{ $item->user->name }}</td>
                            <td class="text-nowrap vlign--middle">{{ $item->user->email }}</td>
                            <td class="text-nowrap vlign--middle">Member</td>
                        </tr>
                    @endforeach
                    @if (count($department->departmentuserapprover) == 0 && count($department->departmentusermember) == 0)
                        <tr>
                            <td colspan="4" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </section>
@endsection
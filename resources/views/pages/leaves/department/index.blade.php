@extends('layouts.app-leaves')

@section('title', 'Department')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Department</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            List
                            <a href="/leaves-department/create" class="float-right">Create Dept.</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $item)
                        <tr>
                            <td class="text-nowrap">
                                <div class="dropdown float-right">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a href="/leaves-department-user/create/{{ $item->id }}" class="dropdown-item">Add Member</a>
                                        <a href="/leaves-department-peak/{{ $item->id }}" class="dropdown-item">Peak</a>
                                        <a href="/leaves-department/{{ $item->id }}/edit" class="dropdown-item">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <form action="/leaves-department/{{ $item->id }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="dropdown-item" value="Delete" onclick="return confirm('Are you sure?')">
                                        </form>
                                    </div>
                                </div>
                                <p class="font-weight-bold">{{ $item->name }}</p>                                
                                <div class="mb-5">
                                    <table class="table table-bordered table-sm">
                                        @forelse ($item->departmentsusers->sortBy('user.name') as $departmentsuser)
                                            <tr class="{{ $departmentsuser->is_approver ? 'bg-white' : 'bg-light' }}">
                                                <td>
                                                    <a href="/leaves-department-user/edit/{{ $departmentsuser->id }}" class="d-block">
                                                        {{ $departmentsuser->user->name }}
                                                    </a>
                                                </td>
                                                <td class="text-right pr-3">{{ $departmentsuser->is_approver ? 'Approver' : 'Member' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2"><i class="text-gray">{{ __('messages.empty') }}</i></td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
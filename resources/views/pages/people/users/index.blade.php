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
            <table class="table table-striped table-responsive-sm">
                <thead>
                    <tr>
                        <th colspan="3">List</th>
                        <th class="text-right text-nowrap">
                            @if (!isset($_GET['all'])) <a href="/user?all=1" class="mr-5">Show Inactive</a>
                            @else <a href="/user" class="mr-5">Hide Inactive</a>
                            @endif
                            <a href="/user/create">Create</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $item)
                        <tr>
                            <td><img src="storage/public/images/users/{{ $item->avatar }}" alt="" class="img-circle thumb--xs"></td>
                            <td class="align-middle text-nowrap">
                                {{ $item->name }}
                                <div class="text-info">{{ $item->email }}</div>
                            </td>
                            <td class="align-middle">
                                {{ $item->role_id ? $item->role->name : 'Inactive' }}
                                {{ $item->is_smt ? '- SMT' : '' }}
                            </td>
                            <td class="align-middle text-right">
                                <a href="/user/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
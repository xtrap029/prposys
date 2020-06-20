@extends('layouts.app')

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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="4">List</th>
                        <th class="text-right"><a href="/user/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $item)
                        <tr>
                            <td><img src="storage/public/images/users/{{ $item->avatar }}" alt="" class="img-circle thumb--xs"></td>
                            <td class="align-middle">{{ $item->name }}</td>
                            <td class="align-middle">{{ $item->email }}</td>
                            <td class="align-middle">{{ $item->role_id ? $item->role->name : 'Inactive' }}</td>
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
                <thead>
                    <tr>
                        <th colspan="5">Inactive</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users_inactive as $item)
                        <tr>
                            <td><img src="storage/public/images/users/{{ $item->avatar }}" alt="" class="img-circle thumb--xs"></td>
                            <td class="align-middle">{{ $item->name }}</td>
                            <td class="align-middle">{{ $item->email }}</td>
                            <td></td>
                            <td class="align-middle text-right">
                                <a href="/user/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
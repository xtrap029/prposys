@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="container">
        <h4 class="mb-4">Users</h4>

        <table class="table">
            <thead>
                <tr>
                    <th colspan="4">List</th>
                    <th class="text-right"><a href="/user/create">Create</a></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $item)
                    <tr>
                        <td><img src="storage/public/images/users/{{ $item->avatar }}" alt="" class="thumb thumb--xs"></td>
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
        </table>

        <table class="table mt-5">
            <thead>
                <tr>
                    <th colspan="4">Inactive</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users_inactive as $item)
                    <tr>
                        <td><img src="storage/public/images/users/{{ $item->avatar }}" alt="" class="thumb thumb--xs"></td>
                        <td class="align-middle">{{ $item->name }}</td>
                        <td class="align-middle">{{ $item->email }}</td>
                        <td class="align-middle text-right">
                            <a href="/user/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
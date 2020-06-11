@extends('layouts.app')

@section('title', 'Role')

@section('content')
    <div class="container">
        <h4 class="mb-4">Role</h4>

        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">List</th>
                    <th class="text-right"><a href="/role/create" class="d-none">Create</a></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">
                            <a href="/role/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                            <form action="/role/{{ $item->id }}" method="post" class="d-none">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-link btn-sm" value="Delete">
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">{{ __('messages.empty') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
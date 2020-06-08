@extends('layouts.app')

@section('title', 'Roles')

@section('content')
    <div class="container">
        <h4 class="mb-4">Roles</h4>

        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">List</th>
                    <th class="text-right"><a href="/role/create">Create</a></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">
                            <a href="/role/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                            <form action="/role/{{ $item->id }}" method="post" class="d-inline-block">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-link btn-sm" value="Delete">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Role')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Role</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped">
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
    </section>
@endsection
@extends('layouts.app-people')

@section('title', 'User Attribute')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Attribute</h1>
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
                        <th class="text-right"><a href="/user-attribute/create">Create</a></th>
                    </tr>
                <tbody>
                </thead>
                    @forelse ($user_attributes as $item)
                        <tr>
                            <td>{{ $item->order }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-right">
                                <a href="/user-attribute/{{ $item->id }}/edit" class="btn btn-link btn-sm d-inline-block">Edit</a>
                                <form action="/user-attribute/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
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
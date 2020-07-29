@extends('layouts.app')

@section('title', 'Released By')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Released By</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>List</th>
                        <th class="text-right"><a href="/released-by/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($released_by as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td class="text-right">
                                <a href="/released-by/{{ $item->id }}/edit" class="btn btn-link btn-sm d-inline-block">Edit</a>
                                <form action="/released-by/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete">
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
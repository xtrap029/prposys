@extends('layouts.app-leaves')

@section('title', 'Leave Reason')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Leave Reason</h1>
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
                        <th class="text-right"><a href="/leaves-reason/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reasons as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td class="bg-{{ $item->color }} vlign--middle rounded text-center small">{{ strtoupper($item->color) }}</td>
                            <td class="text-right">
                                <a href="/leaves-reason/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                <form action="/leaves-reason/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
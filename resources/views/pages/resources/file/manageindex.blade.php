@extends('layouts.app-resources')

@section('title', 'Manage Files')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manage Files</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">  
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th class="text-right"><a href="/files-manage/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($files as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ substr($item->description, 0, 100) }}...</td>
                            <td class="text-right">
                                <a href="/files-manage/{{ $item->id }}/edit" class="btn btn-link btn-sm d-inline-block">Edit</a>
                                <form action="/files-manage/{{ $item->id }}" method="post" class="d-inline-block">
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
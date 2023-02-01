@extends('layouts.app-travels')

@section('title', 'Request Type')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Request Type</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped table-responsive-sm">
                <thead>
                    <tr>
                        <th>List</th>
                        <th class="text-right text-nowrap">
                            <a href="/travels-request-type/create" class="mr-4">Create Request Type</a>
                            <a href="/travels-request-type-option/create">Create Request Type Option</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($request_types as $item)
                        <tr>
                            <td class="text-nowrap">
                                <p class="font-weight-bold">{{ $item->name }}</p>
                                <div class="pl-5 py-1">
                                    @forelse ($item->travelsrequesttypeoptions as $option)
                                        <a href="/travels-request-type-option/edit/{{ $option->id }}" class="d-block">{{ $option->name }}</a>
                                    @empty
                                        <i class="text-gray">{{ __('messages.empty') }}</i>
                                    @endforelse
                                </div>
                            </td>
                            <td class="text-right">
                                <a href="/travels-request-type/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                <form action="/travels-request-type/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
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
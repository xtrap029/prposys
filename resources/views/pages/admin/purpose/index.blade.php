@extends('layouts.app')

@section('title', 'Purpose')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Purpose</h1>
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
                        <th class="text-right"><a href="/purpose/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purposes as $item)
                        <tr>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>
                                @foreach ($companies as $company)
                                    @if (in_array($company->id, explode(',', $item->companies)))
                                        <span class="badge badge-pill py-1 px-2 mt-2 small bg-gray">{{ $company->code }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td class="text-right">
                                <a href="/purpose/{{ $item->id }}/edit" class="btn btn-link btn-sm d-inline-block">Edit</a>
                                <form action="/purpose/{{ $item->id }}" method="post" class="d-inline-block">
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
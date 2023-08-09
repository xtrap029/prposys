@extends('layouts.app')

@section('title', 'Company')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Company</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped table-responsive-sm">
                <thead>
                    <tr>
                        <th colspan="3">List</th>
                        <th class="text-right"><a href="/company/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($companies as $item)
                        <tr>
                            <td><img src="storage/public/images/companies/{{ $item->logo }}" alt="" class="thumb thumb--xs"></td>
                            <td class="align-middle text-nowrap">
                                {{ $item->name }}
                                <div class="text-info">{{ $item->code }} / {{ $item->qb_code }} / {{ $item->qb_no }}</div>
                            </td>
                            <td class="align-middle text-nowrap"></td>
                            <td class="align-middle text-right text-nowrap">
                                <a href="/company-project/{{ $item->id }}" class="btn btn-link btn-sm">Projects</a>
                                <a href="/company/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                <form action="/company/{{ $item->id }}" method="post" class="d-inline-block">
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
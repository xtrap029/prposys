@extends('layouts.app')

@section('title', 'Tax Type')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tax Type</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th class="text-right">VAT</th>
                        <th class="text-right">WHT</th>
                        <th class="text-right">PR</th>
                        <th class="text-right">PO</th>
                        <th class="text-right">PC</th>
                        <th class="text-right"><a href="/vat-type/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vat_types as $item)
                        <tr>
                            <td>{{ strtoupper($item->code) }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-right">{{ $item->vat }} %</td>
                            <td class="text-right">{{ $item->wht }} %</td>
                            <td class="text-right">{{ $item->is_pr ? 'YES' : 'NO' }}</td>
                            <td class="text-right">{{ $item->is_po ? 'YES' : 'NO' }}</td>
                            <td class="text-right">{{ $item->is_pc ? 'YES' : 'NO' }}</td>
                            <td class="text-right">
                                <a href="/vat-type/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                <form action="/vat-type/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-right">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection